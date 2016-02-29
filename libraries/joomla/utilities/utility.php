<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Utilities
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * JUtility is a utility functions class
 *
 * @package     Joomla.Platform
 * @subpackage  Utilities
 * @since       11.1
 */
class JUtility
{
    /**
     * Method to extract key/value pairs out of a string with XML style attributes
     *
     * @param   string $string String containing XML style attributes
     *
     * @return  array  Key/Value pairs for the attributes
     *
     * @since   11.1
     */

    public static function parseAttributes($string)
    {
        $attr = array();
        $retarray = array();

        // Let's grab all the key/value pairs using a regular expression
        preg_match_all('/([\w:-]+)[\s]?=[\s]?"([^"]*)"/i', $string, $attr);

        if (is_array($attr)) {
            $numPairs = count($attr[1]);

            for ($i = 0; $i < $numPairs; $i++) {
                $retarray[$attr[1][$i]] = $attr[2][$i];
            }
        }

        return $retarray;
    }

    public static function check_out_off_memory_size()
    {
        $php_value_memory_limit=(int)ini_get('memory_limit');
        $memory_usage=(int)memory_get_usage(true);
        $memory_usage=(int)JUtility::byteToOtherUnit($memory_usage,'MB');
        if($memory_usage>=$php_value_memory_limit-3)
        {
           echo JUtility::printDebugBacktrace();
            die;
        }
    }

    public static function format_url($url='')
    {
        $uri = JUri::getInstance($url);
        $path=$uri->getPath();
        $path=explode('/',$path);
        $path1=array();
        foreach($path as $item)
        {
            if(trim($item)!='')
            {
                $path1[]=$item;
            }
        }
        $path=implode('/',$path1);
        $uri->setPath($path);
        return $uri->toString();
    }
    function render_to_xml($fields,$maxLevel = 9999, $level = 0)
    {
        if($level<=$maxLevel)
        {
            foreach ($fields as $item) {
                $level1=$level+1;
                if(is_array($item->children)&&count($item->children)>0 ) {
                    if($level==0){
                        if(strtolower($item->name)!='option')
                        {
                            echo '<fields name="'.strtolower($item->name).'">';
                        }
                    }else{
                        echo '<fields name="'.strtolower($item->name).'">';
                    }
                    JModelAdmin::render_to_xml($item->children,  $maxLevel, $level1);
                    if($level==0){
                        if(strtolower($item->name)!='option')
                        {
                            echo '</fields>';
                        }
                    }else{
                        echo '</fields>';
                    }
                }else{
                    $config_property=$item->config_property;
                    $config_property=base64_decode($config_property);
                    $config_property = (array)up_json_decode($config_property, false, 512, JSON_PARSE_JAVASCRIPT);

                    $config_params=$item->config_params;
                    $config_params=base64_decode($config_params);
                    $config_params = (array)up_json_decode($config_params, false, 512, JSON_PARSE_JAVASCRIPT);
                    $name=strtolower($item->name);
                    ?>

                    <field type="<?php echo $item->type?$item->type:'text' ?>" readonly="<?php echo $item->readonly==1?'true':'false' ?>" label="<?php echo $item->label ?>" default="<?php echo $item->default ?>"
                           name="<?php echo $name ?>" onchange="<?php echo strtolower($item->onchange) ?>"


                        <?php
                        foreach($config_property as $a_item){ ?>
                            <?php if($a_item->property_key&&$a_item->property_value){
                                echo " ";
                                echo "{$a_item->property_key}=\"{$a_item->property_value}\"";
                                echo " ";
                            } ?>
                        <?php }


                        ?>
                        >
                        <?php if(count($config_params)){

                            foreach($config_params as $a_item){ ?>
                                <?php if($a_item->param_key!=''&&$a_item->param_value!=''){ ?>
                                    <option value="<?php echo $a_item->param_key ?>"><?php echo $a_item->param_value ?></option>
                                <?php } ?>
                            <?php }
                        } ?>
                    </field>
                    <field type="checkbox" label="<?php echo $item->label ?>" default="0"
                           name="enable_<?php echo $name ?>" onchange="<?php echo strtolower($item->onchange) ?>">

                    </field>
                    <?php
                }

            }

        }

    }

    public static function get_cache_var_by_cache_id($cache_id,$group='_system',$handler='callback')
    {
        $cache = JFactory::getCache($group, $handler);
        $caching=$cache->cache->getCaching();
        $cache->setCaching(1);
        $var=$cache->cache->get($cache_id);
        $cache->setCaching($caching);
        return $var;
    }

    public static function get_up_json_decode($string_base_64='')
    {

        if (base64_encode(base64_decode($string_base_64, true)) == $string_base_64) {
            $object_base_64 = base64_decode($string_base_64, true);

        } else {
            $object_base_64 = '';
        }
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $object_base_64 = up_json_decode($object_base_64, false, 512, JSON_PARSE_JAVASCRIPT);
        return $object_base_64;


    }

    public function replate_request($string)
    {
        $input = JFactory::getApplication()->input;
        $requestString = '/(.*?)request(\(|\'|)(.*?)(\)|\'| )/s';
        preg_match_all($requestString, $string, $requests);
        $requests = $requests[3];
        $listRequest = array();
        foreach ($requests as $request) {
            $request = explode(',', $request);
            if (strtolower($request[0]) == 'website_id') {

                $website_id = $input->get('website_id', 0);
                if (!$website_id) {
                    $website = JFactory::getWebsite();
                    $website_id = $website->website_id;
                }
                $listRequest[] = $website_id;
            } else {
                $listRequest[] = $input->get($request[0], $request[1]);
            }
        }
        $listRequest2 = array();
        foreach ($requests as $request) {
            $listRequest2[] = 'request(' . $request . ')';
        }
        $string = str_ireplace($listRequest2, $listRequest, $string);

        return $string;

    }

    public function get_value_by_key($item, $list_key = array(), $order_key = 0)
    {

        if ($order_key != count($list_key) - 1) {
            $key = $list_key[$order_key];
            if (trim($key) == '') {
                echo "<pre>";
                echo "key is null";
                echo "<br/>";
                print_r($item);
                echo "</pre>";
                die;
            }
            $item1 = $item->{$list_key[$order_key]};
            $order_key1 = $order_key + 1;
            return JUtility::get_value_by_key($item1, $list_key, $order_key1);
        } else {
            return $item->{$list_key[$order_key]};
        }

    }

    public static function toStrictBoolean($_val, $_trueValues = array('yes', 'y', 'true', 'on','1'), $_forceLowercase = true)
    {
        if (is_string($_val)) {
            return (in_array(
                ($_forceLowercase ? strtolower($_val) : $_val)
                , $_trueValues)
            );
        } else {
            return (boolean)$_val;
        }
    }

    public static function isJson($string)
    {
        return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
    }

    public function get_class_icon_font()
    {
        jimport('joomla.filesystem.file');
        $iconFiles = array(
            'templates/sprflat/assets/less/icons.less'
        );
        $content = '';
        foreach ($iconFiles as $file) {
            $content .= JFile::read(JPATH_ROOT . '/' . $file);
        }
        $icon_class = array();
        $requestString = '/(.*?).(\(|\'|)(.*?)(:before(.*?){)/';
        preg_match_all($requestString, $content, $icon_class);
        $icon_class = $icon_class[3];
        return $icon_class;
    }

    public function get_class_icon_flag_image()
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        $folder_flags = 'images/all_flags';
        $flags = JFolder::files(JPATH_ROOT . '/' . $folder_flags, 'gif');
        $list_full_path_flag = array();
        foreach ($flags as $flag) {
            $list_full_path_flag[] = $folder_flags . '/' . $flag;
        }
        return $list_full_path_flag;
    }

    public function get_content_file(&$object, $file_php, $table, $filed, $primary_key = 'id')
    {

        $app = JFactory::getApplication();
        $table = str_replace('#__', '', $table);
        require_once JPATH_ROOT . '/components/com_phpmyadmin/tables/updatetable.php';
        $db = JFactory::getDbo();
        $table = new JTableUpdateTable($db, $table, $primary_key);

        $table->load($object->$primary_key);
        $params_filed = explode('.', $filed);
        if (count($params_filed) > 1) {
            $params_filed = array_reverse($params_filed);
            $master_key = array_pop($params_filed);
            $params_filed = array_reverse($params_filed);
            $filed1 = implode('.', $params_filed);
            $params = new JRegistry;
            $params->loadString($table->{$master_key});
            $php_content = $params->get($filed1);

        } else {
            $php_content = $table->$filed;
        }
        $php_content = trim($php_content);
        if (base64_encode(base64_decode($php_content, true)) === $php_content) {
            $php_content = base64_decode($php_content);
        } else {
            $php_content = '';
        }
        jimport('joomla.filesystem.file');
        if (!JFile::exists($file_php)) {
            JFile::write($file_php, $php_content);
        } else {

            $file_name_change = $app->input->get('file_name_change', 0);
            $file_name_change = strtolower($file_name_change);
            $file_php_info = pathinfo($file_php);
            $file_name = $file_php_info['filename'];
            $file_name = strtolower($file_name);
            if ($file_name_change == $file_name) {

                $php_content = JFile::read($file_php);
                $params_filed = explode('.', $filed);
                if (count($params_filed) > 1) {
                    $params_filed = array_reverse($params_filed);
                    $master_key = array_pop($params_filed);
                    $params_filed = array_reverse($params_filed);
                    $filed = implode('.', $params_filed);
                    $params = new JRegistry;
                    $params->loadString($table->{$master_key});
                    $params->set($filed, base64_encode($php_content));
                    $table->{$master_key} = json_encode($params);

                } else {
                    $table->{$filed} = base64_encode($php_content);
                }
                if (!$table->store()) {
                    return $table->getError();
                }
            } elseif ($file_name_change == 0) {
                JFile::write($file_php, $php_content);
            }
        }
        $content = include_once($file_php);
        return $content;

    }

    public static function compileLess($input, $output)
    {

        $cssTemplate = basename($output);

        $app = JFactory::getApplication();
        if (!defined('FOF_INCLUDED')) {
            require_once JPATH_ROOT . '/libraries/f0f/include.php';
        }
        require_once JPATH_ROOT . '/libraries/f0f/less/less.php';
        $less = new F0FLess;

        $less->setFormatter(new F0FLessFormatterJoomla);
        $result = $less->compileFile($input, $output);
        if (!$result) {
            echo $input;
            echo "<br/>";
            echo $result;
            echo "<br/>";
            die;
        }
        return $result;


    }

    public static function remove_string_javascript($str)
    {
        preg_match_all('/<script type=\"text\/javascript">(.*?)<\/script>/s', $str, $estimates);
        return $estimates[1][0];

    }

    public function remove_string_css($str)
    {
        preg_match_all('/<style type=\"text\/css">(.*?)<\/style>/s', $str, $estimates);
        return $estimates[1][0];

    }

    function googleCompressJs($jsContent)
    {
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

    function get_font_face_google($list_font_face)
    {
        $data = array(
            'family' => implode('|', $list_font_face)
        );
        $data = http_build_query($data);
        $url = 'http://fonts.googleapis.com/css?' . $data;
        //$jsContent=$this->compress($jsContent);
        $headers[] = 'Content-type: application/x-www-form-urlencoded';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_POST, FALSE);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process);
        return $return;
    }

    function listFolderFiles($path, &$returnArray = array(), $listKey = '')
    {

        $lastFolder = basename($path);
        $folders = JFolder::folders($path);
        $i = 1;
        foreach ($folders as $folder) {
            $key = $listKey != '' ? $listKey . '.' . $i : $i;
            $returnArray['folders:' . $key] = $folder;
            JUtility::listFolderFiles($path . '/' . $folder, $returnArray, $key);
            $i++;
        }
        $files = JFolder::files($path);
        $i = 1;
        foreach ($files as $file) {
            $key = $listKey != '' ? $listKey . '.' . $i : $i;
            $returnArray['files:' . $key] = $file;
            $i++;
        }

    }

    public static function printDebugBacktrace($title = 'Debug Backtrace:')
    {
        $output = "";
        $output .= "<hr /><div>" . $title . '<br /><table border="1" cellpadding="2" cellspacing="2">';

        $stacks = debug_backtrace();

        $output .= "<thead><tr><th><strong>File</strong></th><th><strong>Line</strong></th><th><strong>Function</strong></th>" .
            "</tr></thead>";
        foreach ($stacks as $_stack) {
            if (!isset($_stack['file'])) $_stack['file'] = '[PHP Kernel]';
            if (!isset($_stack['line'])) $_stack['line'] = '';

            $output .= "<tr><td>{$_stack["file"]}</td><td>{$_stack["line"]}</td>" .
                "<td>{$_stack["function"]}</td></tr>";
        }
        $output .= "</table></div><hr /></p>";
        return $output;
    }

    public static function printDebugBacktrace2($stacks, $title = 'Debug Backtrace:')
    {
        $output = "";
        $output .= "<hr /><div>" . $title . '<br /><table border="1" cellpadding="2" cellspacing="2">';


        $output .= "<thead><tr><th><strong>File</strong></th><th><strong>Line</strong></th><th><strong>Function</strong></th>" .
            "</tr></thead>";
        foreach ($stacks as $_stack) {
            if (!isset($_stack['file'])) $_stack['file'] = '[PHP Kernel]';
            if (!isset($_stack['line'])) $_stack['line'] = '';

            $output .= "<tr><td>{$_stack["file"]}</td><td>{$_stack["line"]}</td>" .
                "<td>{$_stack["function"]}</td></tr>";
        }
        $output .= "</table></div><hr /></p>";
        return $output;
    }

    public static function  getListWebsite()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__website');
        $query->select('id,title');
        $db->setQuery($query);
        return $db->loadObjectList();

    }

    function remoteExecute(JDatabaseQuery $query, $conditions)
    {
        $config = JFactory::getConfig();
        $host = $config->get('host', '', 'string');
        $linkExecute = $config->get('linkExecute', 'http://websitetemplatepro.com/', 'string');
        if ($config->get('host') != 'localhost')
            return;
        $input = JFactory::getApplication()->input;
        $option = $input->get('option', '', 'string');
        if ($option == '') {
            JLog::add('component not found', JLog::ERROR, $query->type);
        }
        $logFile = $option . '_flow1.txt';
        JLog::addLogger(array('text_file' => $logFile, 'text_file_path' => 'logs'), JLog::ALL, array($option));
        $msg = "Remote execute command {$query->type}:$query";
        JLog::add($msg, $conditions);


        $data = array(
            'option' => 'com_utility'
        , 'task' => 'Utility.executeQuery'
        , 'query' => base64_encode($query)
        );
        $url = '';
        $url .= '?' . http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $result = curl_exec($ch);
        if (!$result) {
            $msg = "Error remote execute command {$query->type}:$query";
            JLog::add($msg, JLog::ERROR);
        }
    }


    /**
     * @param array $array_text
     * @param $to
     * @return stdClass
     *  $array_text=array(
     * '<div title="hello">hello</div>'
     * ,'user'
     * ,'test'
     * );
     * $stranslate= JUtility::googleTranslations($array_text,'vi');
     * echo "<pre>";
     * print_r($stranslate);
     * die;
     *
     */
    static public function fillLanguage($array_text = array(), &$array_text1 = array(), &$array_text2 = array(), $to = '')
    {
        $search_text = array();
        if (count($array_text)) {
            foreach ($array_text as $text) {
                $search_text[] = base64_encode($text);
            }
        }

        if (count($search_text)) {
            $search_text = '"' . implode('","', $search_text) . '"';
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('translate.*');
        $query->from('#__translate as translate');
        $query->where('translate.en_base_64_text IN(' . $search_text . ')');
        $query->where('translate.key_lang= ' . $db->q($to));
        $db->setQuery($query);
        $listLanguage = $db->loadObjectList('en_base_64_text');

        if (count($array_text)) {

            foreach ($array_text as $key => $text) {
                $this_text = base64_encode($text);
                $this_text = $listLanguage["$this_text"];
                if (is_object($this_text)) {
                    $array_text1[$key] = $this_text->to_lang;
                } else {

                    $array_text2[$key] = $text;
                }

            }
        }
    }

    public function get_google_web_fonts()
    {
        require_once JPATH_ROOT . '/libraries/google-api-php-client-master/src/Google/Client.php';
        require_once JPATH_ROOT . '/libraries/google-api-php-client-master/src/Google/Service/Webfonts.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Google_Service_Pagespeedonline PHP Starter Application');

// Visit https://code.google.com/apis/console?api=translate to generate your
// client id, client secret, and to register your redirect uri.
        $client->setDeveloperKey('AIzaSyDZK_pbDD9Nb2lgAGQ46uoHNKzzMpiKOqw');
        $web_fonts = new Google_Service_Webfonts($client);
        $list_font = $web_fonts->webfonts->listWebfonts()->getItems();
        return $list_font;

    }

    function Google_Service_Pagespeedonline()
    {
        require_once JPATH_ROOT . '/libraries/google-api-php-client-master/src/Google/Client.php';
        require_once JPATH_ROOT . '/libraries/google-api-php-client-master/src/Google/Service/Translate.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Google_Service_Pagespeedonline PHP Starter Application');

// Visit https://code.google.com/apis/console?api=translate to generate your
// client id, client secret, and to register your redirect uri.
        $client->setDeveloperKey('AIzaSyDZK_pbDD9Nb2lgAGQ46uoHNKzzMpiKOqw');
        $service = new Google_Service_Translate($client);
    }

    function googleTranslations($array_text = array(), $to)
    {
        $array_text1 = array();
        $array_text2 = array();
        JUtility::fillLanguage($array_text, $array_text1, $array_text2, $to);
        if (!count($array_text2)) {
            return $array_text1;
        }
        require_once JPATH_ROOT . '/libraries/google-api-php-client-master/src/Google/Client.php';
        require_once JPATH_ROOT . '/libraries/google-api-php-client-master/src/Google/Service/Translate.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Translate PHP Starter Application');

// Visit https://code.google.com/apis/console?api=translate to generate your
// client id, client secret, and to register your redirect uri.
        $client->setDeveloperKey('AIzaSyDZK_pbDD9Nb2lgAGQ46uoHNKzzMpiKOqw');
        $service = new Google_Service_Translate($client);

        $langs = $service->languages->listLanguages();
        $langs = $langs->toSimpleObject();
        $langs = $langs->data['languages'];

        if (!in_array(array('language' => $to), $langs))
            return false;
        $translations = $service->translations->listTranslations($array_text2, $to);
        $translations = $translations->toSimpleObject();

        $translations = $translations->data['translations'];
        $array_result = array();
        if (is_array($translations)) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->insert('#__translate')->columns('en_base_64_text, key_lang,to_lang');
            $i = 0;
            foreach ($array_text2 as $key => $text) {
                $text = $db->q(base64_encode($text));
                $text_translate = $db->q($translations[$i]['translatedText']);
                $query->values($text . ',' . $db->q($to) . ',' . $text_translate);
                $array_text2[$key] = $translations[$i]['translatedText'];
                $i++;
            }
            $db->setQuery($query);
            $db->execute();
            $array_result = array_merge($array_text1, $array_text2);
        } else {
            $array_result = $array_text1;
        }
        return $array_result;
    }

    static function changeLanguageBody($contents)
    {
        $config = JFactory::getConfig();
        $allwayTranslateOnlineByGoogle = $config->get('allwayTranslateOnlineByGoogle', 0, 'int');
        if (!$allwayTranslateOnlineByGoogle)
            return $contents;
        require_once JPATH_ROOT . '/libraries/simplehtmldom_1_5/simple_html_dom.php';
        $html = str_get_html($contents);
        if (trim($html) == '')
            return $contents;
        $array_text = array();
        foreach ($html->find('.e-change-lang') as $change_lang) {
            $array_text[] = $change_lang->innertext;
        }
        $primaryLanguage = $config->get('primaryLanguage', 14, 'int');

        $session = JFactory::getSession();
        $language_id = $session->get('language_id', $primaryLanguage);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__language_google');
        $query->where('id=' . $language_id);
        $db->setQuery($query);
        $language = $db->loadObject();

        if ($language_id != $primaryLanguage) {
            $array_text = JUtility::googleTranslations($array_text, $language->iso639code);
        } else {
            return $contents;
        }


        if (is_array($array_text)) {

            $i = 0;
            foreach ($html->find('.e-change-lang') as $change_lang) {
                $change_lang->outertext = $array_text[$i];
                $i++;
            }
        }
        return (string)$html;
    }

    function saveImageFromUrl($url, $savePath)
    {

        $fileName = basename($url);
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
        $user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        $return = curl_exec($process);
        curl_close($process);
        echo $savePath . $fileName;
        echo "<br/>";
        file_put_contents($savePath . $fileName, $return);
    }

    static function  getCurl($link = '', $curlopt_ssl_verifypeer = false, $curlopt_ssl_verifyhost = false, $curlopt_encoding = 'gzip', $curlopt_returntransfer = true)
    {
        if ($link == '')
            return;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $curlopt_ssl_verifypeer);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $curlopt_ssl_verifyhost);
        curl_setopt($ch, CURLOPT_ENCODING, $curlopt_encoding);
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $curlopt_returntransfer);
        $result = curl_exec($ch);
        return $result;

    }

     static function getMemoryUseByVar($var, $unit = "", $decimals = 2, $format = true)
    {
        $new_object = new stdClass();
        $new_object->temp = $var;
        $mem = memory_get_usage();
        $DB_tmp = clone  $new_object;
        $mem = memory_get_usage() - $mem;
        $mem = JUtility::byteFormat($mem, $unit, $decimals, $format);
        unset($DB_tmp);
        return $mem;
    }

    static function byteFormat($bytes, $unit = "", $decimals = 2, $format = true)
    {
        $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4,
            'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

        $value = 0;
        if ($bytes > 0) {
            // Generate automatic prefix by bytes
            // If wrong prefix given
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes) / log(1024));
                $unit = array_search($pow, $units);
            }

            // Calculate byte value by prefix
            $value = ($bytes / pow(1024, floor($units[$unit])));
        }

        // If decimals is not numeric or decimals is less than 0
        // then set default value
        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }
        if ($format) {
            // Format output
            return sprintf('%.' . $decimals . 'f ' . $unit, $value);
        } else {
            return $value;
        }
    }

    function getPropertyClass($className, $method = '')
    {
        $arrayResult = array();
        $reflector = new ReflectionClass($className);
        $arrayResult['filename'] = $reflector->getFileName();
        if ($method != '') {
            $arrayResult['method'] = $reflector->getMethod($method);
            $className = $arrayResult['method']->class;
            $reflector = new ReflectionClass($className);
            $arrayResult['pathofmethod'] = $reflector->getFileName();

        }
        return $arrayResult;
    }

    static function byteToOtherUnit($bytes, $unit = "", $decimals = 2)
    {
        $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4,
            'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

        $value = 0;
        if ($bytes > 0) {
            // Generate automatic prefix by bytes
            // If wrong prefix given
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes) / log(1024));
                $unit = array_search($pow, $units);
            }

            // Calculate byte value by prefix
            $value = ($bytes / pow(1024, floor($units[$unit])));
        }

        // If decimals is not numeric or decimals is less than 0
        // then set default value
        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        // Format output
        return $value;
    }

    public function getDataSourceNameAvailable($name, $listName, $min = 0, $max = 1000)
    {
        for ($i = $min; $i < $max; $i++) {
            $nameAvailable = "$name$i";
            if (!in_array($nameAvailable, $listName)) {
                return $nameAvailable;
            }
        }
    }


}
