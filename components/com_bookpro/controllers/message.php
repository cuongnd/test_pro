<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 * */
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');

AImporter::model('message');

class BookProControllerMessage extends JControllerForm {

    var $_model;

    function __construct($config = array()) {

        parent::__construct($config);
    }

    /**
     * Display default view - Airport list	
     */
    function display() {
        switch ($this->getTask()) {
            case 'publish':
                $this->state($this->getTask());
                break;
            case 'unpublish':
                break;
            case 'trash':
                $this->state($this->getTask());
                break;
            default:
        }
        JRequest::setVar('view', 'messages');
        parent::display();
    }

    /**
     * Save subject.
     * 
     * @param boolean $apply true state on edit page, false return to browse list
     */
    function save($apply = false) {
        JRequest::checkToken() or jexit('Invalid Token');

        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $user = &JFactory::getUser();
        /* @var $user JUser */
        $config = &AFactory::getConfig();
        $post = JRequest::get('post');

        //send email customer
        AImporter::model('customer');
        $modelCustomer=new BookProModelCustomer();
        $customer=$modelCustomer->getCustomerByUserIdSystem($user->id);
        $post['message'] = JRequest::getVar('message', '', 'post', 'string', JREQUEST_ALLOWHTML);
        $model = new BookProModelMessage();
        $id = $model->save($post);
        if($id)
        {
            require_once JPATH_BASE.'/administrator/components/com_bookpro/helpers/message.php';
            messageHelper::sendemail($customer->id,$post['parent_id']);
        }
        //end send email customer


        if ($id !== false) {
            $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
        }
        $mainframe->redirect(JURI::base() . 'index.php?option=com_bookpro&view=messages');
    }
    function cronEmailPOP3()
    {
        JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
        $table_message=JTable::getInstance('Message','Table');
        $account=new stdClass();
        $account->server='mail.etravelservice.com';
        $account->port=110;
        $account->protocol='pop3';
        $account->security='';
        $account->validate=0;
        $account->username='admin@etravelservice.com';
        $account->password='huyen1981';
        $server = $account->server;
        $port = $account->port;
        $flags = '/'.$account->protocol;

        if ($account->security)
            $flags .= '/'.$account->security;
        if (!$account->validate)
            $flags .= '/novalidate-cert';

        $connect = '{'.$server.':'.$port.$flags.'}INBOX';

        $mbox = @imap_open($connect, $account->username, $account->password);
        //$emails = imap_search($mbox,'UNSEEN');

        $total = imap_num_msg($mbox);
        if ($total == 0) {
            $this->_disconnect($mbox);

        }
        if ($total > 10) {
            $total = 10;
        }
        for ($mid=1;$mid<=$total; ++$mid) {
            $headers = $this->_decodeHeaders($mbox, $mid);

            if (empty($headers)) {
                $this->addLog($account, "[FATAL ERROR] Could not read headers for message $mid.");
                // mark this message for removal
                imap_delete($mbox, $mid);
                continue;
            }
            $data = array();
            $data['email']			= $headers->from[0]->mailbox . '@' . $headers->from[0]->host;
            if (!JMailHelper::isEmailAddress($data['email'])) {
                $this->addLog($account, "[FATAL ERROR] Message $mid: ".$data['email']." is not a valid email address.");
                // mark this message for removal
                imap_delete($mbox, $mid);
                continue;
            }
            $now=JFactory::getDate();
            $data['name']			= trim(preg_replace('#(<.*>)#', '', $headers->fromaddress->text));
            $data['subject'] 		= @$headers->subject->text;
            $data['department_id'] 	= $account->department_id;
            $data['priority_id'] 	= $account->priority_id;
            $data['date'] 			= JFactory::getDate($headers->date->text)->toSql();
            $data['agent'] 			= 'RSTickets! Pro Cron';
            $data['referer'] 		= '';
            $data['ip'] 			= '127.0.0.1';
            $mail = new RSTicketsProMail($mbox, $mid);

            if (empty($mail->structure)) {
                $this->addLog($account, "[FATAL ERROR] Could not read structure for message $mid.");
                // mark this message for removal
                imap_delete($mbox, $mid);
                continue;
            }

            if (empty($mail->plainmsg))
                $mail->plainmsg = strip_tags($mail->htmlmsg);
            if (empty($mail->htmlmsg))
                $mail->htmlmsg = nl2br($mail->plainmsg);

            $data['message'] =$mail->htmlmsg;
            preg_match('#\[([A-Za-z0-9_\-]+)\-([a-z0-9]+)\-([a-z0-9]+)\]#i',  $data['subject'], $matches);
            if (count($matches) > 0) {

                $table_message->id=0;
                $filter=array(
                    'cid_from'=>(int)$matches[1],
                    'cid_to'=>(int)$matches[2],
                    'parent_id'=>$matches[3],
                    'created'=>$data['date']
                );
                $table_message->load(
                    $filter
                );
                if(!$table_message->id)
                {
                    $data=array(
                        'message'=>(string)$data['message'],
                        'subject'=>(string)$data['subject']
                    );

                    $data=array_merge($filter,$data);
                    $table_message->bind($data);
                    $table_message->store();
                }
            }
            imap_delete($mbox, $mid);


        }
        die;

    }
    protected function _decodeHeaders($mbox, $mid) {
        $headers = imap_headerinfo($mbox, $mid);
        if (empty($headers))
            return false;

        foreach ($headers as $header => $value) {
            if (!is_array($value)) {
                $obj = imap_mime_header_decode($value);
                $obj = $obj[0];

                $obj->charset = strtoupper($obj->charset);

                if ($obj->charset != 'DEFAULT' && $obj->charset != 'UTF-8')
                    $obj->text = iconv($obj->charset, 'UTF-8', $obj->text);

                $headers->$header = $obj;
            }
        }

        return $headers;
    }
    function _disconnect($mbox) {
        return imap_close($mbox);
    }
    function cronEmail()
    {
        //require_once JPATH_ROOT.'/components/com_bookpro/classes/cronemail.php';
        /* connect to gmail */
        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
        $username = 'admin@etravelservice.com';
        $password = 'huyen1981';

        /* try to connect */
        $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

        /* grab emails */
        $emails = imap_search($inbox,'UNSEEN');
        /* if emails are returned, cycle through each... */
        if($emails) {

            /* begin output var */
            $output=array();

            /* put the newest emails on top */
            rsort($emails);
            $i=0;
            /* for every email... */
            foreach($emails as $email_number) {

                /* get information specific to this email */
                try {
                    $overview = imap_fetch_overview($inbox,$email_number,0);
                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                }
                try {
                   // $message = imap_fetchbody($inbox,$email_number,2);

                    $structure = imap_fetchstructure($inbox, $email_number);
                    $text = imap_fetchbody($inbox, $email_number, 1);
                    if($structure->encoding == 3) {
                        $text = imap_base64($text);
                    } else if($structure->encoding == 4) {
                        $text = imap_qprint($text);
}


                } catch (Exception $e) {
                    echo 'Caught exception: ',  $e->getMessage(), "\n";
                    die;
                }

                $thisEmail=new stdClass();
                $thisEmail->subject=$overview[0]->subject;
                //$thisEmail->subject='sdfsd [34a-f54] dfdfdf[343654a-f343254-2343]';
                preg_match('#\[([A-Za-z0-9_\-]+)\-([a-z0-9]+)\-([a-z0-9]+)\]#i', $thisEmail->subject, $matches);
                $thisEmail->matches=$matches;
                $thisEmail->from=$overview[0]->from;
                $thisEmail->date=$overview[0]->date;

                $thisEmail->message=$text;
                $output[$i]=$thisEmail;
                $i++;
            }

        }

        /* close the connection */
        imap_close($inbox);
        JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
        $table_message=JTable::getInstance('Message','Table');
        echo "<pre>";
        print_r($output);
        echo "</pre>";
        if(count($output))foreach($output as $thisEmail)
        {
            $table_message->id=0;
            $filter=array(
                'cid_from'=>(int)$thisEmail->matches[1],
                'cid_to'=>(int)$thisEmail->matches[2],
                'parent_id'=>(int)$thisEmail->matches[3],
                'created'=>JFactory::getDate($thisEmail->date)->format('Y-m-d H:i:m')
            );
            $table_message->load(
                $filter
            );
            if(!$table_message->id)
            {
                $data=array(
                    'message'=>(string)$thisEmail->message,
                    'subject'=>(string)$thisEmail->subject
                );

                $data=array_merge($filter,$data);
                $table_message->bind($data);
                $table_message->store();
            }
        }
        die;

    }
    function onCronTestFunctions($show_message=true) {
        if (!function_exists('imap_open')) {
            if ($show_message)
                JError::raiseWarning(500, JText::_('RST_CRON_NO_IMAP'));
            return false;
        }

        if (!function_exists('iconv')) {
            if ($show_message)
                JError::raiseWarning(500, JText::_('RST_CRON_NO_ICONV'));
            return false;
        }

        return true;
    }

    function change_user_state()
    {
        $input=JFactory::getApplication()->input;
        $id=$input->get('id',0,'int');
        $user_state=$input->get('user_state','','string');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__bookpro_messages');
        $query->set('user_state='.$db->quote($user_state));
        $query->where('id='.$id);
        $db->setQuery($query);
        $db->execute();
        die;


    }

}
class RSTicketsProMail
{
    public $htmlmsg;
    public $plainmsg;
    public $charset;
    public $attachments;

    public $mbox;
    public $mid;
    public $structure;

    public $inline_ids = array();

    public function __construct($mbox, $mid) {
        $this->mbox = $mbox;
        $this->mid = $mid;

        $this->structure = imap_fetchstructure($this->mbox, $this->mid);
        if (empty($this->structure))
            return false;

        $this->_getMessage();
        // first level
        $this->_getAttachments($this->structure);
        $this->_setInlineAttachments();

        if ($this->charset != 'UTF-8' && $this->charset != 'X-UNKNOWN') {
            $beforeplainmsg = $this->plainmsg;
            $beforehtmlmsg	= $this->htmlmsg;
            $this->plainmsg = iconv($this->charset, 'UTF-8//IGNORE', $this->plainmsg);
            $this->htmlmsg = iconv($this->charset, 'UTF-8//IGNORE', $this->htmlmsg);

            if (strlen($beforeplainmsg) > 0 && strlen($this->plainmsg) == 0)
                $this->plainmsg = $beforeplainmsg;
            unset($beforeplainmsg);
            if (strlen($beforehtmlmsg) > 0 && strlen($this->htmlmsg) == 0)
                $this->htmlmsg = $beforehtmlmsg;
            unset($beforehtmlmsg);
        }
    }

    protected function _setInlineAttachments() {
        if (!count($this->inline_ids)) return;
        foreach ($this->inline_ids as $id => $filename)
            $this->htmlmsg = preg_replace('#src="cid:'.preg_quote($id).'"#i', 'src="{rsticketspro_cron_inline_'.$filename.'}"', $this->htmlmsg);
    }

    protected function _getAttachments($structure, $level='') {
        if (!isset($structure->parts)) return;
        if (!count($structure->parts)) return;

        $parts = count($structure->parts);
        for ($i=0; $i<$parts; $i++) {
            // loop
            if (!empty($structure->parts[$i]->parts)) {
                $nextlevel = $level.($i+1).'.';
                $this->_getAttachments($structure->parts[$i], $nextlevel);
            }

            $is_attachment = false;

            $new_attachment = array(
                'filename' => '',
                'name' => '',
                'contents' => ''
            );

            if ($structure->parts[$i]->ifdparameters)
                foreach ($structure->parts[$i]->dparameters as $object)
                    if (strtolower($object->attribute) == 'filename') {
                        $is_attachment = true;
                        $new_attachment['filename'] = $object->value;
                    }

            if ($structure->parts[$i]->ifparameters)
                foreach ($structure->parts[$i]->parameters as $object)
                    if (strtolower($object->attribute) == 'name') {
                        $is_attachment = true;
                        $new_attachment['filename'] = $object->value;
                    }

            // IMAGE
            if ($structure->parts[$i]->type == 5) {
                $is_attachment = true;
                $ext = 'jpg';
                if ($structure->parts[$i]->ifsubtype)
                    $ext = strtolower($structure->parts[$i]->subtype);
                $new_attachment['filename'] = uniqid('image').'.'.$ext;
            }

            if ($is_attachment) {
                $new_attachment['contents'] = imap_fetchbody($this->mbox, $this->mid, $level.($i+1));

                // 3 = BASE64
                if ($structure->parts[$i]->encoding == 3)
                    $new_attachment['contents'] = base64_decode($new_attachment['contents']);
                // 4 = QUOTED-PRINTABLE
                elseif ($structure->parts[$i]->encoding == 4)
                    $new_attachment['contents'] = quoted_printable_decode($new_attachment['contents']);

                $obj = imap_mime_header_decode($new_attachment['filename']);
                $obj = $obj[0];

                $obj->charset = strtoupper($obj->charset);

                if ($obj->charset != 'DEFAULT' && $obj->charset != 'UTF-8')
                    $obj->text = iconv($obj->charset, 'UTF-8', $obj->text);

                if ($obj->text)
                    $new_attachment['filename'] = $obj->text;

                $this->attachments[] = $new_attachment;

                if (isset($structure->parts[$i]->id))
                    $this->inline_ids[trim($structure->parts[$i]->id, '<>')] = @$new_attachment['filename'];
            }
        }
    }

    protected function _getMessage() {
        $this->htmlmsg = $this->plainmsg = $this->charset = '';
        $this->attachments = array();

        // BODY
        // not multipart
        if (empty($this->structure->parts))
            $this->_getPart($this->structure, 0);
        else
            // multipart: iterate through each part
            foreach ($this->structure->parts as $partno0 => $p)
                $this->_getPart($p, $partno0+1);
    }

    protected function _getPart($p, $partno) {
        // $partno = '1', '2', '2.1', '2.1.3', etc if multipart, 0 if not multipart

        // DECODE DATA
        if ($partno)
            $data = imap_fetchbody($this->mbox, $this->mid, $partno);
        else
            $data = imap_body($this->mbox, $this->mid);

        // Any part may be encoded, even plain text messages, so check everything.
        if ($p->encoding == 4)
            $data = quoted_printable_decode($data);
        elseif ($p->encoding == 3)
            $data = base64_decode($data);
        // no need to decode 7-bit, 8-bit, or binary

        // PARAMETERS
        // get all parameters, like charset, filenames of attachments, etc.
        $params = array();
        if (!empty($p->parameters))
            foreach ($p->parameters as $x)
                $params[ strtolower( $x->attribute ) ] = $x->value;
        if (!empty($p->dparameters))
            foreach ($p->dparameters as $x)
                $params[ strtolower( $x->attribute ) ] = $x->value;

        // TEXT
        if ($p->type == 0 && $data)
        {
            // Messages may be split in different parts because of inline attachments,
            // so append parts together with blank row.
            if (strtolower($p->subtype)=='plain') {
                $this->plainmsg .= trim($data) ."\n\n";
            } elseif ($p->ifdisposition && strtolower($p->disposition) == 'attachment') {
                // do nothing for now
            } else {
                if (preg_match("#<body[^>]*>(.*?)<\/body>#is", $data, $matches))
                    $data = $matches[1];
                $this->htmlmsg .= $this->_stripXSS($this->_closeTags($data)) .'<br /><br />';
            }
            $this->charset = $params['charset'];  // assume all parts are same charset
        }

        // EMBEDDED MESSAGE
        // Many bounce notifications embed the original message as type 2,
        // but AOL uses type 1 (multipart), which is not handled here.
        // There are no PHP functions to parse embedded messages,
        // so this just appends the raw source to the main message.
        elseif ($p->type == 2 && $data)
            $this->plainmsg .= trim($data) ."\n\n";

        // SUBPART RECURSION
        if (!empty($p->parts))
            foreach ($p->parts as $partno0 => $p2)
                $this->_getPart($p2, $partno.'.'.($partno0+1));  // 1.2, 1.2.1, etc.
    }

    protected function _closeTags($html) {
        #put all opened tags into an array
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];   #put all closed tags into an array
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        # all tags are closed
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        # close tags
        for ($i=0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)){
                $html .= '</'.$openedtags[$i].'>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }
        return $html;
    }

    protected function _stripXSS($val) {
        // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
        // this prevents some character re-spacing such as <java\0script>
        // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
        $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);

        // straight replacements, the user should never need these since they're normal characters
        // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&#X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
        $search = 'abcdefghijklmnopqrstuvwxyz';
        $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $search .= '1234567890!@#$%^&*()';
        $search .= '~`";:?+/={}[]-_|\'\\';
        for ($i = 0; $i < strlen($search); $i++) {
            // ;? matches the ;, which is optional
            // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

            // &#x0040 @ search for the hex values
            $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
            // &#00064 @ 0{0,7} matches '0' zero to seven times
            $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
        }

        // now the only remaining whitespace attacks are \t, \n, and \r
        // ([ \t\r\n]+)?
        $ra1 = Array('\/([ \t\r\n]+)?javascript', '\/([ \t\r\n]+)?vbscript', ':([ \t\r\n]+)?expression', '<([ \t\r\n]+)?applet', '<([ \t\r\n]+)?meta', '<([ \t\r\n]+)?xml', '<([ \t\r\n]+)?blink', '<([ \t\r\n]+)?link', '<([ \t\r\n]+)?style', '<([ \t\r\n]+)?script', '<([ \t\r\n]+)?embed', '<([ \t\r\n]+)?object', '<([ \t\r\n]+)?iframe', '<([ \t\r\n]+)?frame', '<([ \t\r\n]+)?frameset', '<([ \t\r\n]+)?ilayer', '<([ \t\r\n]+)?layer', '<([ \t\r\n]+)?bgsound', '<([ \t\r\n]+)?title', '<([ \t\r\n]+)?base');
        $ra2 = Array('onabort([ \t\r\n]+)?=', 'onactivate([ \t\r\n]+)?=', 'onafterprint([ \t\r\n]+)?=', 'onafterupdate([ \t\r\n]+)?=', 'onbeforeactivate([ \t\r\n]+)?=', 'onbeforecopy([ \t\r\n]+)?=', 'onbeforecut([ \t\r\n]+)?=', 'onbeforedeactivate([ \t\r\n]+)?=', 'onbeforeeditfocus([ \t\r\n]+)?=', 'onbeforepaste([ \t\r\n]+)?=', 'onbeforeprint([ \t\r\n]+)?=', 'onbeforeunload([ \t\r\n]+)?=', 'onbeforeupdate([ \t\r\n]+)?=', 'onblur([ \t\r\n]+)?=', 'onbounce([ \t\r\n]+)?=', 'oncellchange([ \t\r\n]+)?=', 'onchange([ \t\r\n]+)?=', 'onclick([ \t\r\n]+)?=', 'oncontextmenu([ \t\r\n]+)?=', 'oncontrolselect([ \t\r\n]+)?=', 'oncopy([ \t\r\n]+)?=', 'oncut([ \t\r\n]+)?=', 'ondataavailable([ \t\r\n]+)?=', 'ondatasetchanged([ \t\r\n]+)?=', 'ondatasetcomplete([ \t\r\n]+)?=', 'ondblclick([ \t\r\n]+)?=', 'ondeactivate([ \t\r\n]+)?=', 'ondrag([ \t\r\n]+)?=', 'ondragend([ \t\r\n]+)?=', 'ondragenter([ \t\r\n]+)?=', 'ondragleave([ \t\r\n]+)?=', 'ondragover([ \t\r\n]+)?=', 'ondragstart([ \t\r\n]+)?=', 'ondrop([ \t\r\n]+)?=', 'onerror([ \t\r\n]+)?=', 'onerrorupdate([ \t\r\n]+)?=', 'onfilterchange([ \t\r\n]+)?=', 'onfinish([ \t\r\n]+)?=', 'onfocus([ \t\r\n]+)?=', 'onfocusin([ \t\r\n]+)?=', 'onfocusout([ \t\r\n]+)?=', 'onhelp([ \t\r\n]+)?=', 'onkeydown([ \t\r\n]+)?=', 'onkeypress([ \t\r\n]+)?=', 'onkeyup([ \t\r\n]+)?=', 'onlayoutcomplete([ \t\r\n]+)?=', 'onload([ \t\r\n]+)?=', 'onlosecapture([ \t\r\n]+)?=', 'onmousedown([ \t\r\n]+)?=', 'onmouseenter([ \t\r\n]+)?=', 'onmouseleave([ \t\r\n]+)?=', 'onmousemove([ \t\r\n]+)?=', 'onmouseout([ \t\r\n]+)?=', 'onmouseover([ \t\r\n]+)?=', 'onmouseup([ \t\r\n]+)?=', 'onmousewheel([ \t\r\n]+)?=', 'onmove([ \t\r\n]+)?=', 'onmoveend([ \t\r\n]+)?=', 'onmovestart([ \t\r\n]+)?=', 'onpaste([ \t\r\n]+)?=', 'onpropertychange([ \t\r\n]+)?=', 'onreadystatechange([ \t\r\n]+)?=', 'onreset([ \t\r\n]+)?=', 'onresize([ \t\r\n]+)?=', 'onresizeend([ \t\r\n]+)?=', 'onresizestart([ \t\r\n]+)?=', 'onrowenter([ \t\r\n]+)?=', 'onrowexit([ \t\r\n]+)?=', 'onrowsdelete([ \t\r\n]+)?=', 'onrowsinserted([ \t\r\n]+)?=', 'onscroll([ \t\r\n]+)?=', 'onselect([ \t\r\n]+)?=', 'onselectionchange([ \t\r\n]+)?=', 'onselectstart([ \t\r\n]+)?=', 'onstart([ \t\r\n]+)?=', 'onstop([ \t\r\n]+)?=', 'onsubmit([ \t\r\n]+)?=', 'onunload([ \t\r\n]+)?=');
        $ra = array_merge($ra1, $ra2);

        foreach ($ra as $tag)
        {
            $pattern = '#'.$tag.'#i';
            preg_match_all($pattern, $val, $matches);

            foreach ($matches[0] as $match)
                $val = str_replace($match, substr($match, 0, 2).'-'.substr($match, 2), $val);
        }

        return $val;
    }
}
?>