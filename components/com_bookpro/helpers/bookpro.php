<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

class BookProHelperFrontEnd {

	
	
	
	static function renderLayout($name,$data,$path='/components/com_bookpro/layouts'){
		$path=JPATH_ROOT .$path;
		return JLayoutHelper::render($name,$data,$path);
	}

    /**
     * Set pages submenus.
     *
     * @param
     *        	$set
     */
    static function setSubmenu($set) {
        AImporter::helper('adminui');
        AdminUIHelper::startAdminArea();
    }

    static function getyearold($BirthDate) {

        list($Year, $Month, $Day) = explode("/", $BirthDate);

        $YearDiff = date("Y") - $Year;

        if (date("m") < $Month || (date("m") == $Month && date("d") < $DayDiff)) {
            $YearDiff--;
        }
        return $YearDiff;
    }

    
    
    static function getObjectAddress($city_id) {
        $add = $hotel->address1;
        $db = & JFactory::getDBO();
        $sql = 'SELECT h.*,c.country_name AS country, s.state_name AS state_name FROM #__bookpro_dest AS h ';
        $sql .= ' JOIN #__bookpro_country AS c ON h.country_id=c.id';
        $sql .= ' LEFT OUTER JOIN #__bookpro_state AS s ON h.state_id=s.id';
        $sql .= ' WHERE h.id=' . $city_id;
        $db->setQuery($sql);
        $city = $db->loadObject();
        return $city;
    }

    public function compileLess($input,$output)
    {
        $app          = JFactory::getApplication();
        if (!defined('FOF_INCLUDED'))
        {
            require_once JPATH_ROOT . '/libraries/f0f/include.php';
        }
        require_once JPATH_ROOT.'/libraries/f0f/less/less.php';
        $less = new F0FLess;
        $less->setFormatter(new F0FLessFormatterJoomla);

        try
        {
            $less->compileFile($input, $output);

            return true;
        }
        catch (Exception $e)
        {
            $app->enqueueMessage($e->getMessage(), 'error');
        }

    }

    static function sub_string($str, $len, $more = '...', $encode = 'utf-8') {
        if ($str == "" || $str == NULL || is_array($str) || strlen($str) <= $len) {
            return $str;
        }
        $str = mb_substr($str, 0, $len, $encode);
        if ($str != "") {
            if (!substr_count($str, " ")) {
                $str .= $more;
                return $str;
            }
            while (strlen($str) && ($str[strlen($str) - 1] != " ")) {
                $str = mb_substr($str, 0, -1, $encode);
            }
            $str = mb_substr($str, 0, -1, $encode);
            $str .= $more;
        }
        $str = preg_replace("/[[:blank:]]+/", " ", $str);
        return $str;
    }

    static function getOrderLink($user_id) {

        $user = JUser::getInstance($user_id);
    }

    static function getActivitiesById($hotel_id) {
        if ($hotel_id) {
            $db = & JFactory::getDBO();
            $sql = 'SELECT `act`.`activity_id`  FROM #__bookpro_touractivity AS act ';
            $sql .= ' WHERE `act`.`tour_id`=' . $hotel_id;
            $db->setQuery($sql);
            return $db->loadColumn();
        } else {
            return array();
        }
    }
    static function getGroupById($group_id) {
        if ($group_id) {
            $db = & JFactory::getDBO();
            $sql = 'SELECT `tp`.`package_id`  FROM #__bookpro_tourid_packageid AS tp ';
            $sql .= ' WHERE `tp`.`tour_id`=' . $group_id;
            $db->setQuery($sql);
            return $db->loadColumn();
        } else {
            return array();
        }
    }
    static function getTourTypeId($tour_type) {
        if ($tour_type) {
            $db = & JFactory::getDBO();
            $sql = 'SELECT `t`.`type_id`  FROM #__bookpro_tourid_typeid AS t ';
            $sql .= ' WHERE `t`.`tour_id`=' . $tour_type;
            $db->setQuery($sql);
            return $db->loadColumn();
        } else {
            return array();
        }
    }
    static function getPackageTypesId($pack_type) {
        if ($pack_type) {
            $db = & JFactory::getDBO();
            $sql = 'SELECT `t`.`package_type_id`  FROM #__bookpro_tour_package_typeid AS t ';
            $sql .= ' WHERE `t`.`tour_id`=' . $pack_type;
            $db->setQuery($sql);
            return $db->loadColumn();
        } else {
            return array();
        }
    }

    function getCatType() {
        return array(
            array(
                'value' => '2',
                'text' => 'Tour'
            ),
            array(
                'value' => '5',
                'text' => 'Hotel'
            ),
            array(
                'value' => '6',
                'text' => 'Tour Duration'
            ),
            array(
                'value' => '9',
                'text' => 'Tour pickup location'
            ),
            array(
                'value' => '10',
                'text' => 'Customer Referral'
            ),
            array(
                'value' => '11',
                'text' => 'Drop Location'
            ),
            array(
                'value' => '12',
                'text' => 'Suburb Location'
            )
        );
    }

    static function formatGender($gender, $symbol = false) {
        if ($gender) {
            if ($symbol)
                return JText::_('M');
            else
                return JText::_('COM_BOOKPRO_MALE');
        } else if ($symbol)
            return JText::_('F');
        else
            return JText::_('COM_BOOKPRO_FEMALE');
    }

    static function formatAge($age) {
        switch ($age) {
            case 1 :
                return JText::_('COM_BOOKPRO_ADULT');
                break;
            case 0 :
                return JText::_('COM_BOOKPRO_CHILDREN');
                break;
            case 2 :
                return JText::_('COM_BOOKPRO_INFANT');
                break;
        }
    }

   

    function getSelectBoxGroups($select = null) {
        $config = AFactory::getConfig();
        $arrayGroup[] = JText::_('COM_BOOKPRO_SELECT_USER_GROUP');
        $arrayGroup[$config->supplierUsergroup] = JText::_('COM_BOOKPRO_SUPPLIER');
        $arrayGroup[$config->customersUsergroup] = JText::_('COM_BOOKPRO_CUSTOMER');
        $arrayGroup[$config->agentUsergroup] = JText::_('COM_BOOKPRO_AGENT');
        return JHtmlSelect::genericlist($arrayGroup, 'group_id', '', 'value', 'text', $select);
    }

    static function getGender() {
        return array(
            array(
                'value' => 1,
                'text' => JText::_('COM_BOOKPRO_MALE')
            ),
            array(
                'value' => 0,
                'text' => JText::_('COM_BOOKPRO_FEMALE')
            )
        );
    }

    static function getAge() {
        return array(
            array(
                'value' => 1,
                'text' => JText::_('COM_BOOKPRO_ADULT')
            ),
            array(
                'value' => 0,
                'text' => JText::_('COM_BOOKPRO_CHILDREN')
            ),
            array(
                'value' => 2,
                'text' => JText::_('INFANT')
            )
        );
    }

    /**
     *
     * @param unknown $selected
     */
    static function getPassengerGroup($name, $selected = null) {
        AImporter::model('cgroups');
        $model = new BookProModelCGroups ();
        $model->init(array(
            'state' => 1
        ));
        $results = $model->getData();
        return AHtmlFrontEnd::getFilterSelect($name, JText::_("COM_BOOKPRO_SELECT_GROUP"), $results, $selected, false, 'class="input-medium pBirthday[]"', 'id', 'title');
    }

  

    static function formatPassengerGroup($value) {
        $pgroups = explode(';', JText::_('COM_BOOKPRO_PASSENGER_GROUPS'));
        $result = array();
        for ($i = 0; $i < count($pgroups); $i++) {
            $tmp = explode(':', $pgroups [$i]);
            if ($value == $tmp [0])
                return JText::_('COM_BOOKPRO_PASSENGER_GROUP_' . $tmp [1]);
        }
    }

    static function getCountryList($name, $select, $att = '', $ordering = "id", $group_id = null) {
        AImporter::model('countries');
        $model = new BookProModelCountries();
        $config = &AFactory::getConfig();

        if ($group_id == $config->supplierUsergroup) {
            $fullList = $model->getItemsByGroupSupplier();
        } else {
            $state = $model->getState();
            $state->set('list.limit', 0);
            $fullList = $model->getItems();
        }
        return AHtmlFrontEnd::getFilterSelect($name, JText::_("COM_BOOKPRO_SELECT_COUNTRY"), $fullList, $select, false, $att, 'id', 'country_name');
    }

    static function getCountrySelect($select) {
        $model = new BookProModelCountries();
        $state = $model->getState();
        $state->set('list.start', 0);
        $state->set('list.limit', 0);
        $fullList = $model->getItems();
        return AHtmlFrontEnd::getFilterSelect('country_id', JText::_('COM_BOOKPRO_SELECT_COUNTRY'), $fullList, $select, true, '', 'id', 'country_name');
    }

    static function getCountryTourBookSelect($select, $field_id = 'country', $field = 'country') {
        $model = new BookProModelCountries();
        $state = $model->getState();
        $state->set('list.start', 0);
        $state->set('list.limit', 0);
        $fullList = $model->getItems();
        return AHtmlFrontEnd::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_COUNTRY'), $fullList, $select, false, 'class="validate-select" id="' . $field_id . '"', 'id', 'country_name');
    }

    /**
     * Clean code from SUP tag.
     *
     * @param string $code
     * @return string cleaned code
     */
    static function cleanSupTag($code) {
        $code = str_replace(array(
            '<sup>',
            '</sup>'
                ), '', $code);
        return $code;
    }

  

    static function importLightBox() {
        JHtml::script(LIGHTBOX_BASE . 'js/lightbox-2.6.min.js');
        JHtml::stylesheet(LIGHTBOX_BASE . 'css/lightbox.css');
        // JHtml::stylesheet(LIGHTBOX_BASE . 'css/screen.css');
    }

    /**
     * Format person name.
     *
     * @param TableCustomer $person
     * @param boolean $safe
     *        	use HTML special chars to safe string, default false
     * @param boolean $addCompany
     *        	add Company Name, default false
     * @return string
     */
    static function formatName($person, $safe = false) {
        $parts = array();

        $person->firstname = JString::trim($person->firstname);
        $person->lastname = JString::trim($person->lastname);

        if ($person->firstname) {
            $parts [] = $person->firstname;
        }
        if ($person->lastname) {
            $parts [] = $person->lastname;
        }

        $name = JString::trim(implode(' ', $parts));
        if ($safe) {
            $name = htmlspecialchars($name, ENT_QUOTES, ENCODING);
        }
        return $name;
    }

    function formatPassengerName(&$flight, $safe = false) {
        $parts = array();

        $flight->desto = JString::trim($person->firstname);
        $flight->lastname = JString::trim($person->lastname);

        if ($person->firstname) {
            $parts [] = $person->firstname;
        }
        if ($person->lastname) {
            $parts [] = $person->lastname;
        }

        $name = JString::trim(implode(' ', $parts));
        if ($safe) {
            $name = htmlspecialchars($name, ENT_QUOTES, ENCODING);
        }
        return $name;
    }

    /**
     * Format person adrress
     *
     * @param TableCustomer $person
     * @return string HTML code
     */
    function formatAdrress(&$person) {
        $parts = array();
        $person->city = JString::trim($person->city);
        $person->street = JString::trim($person->street);
        $person->zip = JString::trim($person->zip);
        $person->country = JString::trim($person->country);
        if ($person->country) {
            $parts [] = $person->country;
        }
        if ($person->city) {
            $parts [] = $person->city;
        }
        if ($person->street) {
            $parts [] = $person->street;
        }
        if ($person->zip) {
            $parts [] = $person->zip;
        }
        return JString::trim(implode(', ', $parts));
    }

    /**
     * Get email link
     *
     * @param TableCustomer $person
     * @param boolean $link
     *        	display as link, default true
     * @return string HTML code
     */
    function getEmailLink(&$person, $link = true) {
        $person->email = JString::trim($person->email);
        if ($person->email) {
            return $link ? '<a href="mailto:' . $person->email . '" title="' . JText::_('Send email') . '">' . $person->email . '</a>' : $person->email;
        }
        return '';
    }

    function getIconEmail(&$person) {
        $email = JString::trim($person->email);
        if ($email) {
            return '<a href="mailto:' . $email . '" class="aIcon aIconEmail" title=""></a>';
        }
        return '';
    }

    function getTZOffset($inSeconds = true) {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $tzoffset = $mainframe->getCfg('offset');

        $dateTimeZone = new DateTimeZone($tzoffset);
        $dateTime = new DateTime('now', $dateTimeZone);
        $tzoffset = $dateTimeZone->getOffset($dateTime);
        if (!$inSeconds)
            $tzoffset /= 60 / 60;
        else
            $inSeconds = false;

        if ($inSeconds)
            $tzoffset *= 60 * 60;
        return $tzoffset;
    }

    /**
     * Convert date into given format with given time zone offset.
     *
     * @param $date string
     *        	date to convert
     * @param $format string
     *        	datetime format
     * @param $tzoffset int
     *        	time zone offset
     * @return BookingDate
     */
    function convertDate($date, $format = 'Y-m-d H:i:s', $tzoffset = 0) {
        static $cache;
        $key = $date . $format . $tzoffset;
        if (!isset($cache [$key])) {
            $output = new BookingDate ();
            $output->orig = $date;
            $output->uts = strtotime($date);
            $output->dts = date($format, $output->uts);
            $output->uts = strtotime($output->dts) - $tzoffset;
            $output->dts = date('Y-m-d H:i:s', $output->uts);
            $cache [$key] = $output;
        }
        return $cache [$key];
    }

    /**
     * Get difference between two dates in days count.
     *
     * @param $dateEnd BookingDate
     * @param $dateStart BookingDate
     * @return int
     */
    function getCountDays(&$dateEnd, &$dateStart) {
        $difference = $dateEnd->uts - $dateStart->uts;
        $countDays = $difference ? round($difference / DAY_LENGTH) : 1;
        return $countDays;
    }

    /**
     * Get unix time stamp of date with given time zone offset.
     *
     * @param $date string
     * @param $tzoffset int
     * @return int
     */
    function getUts($date, $tzoffset = 0) {
        $uts = strtotime($date) + $tzoffset;
        return $uts;
    }

    /**
     * Get date from start date by given offset (days count).
     * For example: start date 01-01-2010 and offset 4, return 05-01-2010.
     *
     * @param $date string
     * @param $offset int
     * @return string
     */
    function getDateFromStartByOffset($date, $offset) {
        $date = date('Y-m-d', strtotime($date . ' + ' . $offset . ' days'));
        return $date;
    }

    /**
     * Get week code by given unix time stamp.
     *
     * @param $uts int
     * @return string
     */
    function getWeekCodeByUts($uts) {
        $week = date('N', $uts);
        return $week;
    }

    /**
     * Get Calendar Reservation Box by given Subject ID and Datetime Reservation Start.
     *
     * @param $subjectId int
     * @param $from string
     *        	MySQL datetime
     * @return BookingBox or null if no find
     */
    function getCalendarBox($subjectId, $from) {
        $from = &BookproHelper::dateBeginDay($from);
        $fromTime = date('H:i', $from->uts);
        // get calendar for day date
        $calendar = &BookproHelper::getCalendar($subjectId, $from->dts, $from->dts);
        if (count($calendar)) {
            // get first day of calendar
            $currentDay = &reset($calendar);
            /* @var $currentDay BookingDay */
            // get date boxes
            $boxes = &$currentDay->boxes;
            $countBoxes = count($boxes);
            // search in boxes
            for ($i = 0; $i < $countBoxes; $i++) {
                $box = &$boxes [$i];
                /* @var $box BookingBox */
                if ($box->fromTime == $fromTime) {
                    return $box;
                }
            }
        }
        return null;
    }

    function timeToFloat($time, $tzoffset = 0) {
        $unixTimeOffset = ($unixTime = strtotime($time)) + $tzoffset;
        $timeToFloat = round(date('G', $unixTimeOffset) + date('i', $unixTimeOffset) / 60, 2);
        if (date('H:i:s', $unixTimeOffset) < date('H:i:s', $unixTime))
            $timeToFloat += 24;
        return $timeToFloat;
    }

    /**
     * Convert float value to MySQL time value.
     *
     * @param float $value
     * @return string
     */
    function floatToTime($value) {
        if (($hour = floor($value)) < 10)
            $hour = '0' . $hour;
        if (($minute = round(($value - $hour) * 60)) < 10)
            $minute = '0' . $minute;
        return $hour . ':' . $minute;
    }

    /**
     * Display time without zero minutes value.
     *
     * @param string $time
     *        	in format HH:MM
     * @return string for example: if value = 12:00 return 12
     */
    function displayTime($time) {
        // return ($time[3] . $time[4]) == '00' ? ($time[0] . $time[1]) : $time;
        return $time;
    }

    /**
     * Gets string value of week day by day number code.
     *
     * @param int $code
     * @return string
     */
    function dayCodeToString($code) {
        switch ($code) {
            case 1 :
                return 'monday';
            case 2 :
                return 'tuesday';
            case 3 :
                return 'wednesday';
            case 4 :
                return 'thursday';
            case 5 :
                return 'friday';
            case 6 :
                return 'saturday';
            case 7 :
                return 'sunday';
        }
    }

    /**
     * Get route to create reservation by price ID and start reservation datetime property.
     *
     * @param int $id
     *        	subject ID
     * @param tring $from
     *        	reservation date (MySQL datetime)
     * @return string URL
     */
    function getReservationRoute($priceId, $from) {
        return ARoute::customUrl(array(
                    'controller' => CONTROLLER_RESERVATION,
                    'task' => 'add',
                    'id' => $priceId,
                    'from' => $from
        ));
    }

    function getIPath($image = null) {
        static $ipath;
        $image = str_replace("\\", "/", $image);
        if (empty($ipath)) {
            $config = AFactory::getConfig();
            $ipath = $config->images;

            $ipath = AImage::getIPath($ipath);
            if (!file_exists($ipath)) {
                @mkdir($ipath, 0775, true);
            }
        }
        return is_null($image) ? $ipath : ($ipath . $image);
    }

    /**
     * Get relative path to directory with image.
     *
     * @param $image add
     *        	into path image name
     * @return string
     */
    function getRIPath($image) {
        $params = &JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
        $ripath = $params->getValue('images', 'images/bookpro');
        $ripath = AImage::getRIPath($ripath) . $image;
        return $ripath;
    }

 
    /**
     * Number into database format.
     * For example: 4 return like 04
     *
     * @param $number int
     * @return string
     */
    function intToDBFormat($number) {
        $number = (int) $number;
        if ($number < 10) {
            $number = '0' . $number;
        }
        return $number;
    }

    /**
     * Get captcha HTML code to include into page.
     *
     * @param $size int
     *        	num of digits in captcha
     * @return string HTML code
     */
    function captcha($size = 6) {
        $code = '';
        for ($i = 0; $i < $size; $i++)
            $code .= chr(rand(65, 90));
        $code = base64_encode($code);
        $html = '<img src="' . JURI::root() . 'components/' . OPTION . '/views/captcha/captcha.php?c=' . $code . '" class="captcha" />';
        $html .= '<div class="captchaInput">';
        $html .= '<input type="text" name="captcha" size="' . $size . '" value="" autocomplete="off" />';
        $html .= '<input type="hidden" name="encodeCaptcha" value="' . $code . '" />';
        $html .= '</div>';
        return $html;
    }

 

    /**
     * Apply database queries from string source.
     *
     * @param string $queries
     */
    function queries($queries) {
        $db = &JFactory::getDBO();
        /* @var $db JDatabaseMySQL */
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $queries = $db->splitSql($queries);
        $count = count($queries);
        for ($i = 0; $i < $count; $i++)
            if (($query = JString::trim($queries [$i]))) {
                $db->setQuery($query);
                $db->query();
            }
    }

  
    function getSubjectImages(&$subject) {
        $images = $subject->images;
        $images = JString::trim($images);
        if ($images) {
            $images = explode(';', $images);
            return $images;
        }
        return array();
    }

    /**
     * Set list of subject images to database format.
     *
     * @param TableSubject $subject
     */
    function setSubjectImages(&$images) {
        $images = implode(';', $images);
    }

    /**
     * Utility for taking array of ids from objects array.
     *
     * @param $list array
     *        	of objects where object containing id parameter
     * @return array of integer ids values
     */
    function getIdsFromObjectList(&$list) {
        $count = count($list);
        $ids = array();
        for ($i = 0; $i < $count; $i++) {
            $ids [] = $list [$i]->id;
        }
        return $ids;
    }

    function getSubjectFiles(&$subject, $params = array()) {
        $defaults = array(
            'onlyShow' => false,
            'onlySend' => false,
            'onlyFilepaths' => false
        );

        $params = array_merge($defaults, $params);

        $files = $subject->files;
        $files = JString::trim($files);

        AImporter::helper('file');

        if ($files) {
            $files = explode(';', $files);
            foreach ($files as $key => $file) { // if some directory starts with \n, it is converted to new line => must escape
                $file = explode('::', $file);
                $fileObj = new stdClass ();
                $fileObj->origname = trim(str_replace("\n", '\\n', $file [0]), ' ' . DS); // original name relativce to images subdir
                $fileObj->filename = preg_replace('#^.*\/([^/]+)$#', '$1', $fileObj->origname); // name without any directory
                $fileObj->fullpath = AFile::getFPath($fileObj->origname, false); // server path to file
                $fileObj->url = AFile::getFPath($fileObj->origname, true); // url to file
                $fileObj->show = empty($file [1]) ? 0 : 1;
                $fileObj->send = empty($file [2]) ? 0 : 1;
                $fileObj->string = $fileObj->origname . '::' . $fileObj->show . '::' . $fileObj->send;
                $files [$key] = $fileObj;

                if (!file_exists($files [$key]->fullpath) || ($params ['onlyShow'] && !$files [$key]->show) || ($params ['onlySend'] && !$files [$key]->send)) {
                    unset($files [$key]);
                    continue;
                }

                if ($params ['onlyFilepaths'])
                    $files [$key] = $files [$key]->fullpath;
            }

            return $files;
        }
        return array();
    }

    /**
     * Set list of subject files to database format.
     *
     * @param array $files
     */
    function setSubjectFiles(&$files) {
        $files = implode(';', $files);
    }

    /**
     * Get e-mail mode value to using in JUtility::sendMail().
     *
     * @param int $emailMode
     *        	setting value
     * @return boolean true HTML, false PLAIN TEXT
     */
    function getEmailMode($emailMode) {
        $emailMode = $emailMode != PLAIN_TEXT;
        return $emailMode;
    }

    /**
     * Convert HTML code to plain text.
     * Paragraphs (tag <p>) and
     * break line (tag <br/>) replace by end line sign (\n or \r\n)
     * and remove all others HTML tags.
     *
     * @param $string to
     *        	convert
     * @return $string converted to plain text
     */
    function html2text($string) {
        $string = str_replace('</p>', '</p>' . PHP_EOL, $string);
        $string = str_replace('<br />', PHP_EOL, $string);
        $string = strip_tags($string);

        return $string;
    }
    function getFileThumbnail($filename)
    {
    	$ext = strtolower(JFile::getExt($filename));

    	//icons taken from JoomDOC
    	$icons = array();
    	$icons['32-pdf.png']=array('pdf');
    	$icons['32-ai-eps-jpg-gif-png.png']=array('ai','eps','jpg','jpeg','gif','png','bmp');
    	$icons['32-xls-xlsx-csv.png']=array('xls','xlsx','csv');
    	$icons['32-ppt-pptx.png']=array('ppt','pptx');
    	$icons['32-doc-rtf-docx.png']=array('doc','rtf','docx');
    	$icons['32-mpeg-avi-wav-ogg-mp3.png']=array('mpeg','avi','ogg','mp3');
    	$icons['32-tar-gzip-zip-rar.png']=array('tar','gzip','zip','rar');
    	$icons['32-mov.png']=array('mov');
    	$icons['32-fla']=array('fla');
    	$icons['32-fw']=array('fw');
    	$icons['32-indd.png']=array('indd');
    	$icons['32-mdb-ade-mda-mde-mdp.png']=array('mdb','ade','mda','mde','mdp');
    	$icons['32-psd.png']=array('psd');
    	$icons['32-pub.png']=array('pub');
    	$icons['32-swf.png']=array('swf');
    	$icons['32-asp-php-js-asp-css.png']=array('asp','php','js','css');

    	foreach ($icons as $icon => $extension)
    		if (in_array($ext,$extension)){
    		$thumb = $icon;
    		break;}

    		if (!isset($thumb))
    			$thumb = '32-default.png';

    		return IMAGES.'icons_file/'.$thumb;
    }



    function get() {
        return '<a href="http://ibookingonline.com" target="_blank">Travel booking solution</a>';
    }

    /**
     *
     * @param string $from
     * @param string $fromname
     * @param string $email
     * @param string $subject
     * @param string $body
     * @param boolean $htmlMode
     * @return boolean
     */
    function sendMail($from, $fromname, $email, $subject, $body, $htmlMode) {
        if (!$htmlMode)
            $body = BookProHelper::html2text($body);

        if (is_array(($froms = explode(',', str_replace(';', ',', $from)))) && count($froms))
            $from = reset($froms);
        else {
            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $from = $mainframe->getCfg('mailfrom');
        }
        if (is_array(($emails = explode(',', str_replace(';', ',', $email))))) {
            $mail = &JFactory::getMailer();
            /* @var $mail JMail */
            foreach ($emails as $email)
                $mail->sendMail($from, $fromname, $email, $subject, $body, $htmlMode, null, null, $attachments);
        }
    }

}

?>
