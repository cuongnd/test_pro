<?php
jimport('joomla.filesystem.file');
$app=JFactory::getApplication();
JFactory::getDocument()->setMimeEncoding('image/png');
$file=$app->input->get('file','','string');
$content= JFILE::read(JPATH_ROOT.'/'.$file);
header('Content-Type: image/png');
echo $content;
?>