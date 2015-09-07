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
 * Dashboard view for Comments app.
 *
 * @since	1.0
 * @access	public
 */
class CommentsViewDashboard extends SocialAppsView
{
	/**
	 * Displays the application output in the dashboard.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display( $userId = null , $docType = null )
	{
		//require Komento helpers file
		require_once( JPATH_ROOT . '/components/com_komento/helpers/helper.php' );

		// Get the Komento comments model
		$model = Komento::getModel( 'comments' );

		// Set options for comments retrival
		$options = array(
			'userid'		=> $userId,
			'threaded'		=> 0,
			'sort'			=> 'latest'
			);

		// Get list of comments created by the user on the site.
		$comments = $model->getComments( 'all', 'all', $options );

		$this->set( 'comments' , $comments );
		$this->set( 'name' , JText::_( 'APP_COMMENTS_YOU' ) );

		echo parent::display( 'profile/default' );
	}
}
