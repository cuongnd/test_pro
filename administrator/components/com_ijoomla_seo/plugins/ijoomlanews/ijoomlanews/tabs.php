<?php
/**
 * @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
 * @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author  iJoomla.com <webmaster@ijoomla.com>
 * @url   http://www.ijoomla.com/licensing/
 * the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
 * More info at http://www.ijoomla.com/licensing/
*/
defined('_JEXEC') or die('Restricted access');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
	
	class CreateTabs{			
		function tabs($articles, $ijoomla_news, $ijoomla_blog, $change_log, $video, $component){						
			$array_component_name = array("com_adagency"=>"Ad Agency",
										  "com_magazine"=>"Magazine",
										  "com_surveys"=>"Surveys",
										  "com_sidebars"=>"Sidebars",
										  "com_ijoomla_seo"=>"SEO",
										  "com_ijoomla_rss"=>"RSS Feeder",
										  "com_news_portal"=>"News Portal",
										  "com_digistore"=>"DigiStore",
										  "com_ijoomla_archive"=>"Search & Archive",
										  "com_guru"=>"Guru");
			
			$content = "";
			$nr_articles = $this->getArticleNumbers();
			//---------------------------------------------------
			$content .= '<fieldset>
							<ul class="nav nav-tabs">
								<li class="active"><a href="#news1" data-toggle="tab">'.$array_component_name[$component]." News".'</a></li>
								<li><a href="#changelog" data-toggle="tab">Change Log</a></li>
								<li><a href="#blog" data-toggle="tab">iJoomla Blog</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="news1">'.$this->getArticles($articles, $nr_articles, true).'</div>
								<div class="tab-pane" id="changelog">'.$this->getArticles($change_log, $nr_articles, true).'</div>
								<div class="tab-pane" id="blog">'.$this->getArticles($ijoomla_blog, $nr_articles, false).'</div>
							</div>';
			//---------------------------------------------------
			return $content;
		}

		function getArticles($articles, $nr_articles, $no_images){			
			$content  = "<table style=\"font-family:Arial,Helvetica,sans-serif;\" width=\"100%\">";
			for($i=0; $i<$nr_articles; $i++){					
				if(isset($articles[$i])){
					$item = $articles[$i];									
					$datas = $item->data;
					$title = $datas["title"];
					$description = $datas["description"];
					if($no_images){
						$description = preg_replace("/\<img(.*)\>/msU", "", $description);
					}
					$description = $this->setDescriptionProperties($description);
					$link = $datas["link"]["alternate"]["0"];
					$publish_date = "";
					if(isset($datas["pubdate"])){
						$publish_date = $datas["pubdate"];
						$publish_date = "Created date: ".date('Y-m-d', $publish_date);
					}
					
					$content  .= "<tr>";
					$content  .= 	"<td style=\"font-size: 12px;\">";
					$content  .= 		"<ol style=\"color:red; padding-left:12px; margin:0; list-style-type:disc;\"><li><a href=\"".$link."\" target=\"_blank\">".$title."</a></li></ol>";
					$content  .= 	"</td>";
					$content  .= "</tr>";
					$content  .= "<tr>";
					$content  .= 	"<td style=\"color:#999999\">";
					$content  .= 		$publish_date;
					$content  .= 	"</td>";
					$content  .= "</tr>";
					$content  .= "<tr>";
					$content  .= 	"<td style=\"padding-bottom:15px;\">";
					$content  .= 		strip_tags($description);
					$content  .= 	"</td>";
					$content  .= "</tr>";
				}
				else{
					break;
				}
			}
			$content .= "</table>";
			return $content;
		}
		
		function setDescriptionProperties($description){
			$db =& JFactory::getDBO();
			$sql = "select params from #__extensions where element = 'ijoomlanews'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadResult();			
			$text_length = 100;
			$image_width = 50;
			
			if(trim($result == "") || trim($result == "{}")){
				$params = array("nr_articles"=>"3", "text_length"=>"100", "image_width"=>"50");
				$sql = "update #__extensions set params='".json_encode($params)."' where element = 'ijoomlanews'";
				$db->setQuery($sql);
				$db->query();
			}
			else{
				$result = json_decode($result);
				$text_length = trim($result->text_length) != "" ? trim($result->text_length) : 100;
				$image_width = trim($result->image_width) != "" ? trim($result->image_width) : 50;				
			}
						
			if(strlen($description) > $text_length){
				$description = substr($description, 0, $text_length);				
				$description = $description."...";
			}
			
			if(strpos($description, "<img") !== FALSE){
				if(strpos($description, "width") !== FALSE){
					$description = preg_replace("/width=(.*)\"/msU", 'width="'.$image_width.'px"', $description);
				}
				else{
					$description = str_replace("<img", '<img width="'.$image_width.'px"', $description);
				}
			}
			return $description;
		}
		
		function getArticleNumbers(){
			$db =& JFactory::getDBO();
			$sql = "select params from #__extensions where element = 'ijoomlanews'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadResult();
			if(trim($result == "")){
				$params = array("nr_articles"=>"3", "text_length"=>"100", "image_width"=>"50");
				$sql = "update #__extensions set params='".json_encode($params)."' where element = 'ijoomlanews'";
				$db->setQuery($sql);
				$db->query();
				return "3";
			}
			else{
				$result = json_decode($result);
				return $result->nr_articles;
			}
		}//end function
			
	};
?>