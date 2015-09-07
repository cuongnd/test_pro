<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * displays the advisor feature
 *
 * @package PhpMyAdmin
 */

require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/common.inc.php';
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/Advisor.class.php';
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/ServerStatusData.class.php';
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/server_status_advisor.lib.php';

if (PMA_DRIZZLE) {
    $server_master_status = false;
    $server_slave_status = false;
} else {
    include_once  JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/replication.inc.php';
    include_once  JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/replication_gui.lib.php';
}

$ServerStatusData = new PMA_ServerStatusData();

$response = PMA_Response::getInstance();
$scripts = $response->getHeader()->getScripts();
$scripts->addFile('server_status_advisor.js');

/**
 * Output
 */
$response->addHTML('<div>');
$response->addHTML($ServerStatusData->getMenuHtml());
$response->addHTML(PMA_getHtmlForAdvisor());
$response->addHTML('</div>');
exit;



?>
