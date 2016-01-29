<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	30 May 2012
 * @file name	:	views/message/view.html.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.view');

$config1 =& JblanceHelper::getConfig();

$document = & JFactory::getDocument();
$document->addStyleSheet("components/com_jblance/css/$config1->theme");
$document->addStyleSheet("components/com_jblance/css/style.css");
?>
<table width="100%" style="table-layout:fixed;">
	<tr>
		<td style="vertical-align:top;">
			<?php
				include_once(JPATH_COMPONENT.'/views/jbmenu.php');
			?>
		</td>
	</tr>
</table>
<?php
/**
 * HTML View class for the Jblance component
 */
class JblanceViewMessage extends JViewLegacy {

	function display($tpl = null){
		$app  	=& JFactory::getApplication();
		$layout = $app->input->get('layout', 'inbox', 'string');
		$model	=& $this->getModel();
		$user	=& JFactory::getUser();
		
		JblanceHelper::isAuthenticated($user->id, $layout);
		
		if($layout == 'inbox'){
			$return = $model->getInbox();
			$in_msgs = $return[0];
			$out_msgs = $return[1];
			$newInMsg = $return[2];
			$newOutMsg = $return[3];
			
			$this->assignRef('in_msgs', $in_msgs);
			$this->assignRef('out_msgs', $out_msgs);
			$this->assignRef('newInMsg', $newInMsg);
			$this->assignRef('newOutMsg', $newOutMsg);
		}
		if($layout == 'read'){
			$return = $model->getMessageRead();
			$parent = $return[0];
			$rows = $return[1];
			
			$this->assignRef('parent', $parent);
			$this->assignRef('rows', $rows);
		}
		if($layout == 'compose'){
			/* $return = $model->getMessageRead();
			$parent = $return[0];
			$rows = $return[1];
			
			$this->assignRef('parent', $parent);
			$this->assignRef('rows', $rows); */
		}
		
        parent::display($tpl);

	}
}