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
JHTML::_('behavior.modal');

include(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");

?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="seo-head-line-title">
        <div class="row-fluid">
            <div class="span12">
                <span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_LANGUAGES"); ?></span>
            </div>
        </div>
    </div>
    
    <div class="seo-head-line">
        <div class="row-fluid">
            <div class="span12">
                <?php echo JText::_("COM_IJOOMLA_SEO_LANG_OVERRIDE"); ?>
            </div>
        </div>
    </div>
	
	<table width="100%">
		<tbody>
			<tr>
				<td>
					<textarea style="width: 99%;" class="inputbox" name="filecontent" rows="25" cols="110" style="width: 100%;"><?php echo $this->language; ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
	
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="language" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>