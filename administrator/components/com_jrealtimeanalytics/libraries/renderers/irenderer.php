<?php
/**
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ();

/**
 * @abstract
 * @since	1.0.1
 */
interface IRenderer {
	/**
	 * Istanzia TCPDF eseguendo le inizializzazioni ed esegue la conversione inviando in attachment il PDF 
	 * @access public
	 * @param string $html
	 * @param string $mode
	 * @param string $data
	 * @return Void
	 */
	public function renderContent($html, $model, $mode = 'D');
}
?>