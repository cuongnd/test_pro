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

jimport('joomla.application.component.model');

Foundry::import( 'admin:/includes/model' );

class EasySocialModelPrivacy extends EasySocialModel
{
	private $data			= null;

	static $_privacyitems 	= array();

	public function __construct( $config = array() )
	{
		parent::__construct( 'privacy' , $config );
	}

	public function buildPrivacyCondExists( $key, $rule, $refColumn )
	{
		$my = Foundry::user();
		$db = Foundry::db();

		$query = '';
		$container = array();

		if( $my->id == 0 )
		{
			// public only
		    $query = '(select pc.`uid` from `#__social_privacy` as pc';
		    $query .= ' where ' . $db->nameQuote( $refColumn ) . ' = pc.`uid`';
		    $query .= ' and pc.`type` = ' . $db->Quote( $key ) . ' and pc.`rule` = ' . $db->Quote( $rule );
		    $query .= ' and pc.`privacy` = ' . $db->Quote( SOCIAL_PRIVACY_PUBLIC ) . ')';
		    $container[] = $query;

		}
		else
		{
			//$frenQuery = 'select `actor_id` from `#__social_friends` where `target_id` = ' . $db->Quote( $my->id ) . ' union select `target_id` from `#__social_friends` where `actor_id` = ' . $db->Quote( $my->id );

			// public and member
		    $query = '(select pc.`uid` from `#__social_privacy` as pc';
		    $query .= ' where ' . $db->nameQuote( $refColumn ) . ' = pc.`uid`';
		    $query .= ' and pc.`type` = ' . $db->Quote( $key ) . ' and pc.`rule` = ' . $db->Quote( $rule );
		    $query .= ' and pc.`privacy` <= ' . $db->Quote( SOCIAL_PRIVACY_MEMBER ) . ')';
			$container[] = $query;


			// friends of friend
		    $query = '(select pc2.`uid` from `#__social_privacy` as pc2';
		    $query .= ' inner join (';
			$query .= 'select target_id as id from #__social_friends as a1';
			$query .= ' where ( exists (select aa1.actor_id from #__social_friends as aa1 where a1.actor_id = aa1.actor_id and aa1.target_id = '. $db->Quote( $my->id ) .')';
			$query .= ' or exists (select aa1.target_id from #__social_friends as aa1 where a1.actor_id = aa1.target_id and aa1.actor_id = '. $db->Quote( $my->id ) .'))';
			$query .= ' union ';
			$query .= ' select actor_id as id from #__social_friends as a2';
			$query .= ' where ( exists (select aa2.actor_id from #__social_friends as aa2 where a2.target_id = aa2.actor_id and aa2.target_id = '. $db->Quote( $my->id ) .')';
			$query .= ' or exists (select aa2.target_id from #__social_friends as aa2 where a2.target_id = aa2.target_id and aa2.actor_id = '. $db->Quote( $my->id ) .'))';
			$query .= ' union ';
			$query .= ' select id from #__users as a3';
			$query .= ' where ( exists (select aa3.actor_id from #__social_friends as aa3 where a3.id = aa3.actor_id and aa3.target_id = ' . $db->Quote( $my->id ) . ')';
			$query .= ' or exists (select aa3.target_id from #__social_friends as aa3 where a3.id = aa3.target_id and aa3.actor_id = '. $db->Quote( $my->id ) . '))';
			$query .= ') as ex on pc2.uid = ex.id';
			$query .= ' where ' . $db->nameQuote( $refColumn ) . ' = pc2.`uid`';
		    $query .= ' and pc2.`type` = ' . $db->Quote( $key ) . ' and pc2.`rule` = ' . $db->Quote( $rule );
		    $query .= ' and pc2.`privacy` = ' . $db->Quote( SOCIAL_PRIVACY_FRIENDS_OF_FRIEND ) . ')';
			$container[] = $query;


			// friends
		    $query = '(select pc3.`uid` from `#__social_privacy` as pc3';
		    $query .= ' where ' . $db->nameQuote( $refColumn ) . ' = pc3.`uid`';
		    $query .= ' and pc3.`type` = ' . $db->Quote( $key ) . ' and pc3.`rule` = ' . $db->Quote( $rule );
		    $query .= ' and pc3.`privacy` = ' . $db->Quote( SOCIAL_PRIVACY_FRIEND );
			$query .= ' and ( exists ( select f1.`actor_id` from `#__social_friends` as f1 where pc3.`uid` = f1.`actor_id` and f1.`target_id` = ' . $db->Quote( $my->id ) . ')';
			$query .= '   or exists ( select f2.`target_id` from `#__social_friends` as f2 where pc3.`uid` = f2.`target_id` and f2.`actor_id` = ' . $db->Quote( $my->id ). ') )';
			$query .= ')';
			$container[] = $query;


			// only me
		    $query = '(select pc4.`uid` from `#__social_privacy` as pc4';
		    $query .= ' where ' . $db->nameQuote( $refColumn ) . ' = pc4.`uid`';
		    $query .= ' and pc4.`type` = ' . $db->Quote( $key ) . ' and pc4.`rule` = ' . $db->Quote( $rule );
		    $query .= ' and pc4.`privacy` = ' . $db->Quote( SOCIAL_PRIVACY_ONLY_ME );
		    $query .= ' and pc4.`uid` = ' . $db->Quote( $my->id ) . ')';
		    $container[] = $query;


			// custom
			$query = '(select pc5.`uid` from `#__social_privacy` as pc5';
			$query .= ' inner join #__social_privacy_customize as ctm on pc5.`uid` = ctm.`uid` and pc5.`type` = ctm.`type` and pc5.`rule` = ctm.`rule`';
		    $query .= ' where ' . $db->nameQuote( $refColumn ) . ' = pc5.`uid`';
		    $query .= ' and pc5.`type` = ' . $db->Quote( $key ) . ' and pc5.`rule` = ' . $db->Quote( $rule );
		    $query .= ' and pc5.`privacy` = ' . $db->Quote( SOCIAL_PRIVACY_CUSTOM );
			$query .= ' and ctm.`user_id` = ' . $db->Quote( $my->id ) . ')';
		    $container[] = $query;

		}

		$query = implode( ' or exists ', $container);

		return $query;
	}

	public function getPrivacyId( $type, $rule, $useDefault = false )
	{
		$db = Foundry::db();

		$query = 'select ' . $db->nameQuote( 'id' ) . ' from ' . $db->nameQuote( '#__social_privacy' );
		$query .= ' where ' . $db->nameQuote( 'type' ) . ' = ' . $db->Quote( $type );
		$query .= ' and ' . $db->nameQuote( 'rule' ) . ' = ' . $db->Quote( $rule );

		$db->setQuery( $query );
		$result = $db->loadResult();

		if( empty( $result ) && $useDefault )
		{
			$query = 'select ' . $db->nameQuote( 'id' ) . ' from ' . $db->nameQuote( '#__social_privacy' );
			$query .= ' where ' . $db->nameQuote( 'type' ) . ' = ' . $db->Quote( 'core' );
			$query .= ' and ' . $db->nameQuote( 'rule' ) . ' = ' . $db->Quote( 'view' );

			$db->setQuery( $query );
			$result = $db->loadResult();
		}

		return $result;
	}


	/**
	 * Updates the privacy of an object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function updatePrivacy( $uid , $data, $type = SOCIAL_PRIVACY_TYPE_USER )
	{
		$db  = Foundry::db();
		$sql = Foundry::sql();

		if( count( $data ) <= 0 )
			return false;

		foreach( $data as $item )
		{
			$tbl = Foundry::table( 'PrivacyMap' );

			$valueInInt = '';

			if( $item->mapid )
			{
				$tbl->load( $item->mapid );
			}

			$tbl->privacy_id = $item->id;
			$tbl->uid 		 = $uid;
			$tbl->utype 	 = $type;
			//$tbl->value 	 = Foundry::call( 'Privacy' , 'toValue' , $item->value );
			$tbl->value 	 = Foundry::privacy()->toValue( $item->value );
			$valueInInt		 = $tbl->value;
			$state = $tbl->store();

			if( ! $state )
			{
				return $tbl->getError();
			}


			// reset sql object.
			$sql->clear();

			//clear the existing customized privacy data.
			$sql->delete( '#__social_privacy_customize' );
			$sql->where( 'uid', $tbl->id );
			$sql->where( 'utype', SOCIAL_PRIVACY_TYPE_USER );

			$db->setQuery( $sql );
			$db->query();

			// save custom users here.
			if( $tbl->value == SOCIAL_PRIVACY_CUSTOM && count( $item->custom ) > 0 )
			{
				foreach( $item->custom as $customUserId )
				{
					if( empty( $customUserId ) )
					{
						continue;
					}

					$tblCustom = Foundry::table( 'PrivacyCustom' );

					$tblCustom->uid 	= $tbl->id;
					$tblCustom->utype 	= SOCIAL_PRIVACY_TYPE_USER;
					$tblCustom->user_id = $customUserId;
					$tblCustom->store();

				}
			}

			// lets check if we need to reset the privacy_items or not.
			// we can do either delete or updates. delete seems more clean.
			if( isset( $item->reset ) && $item->reset && $type == SOCIAL_PRIVACY_TYPE_USER )
			{
				$query = 'delete from `#__social_privacy_items`';
				$query .= ' where `privacy_id` = ' . $db->Quote( $item->id );
				$query .= ' and `user_id` = ' . $db->Quote( $uid );
				$query .= ' and `type` != ' . $db->Quote( SOCIAL_TYPE_FIELD );

				$sql->clear();
				$sql->raw( $query );
				$db->setQuery( $sql );
				$db->query();

				// need to update stream for related privacy items.
				$query = 'select `type` from `#__social_privacy` where `id` = ' . $db->Quote( $item->id );
				$sql->clear();
				$sql->raw( $query );
				$db->setQuery( $sql );
				$pType = $db->loadResult();


				$isPublic 	= ( $valueInInt == SOCIAL_PRIVACY_PUBLIC ) ? 1 : 0;

				$updateQuery = 'update `#__social_stream` set `ispublic` = ' . $db->Quote( $isPublic );
				switch( $pType )
				{
					case 'photos':
						$updateQuery .= ' where `actor_id` = ' . $db->Quote( $uid ) . ' and `context_type` = ' . $db->Quote( SOCIAL_TYPE_PHOTO ) ;
						break;
					case 'albums':
						$updateQuery .= ' where `actor_id` = ' . $db->Quote( $uid ) . ' and `context_type` = ' . $db->Quote( SOCIAL_TYPE_ALBUM ) ;
						break;
					case 'story':
						$updateQuery .= ' where `actor_id` = ' . $db->Quote( $uid ) . ' and `context_type` IN (' . $db->Quote( SOCIAL_TYPE_STORY ) . ', ' . $db->Quote( SOCIAL_TYPE_LINKS ) . ')' ;
						break;
					case 'core':
						$updateQuery .= ' where `actor_id` = ' . $db->Quote( $uid ) . ' and `context_type` NOT IN' ;
						$updateQuery .= ' ('. $db->Quote( SOCIAL_TYPE_STORY ) . ', ' . $db->Quote( SOCIAL_TYPE_LINKS ) . ', ' . $db->Quote( SOCIAL_TYPE_PHOTO ) . ', ' . $db->Quote( SOCIAL_TYPE_ALBUM ). ')' ;
						break;

					default:
						$updateQuery .= ' where `actor_id` = ' . $db->Quote( $uid ) . ' and `context_type` = ' . $db->Quote( $pType ) ;
						break;
				}

				$sql->clear();
				$sql->raw( $updateQuery );
				$db->setQuery( $sql );
				$db->query();

			}
			else if( isset( $item->reset ) && $item->reset && $type == SOCIAL_PRIVACY_TYPE_PROFILE )
			{

				$commandSQL = 'select `user_id` from `#__social_profiles_maps` where `profile_id` = ' . $db->Quote( $uid );

				// uid == profile id.
				// we need to update user's privacy setting as well for this profile.
				$updateQuery = 'update `#__social_privacy_map` set `value` = ' . $db->Quote( $valueInInt );
				$updateQuery .= ' where `privacy_id` = ' . $db->Quote( $item->id );
				$updateQuery .= ' and `uid` IN ( '. $commandSQL .' )';
				$updateQuery .= ' and `utype` = ' . $db->Quote( 'user' );

				$sql->clear();
				$sql->raw( $updateQuery );
				$db->setQuery( $sql );
				$db->query();


				// now lets clear the privacy for items.
				$query = 'delete from `#__social_privacy_items`';
				$query .= ' where `privacy_id` = ' . $db->Quote( $item->id );
				$query .= ' and `user_id` IN ( ' . $commandSQL . ' )';
				$query .= ' and `type` != ' . $db->Quote( SOCIAL_TYPE_FIELD );

				$sql->clear();
				$sql->raw( $query );
				$db->setQuery( $sql );
				$db->query();

				// need to update stream for related privacy items.
				$query = 'select `type` from `#__social_privacy` where `id` = ' . $db->Quote( $item->id );
				$sql->clear();
				$sql->raw( $query );
				$db->setQuery( $sql );
				$pType = $db->loadResult();

				$isPublic 	= ( $valueInInt == SOCIAL_PRIVACY_PUBLIC ) ? 1 : 0;

				$updateQuery = 'update `#__social_stream` set `ispublic` = ' . $db->Quote( $isPublic );
				switch( $pType )
				{
					case 'photos':
						$updateQuery .= ' where `actor_id` IN ( ' . $commandSQL . ' )';
						$updateQuery .= ' and `context_type` = ' . $db->Quote( SOCIAL_TYPE_PHOTO ) ;
						break;
					case 'albums':
						$updateQuery .= ' where `actor_id` IN ( ' . $commandSQL . ' )';
						$updateQuery .= ' and `context_type` = ' . $db->Quote( SOCIAL_TYPE_ALBUM ) ;
						break;
					case 'story':
						$updateQuery .= ' where `actor_id` IN ( ' . $commandSQL . ' )';
						$updateQuery .= ' and `context_type` IN (' . $db->Quote( SOCIAL_TYPE_STORY ) . ', ' . $db->Quote( SOCIAL_TYPE_LINKS ) . ')' ;
						break;
					case 'core':
						$updateQuery .= ' where `actor_id` IN ( ' . $commandSQL . ' )';
						$updateQuery .= ' and `context_type` NOT IN' ;
						$updateQuery .= ' ('. $db->Quote( SOCIAL_TYPE_STORY ) . ', ' . $db->Quote( SOCIAL_TYPE_LINKS ) . ', ' . $db->Quote( SOCIAL_TYPE_PHOTO ) . ', ' . $db->Quote( SOCIAL_TYPE_ALBUM ). ')' ;
						break;

					default:
						$updateQuery .= ' where `actor_id` IN ( ' . $commandSQL . ' )';
						$updateQuery .= ' and `context_type` = ' . $db->Quote( $pType );
						break;
				}

				$sql->clear();
				$sql->raw( $updateQuery );

				$db->setQuery( $sql );
				$db->query();

			}

		}

		return true;
	}

	/**
	 * Responsible to retrieve the data for a privacy item.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	int		The unique id for the item.
	 * @param	string	The unique string for the type of the item.
	 * @param	string	The unique component name.
	 */
	public function getData( $id , $type = SOCIAL_PRIVACY_TYPE_USER )
	{
		$db				= Foundry::db();

		$item			= array();

		// Render default acl items from manifest file.
		// $defaultItems = $this->renderManifest( $component );

		$defaultItems    = $this->getDefaultPrivacy();

		// Render items that are stored in the database.
		$query = 'select a.' . $db->nameQuote( 'type' ) . ', a.' . $db->nameQuote( 'rule' ) . ', b.' . $db->nameQuote( 'value' ) . ',';
		$query .= ' a.' . $db->nameQuote( 'id' ) . ', b.' . $db->nameQuote( 'id' ) . ' as ' . $db->nameQuote( 'mapid' );
		$query .= ' from ' . $db->nameQuote( '#__social_privacy' ) . ' as a';
		$query .= '	inner join ' . $db->nameQuote( '#__social_privacy_map' ) . ' as b on a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'privacy_id' );
		$query .= ' where b.' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $id );
		$query .= ' and b.' . $db->nameQuote( 'utype' ) . ' = ' . $db->Quote( $type );
		$query .= ' order by a.' . $db->nameQuote( 'type' );
		$db->setQuery( $query );

		$result		= $db->loadObjectList();

		// If there's nothing stored into the database, we just return the default values.
		if( !$result )
		{
			return $defaultItems;
		}

		// If there's values stored in the database, map the values back.
		foreach( $result as $row )
		{
			$row->type  = strtolower( $row->type );
			$group 		= $row->type;

			$obj        = new stdClass();

			$obj->type 		= (string) $row->type;
			$obj->rule 		= (string) $row->rule;
			$obj->id 		= $row->id;
			$obj->mapid 	= $row->mapid;

			if( isset( $defaultItems[ $group ] ) )
			{
				$defaultGroup = $defaultItems[$group];

				foreach( $defaultGroup as $rule )
				{
					if( $rule->type == $row->type && $rule->rule == $row->rule)
					{
						$optionKeys 	= array_keys( $rule->options );
						$defaultOptions = array_fill_keys( $optionKeys, '0');

						$key 			= constant( 'SOCIAL_PRIVACY_' . $row->value );

						$defaultOptions[$key] = '1';

						$obj->options = $defaultOptions;

						break;
					}

				}
			}

			$obj->custom = '';

			//get the customized user listing if there is any
			if( $row->value == SOCIAL_PRIVACY_CUSTOM )
			{
				$obj->custom = $this->getPrivacyCustom( $row->mapid , SOCIAL_PRIVACY_TYPE_USER );
			}

			$defaultItems[ $group ][ $obj->rule ] = $obj;
		}


		return $defaultItems;
	}



	public function getDefaultPrivacy()
	{
		$db = Foundry::db();

		$query = 'select * from ' . $db->nameQuote( '#__social_privacy') . ' order by ' . $db->nameQuote( 'type' );
		$db->setQuery( $query );

		$result = $db->loadObjectList();

		$items = array();

		foreach( $result as $item )
		{

			$obj 			= new stdClass();
			$obj->id 		= $item->id;
			$obj->mapid 	= '0';
			$obj->type 		= $item->type;
			$obj->rule 		= $item->rule;
			$obj->options	= array();

			$default = Foundry::call( 'Privacy' , 'toKey' , $item->value );
			$options = Foundry::json()->decode( $item->options );

			foreach( $options->options as $key => $option )
			{
				$obj->options[ $option ] = ( $default == $option ) ? '1' : '0';
			}

			$obj->custom = null;

			$items[ $item->type ][ $item->rule ]	= $obj;
		}

		// Sort the items
		krsort($items);

		return $items;
	}


	/**
	 * Renders the default manifest file for privacies.
	 *
	 * @since	3.0
	 * @access	public
	 * @param	string	The component's unique element name
	 */
	public function renderManifest( $component = 'com_easysocial' )
	{
		$file 		= JPATH_ROOT . '/administrator/components/' . strtolower( $component ) . '/privacy.xml';

		if( !JFile::exists( $file ) )
		{
			return false;
		}

		// Try to load the privacy manifest file.
		$parser 	= Foundry::get( 'Parser' );
		$state 		= $parser->load( $file );

		if( !$state )
		{
			$this->setError( JText::sprintf( 'Manifest file for %1s cannot be found.' , $component ) );
			return false;
		}

		$items		= array();
		$nodes		= $parser->children();

		foreach( $nodes as $node )
		{
			$obj 			= new stdClass();
			$obj->type 		= (string) $node->type;
			$obj->rule 		= (string) $node->rule;
			$obj->options	= array();

			// Item properties.
			$options 	= $node->options->xpath( 'option' );

			foreach( $options as $key => $option )
			{
				$key 	= (string) $option;

				// Determine if the value is selected.
				$obj->options[ $key ]	= is_null( $option->attributes()->selected ) ? 0 : (string) $option->attributes()->selected;
			}

			$group 		= (string) $node->type;
			$items[ $group ][ $obj->rule ]	= $obj;
		}

		// Sort the items
		krsort($items);

		return $items;
	}

	/**
	 * Responsible to add / upate user privacy on an object
	 *
	 * @since	3.0
	 * @access	public
	 * @param	int		The user id.
	 * @param	int 	The unique id form the object.
	 * @param 	string 	The type of object.
	 * @param	string	The privacy value from user.
	 * @param	string	The custom user id.
	 *
	 */
	public function update($userId, $pid, $uId, $uType, $value, $custom = '' )
	{
		// lets check if this user already has the record or not.
		// if not, we will add it here.
		// if exists, we will update the record.

		$db 	= Foundry::db();

		// check if user selected custom but there is no userids, then we do not do anything.
		if( $value == 'custom' && empty( $custom ) )
		{
			return false;
		}

		$query = 'select `id` from `#__social_privacy_items`';
		$query .= ' where `user_id` = ' . $db->Quote( $userId );
		$query .= ' and `uid` = ' . $db->Quote( $uId );
		$query .= ' and `type` = ' . $db->Quote( $uType );

		$db->setQuery( $query );

		$result = $db->loadResult();

		$privacy 	= Foundry::privacy( $userId );
		$valueInInt = $privacy->toValue( $value );

		$tbl = Foundry::table( 'PrivacyItems' );

		if( $result )
		{
			// record exist. update here.
			$tbl->load( $result );
			$tbl->value = $valueInInt;

		}
		else
		{
			// record not found. add new here.
			$tbl->user_id 		= $userId;
			$tbl->privacy_id 	= $pid;
			$tbl->uid 			= $uId;
			$tbl->type 			= $uType;
			$tbl->value 		= $valueInInt;
		}

		if(! $tbl->store() )
		{
			return false;
		}

		//clear the existing customized privacy data.
		$sql = Foundry::sql();

		$sql->delete( '#__social_privacy_customize' );
		$sql->where( 'uid', $tbl->id );
		$sql->where( 'utype', SOCIAL_PRIVACY_TYPE_ITEM );

		$db->setQuery( $sql );
		$db->query();

		// if there is custom userids.
		if( $value == 'custom' && !empty( $custom ) )
		{
			$customList = explode( ',', $custom );

			for( $i = 0; $i < count( $customList ); $i++ )
			{
				$customUserId = $customList[ $i ];

				if( empty( $customUserId ) )
				{
					continue;
				}

				$tblCustom = Foundry::table( 'PrivacyCustom' );

				$tblCustom->uid 	= $tbl->id;
				$tblCustom->utype 	= SOCIAL_PRIVACY_TYPE_ITEM;
				$tblCustom->user_id = $customUserId;
				$tblCustom->store();

			}
		}

		// need to update the stream's ispublic flag.
		if( $uType != SOCIAL_TYPE_FIELD )
		{
			$context 	= $uType;
			$column 	= 'context_id';
			$updateId 	= $uId;
			$isPublic 	= ( $valueInInt == SOCIAL_PRIVACY_PUBLIC ) ? 1 : 0;

			$updateQuery = 'update #__social_stream set ispublic = ' . $db->Quote( $isPublic );


			switch( $context )
			{
				case SOCIAL_TYPE_ACTIVITY:
					$updateQuery .= ' where `id` = ( select `uid` from `#__social_stream_item` where `id` = ' . $db->Quote( $uId ) . ')';
					break;
				case SOCIAL_TYPE_STORY:
				case SOCIAL_TYPE_LINKS:
					$updateQuery .= ' where `id` = ' . $db->Quote( $uId );
					break;

				default:
					$updateQuery .= ' where `id` IN ( select `uid` from `#__social_stream_item` where `context_type` = ' . $db->Quote( $context ) . ' and `context_id` = ' . $db->Quote( $uId ) . ')';
					break;
			}

			$sql->clear();
			$sql->raw( $updateQuery );
			$db->setQuery( $sql );
			$db->query();
		}

		// lets trigger the onPrivacyChange event here so that apps can handle their items accordingly.
		$obj = new stdClass();
		$obj->user_id 		= $userId;
		$obj->privacy_id 	= $pid;
		$obj->uid 			= $uId;
		$obj->utype 		= $uType;
		$obj->value 		= $valueInInt;
		$obj->custom 		= $custom;

		// Get apps library.
		$apps 	= Foundry::getInstance( 'Apps' );

		// Try to load user apps
		$state 	= $apps->load( SOCIAL_APPS_GROUP_USER );
		if( $state )
		{
			// Only go through dispatcher when there is some apps loaded, otherwise it's pointless.
			$dispatcher		= Foundry::dispatcher();

			// Pass arguments by reference.
			$args 			= array( $obj );

			// @trigger: onPrepareStream for the specific context
			$result 		= $dispatcher->trigger( SOCIAL_APPS_GROUP_USER , 'onPrivacyChange' , $args , $uType );
		}

		return true;
	}
	public function getPrivacyCustom( $pItemId, $type = SOCIAL_PRIVACY_TYPE_ITEM )
	{
		$db 	= Foundry::db();
		$sql 	= Foundry::sql();

		$sql->select( '#__social_privacy_customize' );
		$sql->column( 'user_id' );
		$sql->where( 'uid', $pItemId, '=' );
		$sql->where( 'utype', $type, '=' );

		$db->setQuery( $sql );
		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 * Retrieves the privacy object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPrivacyItem( $uid , $type , $ownerId , $command )
	{
		static $cached 	= array();

		// Build the index for cached item
		$index 	= $uid . $type;
		$key 	= $uid . '.' . $type;


		if( isset( $cached[ $index ] ) )
		{
			return $cached[ $index ];
		}

		$db 			= Foundry::db();
		$result 		= array();
		static $items	= array();

		if( isset( $items[ $index ] ) )
		{
			$result 	= $items[ $index ];
		}
		else
		{
			if( isset( self::$_privacyitems[ $key ] ) )
			{
				if( self::$_privacyitems[ $key ] )
				{
					$result = clone( self::$_privacyitems[ $key ] );
				}
			}
			else
			{
				if( $uid )
				{
					$query = 'select a.' . $db->nameQuote( 'id' ) . ', a.' . $db->nameQuote( 'value' ) . ' as ' . $db->nameQuote( 'default' ) . ', a.' . $db->nameQuote( 'options' ) . ', ';
					$query .= 'b.' . $db->nameQuote( 'user_id' ) . ', b.' . $db->nameQuote( 'uid' ) . ', b.' . $db->nameQuote( 'type' ) . ', b.' . $db->nameQuote( 'value' ) . ',';
					$query .= 'b.' . $db->nameQuote( 'id' ) . ' as ' . $db->nameQuote( 'pid' );
					$query .= ' from ' . $db->nameQuote( '#__social_privacy' ) . ' as a';
					$query .= '		inner join ' . $db->nameQuote( '#__social_privacy_items' ) . ' as b on a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'privacy_id' );
					$query .= ' where b.' . $db->nameQuote( 'uid') . ' = ' . $db->Quote( $uid );
					$query .= ' and b.' . $db->nameQuote( 'type' ) . ' = ' . $db->Quote( $type );
					if( $ownerId )
					{
						$query .= ' and b.' . $db->nameQuote( 'user_id' ) . ' = ' . $db->Quote( $ownerId );
					}

					// var_dump( $ownerId );
					// echo $query;exit;


					$db->setQuery( $query );
					$result = $db->loadObject();

					$items[ $index ]	= $result;
				}
			}
		}

		// If we still can't find a result, then we need to load from the default items
		if( !$result || !isset( $result->id ) )
		{
			// Retrieve the core values
			$defaultValue				= $this->getPrivacyDefaultValues( $command, $ownerId );

			$result 			= clone( $defaultValue );
			$result->uid 		= $uid;
			$result->type 		= $type;
			$result->user_id 	= $ownerId;
			$result->value 		= isset( $result->default ) ? $result->default : '';
			$result->pid  		= '0';
		}

		if( !isset( $result->options ) )
		{
			$result->options	= '';
		}

		$default = Foundry::call( 'Privacy' , 'toKey' , $result->value );
		$options = Foundry::json()->decode( $result->options );

		$result->option	= array();

		if( $options )
		{
			foreach( $options->options as $key => $option )
			{
				$result->option[ $option ] = ( $default == $option ) ? '1' : '0';
			}
		}

		// get the custom user id.
		$result->custom = array();

		if( $result->value == SOCIAL_PRIVACY_CUSTOM )
		{
			if( $result->pid )
			{
				$result->custom = $this->getPrivacyCustom( $result->pid );
			}
			else if( $result->mapid )
			{
				$result->custom = $this->getPrivacyCustom( $result->mapid, SOCIAL_PRIVACY_TYPE_USER );

			}
		}

		$my = Foundry::user();

		$result->editable = false;

		if( $result->user_id == $my->id )
		{
			$result->editable = true;
		}

		$cached[ $index ]	= $result;

		return $cached[ $index ];
	}

	/**
	 * Retrieves the default values for the privacy item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPrivacyDefaultValues( $command = null , $userId = null )
	{
		static $core 	= array();

		$command 	= !$command ? 'core.view' : $command;
		$index		= $command . $userId;

		$data 		= explode( '.' , $command );
		$element 	= array_shift( $data );
		$rule 		= implode( '.', $data);

		if( isset( $core[ $index ] ) )
		{
			return $core[ $index ];
		}

		$default 	= null;

		// If owner id is provided, try to get the owner's privacy object
		if( $userId )
		{
			$userPrivacy 	= $this->getPrivacyUserDefaultValues( $userId );

			if( $userPrivacy )
			{
				foreach( $userPrivacy as $item )
				{
					if( $item->type == $element && $item->rule == $rule )
					{
						$default = $item;
						break;
					}
				}
			}
		}

		$systemPrivacy	= $this->getPrivacySystemDefaultValues();


		// If the default value is still null, try to search for default values from our own table
		if( !$default )
		{
			foreach( $systemPrivacy as $item )
			{
				if( $item->type == $element && $item->rule == $rule )
				{
					$default 	= $item;

					break;
				}
			}
		}

		// If we still can't find the default, then we just revert to the core.view privacy here.
		if( !$default )
		{
			foreach( $systemPrivacy as $defaultItem )
			{
				if( $defaultItem->type == 'core' && $defaultItem->rule == 'view' )
				{
					$default = $defaultItem;
					break;
				}
			}


		}

		$core[ $index ]	= $default;

		return $core[ $index ];
	}

	public function getPrivacySystemDefaultValues()
	{
		static $system	= null;

		if( $system )
		{

			return $system;
		}

		$db 		= Foundry::db();
		$sql 		= $db->sql();
// var_dump( 'called' );
		// Try to get the privacy from the master table
		$query 		= array();
		$query[]	= 'SELECT a.' . $db->nameQuote( 'type' ) . ', a.' . $db->nameQuote( 'rule' ) . ', a.' . $db->nameQuote( 'id' ) . ', a.' . $db->nameQuote( 'value' ) . ' AS ' . $db->nameQuote( 'default' ) . ', a.' . $db->nameQuote( 'options' );
		$query[] 	= ', 0 as ' . $db->nameQuote( 'mapid' );
		$query[]	= 'FROM ' . $db->nameQuote( '#__social_privacy' ) . ' AS a';

		$query 		= implode( ' ' , $query );

		$sql->raw( $query );
		$db->setQuery( $sql );

		$system		= $db->loadObjectList();

		return $system;
	}

	public function getPrivacyUserDefaultValues( $userId )
	{
		static $users 	= array();

		if( isset( $users[ $userId ] ) )
		{
			return $users[ $userId ];
		}

		$db 	= Foundry::db();

		$query = 'select a.'.$db->nameQuote( 'type' ) . ', a.' . $db->nameQuote( 'rule' ) . ', a.' . $db->nameQuote( 'id' ) . ', b.' . $db->nameQuote( 'value' ) . ' as ' . $db->nameQuote( 'default' ) . ', a.' . $db->nameQuote( 'options' );
		$query .= ', b.' . $db->nameQuote( 'id' ) . ' as ' . $db->nameQuote( 'mapid' );
		$query .= ' from ' . $db->nameQuote( '#__social_privacy' ) . ' as a';
		$query .= ' inner join ' . $db->nameQuote( '#__social_privacy_map' ) . ' as b';
		$query .= ' ON a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'privacy_id' );
		$query .= ' where b.' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $userId );
		$query .= ' and b.' . $db->nameQuote( 'utype' ) . ' = ' . $db->Quote( SOCIAL_PRIVACY_TYPE_USER );

		$db->setQuery( $query );

		$users[ $userId ]	= $db->loadObjectList();

		return $users[ $userId ];
	}

	/**
	 * string - privacy rule in the form of element.rule. e.g. core.view, photos.view
	 *
	 */
	public function getPrivacyItemCoreOld( $command = null, $ownerId = null , $debug = false )
	{
		static $core 			= array();
		static $userCore 		= array();
		static $defaultCore 	= array();


		$key 	= ( is_null( $command ) || empty( $command ) ) ? 'core.view' : $command;
		$skey 	= $key . $ownerId;

		$data 		= explode( '.' , $key );
		$element 	= array_shift( $data );
		$rule 		= implode( '.', $data);

		if(! isset( $core[ $skey ] ) )
		{
			$db 		 = Foundry::db();
			$defaultData = null;

			// lets get the default value configured by owner.
			if( $ownerId )
			{
				if( ! isset( $userCore[ $ownerId ] ) )
				{
					$query = 'select a.'.$db->nameQuote( 'type' ) . ', a.' . $db->nameQuote( 'rule' ) . ', a.' . $db->nameQuote( 'id' ) . ', b.' . $db->nameQuote( 'value' ) . ' as ' . $db->nameQuote( 'default' ) . ', a.' . $db->nameQuote( 'options' );
					$query .= ' from ' . $db->nameQuote( '#__social_privacy' ) . ' as a';
					$query .= ' inner join ' . $db->nameQuote( '#__social_privacy_map' ) . ' as b';
					$query .= ' ON a.' . $db->nameQuote( 'id' ) . ' = b.' . $db->nameQuote( 'privacy_id' );
					$query .= ' where b.' . $db->nameQuote( 'uid' ) . ' = ' . $db->Quote( $ownerId );
					$query .= ' and b.' . $db->nameQuote( 'utype' ) . ' = ' . $db->Quote( SOCIAL_PRIVACY_TYPE_USER );

					$db->setQuery( $query );
					$prules = $db->loadObjectList();

					$userCore[ $ownerId ] = $prules;
				}

				$prules = $userCore[ $ownerId ];

				if( $prules )
				{
					foreach( $prules as $item )
					{
						if( $item->type == $element && $item->rule == $rule )
						{
							$defaultData = $item;
							break;
						}
					}
				}

			}

			if( ! $defaultData )
			{
				if( ! isset( $defaultCore['default'] ) )
				{
					// lets fall back to privacy master then if stil no records found.
					$query = 'select a.'.$db->nameQuote( 'type' ) . ', a.' . $db->nameQuote( 'rule' ) . ', a.' . $db->nameQuote( 'id' ) . ', a.' . $db->nameQuote( 'value' ) . ' as ' . $db->nameQuote( 'default' ) . ', a.' . $db->nameQuote( 'options' );
					$query .= ' from ' . $db->nameQuote( '#__social_privacy' ) . ' as a';

					$db->setQuery( $query );

					$defaultCore['default'] = $db->loadObjectList();

				}

				foreach( $defaultCore['default'] as $defaultItem )
				{
					if( $defaultItem->type == $element && $defaultItem->rule == $rule )
					{
						$defaultData = $defaultItem;
						break;
					}
				}

				if( ! $defaultData )
				{
					foreach( $defaultCore['default'] as $defaultItem )
					{
						if( $defaultItem->type == 'core' && $defaultItem->rule == 'view' )
						{
							$defaultData = $defaultItem;
							break;
						}
					}

				}
			}

			$core[ $skey ] = $defaultData;
		}

		return $core[ $skey ];
	}


	/**
	 * method used in backend to list down all the privacy items.
	 *
	 */
	public function getList()
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_privacy' );

		// Determines if user wants to search for something
		$search 	= $this->getState( 'search' );

		if( $search )
		{
			$sql->where( 'type' , $search , 'LIKE' , 'OR' );
			$sql->where( 'rule' , $search , 'LIKE' , 'OR' );
			$sql->where( 'description' , $search , 'LIKE' , 'OR' );
		}

		$ordering 	= $this->getState( 'ordering' );

		if( $ordering )
		{
			$direction 	= $this->getState( 'direction' );

			$sql->order( $ordering , $direction );
		}


		$this->setTotal( $sql->getTotalSql() );

		$rows 	= parent::getData( $sql->getSql() );

		if( !$rows )
		{
			return false;
		}

		// We want to pass back a list of PointsTable object.
		$data 	= array();

		// Load the admin language file whenever there's points.
		JFactory::getLanguage()->load( 'com_easysocial' , JPATH_ROOT . '/administrator' );

		foreach( $rows as $row )
		{
			$privacy 	= Foundry::table( 'Privacy' );
			$privacy->bind( $row );

			$data[]	= $privacy;
		}

		return $data;

	}

	/**
	 * Scans through the given path and see if there are any privacy's rule files.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The paths
	 * @return
	 */
	public function scan( $path )
	{
		jimport( 'joomla.filesystem.folder' );

		$data 	= array();

		$directory 		= JPATH_ROOT . $path;
		$directories 	= JFolder::folders( $directory , '.' , true, true );

		foreach( $directories as $folder )
		{
			// just need to get one level folder.
			$files 		= JFolder::files( $folder , '.privacy' , false , true );
			if( $files )
			{
				foreach( $files as $file )
				{
					// we only need to take files with extention .privacy
					$filesegments = explode( '.', $file );
					if( $filesegments[ count( $filesegments ) - 1 ] == 'privacy' )
					{
						$data[] = $file;
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Given a path to the file, install the privacy rules.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string		The path to the privacy .json file.
	 * @return	bool		True if success false otherwise.
	 */
	public function install( $path )
	{
		// Import platform's file library.
		jimport( 'joomla.filesystem.file' );

		// Read the contents
		$contents 	= JFile::read( $path );

		// If contents is empty, throw an error.
		if( empty( $contents ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'Privacy: Unable to read the file ' . $path );
			$this->setError( JText::_( 'Unable to read privacy rule file' ) );
			return false;
		}

		$json 		= Foundry::json();
		$data 		= $json->decode( $contents );

		if(! is_array( $data ) )
		{
			$data = array( $data );
		}


		// Let's test if there's data.
		if( empty( $data ) )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'Privacy: Unable to read the file ' . $path );
			$this->setError( JText::_( 'Unable to read privacy rule file' ) );
			return false;
		}

		$privLib 	= foundry::privacy();
		$result 	= array();

		foreach( $data as $row )
		{
			$type 	= $row->type;
			$rules 	= $row->rules;

			if( count( $rules ) > 0 )
			{
				foreach( $rules as $rule )
				{
					$command 		= $rule->command;
					$description 	= $rule->description;
					$default 		= $rule->default;
					$options 		= $rule->options;

					$optionsArr = array();
					foreach( $options as $option )
					{
						$optionsArr[] = $option->name;
					}

					$ruleOptions 	= array( 'options' => $optionsArr );
					$optionString 	= $json->encode( $ruleOptions );

					// Load the tables
					$privacy 	= Foundry::table( 'Privacy' );

					// If this already exists, we need to skip this.
					$state 	= $privacy->load( array( 'type' => $type , 'rule' => $command ) );

					if( $state )
					{
						continue;
					}

					$privacy->type 			= $type;
					$privacy->rule 			= $command;
					$privacy->description 	= $description;
					$privacy->value 		= $privLib->toValue( $default );
					$privacy->options 		= $optionString;

					$privacy->store();

					$result[] = $type . '.' . $command;

				}
			}
		}

		return $result;
	}

	public function setStreamPrivacyItemBatch( $data )
	{
		$db 	= Foundry::db();
		$sql 	= $db->sql();

		// _privacyitems
		$streamModel = Foundry::model( 'Stream' );

		$dataset = array();
		foreach( $data as $item )
		{
			$relatedData = $streamModel->getBatchRalatedItem( $item->id );

			// If there are no related data, skip this.
			if( !$relatedData )
			{
				continue;
			}

			$element 	= $item->context_type;

			$streamItem = $relatedData[0];
			$uid 		= $streamItem->context_id;

			if( $element == 'photos' && count( $relatedData ) > 1 )
			{
				if( $streamItem->target_id )
				{
					$key = $streamItem->target_id . '.albums';

					if( ! isset( self::$_privacyitems[ $key ] ) )
					{
						$dataset[ 'albums' ][] = $streamItem->target_id;
					}
				}

				foreach( $relatedData as $itemData )
				{
					$key = $itemData->context_id . '.photos';

					if( ! isset( self::$_privacyitems[ $key ] ) )
					{
						$dataset[ 'photos' ][] = $itemData->context_id;
					}
				}

				// go to next item
				continue;
			}

			if( $element == 'story' || $element == 'links' )
			{
				$uid = $streamItem->uid;
			}

			if( $element == 'badges' || $element == 'shares' )
			{
				$uid 	 = $streamItem->id;
				$element = SOCIAL_TYPE_ACTIVITY;
			}

			if( !$uid )
			{
				continue;
			}

			$key = $uid . '.' . $element;

			if( ! isset( self::$_privacyitems[ $key ] ) )
			{
				$dataset[ $element ][] = $uid;
			}
		}

		//var_dump( $dataset );

		// lets build the sql now.
		if( $dataset )
		{

			$mainSQL = '';
			foreach( $dataset as $element => $uids )
			{
				$ids = implode( ',', $uids );

				foreach( $uids as $uid )
				{
					$key = $uid . '.' . $element;
					self::$_privacyitems[ $key ] = array();
				}

				$query = 'select a.`id`, a.`value` as `default`, a.`options`, ';
				$query .= 'b.`user_id`, b.`uid`, b.`type`, b.`value`,';
				$query .= 'b.`id`  as `pid`';
				$query .= ' from `#__social_privacy` as a';
				$query .= '		inner join `#__social_privacy_items` as b on a.`id` = b.`privacy_id`';
				$query .= ' where b.uid IN (' . $ids . ')';
				$query .= ' and b.type = ' . $db->Quote( $element );

				$mainSQL .= ( empty( $mainSQL ) ) ? $query : ' UNION ' . $query;

			}

			$sql->raw( $mainSQL );
			$db->setQuery( $sql );

			$result = $db->loadObjectList();

			if( $result )
			{
				foreach( $result as $rItem )
				{
					$key = $rItem->uid . '.' . $rItem->type;
					self::$_privacyitems[ $key ] = $rItem;
				}
			}

		}

	}



}
