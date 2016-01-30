<?php
jimport('joomla.filesystem.file');
$app=JFactory::getApplication();
ob_clean();
JFactory::getDocument()->setMimeEncoding('application/ttf');
$file=$app->input->get('file','','string');
$content= JFILE::read(JPATH_ROOT.'/'.$file);
echo $content;
?>