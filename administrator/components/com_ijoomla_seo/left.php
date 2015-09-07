<?php 
defined( '_JEXEC' ) or die( 'Restricted access' ); 

$task = JRequest::getVar("task", "");
if($task == "vimeo"){
	return false;
}

 
?>
<div class="nav-collapse collapse">
    <table width="100%">
        <tr>
            <td width="50%" style="text-align:left;">
                <a href="http://seo.ijoomla.com" target="_blank">
                	<img src="components/com_ijoomla_seo/images/logo_top.png" />
                </a>
            </td>
            <td width="50%" style="text-align:right;">
                <a href="http://www.ijoomla.com" target="_blank">
                	<img src="components/com_ijoomla_seo/images/ijoomla-logo.png" />
                </a>
            </td>
        </tr>
    </table>
</div>

<div class="ui-app">
	<div class="navbar adagencynavbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<div class="nav-collapse collapse">
					<table width="100%">
						<tr>
							<td	style="padding-right: 25px; text-align: right; width:70%">
								<a href="http://tiny.cc/jed-seo" target="_blank" />
                                	<?php
										echo '<span class="small-text">'.JText::_("COM_IJOOMLA_SEO_POST_RATING").'</span>';
									?>
									<img src="components/com_ijoomla_seo/images/icons/mini_joomla_logo.png" />
								</a>
							</td>
							<td width="13%">
								<a href="http://twitter.com/ijoomla" target="_blank" />
                                	<?php
										echo '<span class="small-text">'.JText::_("COM_IJOOMLA_SEO_TWITTER").'</span>';
									?>
									<img src="components/com_ijoomla_seo/images/icons/twitter.png" />
								</a>
							</td>
							<td width="15%">
								<a href="https://www.facebook.com/ijoomla" target="_blank" />
                                	<?php
										echo '<span class="small-text">'.JText::_("COM_IJOOMLA_SEO_FACEBOOK").'</span>';
									?>
									<img src="components/com_ijoomla_seo/images/icons/facebook.png" />
								</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="clearfix"></div>

<div class="ui-app">
	<div class="navbar adagency-navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li>
							<a href="index.php?option=com_ijoomla_seo">
								<i class="icon-home"></i>
								<?php echo JText::_("COM_IJOOMLA_SEO_CONTROL_PANEL"); ?>
							</a>
						</li>
                        
                        <li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle active" href="#">
								<i class="icon-cog"></i>
								<?php echo JText::_("COM_IJOOMLA_SEO_CONFIG"); ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=config&task2=general">
										<i class="icon-wrench"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_GENERAL"); ?>
									</a>
								</li>
                                <li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=config&task2=track_keywords">
										<i class="icon-wrench"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_KEY"); ?>
									</a>
								</li>
                                <li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=config&task2=google_ping">
										<i class="icon-wrench"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_GOOGLE_PING"); ?>
									</a>
								</li>
								<li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=language&id=english.ijoomla_seo&hidemainmenu=1">
										<i class="icon-font"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_LANGUAGES"); ?>
									</a>
								</li>
							</ul>
						</li>
                        
                        <li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle active" href="#">
								<i class="icon-eye-open"></i>
								<?php echo JText::_("COM_IJOOMLA_SEO_METATAGS"); ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
                                <li class="">
                                    <a href="index.php?option=com_ijoomla_seo&controller=menus&choosemain=1">
                                        <i class="icon-magnet"></i>
                                        <?php echo JText::_("COM_IJOOMLA_SEO_METATAGS"); ?>
                                    </a>
                                </li>
                                <li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=config&task2=manage_meta">
										<i class="icon-wrench"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_LM_SETTINGS"); ?>
									</a>
								</li>
							</ul>
						</li>
                        
                        
                        <li class="">
                            <a href="index.php?option=com_ijoomla_seo&controller=keys">
                                <i class="icon-plus"></i>
                                <?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS"); ?>
                            </a>
                        </li>
                        
                        <li class="">
                            <a href="index.php?option=com_ijoomla_seo&controller=pages">
                                <i class="icon-thumbs-up"></i>
                                <?php echo JText::_("COM_IJOOMLA_SEO_PAGES"); ?>
                            </a>
                        </li>
                        
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle active" href="#">
								<i class="icon-share"></i>
								<?php echo JText::_("COM_IJOOMLA_SEO_REDIRECTS"); ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=redirect">
										<i class="icon-share"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_REDIRECTS"); ?>
									</a>
								</li>
                                <li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=redirectcategory">
										<i class="icon-th"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_CATEGORIES"); ?>
									</a>
								</li>
							</ul>
						</li>
                        
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle active" href="#">
								<i class="icon-resize-full"></i>
								<?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LINKS"); ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=ilinks">
										<i class="icon-resize-full"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LIST"); ?>
									</a>
								</li>
								<li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=ilinkscategory">
										<i class="icon-th"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_CATEGORIES"); ?>
									</a>
								</li>
                                <li class="">
									<a href="index.php?option=com_ijoomla_seo&controller=config&task2=keyword_linking">
										<i class="icon-wrench"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_LM_SETTINGS"); ?>
									</a>
								</li>
							</ul>
						</li>
                        
						<li class="dropdown">
							<a data-toggle="dropdown" class="dropdown-toggle active" href="#">
								<i class="icon-question-sign"></i>
								<?php echo JText::_("COM_IJOOMLA_SEO_DOCUMENTATION"); ?> <b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a href="http://www.ijoomla.com/redirect/seo/course.htm" target="_blank">
										<i class="icon-plus-sign"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_GURU_COURSE"); ?>
									</a>
								</li>
                                <li class="">
									<a href="http://www.ijoomla.com" target="_blank">
										<i class="icon-heart"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_IJOOMLA_WEBSITE"); ?>
									</a>
								</li>
								<li class="">
									<a href="http://www.ijoomla.com/redirect/general/support.htm" target="_blank">
										<i class="icon-question-sign"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_SUPPORT_HELP"); ?>
									</a>
								</li>
								<li class="">
									<a href="http://www.ijoomla.com/redirect/seo/forum.htm" target="_blank">
										<i class="icon-comment"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_FORUMS"); ?>
									</a>
								</li>
								<li class="">
									<a href="http://www.ijoomla.com/redirect/general/templates.htm" target="_blank">
										<i class="icon-briefcase"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_TEMPLATES"); ?>
									</a>
								</li>
								<li class="">
									<a href="http://www.ijoomla.com/redirect/general/latestversion.htm" target="_blank">
										<i class="icon-download-alt"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_LATEST_VERSION"); ?>
									</a>
								</li>
								<li class="">
									<a href="http://www.ijoomla.com/redirect/general/othercomponents.htm" target="_blank">
										<i class="icon-th"></i>
										<?php echo JText::_("COM_IJOOMLA_SEO_OTHER_COMPONENTS"); ?>
									</a>
								</li>
							</ul>
						</li>
                        
						<li>
							<a href="index.php?option=com_ijoomla_seo&controller=about">
								<i class="icon-magnet"></i>
								<?php echo JText::_("COM_IJOOMLA_SEO_ABOUT"); ?>
							</a>
						</li>
                        
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="clearfix"></div>