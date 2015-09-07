




<?php
$HTTP_ACCEPT_ENCODING = $_SERVER["HTTP_ACCEPT_ENCODING"];
if( headers_sent() )
    $encoding = false;
else if( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false )
    $encoding = 'x-gzip';
else if( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false )
    $encoding = 'gzip';
else
    $encoding = false;

if( $encoding )
{
    $contents = ' ';
    ob_clean();
    ob_start();
    ?>
    sdss<?php

    $contents=ob_get_clean();
    $tidy = tidy_parse_string($contents);

    $html = $tidy->html();
    $contents= $html->value;
    $contents = trim(preg_replace('/\s\s+/', ' ', $contents));
    $_temp1 = strlen($contents);
    if ($_temp1 < 2048)    // no need to waste resources in compressing very little data
    {

        print($contents);
    }
    else
    {
        header('Content-Encoding: '.$encoding);

        print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
        $contents = gzcompress($contents, 9);
        $contents = substr($contents, 0, $_temp1);
        print($contents);
    }
}
else
    ob_end_flush();
?>