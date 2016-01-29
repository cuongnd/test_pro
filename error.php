<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
if (version_compare(PHP_VERSION, '5.3.10', '<'))
{
    die('Your host needs to use PHP 5.3.10 or higher to run this version of Joomla!');
}

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_JEXEC', 1);

if (file_exists(__DIR__ . '/defines.php'))
{
    include_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
    define('JPATH_BASE', __DIR__);
    require_once JPATH_BASE . '/includes/defines.php';
}
require_once JPATH_BASE . '/includes/framework.php';

// Mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->mark('afterLoad') : null;


// Instantiate the application.
$app = JFactory::getApplication('site');

jimport('joomla.filesystem.file');
$content= JFile::read(JPATH_ROOT.'/tmp/php-scripts.log');
$delete_log=$app->input->get('delete_log',0,'int');
if($delete_log)
{
    JFile::delete(JPATH_ROOT.'/tmp/php-scripts.log');
}
$content=explode("\n",$content);
$content=array_reverse($content);
?>
<form action="error.php" method="post">
    <div style="text-align: right"><button type="submit" value="1" name="delete_log">Delete log</button><button type="submit" value="0" name="reset">Reset</button></div>
    <hr/>
<?php
foreach($content as $row)
{
    echo $row;
    echo "<hr/>";
}
// Execute the application.
?>
</form>
