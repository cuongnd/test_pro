<?php
/**
* @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
* @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html) 
* @author  iJoomla.com webmaster@ijoomla.com
* @url   http://www.ijoomla.com/licensing/
* the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  
* are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
* More info at http://www.ijoomla.com/licensing/
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class iJoomla_SeoViewiJoomla_Seo extends JViewLegacy {
	
	function display($tpl = null){
		$menu_type=$this->getMenuType("article");
		$this->menu_type = $menu_type;
		
		$installer = JRequest::getVar("installer", "");
		if($installer == 1){
			$this->writeLicense();
		}
		else{
			$this->moveOne();
		}
			
		parent::display($tpl);
	}
	
	//here add new component in drop down
	function getMenuType($default){
		 $types = array();		 
		 $types[] = JHTML::_('select.option','article', JText::_("COM_IJOOMLA_SEO_ARTICLES") , 'id', 'name');
		 $types[] = JHTML::_('select.option','menuitems', JText::_("COM_IJOOMLA_SEO_MENU_ITEMS") , 'id', 'name');
		 //$types[] = JHTML::_('select.option','mtree', JText::_("COM_IJOOMLA_SEO_MENU_MTREE") , 'id', 'name');
		 $types[] = JHTML::_('select.option','zoo', JText::_("COM_IJOOMLA_SEO_MENU_ZOO") , 'id', 'name');
		 $types[] = JHTML::_('select.option','ktwo', JText::_("COM_IJOOMLA_SEO_MENU_KTWO") , 'id', 'name');
		 $types[] = JHTML::_('select.option','kunena', JText::_("COM_IJOOMLA_SEO_MENU_KUNENA") , 'id', 'name');
		 $types[] = JHTML::_('select.option','easyblog', JText::_("COM_IJOOMLA_SEO_MENU_EASYBLOG") , 'id', 'name');	
		 
		 $onchange = ' onchange= " javascript: getStats (this.options[this.options.selectedIndex].value, \'\');" ';
		 return JHTML::_('select.genericlist', $types, 'types',  $onchange , 'id', 'name', $default);
	}
	
	//complet new drop down for new component	
	function createSelect($option){
		$ijseo_keysource = JRequest::getVar("keysource", 0,'get','int');
		$menus = $this->get("Menus");				
		$array_options = array();
		
		switch($option){
			case "menuitems" : {
				foreach($menus as $key=>$value){
					$array_options[$menus[$key]->menutype]=$menus[$key]->title;
				}
				break;
			}
			case "mtree" : {
				$array_options["mt_list"] = JText::_("COM_IJOOMLA_SEO_MTREE_LISTING");
				$array_options["mt_cat"] = JText::_("COM_IJOOMLA_SEO_MTREE_CAETGORY");
				break;
			}
			case "sobi" : {
				$array_options["sobi-item"] = "LISTINGS";
				$array_options["sobi-cat"] = "CATEGORIES";
				break;
			}
			case "magazine" : {
				$array_options["mag-cat"] = "ISSUES";
				break;
			}
			case "digistore" : {
				$array_options["digi-prod"] = "PRODUCTS";
				$array_options["digi-cat"] = "CATEGORIES";
				break;
			}
			case "newsportal" : {
				$array_options["np-sec"] = "SECTIONS";
				$array_options["np-cat"] = "CATEGORIES";
				break;
			}
			case "ktwo" : {
				$array_options["k2-item"] = JText::_("COM_IJOOMLA_SEO_KTWO_ITEMS");
				$array_options["k2-cat"] = JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY");
				break;
			}
			case "easyblog" : {
				$array_options["easyblog-item"] = JText::_("COM_IJOOMLA_SEO_KTWO_ITEMS");
				$array_options["easyblog-cat"] = JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY");
				break;
			}
			case "kunena" : {
				$array_options["kunena-cat"] = JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY");
				break;
			}
			case "virtuemart" : {
				$array_options["virtuemart-prod"] = "PRODUCTS";
				$array_options["virtuemart-cat"] = "CATEGORIES";
				break;
			}
			case "zoo" : {
				$array_options["zoo_items"] = JText::_("COM_IJOOMLA_SEO_ZOO_ITEMS");
				$array_options["zoo_cats"] = JText::_("COM_IJOOMLA_SEO_ZOO_CAETGORY");
				break;
			}
			case "wordpress" : {
				$array_options["wordpress-item"] = "POSTS";
				$array_options["wordpress-cat"] = "CATEGORIES";
				break;
			}
			case "mighty" : {
				$array_options["mighty-item"] = "ITEMS";
				$array_options["mighty-cat"] = "CATEGORIES";
				break;
			}
		}
				
		$output = "";
		$output .= "<select name=\"".$option."\" id=\"".$option."\" onchange=\" getStats('".$option."', this.options[this.options.selectedIndex].value, ".$ijseo_keysource.")\" class=\"inputbox\">";
		$output .= 		"<option value=\" \">".JText::_("COM_IJOOMLA_SEO_SELECT")."</option>";
		foreach($array_options as $key=>$value){
			$output .= 	"<option value=\"".$key."\">".$value."</option>";
		}		
		$output .= "</select>";
		
		return $output;		
	}
	
	function writeLicense(){
		$license = $_SESSION["licenseid"];
		$url="http://www.ijoomla.com/index.php?option=com_digistore&controller=digistoreAutoinstaller&task=get_domain_by_license&tmpl=component&license=".$license."&filename=com_ijoomla_seo.zip";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // add useragent
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
		if((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
		}
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // max. seconds to execute
		curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
		$page_content = @curl_exec($ch);
		
		$page_content = str_replace("https://", "", $page_content);
		$page_content = str_replace("http://", "", $page_content);
		
		$fp = fopen(JPATH_SITE.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."views".DS."ijoomla_seo".DS."tmpl".DS."edit.php", 'a');
		fwrite($fp, "\n");
		fwrite($fp, '<?php $license_domain = "'.trim($page_content).'"; ?>');
		fclose($fp);
		
		echo '<script type="text/jscript" language="javascript">
				window.location.href = "'.JURI::root().'administrator/index.php?option=com_ijoomla_seo";
			</script>';
	}
	
	function moveOne(){
		require_once(JPATH_SITE.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."views".DS."ijoomla_seo".DS."tmpl".DS."edit.php");
		$host = $_SERVER['HTTP_HOST'];
		if(strpos($host, "localhost") !== FALSE){
			return true;
		}
		else{
			$host = str_replace("https://", "", $host);
			$host = str_replace("http://", "", $host);
			$host = str_replace("www.", "", $host);
			
			$temp = explode(".", $host);
			if(count($temp) > 2){
				unset($temp["0"]);
				$host = implode(".", $temp);
			}
			
/*			if(strpos($host, $license_domain) === FALSE && strpos($license_domain, $host) === FALSE){
				$app = JFactory::getApplication("admin");
				$app->redirect(JURI::root()."administrator", JText::_("NO_LICENSES_FOR_EXTENSIONS"), "error");
			}
*/
		}
	}
}

?>
