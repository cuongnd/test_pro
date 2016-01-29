<?php
jimport('joomla.filesystem.file');
$app=JFactory::getApplication();
$doc=JFactory::getDocument();
JFactory::getDocument()->setMimeEncoding('text/css');
$file=$app->input->get('file','','string');
header("Content-Type: text/css");
if(JFile::exists(JPATH_ROOT.'/'.$file))
{
    $content= JFILE::read(JPATH_ROOT.'/'.$file);
    echo  $content;
}
?>