<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

class SocialPage
{
	/**
	 * Store a list of scripts on the page.
	 * @var	Array
	 */
	public $scripts = array();

	/**
	 * Store a list of inline scripts on the page.
	 * @var	Array
	 */
	public $inlineScripts = array();

	/**
	 * Store a list of stylesheets on the page
	 * @var	Array
	 */
	public $stylesheets = array();

	/**
	 * Store a list of inline style sheets on the page
	 * @var	Array
	 */
	public $inlineStylesheets = array();

	/**
	 * The title of the page.
	 * @var	Array
	 */
	public $title = null;

	/**
	 * Page will always be an instance.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public static function getInstance()
	{
		static $obj = null;

		if( !$obj )
		{
			$obj 	= new self();
		}

		return $obj;
	}

	public function toUri( $path )
	{
		// TODO: Move this to the actual toUri
		$url = '';
		$uri = JURI::getInstance();

		// Url
		if( stristr( $path , $uri->getScheme() ) !== false )
		{
			$url = $path;
		}

		// File
		if( is_file( $source ) )
		{
			$url = Foundry::get('assets')->toUri( $path );
		}

		return $url;
	}

	/**
	 * We need to wrap all javascripts into a single <script type="text/javascript"> block. This helps us maintain a single object.
	 *
	 * @access	public
	 * @param 	string 	$source		The script source.
	 */
	public function addScript( $path )
	{
		$url = $this->toUri( $path );

		if( !empty($url) )
		{
			$this->scripts[] = $url;
		}
	}

	public function addInlineScript( $script )
	{
		if( !empty($script) )
		{
			$this->inlineScripts[] = $script;
		}
	}

	/**
	 * Internal method to build scripts to be embedded on the head or
	 * external script files to be added on the head.
	 *
	 * @access	private
	 */
	public function processScripts()
	{
		$doc = JFactory::getDocument();

		// Scripts
		if( !empty( $this->scripts ) )
		{
			foreach( $this->scripts as $script )
			{
				$doc->addScript( $script);
			}
		}

		if ( empty($this->inlineScripts) ) return;

		// Inline scripts
		$script				= Foundry::get('Script');
		$script->file		= SOCIAL_MEDIA . '/head.js';
		$script->scriptTag	= true;
		$script->CDATA 		= true;
		$script->set('contents', implode($this->inlineScripts));
		$inlineScript		= $script->parse();

		if( $doc->getType() == 'html' )
		{
			$doc->addCustomTag( $inlineScript );
		}
	}

	public function addStylesheet( $path )
	{
		$url = $this->toUri( $path );

		if ( !empty($url) )
		{
			$this->stylesheets[] = $url;
		}
	}

	public function addInlineStylesheet( $stylesheet )
	{
		if( !empty($stylesheet) )
		{
			$this->inlineStylesheets[] = $stylesheet;
		}
	}

	public function processStylesheets()
	{
		$doc = JFactory::getDocument();

		// Stylesheets
		if( !empty( $this->stylesheets ) )
		{
			foreach( $this->stylesheets as $stylesheet )
			{
				$doc->addStyleSheet( $stylesheet );
			}
		}

		if ( empty($this->inlineStylesheets) )
		{
			return;
		}

		// Inline scripts
		$stylesheet				= Foundry::get('Stylesheet');
		$stylesheet->file		= SOCIAL_MEDIA . '/head.css';
		$stylesheet->styleTag	= true;
		$stylesheet->CDATA		= true;
		$stylesheet->set('contents', implode($this->inlineStylesheets));

		$inlineStylesheet = $stylesheet->parse();

		if( $doc->getType() == 'html' )
		{
			$doc->addCustomTag( $inlineStylesheet );	
		}
		
	}

	/**
	 * Gets the current title and sets the title on the page.
	 *
	 * @access	private
	 * @param	null
	 */
	private function processTitle()
	{
		$app 	= JFactory::getApplication();

		// We do not want to set the title for admin area.
		if( $app->isAdmin() )
		{
			return;			
		}

		if( $this->title )
		{
			$doc 	= JFactory::getDocument();
			$doc->setTitle( $this->title );
		}
	}

	/**
	 * This is the starting point of the page library.
	 *
	 * @access	public
	 * @param	null
	 * @return 	null
	 */
	public function start()
	{
		// Trigger profiler's start
		Foundry::profiler()->start();

		// Additional triggers to be processed when the page starts.
		$dispatcher 	= Foundry::getInstance( 'Dispatcher' );

		// Trigger: onComponentStart
		$dispatcher->trigger( 'apps' , 'onComponentStart' , array() );

		// Run initialization codes for javascript side of things.
		$this->init();
	}

	/**
	 * This is the ending point of the page library.
	 *
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function end( $options = array() )
	{
		// Initialize required dependencies.
		Foundry::document()->init( $options );

		$processStylesheets	= isset( $options[ 'processStylesheets' ] ) ? $options[ 'processStylesheets' ] : true;

		// @task: Process any scripts that needs to be injected into the head.
		if( $processStylesheets )
		{
			$this->processStylesheets();
		}


		// @task: Process any scripts that needs to be injected into the head.
		$this->processScripts();

		// @task: Process the document title.
		$this->processTitle();

		// @task: Process opengraph tags
		Foundry::opengraph()->render();
		
		// @task: Trigger profiler's end.
		Foundry::profiler()->end();

		// Additional triggers to be processed when the page starts.
		$dispatcher 	= Foundry::dispatcher();

		// Trigger: onComponentStart
		$dispatcher->trigger( 'apps' , 'onComponentEnd' , array() );
	}

	/**
	 * Initializes the javascript framework part.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function init()
	{
		if ( JRequest::getBool( 'compile' ) != true )
		{
			return false;
		}

		$minify = JRequest::getBool('minify', false);

		$compiler = Foundry::getInstance('Compiler');
		$results = $compiler->compile($minify);

		header('Content-type: text/x-json; UTF-8');
		echo json_encode($results);

		exit;
	}

	/**
	 * Adds into the breadcrumb list
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function breadcrumb( $title , $link = '' )
	{
		$app 		= JFactory::getApplication();
		$pathway 	= $app->getPathway();
		$pathways 	= $pathway->getPathwayNames();

		if( !empty( $pathways ) )
		{
			$pathways 	= array_map( array( 'JString' , 'strtolower' ) , $pathways  );
		}
		
		// Set the temporary title
		$tmp 		= JString::strtolower( $title );
		
		if( !in_array( $tmp , $pathways ) )
		{
			$state 		= $pathway->addItem( $title , $link );

			return $state;
		}

		return false;
	}

	/**
	 * Sets the title of the page.
	 *
	 * @access	public
	 * @param	string	$title 	The title of the current page.
	 */
	public function title( $default , $override = true , $view = null )
	{
		// Get the view.
		$view 	= is_null( $view ) ? JRequest::getVar( 'view' ) : $view;

		// Get the passed in title.
		$title	= $default;

		// @TODO: Create SEO section that allows admin to customize the header of the page. Test if there's any custom title set in SEO section

		// Get current menu
		$menu 		= JFactory::getApplication()->getMenu()->getActive();

		if( $menu )
		{
			$params 	= $menu->params;
			$menuView 	= $menu->query[ 'view' ];

			// Check if the current page_title is set in the menu parameters.
			if( $params->get( 'page_title' ) && $override && $view == $menuView )
			{
				$title 	= $params->get( 'page_title' );
			}
		}

		// Prepare Joomla's site title if necessary.
		$jConfig 	= Foundry::config( 'joomla' );
		$addTitle 	= $jConfig->getValue( 'sitename_pagetitles' );

		// Only add Joomla's site title if it was configured to.
		if( $addTitle )
		{
			$siteTitle 	= $jConfig->getValue( 'sitename' );

			if( $addTitle == 1 )
			{
				$title 	= $siteTitle . ' - ' . $title;
			}

			if( $addTitle == 2 )
			{
				$title	= $title . ' - ' . $siteTitle;
			}

		}

		// Set the title
		$this->title 	= $title;


		// Need to think about keywords , author , metadesc and robots

		// nofollow ?



	}
}
