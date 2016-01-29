<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/guest/view.html.php
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

/**
 * HTML View class for the Jblance component
 */
class JblanceViewGuest extends JViewLegacy {

	function display($tpl = null){
		$app  	=& JFactory::getApplication();
		$layout = $app->input->get('layout', 'showfront', 'string');
		$model	=& $this->getModel();
		
		if($layout == 'showfront'){
			
			$return = $model->getShowFront();
			$userGroups = $return[0];
			$this->assignRef('userGroups', $userGroups);
		}
		elseif($layout == 'usergroupfield'){
			$fields = $model->getUserGroupField();
		
			$this->assignRef('fields', $fields);
		}
		
        parent::display($tpl);

	}
}