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

Foundry::import( 'admin:/tables/table' );


class SocialTableAlert extends SocialTable
{
	/**
	 * The unique id of the alert
	 * @var int
	 */
	public $id				= null;

	/**
	 * The element of the alert
	 * @var string
	 */
	public $element			= null;

	/**
	 * Optional extension for the alert rule.
	 * @var string
	 */
	public $extension		= null;

	/**
	 * The rulename of the alert
	 * @var string
	 */
	public $rule			= null;

	/**
	 * The setting of email notification for this rule
	 * @var int(1/0)
	 */
	public $email			= null;

	/**
	 * The setting of system notification for this rule
	 * @var int(1/0)
	 */
	public $system			= null;

	/**
	 * The core state of the rule
	 * @var int(1/0)
	 */
	public $core			= null;

	/**
	 * The app state of the rule
	 * @var int(1/0)
	 */
	public $app				= null;

	/**
	 * Determines if this rule was created for fields
	 * @var int(1/0)
	 */
	public $field			= null;

	/**
	 * The group for the app or field
	 * @var int(1/0)
	 */
	public $group			= null;

	/**
	 * The created datetime of the rule
	 * @var datetime
	 */
	public $created			= null;

	// Extended data for table class purposes
	public $users			= array();

	public function __construct(& $db )
	{
		parent::__construct( '#__social_alert' , 'id' , $db );
	}

	public function loadByRule( $element, $rule )
	{
		$db		= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_alert' );
		$sql->where( 'element', $element );
		$sql->where( 'rule', $rule );

		$db->setQuery( $sql );
		$result = $db->loadObject();

		if( !$result )
		{
			return false;
		}

		return parent::bind( $result );
	}

	// Chainability
	public function loadUsers()
	{
		if( !$this->users )
		{
			$db		= Foundry::db();
			$sql	= $db->sql();

			$sql->select( '#__social_alert_map' );
			$sql->column( 'user_id', 'id' );
			$sql->column( 'email' );
			$sql->column( 'system' );
			$sql->where( 'alert_id', $this->id );

			$db->setQuery( $sql );

			$result = $db->loadObjectList();

			// Extract the id out as key
			foreach( $result as $row )
			{
				$this->users[$row->id] = $row;
			}
		}

		return $this;
	}

	public function loadLanguage()
	{
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

		// If this is a field, we need to load the proper language file.
		if( $this->field )
		{
			Foundry::language()->loadField( $this->group , $this->element );
		}

		// If this is an app, we need to load the proper language file.
		if( $this->app )
		{
			Foundry::language()->loadApp( $this->group , $this->element );
		}

		if( !empty( $this->extension ) )
		{
			Foundry::language()->load( $this->extension , JPATH_ROOT );
			Foundry::language()->load( $this->extension , JPATH_ADMINISTRATOR );
		}
	}

	/**
	 * Retrieves the title for this alert rule
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTitle()
	{
		$this->loadLanguage();

		$element	= str_ireplace( '.' , '_' , $this->element );
		$rule 		= str_ireplace( '.' , '_' , $this->rule );

		$text 	= $this->getExtension() . 'PROFILE_NOTIFICATION_SETTINGS_' . strtoupper( $element ) . '_' . strtoupper( $rule );

		$text 	= JText::_( $text );

		return $text;
	}

	/**
	 * Retrieves the title for this alert rule
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getDescription()
	{
		$this->loadLanguage();

		$element	= str_ireplace( '.' , '_' , $this->element );
		$rule 		= str_ireplace( '.' , '_' , $this->rule );
		$text 		= $this->getExtension() . 'PROFILE_NOTIFICATION_SETTINGS_' . strtoupper( $element ) . '_' . strtoupper( $rule ) . '_DESC';

		$text 	= JText::_( $text );

		return $text;
	}

	/**
	 * Retrieves the extension of this rule
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function getExtension()
	{
		$extension 	= 'COM_EASYSOCIAL_';

		if( $this->extension )
		{
			$extension 	= strtoupper( $this->extension ) . '_';
		}

		return $extension;
	}

	/**
	 * Retrieves a list of users
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string	The type of recipient result. 'email' or 'system'
	 * @return
	 */
	public function getUsers( $type = '', $filter = array() )
	{
		$this->loadUsers();

		if( !empty( $type ) )
		{
			$sets = array();

			if( $this->$type >= 0 )
			{
				$participants = $this->formatId( $filter );
				$users = $this->formatId( $this->users );

				foreach( $participants as $participant )
				{
					if( ( in_array( $participant, $users ) && $this->users[$participant]->$type ) || ( !in_array( $participant, $users ) && $this->$type ) )
					{
						$sets[] = $participant;
					}
				}

				// Array unique it
				$sets = array_unique( $sets );
			}

			return $sets;
		}

		return $this->users;
	}

	public function registerUser( $user_id )
	{
		$table = Foundry::table( 'alertmap' );
		$loaded = $table->loadByAlertId( $this->id, $user_id );

		if( !$loaded )
		{
			$table->alert_id 	= $this->id;
			$table->user_id 	= $user_id;
			$table->email 		= $this->email;
			$table->system 		= $this->system;

			$state = $table->store();

			if( !$state )
			{
				Foundry::logError( __FILE__, __LINE__, $table->getError() );
				return false;
			}
		}

		return true;
	}

	/**
	 * Apps email template namespace
	 * apps/{group}/{element}/alerts.{rulename}
	 *
	 * Apps email template path
	 * apps/{group}/{element}/themes/{default/themeName}/emails/{html/text}/alerts.{rulename}
	 *
	 * Core email template namespace
	 * site/{element}/alerts.{rulename}
	 *
	 * Core email template path
	 * site/themes/{wireframe/themeName}/emails/{html/text}/{element}/alert.{rulename}
	 *
	 * @since 1.0
	 * @access	public
	 * @param	array	$participants	The array of participants (user id) of the action
	 * @param	array	$options		Custom options of the email notification
	 *
	 * @return	boolean		State of the email notification
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function getMailTemplateName()
	{
		$base = $this->app > 0 ? 'apps/user' : 'site';

		$path = $base . '/' . $this->element . '/alerts.' . $this->rule;

		return $path;
	}

	/**
	 * Apps sample title
	 * APP_ELEMENT_RULENAME_ALERTTYPE_TITLE
	 *
	 * Core sample title
	 * COM_EASYSOCIAL_ELEMENT_RULENAME_ALERTTYPE_TITLE
	 *
	 * @since 1.0
	 * @access	public
	 * @param	string	$type	The alert type
	 *
	 * @return	string	The JText title string
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function getNotificationTitle( $type )
	{
		$this->loadLanguage();

		$segments = array();

		$segments[] = $this->app > 0 ? 'APP' : 'COM_EASYSOCIAL';

		$segments[] = strtoupper( $this->element );
		$segments[] = strtoupper( $this->rule );
		$segments[] = strtoupper( $type );
		$segments[] = 'TITLE';

		$title = JText::_( implode( '_', $segments ) );

		return $title;
	}

	public function send( $participants, $emailOptions = array(), $systemOptions = array() )
	{
		if( $emailOptions !== false )
		{
			$this->sendEmail( $participants, $emailOptions );
		}

		if( $systemOptions !== false )
		{
			$this->sendSystem( $participants, $systemOptions );
		}

		return true;
	}

	/**
	 * Apps email title (assuming that app itself have already loaded the language file before calling this function)
	 * APP_{ELEMENT}_{RULENAME}_EMAIL_TITLE
	 *
	 * Apps email template namespace
	 * apps/{group}/{element}/alerts.{rulename}
	 *
	 * Apps email template path
	 * apps/{group}/{element}/themes/{default/themeName}/emails/{html/text}/alerts.{rulename}
	 *
	 * Core email title
	 * COM_EASYSOCIAL_{ELEMENT}_{RULENAME}_EMAIL_TITLE
	 *
	 * Core email template namespace
	 * site/{element}/alerts.{rulename}
	 *
	 * Core email template path
	 * site/themes/{wireframe/themeName}/emails/{html/text}/{element}/alert.{rulename}
	 *
	 * @since 1.0
	 * @access	public
	 * @param	array	$participants	The array of participants (user id) of the action
	 * @param	array	$options		Custom options of the email notification
	 *
	 * @return	boolean		State of the email notification
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function sendEmail( $participants, $options = array() )
	{
		$users = $this->getUsers( 'email', $participants );

		if( !empty( $users ) )
		{
			// Set default title if no title is passed in
			if( !isset( $options['title'] ) )
			{
				$options['title'] = $this->getNotificationTitle( 'email' );
			}

			// Set default template if no template is passed in
			if( !isset( $options['template'] ) )
			{
				$options['template'] = $this->getMailTemplateName();
			}

			if( !isset( $options['html'] ) )
			{
				$options['html'] = 1;
			}

			// If params is not set, just give it an empty array
			if( !isset( $options[ 'params' ] ) )
			{
				$options[ 'params' ]	= array();
			}

			$mailer = Foundry::mailer();

			$data	= new SocialMailerData();

			$data->set( 'title', $options['title'] );
			$data->set( 'template', $options['template'] );
			$data->set( 'html', $options['html'] );

			if( isset( $options['params'] ) )
			{
				$data->setParams( $options[ 'params' ] );
			}

			// If priority is set, set the priority
			if( isset( $options[ 'priority' ] ) )
			{
				$data->set( 'priority' , $options[ 'priority' ] );
			}

			if( isset( $options['sender_name'] ) )
			{
				$data->set( 'sender_name', $options['sender_name'] );
			}

			if( isset( $options['sender_email'] ) )
			{
				$data->set( 'sender_email', $options['sender_email'] );
			}

			if( isset( $options['replyto_email'] ) )
			{
				$data->set( 'replyto_email', $options['replyto_email'] );
			}


			foreach( $users as $uid )
			{
				$user		= Foundry::user( $uid );

				// Get the params
				$params 	= $options[ 'params' ];

				// Detect the "name" in the params. If it doesn't exist, set the target's name.
				if( is_array( $params ) )
				{
					if( !isset( $params[ 'recipientName' ] ) )
					{
						$params[ 'recipientName' ]	= $user->getName();
					}

					if( !isset( $params[ 'recipientAvatar' ] ) )
					{
						$params[ 'recipientAvatar' ] 	= $user->getAvatar();
					}

					$data->setParams( $params );
				}

				$data->set( 'recipient_name', $user->getName() );
				$data->set( 'recipient_email', $user->email );

				$mailer->create( $data );
			}
		}

		return true;
	}

	/**
	 * Apps system title (assuming that app itself have already loaded the language file before calling this function)
	 * APP_ELEMENT_RULENAME_EMAIL_TITLE
	 *
	 * Core system title
	 * COM_EASYSOCIAL_ELEMENT_RULENAME_SYSTEM_TITLE
	 *
	 * @since 1.0
	 * @access	public
	 * @param	array	$participants	The array of participants (user id) of the action
	 * @param	array	$options		Custom options of the system notification
	 *
	 * @return	boolean		State of the system notification
	 *
	 * @author	Jason Rey <jasonrey@stackideas.com>
	 */
	public function sendSystem( $participants, $options = array() )
	{
		$users = $this->getUsers( 'system', $participants );

		if( empty( $users ) )
		{
			return false;
		}

		if( !isset( $options['uid'] ) )
		{
			$options['uid'] = 0;
		}

		if( !isset( $options['type'] ) )
		{
			$options['type'] = $this->element;
		}

		if( !isset( $options['cmd'] ) )
		{
			$options['cmd'] = $options['type'] . '.' . $this->rule;
		}

		if( !isset( $options['title'] ) )
		{
			$options['title'] = $this->getNotificationTitle( 'system' );
		}

		if( !isset( $options['actor_id'] ) )
		{
			$options['actor_id'] = 0;
		}

		if( !isset( $options['actor_type'] ) )
		{
			$options['actor_type'] = SOCIAL_TYPE_USER;
		}

		if( !isset( $options['target_type'] ) )
		{
			$options['target_type'] = SOCIAL_TYPE_USER;
		}

		if( !isset( $options[ 'url' ] ) )
		{
			$options[ 'url' ]	= JRequest::getURI();
		}

		// dump( $options );
		$notification = Foundry::notification();

		$data = $notification->getTemplate();

		$data->setObject( $options['uid'], $options['type'], $options['cmd'] );
		$data->setTitle( $options['title'] );

		// Determines if caller wants aggregation to happen for this system notifications.
		if( isset( $options[ 'aggregate' ] ) )
		{
			$data->setAggregation();
		}

		// Determines if the app wants to set a context_type
		if( isset( $options[ 'context_type' ] ) )
		{
			$data->setContextType( $options[ 'context_type' ] );
		}

		// Determines if the app wants to set a context_type
		if( isset( $options[ 'context_ids' ] ) )
		{
			$data->setContextId( $options[ 'context_ids' ] );
		}

		if( isset( $options['actor_id'] ) )
		{
			$data->setActor( $options['actor_id'], $options['actor_type'] );
		}

		if( isset( $options[ 'image' ] ) )
		{
			$data->setImage( $options[ 'image' ] );
		}

		if( isset( $options['params'] ) )
		{
			$data->setParams( $options['params'] );
		}

		if( isset( $options[ 'url' ] ) )
		{
			$data->setUrl( $options[ 'url' ] );
		}


		foreach( $users as $uid )
		{
			// Empty target shouldn't have notification
			if( !empty( $uid ) )
			{
				$data->setTarget( $uid, $options['target_type'] );

				$notification->create( $data );
			}
		}

		return true;
	}

	public function getApp()
	{
		static $app = array();

		if( $this->app == 0 )
		{
			return false;
		}

		if( !isset( $app[$this->element] ) )
		{
			$table = Foundry::table( 'app' );
			$state = $table->load( array( 'element' => $this->element, 'group' => $this->group, 'type' => SOCIAL_APPS_TYPE_APPS ) );

			if( !$state )
			{
				$app[$this->element] = false;
			}
			else
			{
				$app[$this->element] = $table;
			}
		}

		return $app[$this->element];
	}

	private function formatId( $participants )
	{
		$users = array();

		if( $participants )
		{
			foreach( $participants as $user )
			{
				if( is_object( $user ) )
				{
					$users[] = $user->id;
				}

				if( is_string( $user ) || is_int( $user ) )
				{
					$users[] = $user;
				}
			}
		}

		return $users;
	}
}
