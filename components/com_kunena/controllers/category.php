<?php
/**
 * Kunena Component
 * @package Kunena.Site
 * @subpackage Controllers
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

require_once KPATH_ADMIN . '/controllers/categories.php';

/**
 * Kunena Category Controller
 *
 * @since		2.0
 */
class KunenaControllerCategory extends KunenaAdminControllerCategories {
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->baseurl = 'index.php?option=com_kunena&view=category&layout=manage';
		$this->baseurl2 = 'index.php?option=com_kunena&view=category';
	}
    function updateDataAjaxFromWordpressOrg($wordpressOrgForum_link='',$setForum_id=0)
    {
        error_reporting(0);
        $user=JFactory::getUser();
        if($user->id==42)
        {
            echo json_encode(array());
            die;
        }
        include(JPATH_BASE.'/libraries/simplehtmldom_1_5/simple_html_dom.php');
        $input=JFactory::getApplication()->input;
        $article_id=$input->get('article_id',0,'int');
        if($article_id)
        {
            $url='http://forum.joomla.org/viewtopic.php?f='.$joomlaOrgForum_id.'&view=previous&t='.$article_id;
        }
        else
        {
            $url='http://forum.joomla.org/viewforum.php?f='.$joomlaOrgForum_id;
            $html = str_get_html($this->getCurl($url));
            $i=0;
            $url=$html->find('.topictitle',1)->href;
            $url=$this->clearSid($url);
        }

        $html=str_get_html($this->getCurl($url));
        $returnArray=array();

        //get previous link
        $url=$this->getPreviousLink($html);
        $url=$this->clearSid($url);

        $uri=JUri::getInstance($url);
        $returnArray['t']=$uri->getVar('t');
        //end get previous link

        //get title artical
        $title=$html->find('.titles',0)->plaintext;
        //end get article



        //get rows
        $rows=array();
        $i=0;
        $j=0;
        $countElement=count($html->find('div[id="pagecontent"] > table.tablebg'));
        foreach($html->find('div[id="pagecontent"] > table.tablebg') as $element)
        {
            if($i>0&&$i<$countElement)
            {
                $postauthor=$element->find('.postauthor',0)->plaintext;
                $postbody=$element->find('.postbody',0)->innertext;
                if($postauthor!=''&&$postbody!='')
                {
                    $rows[$j]['postauthor']=$postauthor;
                    $rows[$j]['postbody']=$postbody;
                    $j++;
                }
            }
            $i++;
        }


        //end get rows

        $returnArray['title']=$title;
        //$returnArray['rows']=$rows;
        // check exists topic
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__kunena_messages as messages');
        $query->select('messages.*');
        $query->where('messages.topicjoomlaforum_id='.$article_id);
        $db->setQuery($query);
        $messagers=$db->loadObjectList();
        $countMessagerStore=count($messagers);
        if($countMessagerStore==count($rows))
        {
            echo json_encode($returnArray);
            die;
        }
        //end check exists topic


        require_once JPATH_BASE."/components/com_kunena/controllers/topic.php";


        $controllerTopic=new KunenaControllerTopic();
        $controllerTopic->catid=$setForum_id;
        $controllerTopic->id=0;
        $first=true;
        $last_post_id=0;
        //if exists messager set parentid=$messagers

        if($countMessagerStore)
        {
            $last_post_id=reset($messagers)->thread;
            $first=false;
        }
        for($i=$countMessagerStore;$i<count($rows);$i++)
        {
            $row=$rows[$i];
            if($first)
            {
                JRequest::setVar('subject',$returnArray['title'],'POST');
            }
            else
            {
                JRequest::setVar('subject','Re:'.$returnArray['title'],'POST');
                JRequest::setVar('parentid',$last_post_id,'POST');
            }
            JRequest::setVar('topicjoomlaforum_id',$article_id,'POST');

            JRequest::setVar('authorname',$row['postauthor'],'POST');
            JRequest::setVar('message',$row['postbody'],'POST');
            JRequest::setVar('autopost',1,'POST');
            JRequest::setVar(JSession::getFormToken(),'1','POST');
            $last_post_id =$controllerTopic->post();
            $first=false;
        }
        echo json_encode($returnArray);
        die;
    }
    function savelinkwordpress()
    {
        error_reporting(0);
        $user=JFactory::getUser();
        if($user->id!=42)
        {
            echo json_encode(array());
            die;
        }
        include(JPATH_BASE.'/libraries/simplehtmldom_1_5/simple_html_dom.php');
        $input=JFactory::getApplication()->input;
        $pagenumber=$input->get('pagenumber',0,'int');
        if($pagenumber)
        {
            $url='http://wordpress.org/support/forum/themes-and-templates/page/'.$pagenumber;
        }
        else
        {
            $url='http://wordpress.org/support/forum/themes-and-templates';
        }
        if(!$pagenumber)
            $pagenumber=2;
        else
            $pagenumber++;

        $html=str_get_html($this->getCurl($url));

        $returnArray=array();
        $returnArray['pagenumber']=$pagenumber;

        $db=JFactory::getDbo();
        //get rows
        $rows=array();
        foreach($html->find('table.widefat tbody tr a') as $element)
        {
            $rows[]=$db->quote('wordpress') .','.$db->quote($element->href).','.$db->quote($url);
        }
        $query=$db->getQuery(true);
        $query->insert('#__kunena_link');
        $query->columns('type,link1,link2');
        $query->values($rows);
        $db->setQuery($query);
        $db->execute();
        echo json_encode($returnArray);
        die;
    }


    function updateDataAjaxFromJoomlaOrg($joomlaOrgForum_id=0,$setForum_id=0)
    {
        error_reporting(0);
        $user=JFactory::getUser();
        if($user->id==42)
        {
            echo json_encode(array());
            die;
        }
        include(JPATH_BASE.'/libraries/simplehtmldom_1_5/simple_html_dom.php');
        $input=JFactory::getApplication()->input;
        $article_id=$input->get('article_id',0,'int');
        if($article_id)
        {
            $url='http://forum.joomla.org/viewtopic.php?f='.$joomlaOrgForum_id.'&view=previous&t='.$article_id;
        }
        else
        {
            $url='http://forum.joomla.org/viewforum.php?f='.$joomlaOrgForum_id;
            $html = str_get_html($this->getCurl($url));
            $i=0;
            $url=$html->find('.topictitle',1)->href;
            $url=$this->clearSid($url);
        }

        $html=str_get_html($this->getCurl($url));
        $returnArray=array();

        //get previous link
        $url=$this->getPreviousLink($html);
        $url=$this->clearSid($url);

        $uri=JUri::getInstance($url);
        $returnArray['t']=$uri->getVar('t');
        //end get previous link

        //get title artical
        $title=$html->find('.titles',0)->plaintext;
        //end get article



        //get rows
        $rows=array();
        $i=0;
        $j=0;
        $countElement=count($html->find('div[id="pagecontent"] > table.tablebg'));
        foreach($html->find('div[id="pagecontent"] > table.tablebg') as $element)
        {
            if($i>0&&$i<$countElement)
            {
                $postauthor=$element->find('.postauthor',0)->plaintext;
                $postbody=$element->find('.postbody',0)->innertext;
                if($postauthor!=''&&$postbody!='')
                {
                    $rows[$j]['postauthor']=$postauthor;
                    $rows[$j]['postbody']=$postbody;
                    $j++;
                }
            }
            $i++;
        }


        //end get rows

        $returnArray['title']=$title;
        //$returnArray['rows']=$rows;
        // check exists topic
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__kunena_messages as messages');
        $query->select('messages.*');
        $query->where('messages.topicjoomlaforum_id='.$article_id);
        $db->setQuery($query);
        $messagers=$db->loadObjectList();
        $countMessagerStore=count($messagers);
        if($countMessagerStore==count($rows))
        {
            echo json_encode($returnArray);
            die;
        }
        //end check exists topic


        require_once JPATH_BASE."/components/com_kunena/controllers/topic.php";


        $controllerTopic=new KunenaControllerTopic();
        $controllerTopic->catid=$setForum_id;
        $controllerTopic->id=0;
        $first=true;
        $last_post_id=0;
        //if exists messager set parentid=$messagers

        if($countMessagerStore)
        {
            $last_post_id=reset($messagers)->thread;
            $first=false;
        }
        for($i=$countMessagerStore;$i<count($rows);$i++)
        {
            $row=$rows[$i];
            if($first)
            {
                JRequest::setVar('subject',$returnArray['title'],'POST');
            }
            else
            {
                JRequest::setVar('subject','Re:'.$returnArray['title'],'POST');
                JRequest::setVar('parentid',$last_post_id,'POST');
            }
            JRequest::setVar('topicjoomlaforum_id',$article_id,'POST');

            JRequest::setVar('authorname',$row['postauthor'],'POST');
            JRequest::setVar('message',$row['postbody'],'POST');
            JRequest::setVar('autopost',1,'POST');
            JRequest::setVar(JSession::getFormToken(),'1','POST');
            $last_post_id =$controllerTopic->post();
            $first=false;
        }
        echo json_encode($returnArray);
        die;
    }
    function saveContentWordpressFromLinkData($joomlaOrgForum_id=0,$setForum_id=0)
    {
        error_reporting(0);
        $user=JFactory::getUser();
        if($user->id!=42)
        {
            echo json_encode(array());
            die;
        }
        $db=JFactory::getDbo();
        include(JPATH_BASE.'/libraries/simplehtmldom_1_5/simple_html_dom.php');
        $input=JFactory::getApplication()->input;
        $link_id=$input->get('link_id',0,'int');
        if($link_id)
        {
            $query=$db->getQuery(true);
            $query->from('#__kunena_link as klink');
            $query->select('klink.id,klink.link1');
            $query->where('klink.id='.$link_id);
            $db->setQuery($query);
            $obj_url=$db->loadObject();
            $url=$obj_url->link1;
        }
        else
        {
            $query=$db->getQuery(true);
            $query->from('#__kunena_link as klink');
            $query->select('klink.id,klink.link1');
            $query->where('klink.state=1');
            $db->setQuery($query);
            $obj_url=$db->loadObject();
            $url=$obj_url->link1;
        }
        $html=str_get_html($this->getCurl($url));

        //get title artical
        $title=$html->find('div.topictitle h2',0);
        $title->find('small',0)->clear();
        $returnArray=array();
        $returnArray['title']=strip_tags(str_replace('</small>','',$title->innertext));
        //end get article
        $rows=array();
        $i=0;
        $countElement=count($html->find('li.postitem'));
        foreach($html->find('li.postitem') as $element)
        {

                $postauthor=$element->find('div.threadauthor p a',1)->innertext;
                    $postbody=$element->find('.post',0)->innertext;
                if($postauthor!=''&&$postbody!=''&&strip_tags($postbody)!='')
                {
                    $rows[$i]['postauthor']=$postauthor;
                    $rows[$i]['postbody']=str_replace(array('<','>'),array('[',']'),$postbody);
                    $i++;
                }
        }
        require_once JPATH_BASE."/components/com_kunena/controllers/topic.php";
        $controllerTopic=new KunenaControllerTopic();
        $controllerTopic->catid=12;
        $controllerTopic->id=0;
        $first=true;
        $last_post_id=0;
        //if exists messager set parentid=$messagers


        for($i=0;$i<count($rows);$i++)
        {
            $row=$rows[$i];
            if($first)
            {
                JRequest::setVar('subject',$returnArray['title'],'POST');
            }
            else
            {
                JRequest::setVar('subject','Re:'.$returnArray['title'],'POST');
                JRequest::setVar('parentid',$last_post_id,'POST');
            }
            JRequest::setVar('authorname',$row['postauthor'],'POST');
            JRequest::setVar('message',$row['postbody'],'POST');
            JRequest::setVar('autopost',1,'POST');
            JRequest::setVar(JSession::getFormToken(),'1','POST');
            $last_post_id =$controllerTopic->post();
            $first=false;
        }

        //update state=0
        $query=$db->getQuery(true);
        $query->update('#__kunena_link as klink');
        $query->set('klink.state=0');
        $query->where('klink.id='.$obj_url->id);
        $db->setQuery($query);
        $db->execute();
        //end update state

        //find next link
        $query=$db->getQuery(true);
        $query->from('#__kunena_link as klink');
        $query->select('klink.id,klink.link1');
        $query->where('klink.state=1');
        $db->setQuery($query);
        $obj_url=$db->loadObject();
        $url=$obj_url->link1;
        $returnArray['link_id']=$obj_url->id;

        //end find next link
        echo json_encode($returnArray);
        die;
       
    }
    function updateDataAjax()
    {
        //joomla.org:template joomla 2.x -forum id 619
        //this website template joomla 2.x -forum id 17
        //$this->updateDataAjaxFromJoomlaOrg(619,17);

        //joomla.org:template joomla 3.x -forum id 713
        //this website template joomla 3.x -forum id 16
        $this->updateDataAjaxFromJoomlaOrg(713,16);
    }
    function autoLoginByUserIdSystem($user_id=0)
    {
        $user=JFactory::getUser($user_id);
        $session =& JFactory::getSession();
        $session->set('user', $user);

    }
    function getPreviousLink($html)
    {
        foreach($html->find('td.nav a') as $element)
        {
            if($element->plaintext=='Previous topic')
            {

                $url=$element->href;
                break;
            }
        }
        return $url;
    }
    function clearSid($url)
    {
        $url= ltrim ($url,'.');
        $url='http://forum.joomla.org'.$url;
        $uri=JUri::getInstance($url);
        $uri->delVar('sid');
        $url=$uri->toString();
        return $url;
    }
    function getCurl($url)
    {
        $ch = curl_init();
        $header[] = "Accept: application/json";
        $header[] = "Accept-Encoding: gzip";
        $header[] = "Content-length: 0";
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_exec($ch);
        return $result;
    }
	function jump() {
		$catid = JRequest::getInt('catid', 0);
		if (!$catid) $this->setRedirect(KunenaRoute::_('index.php?option=com_kunena&view=category&layout=list', false));
		else $this->setRedirect(KunenaRoute::_("index.php?option=com_kunena&view=category&catid={$catid}", false));
	}

	function markread() {
		if (! JSession::checkToken ('request')) {
			$this->app->enqueueMessage ( JText::_ ( 'COM_KUNENA_ERROR_TOKEN' ), 'error' );
			$this->redirectBack ();
		}

		$catid = JRequest::getInt('catid', 0);
		if (!$catid) {
			// All categories
			$session = KunenaFactory::getSession();
			$session->markAllCategoriesRead ();
			if (!$session->save ()) {
				$this->app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_SESSION_SAVE_FAILED'), 'error' );
			} else {
				$this->app->enqueueMessage ( JText::_('COM_KUNENA_GEN_ALL_MARKED') );
			}
		} else {
			// One category
			$category = KunenaForumCategoryHelper::get($catid);
			if (!$category->authorise('read')) {
				$this->app->enqueueMessage ( $category->getError(), 'error' );
				$this->redirectBack ();
			}

			$session = KunenaFactory::getSession();
			if ($session->userid) {
				// Mark all unread topics in the category to read
				$userinfo = $category->getUserInfo();
				// FIXME: Joomla 2.5 ->toSql()
				$userinfo->allreadtime = JFactory::getDate()->toSql();
				if (!$userinfo->save()) {
					$this->app->enqueueMessage ( JText::_('COM_KUNENA_ERROR_SESSION_SAVE_FAILED'), 'error' );
				} else {
					$this->app->enqueueMessage ( JText::_('COM_KUNENA_GEN_FORUM_MARKED') );
				}
			}
		}
		$this->redirectBack ();
	}

	function subscribe() {
		if (! JSession::checkToken ('get')) {
			$this->app->enqueueMessage ( JText::_ ( 'COM_KUNENA_ERROR_TOKEN' ), 'error' );
			$this->redirectBack ();
		}

		$category = KunenaForumCategoryHelper::get(JRequest::getInt('catid', 0));
		if (!$category->authorise('read')) {
			$this->app->enqueueMessage ( $category->getError(), 'error' );
			$this->redirectBack ();
		}

		if ($this->me->exists()) {
			$success = $category->subscribe(1);
			if ($success) {
				$this->app->enqueueMessage ( JText::_('COM_KUNENA_GEN_CATEGORY_SUBCRIBED') );
			}
		}

		$this->redirectBack ();
	}

	function unsubscribe() {
		if (! JSession::checkToken ('request') ) {
			$this->app->enqueueMessage ( JText::_ ( 'COM_KUNENA_ERROR_TOKEN' ), 'error' );
			$this->redirectBack ();
		}

		$catid = JRequest::getInt('catid', 0);
		$catids = $catid ? array($catid) : array_keys(JRequest::getVar('categories', array(), 'post', 'array'));

		$categories = KunenaForumCategoryHelper::getCategories($catids);
		foreach($categories as $category) {
			if (!$category->authorise('read')) {
				$this->app->enqueueMessage ( $category->getError(), 'error' );
				continue;
			}
			if ($this->me->exists()) {
				$success = $category->subscribe(0);
				if ($success) {
					$this->app->enqueueMessage ( JText::sprintf('COM_KUNENA_GEN_CATEGORY_NAME_UNSUBCRIBED', $category->name) );
				}
			}
		}

		$this->redirectBack ();
	}
}