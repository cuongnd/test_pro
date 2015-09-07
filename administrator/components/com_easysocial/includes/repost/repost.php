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

class SocialRepost
{
	var $uid 		= null;
	var $element 	= null;
	var $group 		= null;

	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct( $uid, $element, $group = SOCIAL_APPS_GROUP_USER )
	{
		$this->uid 		= $uid;
		$this->element 	= $element;
		$this->group 	= $group;
	}

	public static function factory( $uid, $element, $group = SOCIAL_APPS_GROUP_USER )
	{
		return new self( $uid, $element, $group );
	}

	public function debug()
	{
		var_dump( $this->uid );
		var_dump( $this->element );
		var_dump( $this->group );
		exit;
	}


	public function add( $userId = null, $content = null )
	{
		if( empty( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		$model = Foundry::model( 'Repost' );
		$state = $model->add( $this->uid, $this->formKeys( $this->element, $this->group ), $userId, $content );

		return $state;
	}

	public function delete( $userId = null )
	{
		if( empty( $userId ) )
		{
			$userId = Foundry::user()->id;
		}

		$model = Foundry::model( 'Repost' );
		$state = $model->delete( $this->uid, $this->formKeys( $this->element, $this->group ), $userId );

		return $state;
	}

	public function isShared( $userId )
	{

	}

	private function formKeys( $element, $group )
	{
		return $element . '.' . $group;
	}

	public function getCount()
	{
		$model 	= Foundry::model( 'Repost' );
		$cnt 	= $model->getCount( $this->uid, $this->formKeys( $this->element, $this->group ) );

		return $cnt;
	}


	/*
	 * alias for funcion getButton.
	 */

	public function button( $label = null )
	{
		return $this->getButton( $label );
	}

	public function getButton( $label = null )
	{
		$my 		= Foundry::user();

		if( !$label )
		{
			$label = JText::_( 'COM_EASYSOCIAL_REPOST' );
		}

		$themes 	= Foundry::get( 'Themes' );

		$themes->set( 'text', $label );
		$themes->set( 'my'	, $my );
		$themes->set( 'uid'	, $this->uid );
		$themes->set( 'element', $this->element );
		$themes->set( 'group', $this->group );


 		$html = $themes->output( 'site/repost/action' );
 		return $html;
	}

	/*
	 * alias for funcion getHTML.
	 */

	public function toHTML()
	{
		return $this->getHTML();
	}

	/**
	 * Displays the sharing code on the page.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getHTML()
	{
		// Get the count.
		$count 	= 0;
		$text 	= '';

		$count 	= $this->getCount();

		// $text 	= JText::sprintf( 'COM_EASYSOCIAL_REPOST_COUNT_SHARED', $count );
		$cntPluralize 	= Foundry::get( 'Language' )->pluralize( $count, true )->getString();
		$text 			= JText::sprintf( 'COM_EASYSOCIAL_REPOST' . $cntPluralize, $count );

		$themes 	= Foundry::get( 'Themes' );
		$themes->set( 'text'		, $text );
		$themes->set( 'uid', $this->uid );
		$themes->set( 'element', $this->element );
		$themes->set( 'group', $this->group );
		$themes->set( 'count', $count );

 		$html = $themes->output( 'site/repost/item' );
		return $html;
	}


}
