<?php
return;
define('_JEXEC', 1);
$parts = explode(DIRECTORY_SEPARATOR, __DIR__);
array_pop($parts);
define('JPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));
if (file_exists(JPATH_ROOT.'/defines.php'))
{
    include_once JPATH_ROOT.'/defines.php';
}

if (!defined('_JDEFINES'))
{
    define('JPATH_BASE', JPATH_ROOT);
    require_once JPATH_ROOT . '/includes/defines.php';
}

// Defines.


require_once JPATH_BASE . '/includes/framework.php';
$app=JFactory::getApplication('site');
JHtml::_('jquery.framework');
?>

