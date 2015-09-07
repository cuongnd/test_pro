<?php
// namespace administrator\components\com_jchat\views\cpanel;
/**
 *
 * @package JCHAT::CPANEL::administrator::components::com_jchat
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
 * @package JCHAT::CPANEL::administrator::components::com_jchat
 * @subpackage views
 * @subpackage cpanel
 * @since 1.0
 */
class JChatViewCpanel extends JViewLegacy {
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
		$lang = JFactory::getLanguage ();
		?>
	<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
		<div class="icon">
			<a href="<?php echo $link; ?>">
						<img src="components/com_jchat/images/<?php echo $image;?>" />
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
		JToolBarHelper::title( JText::_( 'CPANEL_TOOLBAR' ), 'jchat' );
		JToolBarHelper::custom('cpanel.display', 'config', 'config', 'JCHAT_CPANEL', false);
	}
	
	/**
	 * Effettua il rendering del pannello di controllo
	 * @access public
	 * @return void
	 */
	public function display($tpl = null) {
		jimport ( 'joomla.html.pane' );
		$doc = JFactory::getDocument ();
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jchat/css/generic.css' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_jchat/css/cpanel.css' );
		// Buffer delle icons
		ob_start ();
		$this->getIcon ( 'index.php?option=com_jchat&task=messages.display', 'icon-48-readmess.png', JText::_ ( 'JCHAT_MESSAGES' ) );
		$this->getIcon ( 'index.php?option=com_jchat&task=config.display', 'icon-48-config.png', JText::_ ( 'JCHAT_CONFIG' ) );
		$this->getIcon ( 'index.php?option=com_jchat&task=cpanel.purgefilecache', 'icon-48-purge.png', JText::_ ( 'JCHAT_PURGECACHE' ) );
		$this->getIcon ( 'index.php?option=com_jchat&task=help.display', 'icon-48-help.png', JText::_ ( 'JCHAT_HELP' ) );
		
		$contents = ob_get_clean ();
		
		// Assign reference variables
		$this->icons = $contents;
		$this->componentParams = JComponentHelper::getParams('com_jchat');
		$this->totalusers = $this->get('TotalUsers');
		$this->totallogged = $this->get('TotalLoggedUsers');
		
		// Add toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display ();
	}
}
?>