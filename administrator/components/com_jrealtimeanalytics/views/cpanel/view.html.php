<?php
// namespace administrator\components\com_jrealtimeanalytics\views\cpanel;
/**
 *
 * @package JREALTIMEANALYTICS::CPANEL::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage cpanel
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.view' );

/**
 * CPanel view
 *
 * @package JREALTIMEANALYTICS::CPANEL::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage cpanel
 * @since 1.0
 */
class JRealtimeAnalyticsViewCpanel extends JViewLegacy {
	/**
	 * Renderizza l'iconset del cpanel
	 *
	 * @param $link string
	 * @param $image string
	 * @access private
	 * @return string
	 */
	private function getIcon($link, $image, $text) {
		$mainframe = JFactory::getApplication ();
		$lang = & JFactory::getLanguage ();
		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
							<img src="components/com_jrealtimeanalytics/images/<?php echo $image;?>" />
							<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		JToolBarHelper::title( JText::_( 'CPANEL_HEADER' ), 'jrealtimeanalytics' );
		JToolBarHelper::custom('cpanel.display', 'config', 'config', 'CPANEL', false);
	}
	
	/**
	 * Effettua il rendering del pannello di controllo
	 * @access public
	 * @return void
	 */
	public function display() { 
		jimport ( 'joomla.html.pane' );
		$app = JFactory::getApplication();
		$template = $app->getTemplate();
		$imgsPath = 'templates/' . $template . '/images/admin/';
		$doc = JFactory::getDocument ();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/css/cpanel.css' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jrealtimeanalytics/css/license.css' );
		// Buffer delle icons
		ob_start ();
		$this->getIcon ( 'index.php?option=com_jrealtimeanalytics&task=realstats.display', 'icon-48-realstats.png', JText::_ ( 'REALSTATS' ) );
		$this->getIcon ( 'index.php?option=com_jrealtimeanalytics&task=serverstats.display', 'icon-48-stats.png', JText::_ ( 'SERVERSTATS' ) );
		$this->getIcon ( 'index.php?option=com_jrealtimeanalytics&task=config.display', 'icon-48-config.png', JText::_ ( 'CONFIG_ICON' ) );
		$this->getIcon ( 'index.php?option=com_jrealtimeanalytics&task=help.display', 'icon-48-help.png', JText::_ ( 'INSTRUCTIONS' ) );
		$contents = ob_get_clean ();
		
		// Assign reference variables
		$this->assignRef ( 'icons', $contents );
		$this->assignRef ( 'componentParams', JComponentHelper::getParams('com_jrealtimeanalytics') );
		$this->assignRef ( 'imgpath', $imgsPath);
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		// Output del template
		parent::display ();
	} 
}