<?php

defined('_JEXEC') or die('Restricted access');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

jimport('joomla.application.component.controller');

class iJoomla_SeoController extends JControllerLegacy{
	
	function __construct() {			
		parent::__construct();
		$task = JRequest::getVar("task", '', "get");
		$controller = JRequest::getVar("controller",'',"get");
		$document = JFactory::getDocument();
		$document->addStyleSheet("components/com_ijoomla_seo/css/ij30.css");
	}

	function display($cachable = false){		
		JRequest::setVar('view', JRequest::getCmd('view', 'iJoomla_Seo'));
		$this->setParams();
		parent::display($cachable);
	}
	
	function setParams(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select ('`params`');
		$query->from('#__ijseo_config');
		$db->setQuery($query);			
		$db->query();
		$result = $db->loadColumn();
		$result = $result["0"];
		
		if($result == "" || $result == "{}"){
			$params = array();
			$params["ijseo_allow_no_desc"] = "155";
			$params["ijseo_check_grank"] = "1";
			$params["ijseo_keysource"] = "0";
			$params["ijseo_gposition"] = "1";
			$params["ijseo_allow_no"] = "300";
            $params["ijseo_allow_no2"] = "66";
			$params["ijseo_Replace1"] = "";
			$params["ijseo_Replace2"] = "";
			$params["ijseo_Replace3"] = "";
			$params["ijseo_Replace4"] = "";
			$params["ijseo_Replace5"] = "";
			$params["ijseo_Replace1_with"] = "noreplace";
			$params["ijseo_Replace2_with"] = "noreplace";
			$params["ijseo_Replace3_with"] = "noreplace";
			$params["ijseo_Replace4_with"] = "noreplace";
			$params["ijseo_Replace5_with"] = "noreplace";
			$params["ijseo_wrap_key"] = "nowrap";
			$params["ijseo_type_title"] = "Characters";
            $params["ijseo_type_key"] = "Characters";
			$params["ijseo_gdesc"] = "intro";
			$params["ijseo_type_desc"] = "Characters";
			$params["exclude_key"] = array('');
			$params["ijseo_Image_what"] = "up to";
			$params["ijseo_Image_number"] = "1";
			$params["ijseo_Image_where"] = "keyword";
			$params["ijseo_Image_when"] = "NotSpecified";
			$params["ijseo_wrap_partial"] = "0";
			$params["delimiters"] = ",|;:";
			$params["ijseo_check_ext"] = "com";
			$params["check_nr"] = "20";
			
			$sql = "insert into #__ijseo_config (`params`) values ('".json_encode($params)."')";
			$db->setQuery($sql);			
			$db->query();			
		}				
	}
}

?>