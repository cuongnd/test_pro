<?php
//namespace components\com_jchat\libraries; 
/** 
 * @package JCHAT::components::com_jchat
 * @subpackage libraries
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.language.language');

/**
 * @package JCHAT::components::com_jchat
 * @subpackage libraries
 * @since 1.0
 */ 
class JChatLanguage extends JLanguage {
	/**
	 * Injector language const to JS domain with same name mapping
	 * @access protected
	 * @param $translations Object&
	 * @param $document Object&
	 * @return void
	 */
	public function injectJsTranslations(&$translations, &$document) {
		$jsInject = null;
		// Do translations
		foreach ($translations as $translation) {
			$jsTranslation = strtoupper($translation);
			$translated = JText::_('JCHAT_' . $jsTranslation, true);
			$jsInject .= <<<JS
				var jchat_$translation = '{$translated}'; 
JS;
		}
		$document->addScriptDeclaration($jsInject);
	}

	/**
	 * Override Language instantiator 
	 * 
	 * @access	public
	 * @return	JLanguage  The Language object.
	 * @since	1.5
	 */
	public static function getInstance($lang = null, $debug = false) {
		static $lang;
		
		if(!is_object($lang)) {
			$conf	= JFactory::getConfig();
			$locale	= $conf->get('config.language');
			$lang = new JChatLanguage($locale);
			$lang->setDebug($conf->get('config.debug_lang'));
		}
		
		return $lang;
	}
}
