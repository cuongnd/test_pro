<?php
$root=__DIR__;
$listScript=$_SERVER['QUERY_STRING'];
$listScript=  base64_decode($listScript);
$listScript=  json_decode($listScript);
require_once $root.'/libraries/jsmin-php-master/lib/JSMin.php';
$content_tytpe=  get_content_type($root.'/'.reset($listScript));
$data='';
foreach ($listScript as $source)
{
    $parhFile = $root.'/'.$source;
    if(!file_exists($parhFile))
    {
        continue;
    }
    if($content_tytpe=='text/javascript')
    {
        $handle = fopen($parhFile, "r");
        $dataContent= fread($handle, filesize($parhFile));
        fclose($handle);
        $filename=basename($parhFile);
        $filename=strtolower($filename);
        if(strpos($filename,'.min.'))
        {
            $data .=$dataContent;
        }
        else {
            $data .= JSMin::minify($dataContent);
        }
    }else {
        $handle = fopen($parhFile, "r");
        $data .= fread($handle, filesize($parhFile));
        fclose($handle);
    }
}

_compress($data,$content_tytpe);

function googleCompressJs($jsContent) {
        $data = array(
            'output_file_name' => 'default.js'
            , 'compilation_level' => 'SIMPLE_OPTIMIZATIONS'
            , 'js_code' => $jsContent
            , 'output_format' => 'json'
            , 'output_info' => 'compiled_code'
            , 'warning_level' => 'VERBOSE'
        );

        $url = 'http://closure-compiler.appspot.com/compile';
        //$jsContent=$this->compress($jsContent);
        $headers[] = 'Content-type: application/x-www-form-urlencoded';
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process);
        $return = json_decode($return);
        $jsContent = $return->compiledCode;
        return $jsContent;
    }
    
    
function get_content_type($file) {
     // Determine Content-Type based on file extension
     // Default to text/html
     $info = pathinfo($file);
     $content_types = array(
         'css' => 'text/css; charset=UTF-8',
         'html' => 'text/html; charset=UTF-8',
         'gif' => 'image/gif',
         'ico' => 'image/x-icon',
         'jpg' => 'image/jpeg',
         'jpeg' => 'image/jpeg',
         'png' => 'image/png',
         'js' => 'text/javascript',
         'json' => 'application/json',
         'png' => 'image/png',
         'txt' => 'text/plain',
         'xml' => 'application/xml');
     if (empty($content_types[$info['extension']]))
         return 'text/html; charset=UTF-8';
     return $content_types[$info['extension']];
 }
  function _compress( $data,$content_type ) {
    $supportsGzip = strpos( $_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip' ) !== false;


    if ( $supportsGzip ) {
        $content = gzencode( trim( preg_replace( '/\s+/', ' ', $data ) ), 9);
    } else {
        $content = $data;
    }

    $offset = 60 * 60;
    $expire = "expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";

    header('Content-Encoding: gzip');
    header("content-type: $content_type; charset: UTF-8");
    header("cache-control: must-revalidate");
    header( $expire );
    header( 'Content-Length: ' . strlen( $content ) );
    header('Vary: Accept-Encoding');

    echo $content;
    die;

}
?>