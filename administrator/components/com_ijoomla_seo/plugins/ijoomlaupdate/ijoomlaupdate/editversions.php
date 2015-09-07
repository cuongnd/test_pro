<?php
/**
 * @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
 * @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author  iJoomla.com <webmaster@ijoomla.com>
 * @url   http://www.ijoomla.com/licensing/
 * the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
 * More info at http://www.ijoomla.com/licensing/
*/
define('_JEXEC', 1);
define('JPATH_BASE', substr(substr(dirname(__FILE__), 0, strpos(dirname(__FILE__), "plugins")),0,-1));
if (!isset($_SERVER["HTTP_REFERER"])) exit("Direct access not allowed.");
$mosConfig_absolute_path =substr(JPATH_BASE, 0, strpos(JPATH_BASE, "/administra")); 
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

require_once(JPATH_BASE.DS."includes".DS."defines.php");
require_once(JPATH_BASE.DS."includes".DS."framework.php");
include_once(JPATH_BASE.DS."configuration.php");
include_once(JPATH_BASE.DS."libraries".DS."joomla".DS."object".DS."object.php");
include_once(JPATH_BASE.DS."libraries".DS."joomla".DS."database".DS."database.php");
$config = new JConfig();
$options = array ("host" => $config->host, "user" => $config->user, "password" => $config->password, "database" => $config->db, "prefix" => $config->dbprefix);
$database = JFactory::getDBO();

	function existComponent($component){
		global $database;
		$sql = "select count(*) from #__extensions where element = '".$component."'";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$result = $result["0"];
				
		if($result > 0){			
			return true;
		}
		return false;
	}
	
	function getComponentName($component){
		global $database;
		$sql = "select name from #__extensions where element = '".$component."'";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$result = $result["0"];
		
		return $result;
	}
	
	function getCurrentVersionData($component){
		$version = "";		
		$data = 'www.ijoomla.com/ijoomla_latest_version.txt';		
		$ch = @curl_init($data);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_TIMEOUT, 10); 							
		
		$version = @curl_exec($ch);
		if(isset($version) && trim($version) != ""){					
			$pattern = "/3.0_".$component."=(.*);/msU";	
			preg_match($pattern, $version, $result);
			if(is_array($result) && count($result) > 0){
				$version = trim($result["1"]);
			}
			return $version;
		}
		return false;
	}
	
	function getLocalVersionString($component, $xml_file){			
		$version = '';
		$path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.$component.DS.$xml_file;
		if(file_exists($path)){
			$data = implode("", file($path));
			$pos1 = strpos($data,"<version>");
			$pos2 = strpos($data,"</version>");
			$version = substr($data, $pos1+strlen("<version>"), $pos2-$pos1-strlen("<version>"));
			return $version;
		}
		else{
			return "";
		}
	}
	
	$list_changelog = array("com_adagency"=>"http://adagency.ijoomla.com/changelog/change-log-joomla-3-x/",
							"com_ijoomla_seo"=>"http://seo.ijoomla.com/index.php?option=com_obrss&task=feed&id=4",
							"com_sidebars"=>"http://www.ijoomla.com/redirect/changelogs/sidebars17.htm",
							"com_surveys"=>"http://www.ijoomla.com/redirect/changelogs/survey17.htm",
							"com_guru"=>"http://guru.ijoomla.com/index.php?option=com_obrss&task=feed&id=3",
							"com_publisher"=>"http://www.ijoomla.com/redirect/changelogs/publisher17.htm",
							"com_digistore"=>"http://www.ijoomla.com/redirect/changelogs/digistore17.htm");
	
	$list_all_components = array("com_adagency"=>"adagency.xml",
								 "com_ijoomla_seo"=>"ijoomla_seo.xml", 
								 "com_sidebars"=>"install.xml",
								 "com_surveys"=>"surveys.xml",
								 "com_guru"=>"install.xml",
								 "com_publisher"=>"install.publisher.xml",
								 "com_digistore"=>"digistore.xml");
	$list_installed_components = array();
	
	foreach($list_all_components as $key=>$value){
		if(existComponent($key)){
			$list_installed_components[$key] = $value;
			$show_button = true; 
		}
	}		
?>
<style>
	.adminlist{
		background-color:#E7E7E7;
		border-spacing:1px;
		color:#666666;
		width:100%;
		text-align:center;
		font-family:Arial, Helvetica, sans-serif;
		font-size:13px;	
	}
	
	.adminlist .pagetitle{
		font:bold;
		font-size:18px;
	}
	
	.adminlist .header{
		background:#F0F0F0 none repeat scroll 0 0;
		border-bottom:1px solid #999999;
		border-left:1px solid #FFFFFF;
		color:#666666;
		text-align:center;
	}
	
	.adminlist .row1{
		background:#F9F9F9 none repeat scroll 0 0;
		border-top:1px solid #FFFFFF;
	}
	
	.adminlist a{
		color: blue;	
	}
</style>
<img src="<?php echo JURI::root()."logo.png"; ?>" />
<table class="adminlist">
	<tr class="header">
		<th>#</th>
		<th>Component</th>
		<th>Installed Version</th>
		<th>Latest Version</th>
		<th>Change log</th>
		<th>Download</th>
	</tr>
	<?php
		$i = 1;
		$row = 2;
		foreach($list_installed_components as $component=>$xml_file){
			$latest_version	 = getCurrentVersionData($component);
			$current_version = getLocalVersionString($component, $xml_file);
			$color = "green";
			$color_version = "black";
			if($latest_version != $current_version){
				$color = "red";
				$color_version = "red";
			}
			if($row == "2"){
				$row = "1";
			}
			else{
				$row = "2";
			}
			
			$juri_root = JURI::root();
			$juri_root = str_replace("plugins/system/ijoomlaupdate/ijoomlaupdate/", "", $juri_root);
			
			echo "<tr class=\"row".$row."\">";
			echo 	"<td>";
			echo 		$i++;
			echo 	"</td>";
			echo 	"<td width=\"30%\" style=\"color:".$color."\" >";
			echo 		getComponentName($component);
			echo 	"</td>";
			echo 	"<td align=\"center\" style=\"color:".$color_version."\" >";
			echo 		$current_version;
			echo 	"</td>";
			echo 	"<td align=\"center\" style=\"color:".$color_version."\" >";
			echo 		$latest_version;
			echo 	"</td>";
			echo 	"<td align=\"center\">";
			echo 		"<a href=\"".$list_changelog[$component]."\" target=\"_blank\">Change Log</a>";
			echo 	"</td>";
			echo 	"<td align=\"center\">";
			echo 		'<a href="#" onclick="window.parent.location.href=\''.$juri_root."administrator/index.php?option=com_ijoomlainstaller".'\';">Upgrade</a>';
			echo 	"</td>";
			echo "</tr>";
		}
	?>
</table>