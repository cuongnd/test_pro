<?php
//namespace administrator\components\com_promailer\classes\renderer; 
/**
 * @author Joomla! Extensions Store
 * @package JREALTIMEANALYTICS::SERVERSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage libraries
 * @subpackage renderers
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license Commercial 
 */
defined ( '_JEXEC' ) or die ();
define ( 'PDF_CREATOR', 'TCPDF');
define ( 'K_TCPDF_EXTERNAL_CONFIG', true );
define ( "K_PATH_MAIN", JPATH_COMPONENT . DIRECTORY_SEPARATOR . "libraries" . DIRECTORY_SEPARATOR . "renderers" . DIRECTORY_SEPARATOR . "tcpdf" );
define ( "K_PATH_URL", JPATH_BASE );
define ( "K_PATH_FONTS", K_PATH_MAIN . DIRECTORY_SEPARATOR . 'pdf_fonts' . DIRECTORY_SEPARATOR );
define ( "K_PATH_CACHE", K_PATH_MAIN . DIRECTORY_SEPARATOR . "cache" );
define ( "K_PATH_URL_CACHE", K_PATH_URL . DIRECTORY_SEPARATOR . "cache" );
define ( "K_PATH_IMAGES", K_PATH_MAIN . DIRECTORY_SEPARATOR . "images" );
define ( "K_BLANK_IMAGE", K_PATH_IMAGES . DIRECTORY_SEPARATOR . "_blank.png" );
define ( "K_CELL_HEIGHT_RATIO", 1.25 );
define ( "K_TITLE_MAGNIFICATION", 1.3 );
define ( "K_SMALL_RATIO", 2 / 3 );
define ( "HEAD_MAGNIFICATION", 1.1 );
require_once ('tcpdf/tcpdf.php');
require_once ('irenderer.php');
 
/**
 * Renderer PDF content
 * 
 * @package PROMAILER::RENDERER::administrator::components::com_promailer 
 * @subpackage classes
 * @subpackage renderer
 * @since 1.2
 */  
class PDFRenderer implements IRenderer {
	private $tcpdf;
	private $html_pdf;
	
	/**
	 * Conversione PDF del report ML 
	 */
	public function renderContent($html, $model, $mode = 'D') { 
		$this->html_pdf = $html;
		$dataExport = date ('d-m-Y H:i:s', time());
		// create new PDF document
		$this->tcpdf = new TCPDF ( 'P', 'mm', 'A4', true, 'UTF-8', false );
		
		$config = JFactory::getConfig();
		$sitename = $config->get('sitename') . ' - ' . JUri::root();
		$from = $model->getState('fromPeriod');
		$to = $model->getState('toPeriod');
		
		// set document information
		$this->tcpdf->SetCreator ( PDF_CREATOR );
		$this->tcpdf->SetAuthor ( 'Analytics stats' );
		$this->tcpdf->SetTitle ( 'JRealtime Analytics' );
		$this->tcpdf->SetSubject ( 'Stats' );
		$this->tcpdf->SetHeaderData ( null, 50, $sitename, $from . ' - ' . $to );
		$this->tcpdf->setDisplayMode('real');
		
		// set header and footer fonts
		$this->tcpdf->setHeaderFont ( array ('freesans', '', 10 ) );
		$this->tcpdf->setFont ( 'freesans', '', 8 );
		$this->tcpdf->setFooterFont ( array ('freesans', '', 8 ) );
		
		//set margins
		$this->tcpdf->SetMargins ( 10, 20, 10 );
		$this->tcpdf->SetHeaderMargin ( 10 );
		$this->tcpdf->SetFooterMargin ( 10 );
		
		//set auto page breaks
		$this->tcpdf->SetAutoPageBreak ( TRUE, 10 );
		
		//set image scale factor
		$this->tcpdf->setImageScale ( 1.5 );
		
		//set some language-dependent strings
		//$this->tcpdf->setLanguageArray ( $l );
		 
		//For security safe convertiamo in UTF-8 per TCPDF
		//$this->html_pdf = iconv ( 'ISO-8859-1', 'UTF-8', $this->html_pdf );
		// Print text using writeHTMLCell()
		$this->html_pdf = preg_replace('/[\t]*[\s]+/', ' ', $this->html_pdf ); 
		$chunks = explode('#newpagestart#', $this->html_pdf);
		foreach ($chunks as $chunk) {
			// Add a page
			$this->tcpdf->AliasNbPages();
			$this->tcpdf->AddPage ();
			$this->tcpdf->writeHTML ( $chunk );
		}
		  
		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		if ($mode == 'S') {
			return $this->tcpdf->Output ( null, $mode );
		} else {
			$this->tcpdf->Output ( 'analytics_stats.pdf', $mode );
		}
		exit ();
	}
}

?>