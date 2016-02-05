<?php
jimport('joomla.filesystem.file');
$app=JFactory::getApplication();
$doc=JFactory::getDocument();
JFactory::getDocument()->setMimeEncoding('image/png');
$file=$app->input->get('file','','string');
header('Content-Type: image/png');
ob_clean();
if(JFile::exists(JPATH_ROOT.'/'.$file))
{
    $content= JFILE::read(JPATH_ROOT.'/'.$file);
    echo  $content;
}
?>
