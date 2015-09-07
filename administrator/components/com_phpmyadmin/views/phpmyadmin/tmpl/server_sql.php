<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Server SQL executor
 *
 * @package PhpMyAdmin
 */

/**
 *
 */
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/common.inc.php';

/**
 * Does the common work
 */
$response = PMA_Response::getInstance();
$header   = $response->getHeader();
$scripts  = $header->getScripts();
$scripts->addFile('makegrid.js');
$scripts->addFile('sql.js');

require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/server_common.inc.php';
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/sql_query_form.lib.php';

/**
 * Query box, bookmark, insert data from textfile
 */
$response->addHTML(PMA_getHtmlForSqlQueryForm());

?>
