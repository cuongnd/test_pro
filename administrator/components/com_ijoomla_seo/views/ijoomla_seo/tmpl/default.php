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

defined('_JEXEC') or die('Restricted Access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

require_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."reader.php");
include(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/stats.js");

$display_menus = "none";
$display_mtree = "none";
$display_zoo = "none";
$display_ktwo = "none";
$display_kunena = "none";
$display_easyblog = "none";

$document = JFactory::getDocument();
$document->addScriptDeclaration("
    window.addEvent('domready', function() {
        document.getElementById('toolbar-box').style.display = 'none';
    });
");

?>

<style type="text/css">
	.chzn-drop{
		width:auto !important;
	}
	
	.chzn-container{
		text-align:left;
		width:110px !important;
	}
	
	.chzn-container a:link, table#stats a:visited{
		color:#000000 !important;
	}
</style>

<div class="row-flow">
	<div class="span8">
		<div class="span12">
        	<!-- ------------------------------------- -->
            <table class="table table-condensed" id="stats">
				<tr>
					<th valign="top">
                    	<h2>
							<?php echo JText::_("COM_IJOOMLA_SEO_STATS"); ?>
						</h2>
					</th>
					
					<th style="text-align:right; vertical-align:middle;">
						<?php echo $this->menu_type; ?>
					</th>
					
					<th align="right" width="5%" style="padding-right:10px; vertical-align:middle;">						 
						<div id="menu_types" style="display:<?php echo $display_menus; ?>;">
						<?php
							echo $this->createSelect("menuitems");
						?> 					
                        </div>
						<div id="mtree" style="display:<?php echo $display_mtree; ?>;">
						<?php
							echo $this->createSelect("mtree");
						?> 					
                        </div>
						<div id="zoo" style="display:<?php echo $display_zoo; ?>;">
						<?php
							echo $this->createSelect("zoo");
						?> 					
                        </div>					 
						<div id="ktwo" style="display:<?php echo $display_ktwo; ?>;">
						<?php
							echo $this->createSelect("ktwo");
						?> 					
                        </div>
						<div id="kunena" style="display:<?php echo $display_kunena; ?>;">
						<?php
							echo $this->createSelect("kunena");
						?> 					
                        </div>
						<div id="easyblog" style="display:<?php echo $display_easyblog; ?>;">
						<?php
							echo $this->createSelect("easyblog");
						?> 					
                        </div>
					</th>
				</tr>
				<tr>
					<td colspan="3" style="border:none;">
						<table class="table table-condensed">
							<tr>
								<td><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_UP"); ?></td>
								<td id="stat1"></td>
								<td><?php echo JText::_("COM_IJOOMLA_SEO_MISSING_TITLE_METATAG"); ?></td>
								<td id="stat5"></td>
							</tr>
							<tr>
								<td><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_DOWN"); ?></td>
								<td id="stat2"></td>
								<td><?php echo JText::_("COM_IJOOMLA_SEO_MISSING_KEYS_METATAG"); ?></td>
								<td id="stat6"></td>
							</tr>
							<tr>
								<td><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_CHANGE"); ?></td>
								<td id="stat3"></td>
								<td><?php echo JText::_("COM_IJOOMLA_SEO_MISSING_DESC_METATAG"); ?></td>
								<td id="stat7"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
            
            <script language="javascript" type="text/javascript">
				getStats('article', '');
			</script>
            
            <table class="adminform">
				<tr>
					<td >
						<div id="cpanel">
			
							<!-- ### line 1 ### -->
							<div style="float:left;">
								<div class="icon">
									<a href="index.php?option=com_ijoomla_seo&controller=config">
										<img src="components/com_ijoomla_seo/images/icons/settings.png" alt="<?php echo JText::_("COM_IJOOMLA_SEO_CONFIG");?>" align="middle" name="" border="0" />
                                        <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
	                                        <span><?php echo JText::_("COM_IJOOMLA_SEO_CONFIG");?></span>
                                        </div>
									</a>
								</div>
							</div>
			
							<div style="float:left;">
								<div class="icon">
									<a href="index.php?option=com_ijoomla_seo&controller=menus&choosemain=1">
										<img src="components/com_ijoomla_seo/images/icons/readers.png"
										alt="<?php echo JText::_("COM_IJOOMLA_SEO_METATAGS") ; ?>" align="middle" name="" border="0" />
										
                                        <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                        	<span><?php echo JText::_("COM_IJOOMLA_SEO_METATAGS") ; ?></span>
                                        </div>
									</a>
								</div>
							</div>
			
							<div style="float:left;">
								<div class="icon">
									<a href="index.php?option=com_ijoomla_seo&controller=redirect">
										<img src="components/com_ijoomla_seo/images/icons/redirects.png"
										alt="<?php echo JText::_("COM_IJOOMLA_SEO_REDIRECTS");?>" align="middle" name="" border="0" />
                                        
										<div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                        	<span><?php echo JText::_("COM_IJOOMLA_SEO_REDIRECTS");?></span>
										</div>
									</a>
								</div>
							</div>
								 <div style="float:left;">
										<div class="icon">
											<a href="index.php?option=com_ijoomla_seo&controller=ilinks">
												 <img src="components/com_ijoomla_seo/images/icons/internal.png"
												 alt="<?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LINKS");?>" align="middle" name="" border="0" />
												 
                                                 <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
													<span><?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LINKS");?></span>
                                                 </div>
											</a>
										</div>
								 </div>
								 <div style="float:left;">
										<div class="icon">
											<a href="index.php?option=com_ijoomla_seo&controller=keys">
												 <img src="components/com_ijoomla_seo/images/icons/keywords.png"
												 alt="<?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS");?>" align="middle" name="" border="0" />
												 
                                                 <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                                 	<span><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS");?></span>
                                                 </div>
											</a>
										</div>
								 </div>
								 <div style="float:left;">
										<div class="icon">
											<a href="index.php?option=com_ijoomla_seo&controller=pages">
												 <img src="components/com_ijoomla_seo/images/icons/content.png"
												 alt="<?php echo JText::_("COM_IJOOMLA_SEO_PAGES");?>" align="middle" name="" border="0" />
												 
                                                 <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                                 	<span><?php echo JText::_("COM_IJOOMLA_SEO_PAGES");?></span>
                                                 </div>
											</a>
										</div>
								 </div>
								 <div style="float:left;">
							</div>
			
							<!-- ### line 2 ### -->
							<div style="float:left;">
								<div class="icon">
									<a href="http://www.ijoomla.com/redirect/seo/forum.htm"  target='_blank'>
										<img src="components/com_ijoomla_seo/images/icons/forum.png"
										alt="<?php echo JText::_("COM_IJOOMLA_SEO_FORUMS");?>" align="middle" name="" border="0" />
										
                                        <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                        	<span><?php echo JText::_("COM_IJOOMLA_SEO_FORUMS");?></span>
                                        </div>
									</a>
								</div>
							</div>
			
							<div style="float:left;">
								<div class="icon">
									<a href="http://www.ijoomla.com/redirect/general/contact.htm" target="_blank">
										<img src="components/com_ijoomla_seo/images/icons/contact.png"
										alt="<?php echo JText::_("COM_IJOOMLA_SEO_CONTACT_US");?>" align="middle" name="" border="0" />
										
                                        <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                        	<span><?php echo JText::_("COM_IJOOMLA_SEO_CONTACT_US");?></span>
                                        </div>
									</a>
								</div>
							</div>
                            
                            <div class="icon">
                                <a href="index.php?option=com_ijoomla_seo&controller=language&id=english.ijoomla_seo&hidemainmenu=1">
                                    <img src="components/com_ijoomla_seo/images/icons/language.png"
                                    alt="<?php echo JText::_("COM_IJOOMLA_SEO_LANGUAGES");?>" align="middle" name="" border="0" />
                                    
                                    <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                        <span><?php echo JText::_("COM_IJOOMLA_SEO_LANGUAGES");?></span>
                                    </div>
                                </a>
                            </div>
                            
                            <div style="float:left;">
								<div class="icon">
									<a href="http://www.ijoomla.com/redirect/seo/faq.htm" target="_blank">
										<img src="components/com_ijoomla_seo/images/icons/how_to_use.png"
										alt="<?php echo JText::_("COM_IJOOMLA_SEO_FAQ");?>" align="middle" name="" border="0" />
										
                                        <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                        	<span><?php echo JText::_("COM_IJOOMLA_SEO_FAQ");?></span>
                                        </div>
									</a>
								</div>
							</div>
			
							<!-- ### line 3 ### -->
							<div style="float:left;">
								<div class="icon">
									<a href="http://www.ijoomla.com/redirect/general/support.htm"  target='_blank'>
										<img src="components/com_ijoomla_seo/images/icons/support.png"
										alt="<?php echo JText::_("COM_IJOOMLA_SEO_SUPPORT_HELP");?>" align="middle" name="" border="0" />
										
                                        <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                        	<span><?php echo JText::_("COM_IJOOMLA_SEO_SUPPORT_HELP");?></span>
                                        </div>
									</a>
								</div>
							</div>
			
							<div style="float:left;">
								<div class="icon">
									<a href="http://www.ijoomla.com/redirect/general/latestversion.htm" target="_blank">
										<img src="components/com_ijoomla_seo/images/icons/latest_version.png"
										alt="<?php echo JText::_("COM_IJOOMLA_SEO_LATEST_VERSION"); ?>" align="middle" name="" border="0" />
										
                                        <div style="float:left; width:100%; text-align:center; padding-top: 10px;">
                                        	<span><?php echo JText::_("COM_IJOOMLA_SEO_LATEST_VERSION"); ?></span>
                                        </div>
									</a>
								</div>
							</div>
						</div>
					</td>
				</tr>
			</table>
            
            <!-- ------------------------------------- -->
        </div>
	</div>
    <div class="span4">
		<div class="span12">
			<?php 
				$extensions = get_loaded_extensions();
				$text = "";
				if(in_array("curl", $extensions)){
					$data = "http://www.ijoomla.com/seo_announcements.txt";
					$ch = curl_init($data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_TIMEOUT, 20); 
					$text = curl_exec($ch); 
				}
				else{
					$text = file_get_contents('http://www.ijoomla.com/seo_announcements.txt');
				}
				if($text && (trim($text) != '')){
					echo '<div class="well well-small" style="font-size:12px !important;">'.$text.'</div>' ;
				}
			?>
		</div>
		<div class="clearfix"></div>
        <div class="row-flow">
            <div class="span12">
                <div id="ijoomla_news_tabs">
                </div>
            </div>
        </div>
	</div>
</div>

<div class="clearfix"></div>