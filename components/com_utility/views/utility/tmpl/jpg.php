<?php
jimport('joomla.filesystem.file');
$app=JFactory::getApplication();
$doc=JFactory::getDocument();
JFactory::getDocument()->setMimeEncoding('image/jpeg');
$file=$app->input->get('file','','string');
header('Content-Type: image/jpeg');
ob_clean();
if(JFile::exists(JPATH_ROOT.'/'.$file))
{
    $content= JFILE::read(JPATH_ROOT.'/'.$file);
    echo  $content;
}
?>
