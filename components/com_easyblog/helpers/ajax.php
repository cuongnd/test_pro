<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');

class EasyBlogAjaxHelper
{
	public function addCommand($type, &$data)
	{
		$this->commands[] = array(
			'type' => $type,
			'data' =>& $data
		);

		return $this;
	}

	/* This will handle all ajax commands e.g. success/fail/script */
	public function __call($method, $args)
	{
		$this->addCommand($method, $args);

		return $this;
	}

	public function Foundry($selector=null)
	{
		$chain = array();

		$this->addCommand('script', $chain);

		// Because we need to maintain the variable to be passed by reference,
		// we need to use an array instead as arguments.
		require_once( EBLOG_HELPERS . DIRECTORY_SEPARATOR . 'javascript.php' );
		$js 	= new EasyBlogJavascriptHelper( $chain );

		if (isset($selector))
		{
			$js->Foundry($selector);
		}
		else
		{
			$js->Foundry;
		}

		return $js;
	}

	public function send()
	{
		$jCfg	= EasyBlogHelper::getJConfig();

		if( $jCfg->get( 'debug') )
		{

			if( ob_get_length() !== false )
			{
				while (@ ob_end_clean());
				if( function_exists( 'ob_clean' ) )
				{
					@ob_clean();
				}
			}
		}

		header('Content-type: text/x-json; UTF-8');

		require_once( EBLOG_CLASSES . DIRECTORY_SEPARATOR . 'json.php' );
		$json 	= new Services_JSON();

		$callback = JRequest::getVar('callback');

		if (isset($callback))
		{
			echo $callback . '(' . $json->encode( $this->commands ) . ');';
		} else {
			echo $json->encode( $this->commands );
		}

		exit;
	}

	/**
	 * Processes all AJAX calls here
	 */
	public function process()
	{
		$namespace		= JRequest::getCmd( 'namespace' , '' );
		$isAjaxCall 	= JRequest::getCmd( 'format' ) == 'ajax' && !empty( $namespace );

		if( !$isAjaxCall )
		{
			return false;
		}

		//@task: Process namespace
		$namespace		= explode( '.' , $namespace );

		if( !JRequest::checkToken() && !JRequest::checkToken( 'get' ) )
		{
			echo 'Invalid token';
			exit;
		}

		// @rule: All calls should be made a minimum out of 3 parts of dots (.)
		if( count( $namespace ) < 4 )
		{
			$this->fail( JText::_( 'Invalid calls') );
			return $this->send();
		}

		/**
		 * Namespaces are broken into the following
		 *
		 * site.views.viewname.methodname - Front end ajax calls
		 * admin.views.viewname.methodname - Back end ajax calls
		 */
		list( $location , $type , $view , $method ) = $namespace;

		if( $type != 'views' )
		{
			$this->fail( JText::_( 'Currently only serving views' ) );
			return $this->send();
		}

		$location	= strtolower( $location );
		$view 		= strtolower( $view );

		$path		= $location == 'admin' ? JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' : JPATH_ROOT;
		$path 		.= DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_easyblog';

		$path 	.= DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . DIRECTORY_SEPARATOR . 'view.ajax.php';

		$class 	= 'EasyBlogView' . preg_replace( '/[^A-Z0-9_]/i' , '' , $view );

		if( !class_exists( $class ) )
		{
			jimport( 'joomla.filesystem.file' );

			if( !JFile::exists( $path ) )
			{
				$this->fail( JText::_( 'View file does not exist.') );
				return $this->send();
			}

			require_once( $path );
		}

		$object 	= new $class();
		$args 		= JRequest::getVar( 'args' , '' );

		if(!method_exists( $object , $method ) )
		{
			$this->fail( JText::sprintf( 'The method %1s does not exists.' , $method ) );
			return $this->send();
		}

		if( !empty( $args ) )
		{
			require_once( EBLOG_CLASSES . DIRECTORY_SEPARATOR . 'json.php' );
			$json 	= new Services_JSON();
			$args 	= $json->decode( $args );

			if( !is_array( $args ) )
			{
				$args 	= array( $args );
			}

			call_user_func_array( array( $object , $method ) , $args );
		}
		else
		{
			$object->$method();
		}

		$this->send();
	}
}
