<?php
//namespace administrator\components\com_jrealtimeanalytics\libraries\jpgraph\generators;
/**
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage libraries 
 * @subpackage jpgraph
 * @subpackage generators
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ();
define ( 'SHOWGRAPH_TOTALVISITEDPAGES', 1 ); 
define ( 'SHOWGRAPH_TOTALVISITORS', 3 );
define ( 'SHOWGRAPH_MEDIUMVISITTIME', 4 );
define ( 'SHOWGRAPH_MEDIUMVISITEDPAGESPERSINGLEUSER', 5 );
define ( 'SHOWGRAPH_NUMUSERSGEOGROUPED', 6 ); 
define ( 'SHOWGRAPH_NUMUSERSBROWSERGROUPED', 7 );
define ( 'SHOWGRAPH_NUMUSERSOSGROUPED', 8 );
 
require_once JPATH_COMPONENT . '/libraries/jpgraph/lib/jpgraph.php';
require_once JPATH_COMPONENT . '/libraries/jpgraph/lib/jpgraph_bar.php';
require_once JPATH_COMPONENT . '/libraries/jpgraph/lib/jpgraph_pie.php';
require_once JPATH_COMPONENT . '/libraries/jpgraph/lib/jpgraph_pie3d.php';
require_once JPATH_COMPONENT . '/libraries/jpgraph/generators/igraphgenerator.php';

/** 
 * Realizza l'interfaccia IGraphGenerator per la generazione di grafici su file dato
 * un array di informazioni in ingresso
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage libraries 
 * @subpackage jpgraph
 * @subpackage generators
 * @since	1.2
 */
class jfbcGraphGenerator implements IGraphGenerator {
	/**
	 * Oggetto generatore del grafico
	 * @access protected 
	 * @var Object
	 */
	protected $graphInstance;
	
	/**
	 * Ottenute le informazioni necessarie da avvio alla reale generazione dell'immagine del grafico in cache folder
	 * @access public 
	 * @param Object& $graphData
	 * @return Void
	 */
	public function buildBars(&$graphData) { 
		$filename = JFactory::getUser()->id . '_serverstats_bars.png';
		//Dimensioniamo in larghezza il grafico 
		$graph = $this->graphInstance = new Graph ( 450, 270 );
		
		$datax = array(	JTEXT::_('GRAPH_TOTAL_VISITED_PAGES'),
						JTEXT::_('GRAPH_TOTAL_VISITORS'),
						JTEXT::_('GRAPH_MEDIUM_VISIT_TIME'),
						JTEXT::_('GRAPH_MEDIUM_VISITED_PAGES_PERUSER')
						);

		$mediumVisitTimeSeconds = 0;
		if($graphData[SHOWGRAPH_MEDIUMVISITTIME]) {
		 	$mediumVisitTimeSeconds = (strtotime($graphData[SHOWGRAPH_MEDIUMVISITTIME]) - strtotime('TODAY')) / 60; 
		}
	 	$datay = array ($graphData[SHOWGRAPH_TOTALVISITEDPAGES],
			 			$graphData[SHOWGRAPH_TOTALVISITORS],
			 			$mediumVisitTimeSeconds,
			 			floatval($graphData[SHOWGRAPH_MEDIUMVISITEDPAGESPERSINGLEUSER])); 
	 	
		$graph->SetScale ( "textlin" );
		$graph->xaxis->SetTickLabels($datax); 
		
		$graph->SetShadow ('darkgray');
		$graph->img->SetMargin ( 40, 20, 10, 30 );
		$graph->yaxis->scale->SetGrace ( 1 );
		//Sondaggio sul primo valore se float oppure no per formato valori bar
		$formato =  '%0.2f';
		
		// Create del bar plot1
		$b1plot = new BarPlot ( $datay );  
		$b1plot->SetWidth(1.0);
		$b1plot->SetShadow('darkgray');
		$b1plot->SetFillGradient("orange","#EEDD99",GRAD_WIDE_MIDVER);
		$b1plot->value->show ( false ); 
		$b1plot->value->SetFormat ( $formato );
		  
		// Create the grouped bar plot
		$gbplot = new GroupBarPlot ( array ($b1plot) );
		
		// ...and add it to the graph
		$graph->Add ( $gbplot );
		
		$graph->title->Set ( JText::_('SERVERSTATS_PANEL') );
		//$graph->xaxis->title->Set ( "" );
		//$graph->yaxis->title->Set ( $ytitle );
		
		$graph->title->SetFont ( FF_FONT1, FS_BOLD );
		$graph->yaxis->title->SetFont ( FF_FONT1, FS_BOLD );
		$graph->xaxis->title->SetFont ( FF_FONT1, FS_BOLD );
		
		// Controllo esistenza cartella cache e eventuale creazione
		if (!is_dir(JPATH_COMPONENT . '/cache/')) {
			mkdir(JPATH_COMPONENT . '/cache/', 0755);
		}
		
		// Pre garbage collector
		if (is_file ( JPATH_COMPONENT . '/cache/' . $filename )) {
			unlink ( JPATH_COMPONENT . '/cache/' . $filename );
		}
		// Display the graph 
		$graph->Stroke ( JPATH_COMPONENT . '/cache/' . $filename ); 
	}

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
	public function buildPies(&$graphData, $geoTranslations, $sizes = array(450, 270), $title = true, $legendPos = array(0, 0.02)) {
		//****** GRAFICO GEOLOCATION
		// Set output filename
		$filename = JFactory::getUser()->id . '_serverstats_pie_geolocation.png';
		// Istanza del context dove settiamo anche le dimensioni e del grafico
		$this->graphInstance = new PieGraph ( $sizes[0], $sizes[1] );
		if($title) {
			$this->graphInstance->title->Set(JText::_('GRAPH_SERVERSTATS_GEOLOCATION'));
		}
			
		// Reset resources
		$YData = array();
		if(!is_array($graphData[SHOWGRAPH_NUMUSERSGEOGROUPED]) || !count($graphData[SHOWGRAPH_NUMUSERSGEOGROUPED])) {
			$YData = array(1);
		}
		$legends = array();
		foreach ( $graphData[SHOWGRAPH_NUMUSERSGEOGROUPED] as $geoData ) { 
			$YData [] = $geoData[0];
			$label = isset ($geoTranslations[$geoData[1]]) ? strtoupper ( $geoTranslations[$geoData[1]]['name'] ) : 'Not set'; 
			$legends[] = $label . ' (%d)';
		}
	
		$this->graphInstance->legend->SetShadow('gray@0.4',3);
		$this->graphInstance->legend->SetPos($legendPos[0],$legendPos[1],'left','top');
	
		//Dimensioniamo in larghezza il grafico
		$pie3D = new PiePlot3d ( $YData );
		//$pie3D->SetTheme ( "earth" );
		$pie3D->SetCenter ( 0.62, 0.65 );
		$pie3D->SetAngle ( 30 );
		$pie3D->value->Show ( true );
		$pie3D->SetLegends($legends);
	
		// Add the plot to the graph
		$this->graphInstance->Add ( $pie3D );
		$this->graphInstance->SetShadow ( 'darkgray', 5 );
			
		// Controllo esistenza cartella cache e eventuale creazione
		if (!is_dir(JPATH_COMPONENT . '/cache/')) {
			mkdir(JPATH_COMPONENT . '/cache/', 0755);
		}
		
		// Pre garbage collector graph image
		if (is_file ( JPATH_COMPONENT . '/cache/' . $filename )) {
			unlink ( JPATH_COMPONENT . '/cache/' . $filename );
		}
		// Display the graph 
		$this->graphInstance->Stroke ( JPATH_COMPONENT . '/cache/' . $filename ); 
		
		//****** GRAFICO OS
		// Reset resources
		$YData = array();
		if(!is_array($graphData[SHOWGRAPH_NUMUSERSGEOGROUPED]) || !count($graphData[SHOWGRAPH_NUMUSERSGEOGROUPED])) {
			$YData = array(1);
		}
		$legends = array();
		// Set output filename
		$filename = JFactory::getUser()->id . '_serverstats_pie_os.png';
		// Istanza del context dove settiamo anche le dimensioni e del grafico
		$this->graphInstance = new PieGraph ( $sizes[0], $sizes[1] );
		if($title) {
			$this->graphInstance->title->Set(JText::_('GRAPH_SERVERSTATS_OS'));
		}
			
		$legends = array();
		foreach ( $graphData[SHOWGRAPH_NUMUSERSOSGROUPED] as $osData ) {
			$YData [] = $osData[0]; 
			$legends[] = $osData[1] . ' (%d)';
		}
		
		$this->graphInstance->legend->SetShadow('gray@0.4',3);
		$this->graphInstance->legend->SetPos($legendPos[0],$legendPos[1],'left','top');
		
		//Dimensioniamo in larghezza il grafico
		$pie3D = new PiePlot3d ( $YData );
		//$pie3D->SetTheme ( "earth" );
		$pie3D->SetCenter ( 0.62, 0.65 );
		$pie3D->SetAngle ( 30 );
		$pie3D->value->Show ( true );
		$pie3D->SetLegends($legends);
		
		// Add the plot to the graph
		$this->graphInstance->Add ( $pie3D );
		$this->graphInstance->SetShadow ( 'darkgray', 5 );
	  
		// Pre garbage collector graph image
		if (is_file ( JPATH_COMPONENT . '/cache/' . $filename )) {
			unlink ( JPATH_COMPONENT . '/cache/' . $filename );
		}
		// Display the graph
		$this->graphInstance->Stroke ( JPATH_COMPONENT . '/cache/' . $filename );
		
		//****** GRAFICO BROWSER
		// Reset resources
		$YData = array();
		if(!is_array($graphData[SHOWGRAPH_NUMUSERSGEOGROUPED]) || !count($graphData[SHOWGRAPH_NUMUSERSGEOGROUPED])) {
			$YData = array(1);
		}
		$legends = array();
		// Set output filename
		$filename = JFactory::getUser()->id . '_serverstats_pie_browser.png';
		// Istanza del context dove settiamo anche le dimensioni e del grafico
		$this->graphInstance = new PieGraph ( $sizes[0], $sizes[1] );
		if($title) {
			$this->graphInstance->title->Set(JText::_('GRAPH_SERVERSTATS_BROWSER'));
		}
			
		$legends = array();
		foreach ( $graphData[SHOWGRAPH_NUMUSERSBROWSERGROUPED] as $browserData ) {
			$YData [] = $browserData[0];
			$legends[] = $browserData[1] . ' (%d)';
		}
		
		$this->graphInstance->legend->SetShadow('gray@0.4',3);
		$this->graphInstance->legend->SetPos($legendPos[0],$legendPos[1],'left','top');
		
		//Dimensioniamo in larghezza il grafico
		$pie3D = new PiePlot3d ( $YData );
		//$pie3D->SetTheme ( "earth" );
		$pie3D->SetCenter ( 0.62, 0.65 );
		$pie3D->SetAngle ( 30 );
		$pie3D->value->Show ( true );
		$pie3D->SetLegends($legends);
		
		// Add the plot to the graph
		$this->graphInstance->Add ( $pie3D );
		$this->graphInstance->SetShadow ( 'darkgray', 5 );
		 
		// Pre garbage collector graph image
		if (is_file ( JPATH_COMPONENT . '/cache/' . $filename )) {
			unlink ( JPATH_COMPONENT . '/cache/' . $filename );
		}
		// Display the graph
		$this->graphInstance->Stroke ( JPATH_COMPONENT . '/cache/' . $filename );
	}
}
?>