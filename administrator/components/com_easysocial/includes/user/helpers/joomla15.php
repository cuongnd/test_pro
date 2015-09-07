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

jimport( 'joomla.user.authorization' );

/**
 * Helper class for the user object for Joomla 1.5.
 *
 * @since	1.0
 * @access	public
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialUserHelperJoomla15
{
	/**
	 * This is the current user's object.
	 * @var SocialUser
	 */
	private $access	= null;

	/**
	 * This is the current user's object.
	 * @var SocialUser
	 */
	private $user	= null;

	public function __construct( &$userObj )
	{
		$this->user	= $userObj;
	}

	public function getGroupChildrenTree($gid)
	{
		return JHTML::_('select.genericlist', JFactory::getAcl()->get_group_children_tree( null, 'USERS', false ), 'gid', 'size="10"', 'value', 'text', $gid);
	}

	/**
	 * Gets a list of user group that the user belongs to.
	 *
	 * @since	1.0
	 * @access	public
	 * @return	Array
	 */
	public function getUserGroups()
	{
		// Load our own db.
		$db		= Foundry::db();

		$query  = array();
		$query[]	= 'SELECT `id`, `name` as `title` FROM ' . $db->nameQuote('#__core_acl_aro_groups') . ' AS a';
		$query[]	= 'WHERE (a.`id` > 17 AND a.`id` < 26)';
		$query[]	= 'and a.`id` = ' . $db->Quote( $this->user->gid );

		$query 		= implode( ' ' , $query );
		$db->setQuery( $query );

		$result 	= $db->loadObjectList();

		if( !$result )
		{
			return $result;
		}

		$groups 	= array();

		foreach( $result as $row )
		{
			$groups[ $row->id ]	= $row->title;
		}

		return $groups;
	}

	/**
	 * Binds the data given to the user object.
	 *
	 */
	public function bind( &$user , $data )
	{
		// Map the user groups based on the given data.
		if( !empty( $data[ 'gid' ] ) )
		{
			$user->groups	= array();

			foreach( $data[ 'gid' ] as $id )
			{
				$user->groups[ $id ]	= $id;
			}
		}
	}

	/**
	 * Performs some authority check for Joomla 1.5 userbase.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	
	 * @param
	 * @return	bool	True if success, false otherwise.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function authorityCheck( $my , $user )
	{
		$group		= Foundry::get( 'ACL' )->getGroupsByUserId($user->get('id'));
		$group		= strtolower($group);

		if( $my->get('id') == $user->get( 'id' ) && $user->get('block') == 1 )
		{
			$user->setError( JText( 'You cannot block your own account' ) );
			return false;
		}
		else if ( ( $group == 'super administrator' ) && $user->get('block') == 1 )
		{
			$user->setError( JText::_( 'You cannot block a Super Administrator account' ) );
			return false;
		}
		else if ( ( $group == 'administrator' ) && ( $my->get( 'gid' ) == 24 ) && $user->get('block') == 1 )
		{
			$user->setError( JText::_( 'WARNBLOCK' ) );
			return false;
		}
		else if ( ( $group == 'super administrator' ) && ( $my->get( 'gid' ) != 25 ) )
		{
			$user->setError( JText::_( 'You cannot edit a Super Administrator account' ) );
			return false;
		}

		if( $user->get('gid') == 25 && $my->get('gid') != 25 )
		{
			// disallow creation of Super Admin by non Super Admin users
			$this->setError(JText::_( 'WARNSUPERADMINCREATE' ));
			return false;
		}

		// If user is made an Admin group and user is NOT a Super Admin
		if( $user->get('gid') == 24 && !( $my->get('gid') == 25 || ( $my->get('id') == $my->get( 'id' ) && $my->get('gid') == 24)) )
		{
			// disallow creation of Admin by non Super Admin users
			$this->setError(JText::_( 'WARNSUPERADMINCREATE' ));
			return false;
		}

		return true;
	}

	public function loadSession( $user, $self )
	{
		return true;
	}

	public function authorise($action, $assetname = null)
	{
		// Joomla 1.6 only
		return true;
	}
	
	public function getAccess($option)
	{

		if (!$this->access)
		{
			$db = Foundry::db();

			$options = array(
				'db'				=> &$db,
				'db_table_prefix'	=> $db->getPrefix() . 'core_acl_',
				'debug'				=> 0
			);
			
			$this->access = new JAuthorization( $options );
		}

		return $this->access;
	}

}