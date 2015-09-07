<?php
/**
 * Administrator menu helper class
 *
 * This class was derived from the show_image_in_imgtag.php and imageTools.class.php files in VM.  It provides some
 * image functions that are used throughout the VirtueMart shop.
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Eugen Stranz
 * @copyright Copyright (c) 2004-2008 Soeren Eberhardt-Biermann, 2009 VirtueMart Team. All rights reserved.
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ();

class AdminUIHelper {

	public static $vmAdminAreaStarted = false;
	public static $backEnd = true;

   /**
     * Start the administrator area table
     *
     * The entire administrator area with contained in a table which include the admin ribbon menu
     * in the left column and the content in the right column.  This function sets up the table and
     * displayes the admin menu in the left column.
     */
    static function startAdminArea($backEnd=true) {
		if (JRequest::getWord ( 'format') =='pdf') return;
		if (JRequest::getWord ( 'tmpl') =='component') self::$backEnd=false;
    	if(self::$vmAdminAreaStarted) return;
    	self::$vmAdminAreaStarted = true;
		$front = JURI::root(true).'/components/com_virtuemart/assets/';
		$admin = JURI::root(true).'/administrator/components/com_virtuemart/assets/';
		$document = JFactory::getDocument();

		//loading defaut admin CSS
		$document->addStyleSheet($front.'css/vtip.css');

		//loading defaut script

		$document->addScript($admin.'js/jquery.coookie.js');
		$document->addScript($admin.'js/vm2admin.js');
		if (JText::_('COM_VIRTUEMART_JS_STRINGS') == 'COM_VIRTUEMART_JS_STRINGS') $vm2string = "editImage: 'edit image',select_all_text: 'select all options',select_some_options_text: 'select some options'" ;
		else $vm2string = JText::_('COM_VIRTUEMART_JS_STRINGS') ;
		
		?>
		<?php if (!self::$backEnd) echo '<div class="toolbar" style="height: 84px;position: relative;">'.vmView::getToolbar().'</div>'; ?>
		<div class="virtuemart-admin-area">
		<?php
		// Include ALU System
		if (self::$backEnd) {
		require_once JPATH_VM_ADMINISTRATOR.DS.'liveupdate'.DS.'liveupdate.php';
		?>

		<div id="j-sidebar-container" class="span2">
			<!--<a href="index.php?option=com_virtuemart&view=virtuemart" ><div class="menu-vmlogo"></div></a>-->
				<?php AdminUIHelper::showAdminMenu();
				?>
		</div>
		<div id="j-main-container" class="span10">
		<?php 
		return;
		} ?>
			<div id="admin-content-wrapper">
			<div class="toggler vmicon-show"></div>
				<div id="admin-content" class="admin-content">
		<?php
	}

	/**
	 * Close out the adminstrator area table.
	 * @author RickG, Max Milbers
	 */
	static function endAdminArea() {
		if (!self::$backEnd) return;
		self::$vmAdminAreaStarted = false;
		if (VmConfig::get('debug') == '1') {
		//TODO maybe add debuggin again here
//		include(JPATH_VM_ADMINISTRATOR.'debug.php');
		}
		?>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	<?php
	    }

	/**
	 * Admin UI Tabs
	 * Gives A Tab Based Navigation Back And Loads The Templates With A Nice Design
	 * @param $load_template = a key => value array. key = template name, value = Language File contraction
	 * @params $cookieName = choose a cookiename or leave empty if you don't want cookie tabs in this place
	 * @example 'shop' => 'COM_VIRTUEMART_ADMIN_CFG_SHOPTAB'
	 */
	static public function buildTabs($view, $load_template = array(),$cookieName='') {
		$cookieName = JRequest::getWord('view','virtuemart').$cookieName;
		$document = JFactory::getDocument ();
		$document->addScriptDeclaration ( '
		var virtuemartcookie="'.$cookieName.'";
		');

		$html = '<div id="admin-ui-tabs">';

		foreach ( $load_template as $tab_content => $tab_title ) {
			$html .= '<div class="tabs" title="' . JText::_ ( $tab_title ) . '">';
			$html .= $view->loadTemplate ( $tab_content );
			$html .= '<div class="clear"></div></div>';
		}
		$html .= '</div>';
		echo $html;
	}

	/**
	 * Admin UI Tabs Imitation
	 * Gives A Tab Based Navigation Back And Loads The Templates With A Nice Design
	 * @param $return = return the start tag or the closing tag - choose 'start' or 'end'
	 * @params $language = pass the language string
	 */
	static function imitateTabs($return,$language = '') {
		if ($return == 'start') {
			$document = JFactory::getDocument ();
			$document->addScriptDeclaration ( '
			var virtuemartcookie="vm-tab";
			');
			$html = 	'<div id="admin-ui-tabs">

							<div class="tabs" title="'.JText::_($language).'">';
			echo $html;
		}
		if ($return == 'end') {
			$html = '		</div>
						</div>';
			echo $html;
		}
	}

	/**
	 * Build an array containing all the menu items.
	 *
	 * @param int $moduleId Id of the module to filter on
	 */
	static function _getAdminMenu($moduleId = 0) {
		$db = JFactory::getDBO ();
		$menuArr = array ();

		$filter [] = "jmmod.published='1'";
		$filter [] = "item.published='1'";
		$filter [] = "jmmod.is_admin='1'";
		if (! empty ( $moduleId )) {
			$filter [] = 'vmmod.module_id=' . ( int ) $moduleId;
		}

		$query = 'SELECT `jmmod`.`module_id`, `module_name`, `module_perms`, `id`, `name`, `link`, `depends`, `icon_class`, `view`, `task`
						FROM `#__virtuemart_modules` AS jmmod
						LEFT JOIN `#__virtuemart_adminmenuentries` AS item ON `jmmod`.`module_id`=`item`.`module_id`
						WHERE  ' . implode ( ' AND ', $filter ) . '
						ORDER BY `jmmod`.`ordering`, `item`.`ordering` ';

		$db->setQuery ( $query );
		$result = $db->loadAssocList ();
		//		echo '<pre>'.print_r($query,1).'</pre>';
		for($i = 0, $n = count ( $result ); $i < $n; $i ++) {
			$row = $result [$i];
			$menuArr [$row['module_id']] ['title'] = 'COM_VIRTUEMART_' . strtoupper ( $row['module_name'] ) . '_MOD';
			$menuArr [$row['module_id']] ['items'] [] = $row ;
		}
		return $menuArr;
	}

	/**
	 * Display the administrative ribbon menu.
	 * @todo The link should be done better
	 */
	static function showAdminMenu() {
		$document = JFactory::getDocument ();
		$moduleId = JRequest::getInt ( 'module_id', 0 );

		$menuItems = array();//AdminUIHelper::_getAdminMenu ( $moduleId );
		$sideItems = AdminUIHelper::_getAdminMenu ( $moduleId );
		foreach ( $sideItems as $item ) {
			JHtmlSidebar::addEntry(	JText::_ ( $item ['title'] ), '', null );

			foreach ( $item ['items'] as $link ) {
				$target='';
				if ($link ['name'] == '-') continue ;
				if (strncmp ( $link ['link'], 'http', 4 ) === 0) {
					$url = $link ['link'];
					$target='TARGET="_blank"';
				} else {
					$url = ($link ['link'] === '') ? 'index.php?option=com_virtuemart' :$link ['link'] ;
					$url .= $link ['view'] ? "&view=" . $link ['view'] : '';
					$url .= $link ['task'] ? "&task=" . $link ['task'] : '';
					// $url .= $link['extra'] ? $link['extra'] : '';
				}
			
				JHtmlSidebar::addEntry( JText::_ ( $link ['name'] ), $url, null );
			}
		}
		$sidebar = JHtmlSidebar::render(); 
		echo $sidebar ;
		return ;
		// old code here
		?>

		<div id="admin-ui-menu" class="admin-ui-menu">

		<?php
		$modCount = 1;
		foreach ( $menuItems as $item ) { ?>

			<h3 class="menu-title">
				<?php echo JText::_ ( $item ['title'] )?>
			</h3>

			<div class="menu-list">
				<ul>
				<?php
				foreach ( $item ['items'] as $link ) {
				    $target='';
					if ($link ['name'] == '-') {
						// it was emtpy before
					} else {
						if (strncmp ( $link ['link'], 'http', 4 ) === 0) {
							$url = $link ['link'];
							$target='TARGET="_blank"';
						} else {
							$url = ($link ['link'] === '') ? 'index.php?option=com_virtuemart' :$link ['link'] ;
							$url .= $link ['view'] ? "&view=" . $link ['view'] : '';
							$url .= $link ['task'] ? "&task=" . $link ['task'] : '';
							// $url .= $link['extra'] ? $link['extra'] : '';
						}
						?>
					<li>
						<a href="<?php echo $url; ?>" <?php echo $target; ?>><span class="<?php echo $link ['icon_class'] ?>"></span><?php echo JText::_ ( $link ['name'] )?></a>
					</li>
					<?php
					}
				}
				?>
			    </ul>
			</div>

			<?php
			$modCount ++;
		}
		?>
		</div>
	<?php
	}

}

?>