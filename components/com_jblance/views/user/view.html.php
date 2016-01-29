<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	21 March 2012
 * @file name	:	views/user/view.html.php
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
class JblanceViewUser extends JViewLegacy {

	function display($tpl = null){
		$app  	=& JFactory::getApplication();
		$layout = $app->input->get('layout', 'dashboard', 'string');
		$model	=& $this->getModel();
		$user	=& JFactory::getUser();
		
		JblanceHelper::isAuthenticated($user->id, $layout);
		
		if($layout == 'dashboard'){
			$return = $model->getDashboard();
			$dbElements = $return[0];
			$userInfo = $return[1];
			$feeds = $return[2];
			$pendings = $return[3];
			
			$this->assignRef('dbElements', $dbElements);
			$this->assignRef('userInfo', $userInfo);
			$this->assignRef('feeds', $feeds);
			$this->assignRef('pendings', $pendings);
		}
		elseif($layout == 'editprofile'){
			$return = $model->getEditProfile();
			$userInfo = $return[0];
			$fields = $return[1];
		
			$this->assignRef('userInfo', $userInfo);
			$this->assignRef('fields', $fields);
		}
		elseif($layout == 'editpicture'){
			$return = $model->getEditPicture();
			$row = $return[0];
			
			$this->assignRef('row', $row);
		}
		elseif($layout == 'editportfolio'){
			$return = $model->getEditPortfolio();
			$row = $return[0];
			$portfolios = $return[1];
			
			$this->assignRef('row', $row);
			$this->assignRef('portfolios', $portfolios);
		}
		elseif($layout == 'userlist'){
			$return = $model->getUserList();
			$rows = $return[0];
			$pageNav = $return[1];
			$params = $return[2];
		
			$this->assignRef('pageNav', $pageNav);
			$this->assignRef('rows', $rows);
			$this->assignRef('params', $params);
		}
		elseif($layout == 'viewportfolio'){
		
			$return 	= $model->getViewPortfolio();
			$row 	= $return[0];
			$this->assignRef('row', $row);
		}
		elseif($layout == 'viewprofile'){
			
			$return 	= $model->getViewProfile();
			$userInfo 	= $return[0];
			$fields 	= $return[1];
			$fprojects 	= $return[2];
			$frating 	= $return[3];
			$bprojects 	= $return[4];
			$brating 	= $return[5];
			$portfolios = $return[6];
		
			$this->assignRef('userInfo', $userInfo);
			$this->assignRef('fields', $fields);
			$this->assignRef('fprojects', $fprojects);
			$this->assignRef('frating', $frating);
			$this->assignRef('bprojects', $bprojects);
			$this->assignRef('brating', $brating);
			$this->assignRef('portfolios', $portfolios);
		}
		elseif($layout == 'notify'){
			
			$return = $model->getNotify();
			$row = $return[0];
		
			$this->assignRef('row', $row);
		}
        parent::display($tpl);

	}
}