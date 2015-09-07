<?php
$app=JFactory::getApplication();
JFactory::getDocument()->setMimeEncoding('text/css');
jimport('joomla.filesystem.file');
$file=$app->input->get('file','','string');
$content= JFILE::read(JPATH_ROOT.'/'.$file);
echo $content;