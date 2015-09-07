<?php
class cronemail
{

    public  function _connect($account_id) {
        JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
        $account = JTable::getInstance('Crons','BookProTable');
        $account->load($account_id);
        // {[server]:[port][flags]}
        $server = $account->server;
        $port = $account->port;
        $flags = '/'.$account->protocol;

        if ($account->security)
            $flags .= '/'.$account->security;
        if (!$account->validate)
            $flags .= '/novalidate-cert';

        $connect = '{'.$server.':'.$port.$flags.'}INBOX';

        $mbox = @imap_open($connect, $account->username, $account->password);
        return $mbox;
    }
    public  function _getConnectionErrors() {
        $return = imap_errors();
        if (!is_array($return))
            $return = array();

        return $return;
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


}
?>