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

/**
 * Profile view for Comments app.
 *
 * @since	1.0
 * @access	public
 */
class CommentsViewProfile extends SocialAppsView
{
	/**
	 * Displays the application output in the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display( $userId = null , $docType = null )
	{
		// Load up Komento's helper library
		$file 	= JPATH_ROOT . '/components/com_komento/helpers/helper.php';

		if( !JFile::exists( $file ) )
		{
			return;
		}

		require_once( $file );

		$params	= $this->getUserParams( $userId );

		$user	=  Foundry::user($userId);
		$my		=  Foundry::user();

		// Get the Komento comments model
		$model	= Komento::getModel( 'comments' );

		// Set options for comments retrival
		$options = array(
							'userid'		=> $userId,
							'threaded'		=> 0,
							'sort'			=> 'latest',
							'limit'			=> $params->get( 'total-profile' , 5 )
						);

		// Get list of comments created by the user on the site.
		$result	= $model->getComments( 'all', 'all', $options );

		$comments = array();

		foreach( $result as $row )
		{
			$row 	= Komento::getHelper( 'comment' )->process( $row );

			if( $row === false )
			{
				continue;
			}

			$comments[]	= $row;
		}

		if( $my->id == $userId )
		{
			$name = JText::_( 'APP_COMMENTS_YOU' );
		}
		else
		{
			$name = $user->getName();
		}

		$this->set( 'comments' , $comments );
		$this->set( 'name' , $name );
		$this->set( 'user'		, $user );

		echo parent::display( 'profile/default' );
	}
}
