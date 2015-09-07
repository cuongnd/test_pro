<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Routines management.
 *
 * @package PhpMyAdmin
 */

/**
 * Include required files
 */
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/common.inc.php';
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/Util.class.php';
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/mysql_charsets.inc.php';

/**
 * Include all other files
 */
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/rte/rte_routines.lib.php';

/**
 * Do the magic
 */
$_PMA_RTE = 'RTN';
require_once JPATH_ROOT.'/administrator/components/com_phpmyadmin/assets/libraries/rte/rte_main.inc.php';

?>
