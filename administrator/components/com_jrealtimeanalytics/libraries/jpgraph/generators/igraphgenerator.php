<?php 
//namespace administrator\components\com_jrealtimeanalytics\libraries\jpgraph\generators;
/**
 * @author Joomla! Extensions Store
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage libraries 
 * @subpackage jpgraph
 * @subpackage generators
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ();

/**
 * Definisce le responsabilità delle classi generator dei form
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage libraries 
 * @subpackage jpgraph
 * @subpackage generators
 * @since	1.0
 */
interface IGraphGenerator {
	/**
	 * Ottenute le informazioni necessarie da avvio alla reale generazione dell'immagine del grafico in cache folder
	 * @access public 
	 * @param Object& $graphDataModel 
	 * @return Void
	 */
	public function buildBars(&$graphDataModel);
	
	/**
	 * Ottenute le informazioni necessarie da avvio alla reale generazione dell'immagine del grafico in cache folder
	 * @access public
	 * @param Object& $graphData
	 * @param array $geoTranslations
	 * @param array $sizes
	 * @param boolean $title
	 * @param array $legendPos
	 * @return Void
	 */
	public function buildPies(&$graphData, $geoTranslations, $sizes = array(), $title = true, $legendPos = array());
}
?>