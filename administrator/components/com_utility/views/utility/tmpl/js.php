<?php
jimport('joomla.filesystem.file');
$app=JFactory::getApplication();
$doc=JFactory::getDocument();
JFactory::getDocument()->setMimeEncoding('text/javascript');
$file=$app->input->get('file','','string');
$config=JFactory::getConfig();
$path_parts = pathinfo($file);
$filename=$path_parts['filename'];
$fileMin=JPATH_ROOT.'/'.$path_parts['dirname'].'/'.$filename.'.min.js';
if(JFile::exists($fileMin)&&$config->get('minjs',0))
{
    $content= JFILE::read($fileMin);
    if(!$content)
    {
        echo "check lai cu phap file nay";
        return;
    }else
    {
        echo  $content;
    }
}else {
    $content = JFILE::read(JPATH_ROOT . '/' . $file);

    if ($config->get('minjs', 0)) {
        require_once JPATH_ROOT . '/libraries/jsmin-php-master/lib/JSMin.php';
        $content = JSMin::minify($content);
        jimport('joomla.utilities.utility');
        $content = JUtility::googleCompressJs($content);
        if (!$content) {
            echo "check lai cu phap file nay";
            return;
        }
        JFile::write($fileMin, $content);
        echo $content;
    } else {
        echo $content;
    }
}
?>