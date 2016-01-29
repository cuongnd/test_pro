<?php

/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die(__FILE__);

/**
 * JDocument head renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentRendererHead extends JDocumentRenderer {

    /**
     * Renders the document head and returns the results as a string
     *
     * @param   string  $head     (unused)
     * @param   array   $params   Associative array of values
     * @param   string  $content  The script
     *
     * @return  string  The output of the script
     *
     * @since   11.1
     *
     * @note    Unused arguments are retained to preserve backward compatibility.
     */
    public function render($head, $params = array(), $content = null) {
        return $this->fetchHead($this->_doc);
    }

    /**
     * Generates the head HTML and return the results as a string
     *
     * @param   JDocument  $document  The document for which the head will be created
     *
     * @return  string  The head hTML
     *
     * @since   11.1
     */
    public function fetchHead($document) {
        // Convert the tagids to titles
        if (isset($document->_metaTags['standard']['tags'])) {
            $tagsHelper = new JHelperTags;
            $document->_metaTags['standard']['tags'] = implode(', ', $tagsHelper->getTagNames($document->_metaTags['standard']['tags']));
        }

        // Trigger the onBeforeCompileHead event
        $app = JFactory::getApplication();
        $app->triggerEvent('onBeforeCompileHead');

        // Get line endings
        $lnEnd = $document->_getLineEnd();
        $tab = $document->_getTab();
        $tagEnd = ' />';
        $buffer = '';

        // Generate charset when using HTML5 (should happen first)
        if ($document->isHtml5()) {
            $buffer .= $tab . '<meta charset="' . $document->getCharset() . '" />' . $lnEnd;
        }

        // Generate base tag (need to happen early)
        $base = $document->getBase();
        if (!empty($base)) {
            $buffer .= $tab . '<base href="' . $document->getBase() . '" />' . $lnEnd;
        }

        // Generate META tags (needs to happen as early as possible in the head)
        foreach ($document->_metaTags as $type => $tag) {
            foreach ($tag as $name => $content) {
                if ($type == 'http-equiv' && !($document->isHtml5() && $name == 'content-type')) {
                    $buffer .= $tab . '<meta http-equiv="' . $name . '" content="' . htmlspecialchars($content) . '" />' . $lnEnd;
                } elseif ($type == 'standard' && !empty($content)) {
                    $buffer .= $tab . '<meta name="' . $name . '" content="' . htmlspecialchars($content) . '" />' . $lnEnd;
                }
            }
        }

        // Don't add empty descriptions
        $documentDescription = $document->getDescription();
        if ($documentDescription) {
            $buffer .= $tab . '<meta name="description" content="' . htmlspecialchars($documentDescription) . '" />' . $lnEnd;
        }

        // Don't add empty generators
        $generator = $document->getGenerator();
        if ($generator) {
            $buffer .= $tab . '<meta name="generator" content="' . htmlspecialchars($generator) . '" />' . $lnEnd;
        }

        $buffer .= $tab . '<title>' . htmlspecialchars($document->getTitle(), ENT_COMPAT, 'UTF-8') . '</title>' . $lnEnd;

        // Generate link declarations
        foreach ($document->_links as $link => $linkAtrr) {
            $buffer .= $tab . '<link href="' . $link . '" ' . $linkAtrr['relType'] . '="' . $linkAtrr['relation'] . '"';
            if ($temp = JArrayHelper::toString($linkAtrr['attribs'])) {
                $buffer .= ' ' . $temp;
            }
            $buffer .= ' />' . $lnEnd;
        }
        $liststyleSheets= $this->gzObject($document->_styleSheets,false);

        foreach ($liststyleSheets as  $source=>$object) {
            $list_attribs=$object['attribs'];
            if(!$list_attribs['rel'])
            {
                $list_attribs['rel']='stylesheet';
            }
            $attribs=array();
            foreach($list_attribs as $key_attribute=>$value_attribute)
            {
                $attribs[]="$key_attribute=\"$value_attribute\"";
            }
            $attribs=implode(' ',$attribs);
            $source=JUtility::format_url($source);
            //$buffer.='<link rel="stylesheet" type="text/css" media="screen"  href="'.JUri::root().'index.php?option=com_utility&task=utility.loadFile&file='.$source.'&tmpl=sourcecss&type=css">';
            $buffer.='<link  '.$attribs.' type="'.$object['mime'].'" media="screen"  href="'.JUri::root().$source.'"/>';
        }
        
        // Generate stylesheet links
        
         
        
        // Generate stylesheet declarations
        foreach ($document->_style as $type => $content) {
            $buffer .= $tab . '<style type="' . $type . '">' . $lnEnd;

            // This is for full XHTML support.
            if ($document->_mime != 'text/html') {
                $buffer .= $tab . $tab . '<![CDATA[' . $lnEnd;
            } 

            $buffer .= $content . $lnEnd;

            // See above note
            if ($document->_mime != 'text/html') {
                $buffer .= $tab . $tab . ']]>' . $lnEnd;
            }
            $buffer .= $tab . '</style>' . $lnEnd;
        }
        //$this->cacheJs($document->_scripts);
        //$this->cacheThisJs($document->_scripts);

        $listScript= $this->gzObject($document->_scripts,false);

        $app=JFactory::getApplication();
        $get_min_js=$app->input->get('get_min_js',0);
        $make_min_js=$app->input->get('make_min_js',0);
        if($get_min_js==1&&$make_min_js==0)
        {
            $listScript=$this->get_min_js($listScript);
        }
        if($make_min_js==1)
        {
            $listScript=$this->create_min_js($listScript);
        }

        foreach ($listScript as $source=>$object) {
            //$buffer.="\n".'<script type="text/javascript"  src="'.JUri::root().'index.php?option=com_utility&task=utility.loadFile&file='.$source.'&tmpl=sourcejs&type=js"></script>';
            $buffer.="\n".'<script type="'.$object['mime'].'"  src="'.JUri::root().$source.'"></script>';
        }
        foreach ($document->_scriptDeclaration as $script) {
            $buffer.="\n".'<script type="text/javascript"'.($script->scriptId?' id="'.$script->scriptId.'" ':'').'>'.$script->scriptDeclaration.'</script>';
        }
        $buffer.="\n";


        foreach ($document->_scriptAjaxCallFunction as $key=>$script) {
            $buffer.='<script type="text/javascript" id="'.$script['scriptId'].'">';
            $buffer.='jQuery(document).ready(function($){';
            $buffer.="\n";
            $buffer.=$script['scriptContent'];
            $buffer.="$key();";
            $buffer.='});';
            $buffer.='</script>';
        }




        // Generate script language declarations.
        if (count(JText::script())) {
            $buffer .= $tab . '<script type="text/javascript">' . $lnEnd;
            $buffer .= $tab . $tab . '(function() {' . $lnEnd;
            $buffer .= $tab . $tab . $tab . 'var strings = ' . json_encode(JText::script()) . ';' . $lnEnd;
            $buffer .= $tab . $tab . $tab . 'if (typeof Joomla == \'undefined\') {' . $lnEnd;
            $buffer .= $tab . $tab . $tab . $tab . 'Joomla = {};' . $lnEnd;
            $buffer .= $tab . $tab . $tab . $tab . 'Joomla.JText = strings;' . $lnEnd;
            $buffer .= $tab . $tab . $tab . '}' . $lnEnd;
            $buffer .= $tab . $tab . $tab . 'else {' . $lnEnd;
            $buffer .= $tab . $tab . $tab . $tab . 'Joomla.JText.load(strings);' . $lnEnd;
            $buffer .= $tab . $tab . $tab . '}' . $lnEnd;
            $buffer .= $tab . $tab . '})();' . $lnEnd;
            $buffer .= $tab . '</script>' . $lnEnd;
        }

        foreach ($document->_custom as $custom) {
            $buffer .= $tab . $custom . $lnEnd;
        }
        return $buffer;
    }
    function gzObject($arrrayobject,$base64=true)
    {

        $listObject=array();
        foreach($arrrayobject as $source=>$object)
        {
            if(filter_var($source, FILTER_VALIDATE_URL))
            {
                $uri=  JFactory::getURI($source);
                $path=$uri->getPath();

                if(strpos($path, '?'))
                {
                    $path=  explode('?', $path);
                    $path=$path[0];
                }
                $path=  explode('/', $path);
                foreach ($path as $key=>$item)
                {
                    if($item=='')
                        unset($path[$key]);
                }
                $path=  implode('/', $path);
                $listObject[$path]=$object;
            }
            else
            {
                if(strpos($source, '?'))
                {
                    $source=  explode('?', $source);
                    $source=$source[0];
                }
                $path=  explode('/', $source);
                foreach ($path as $key=>$item)
                {
                    if($item=='')
                        unset($path[$key]);
                }
                $path=  implode('/', $path);
                $listObject[$path]=$object;
                
            }
        }

        if($base64)
        {
            $listObject=  json_encode($listObject);
            $listObject=  base64_encode($listObject);
            return $listObject ;
        }else
        {
            return $listObject;
        }
    }
    function create_min_js($arrrayobject)
    {
        require_once JPATH_ROOT.'/libraries/jsmin-php-master/lib/php-closure.php';
        require_once JPATH_ROOT.'/libraries/jsmin-php-master/lib/JSMin.php';

        foreach($arrrayobject as $source=>$object)
        {
           /* [dirname] => H:\project\test_pro/media/jui_front_end/js
            [basename] => jquery.js
            [extension] => js
            [filename] => jquery*/

            $file_info = pathinfo($object);
            $filename=strtolower($file_info['filename']);
            if (strpos($filename,'.min') !== false) {
                continue;
            }

            $php_closure = new PhpClosure();
            $php_closure->add(JPATH_ROOT.'/'.$object)
                ->advancedMode()
                ->useClosureLibrary()
                ->cacheDir(JPATH_ROOT."/tmp/js-cache/")
            ;
            $js_min_content=  $php_closure->get_content();
            $file_min_js=$file_info["dirname"].'/'.$file_info["filename"].'.min.js';
            if(trim($js_min_content)!='') {
                JFile::write(JPATH_ROOT . '/' . $file_min_js, $js_min_content);
                $arrrayobject[$source] = $file_min_js;
            }
        }
        return $arrrayobject;
    }
    function get_min_js($arrrayobject)
    {

        foreach($arrrayobject as $source=>$object)
        {
           /* [dirname] => H:\project\test_pro/media/jui_front_end/js
            [basename] => jquery.js
            [extension] => js
            [filename] => jquery*/

            $file_info = pathinfo($object);
            $file_min_js=$file_info["dirname"].'/'.$file_info["filename"].'.min.js';
            $arrrayobject[$source]=$file_min_js;
        }
        return $arrrayobject;
    }
    function cacheJs(&$documentScripts) {
        $scripts = array();
        foreach ($documentScripts as $strSrc => $strAttr) {
            $host = '';
            $url = trim($strSrc);
            if (filter_var($url, FILTER_VALIDATE_URL)) {

                $uri = JFactory::getURI($url);

                $url = $uri->getPath();
                $website = JFactory::getWebsite();
                $domain = $website->domain;
                if ($domain != $uri->getHost())
                    $host = $uri->toString(array('scheme', 'host', 'port')) . '/';
            }

            $qa_path = explode('/', $url);

            foreach ($qa_path as $key => $path) {

                if (trim($path) == '') {
                    unset($qa_path[$key]);
                }
            }
            $url = $host . implode('/', $qa_path);
            if (!array_key_exists($url, $scripts)) {
                $scripts[$url] = $url;
            }
        }

        // Generate script file links

        require_once JPATH_ROOT . '/libraries/jsmin-php-master/lib/JSMin.php';
        $jsContent = '';
        $memory_js = 0;
        $listJs = array();
        $tempFile = array();
        foreach ($scripts as $strSrc) {
            if (!filter_var($strSrc, FILTER_VALIDATE_URL)) {
                $jsContent = file_get_contents(JUri::root() . '/' . $strSrc);
                $memory_js+=JUtility::getMemoryUseByVar($jsContent, 'B', 0, false);
                $tempFile[$strSrc] = $strSrc;
                $tempFile[$strSrc] = $strSrc;
                if ((int) $memory_js > 300) {
                    $memory_js = 0;
                    $listJs[] = $tempFile;
                    $tempFile = array();
                }
            }
        }
        if (count($tempFile))
            $listJs[] = $tempFile;
        unset($tempFile);
        unset($jsContent);
        $cacheJs = 'cache/js';
        foreach ($listJs as $strSrc => $jss) {
            $jsContent = '';
            foreach ($jss as $fileJs) {
                $jsContent.=file_get_contents(JPATH_ROOT . '/' . $fileJs);
            }
            $jsContent = $jsContent = JSMin::minify($jsContent);
            $fileWrite = $cacheJs . '/js-' . JUserHelper::genRandomPassword(4) . '.js';
            $myfile = fopen(JPATH_ROOT . '/' . $fileWrite, "a");
            fwrite($myfile, $jsContent);
            fclose($myfile);
            $documentScripts[$fileWrite] = array(
                "mime" => 'text/x-javascript'
                , "defer" => ''
                , "async" => ''
            );
        }
    }

    function cacheThisJs(&$documentScripts) {

        $scripts = array();
        foreach ($documentScripts as $strSrc => $strAttr) {
            $host = '';
            $url = trim($strSrc);
            $array_url = explode('?', $url);
            $url = $array_url[0];
            if (filter_var($url, FILTER_VALIDATE_URL)) {

                $uri = JFactory::getURI($url);

                $url = $uri->getPath();
                $website = JFactory::getWebsite();
                $domain = $website->domain;
                if ($domain != $uri->getHost())
                    $host = $uri->toString(array('scheme', 'host', 'port')) . '/';
            }

            $qa_path = explode('/', $url);

            foreach ($qa_path as $key => $path) {

                if (trim($path) == '') {
                    unset($qa_path[$key]);
                }
            }
            $url = $host . implode('/', $qa_path);
            if (!array_key_exists($url, $scripts)) {
                $scripts[$url] = $url;
            }
            unset($documentScripts[$strSrc]);
        }

        // Generate script file links


        $cacheJs = 'cache/js';
        $app = JFactory::getApplication();
        $cachePath = $app->isAdmin() ? JPATH_ADMINISTRATOR : JPATH_ROOT;
        foreach ($scripts as $strSrc => $js) {

            //$jsContent=JSMin::minify($jsContent);

            $fileWrite = $cacheJs . '/' . $js . '.php';

            if (!file_exists($cachePath . '/' . $fileWrite)) {
                $jsContent = file_get_contents(JPATH_ROOT . '/' . $js);
                //$jsContent=$this->googleCompressJs($jsContent);
                mkdir(dirname($cachePath . '/' . $fileWrite), 0777, true);
                $myfile = fopen($cachePath . '/' . $fileWrite, "a");
                //$jsContent=$this->_compressGzip($jsContent);
                $jsContent = '<?php // compress JS
                header("content-type: text/javascript; charset: UTF-8");
                header("cache-control: must-revalidate");
                $offset = 365 * 24 * 60 * 60;
                $expire = "expires: ".gmdate("D, d M Y H:i:s", time() + $offset)." GMT";
                header($expire);
                if(!ob_start("ob_gzhandler")) ob_start();
                ?>
                 ' . "\n" . $jsContent . "\n" . '
                <?php // replace this line with as much JavaScript code as you want ?>

                <?php ob_flush(); ?>
                ';
                fwrite($myfile, $jsContent);
                fclose($myfile);
            }
            $documentScripts[$fileWrite] = array(
                "mime" => 'text/javascript'
                , "defer" => ''
                , "async" => ''
            );
        }
    }


    function _compressGzip($data, $contentTye = 'text/javascript') {

        $content = '<?php
        $data=\'' . str_replace("'", "\'", $data) . '\';
        $data1=$data;
        $supportsGzip = strpos( $_SERVER[\'HTTP_ACCEPT_ENCODING\'], \'gzip\' ) !== false;


        if ( $supportsGzip ) {
            $content = gzencode( trim( preg_replace( \'/\\s+/\', \' \',$data ) ), 9);
        } else {
            $content = $data;
        }

        $offset = 60 * 60;
        $expire = "expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";

        header(\'Content-Encoding: gzip\');
        header("cache-control: must-revalidate");
        header(\'Content-type: text/javascript\');
        header( $expire );
        header( \'Content-Length: \' . strlen( $content ) );
        header(\'Vary: Accept-Encoding\');
        if(!ob_start("ob_gzhandler")) ob_start();
         echo $data1;

         ob_flush();
          ?>
        ';
        return $content;
    }

    function compress($buffer) {
        /* remove comments */
        $buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);
        /* remove tabs, spaces, newlines, etc. */
        $buffer = str_replace(array("\r\n", "\r", "\t", "\n", '  ', '    ', '     '), '', $buffer);
        /* remove other spaces before/after ) */
        $buffer = preg_replace(array('(( )+\))', '(\)( )+)'), ')', $buffer);
        return $buffer;
    }

}
