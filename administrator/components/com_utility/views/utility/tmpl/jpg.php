<?php
jimport('joomla.filesystem.file');
$app=JFactory::getApplication();
JFactory::getDocument()->setMimeEncoding('text/javascript');
$file=$app->input->get('file','','string');
$content= JFILE::read(JPATH_ROOT.'/'.$file);
echo $content;
?>