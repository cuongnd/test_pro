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

jimport( 'joomla.html.parameter' );

/**
 * Mailer library.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialMailer
{
	/**
	 * The mailer object.
	 * @var SocialMailer
	 */
	static $instance = null;

	/**
	 * This is a singleton object in which it can / should only be instantiated using the getInstance method.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public static function factory()
	{
		return new self();
	}

	/**
	 * Creates a new data object for caller.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $mailer 	= Foundry::getInstance( 'Mailer' );
	 * $data 	= $mailer->getData();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	SocialMailerData	The mailer object.
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getTemplate()
	{
		$mailerObj	= new SocialMailerData();

		return $mailerObj;
	}

	/**
	 * Adds a new mail notification into the system or send an email immediately.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $mailer 	= Foundry::getInstance( 'Mailer' );
	 * $data 	= new SocialMailerData();
	 * $data->set( 'title' 		, 'Some title' );
	 * $data->set( 'template'	, 'email.template.file' );
	 * $data->set( 'recipient_name'	, 'Recipient' );
	 * $data->set( 'recipient_email', 'recipient@email.com' );
	 * $data->set( 'html'		, true );
	 *
	 * // Returns a bool value. True if success.
	 * $state = $mailer->create( $data );
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialMailerData	The required mailer data object.
	 *
	 * @return  boolean True on success, false otherwise
	 */
	public function create( SocialMailerData $mailData )
	{
		// Convert the mail data to an array.
		$data 		= $mailData->toArray();

		$mailer	= Foundry::table( 'Mailer' );
		$mailer->bind( $data );

		// If mail object is configured to send immediately, we shouldn't store it.
		if( $mailer->priority == SOCIAL_MAILER_PRIORITY_IMMEDIATE )
		{
			// Send the mail immediately.
			$state	= $this->send( array( $mailer ) );

			return $state;
		}

		return $mailer->store();
	}

	/**
	 * Process any translations in the email templates
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function translate( $content , $arguments )
	{
		$output    = $content;

		// Ensure that the params are always in object form
		$arguments 	= Foundry::makeObject( $arguments );

		// Get the list of arguments
		if( is_object( $arguments ) )
		{
			$arguments 	= get_object_vars( $arguments );
		}

		// Get a list of keys so we can prepend it with { and prepend it with }
		$keys 		= array_keys( $arguments );

		foreach( $keys as &$key )
		{
			$key 	= '{' . $key . '}';
		}

		// Get the list of values
		$values 	= array_values( $arguments );

		// Perform a search / replace of strings based on the arguments.
		$output     = str_ireplace( $keys , $values , $content );

		// // Process translations
		// preg_match_all( '/%TRANSLATE\{(.*)\}%/i' , $content , $matches );

		// // Let's perform the translations first.
		// if( isset( $matches[ 1 ] ) && !empty( $matches[ 1 ] ) )
		// {
		// 	$translations   = $matches[ 0 ];

		// 	for( $i = 0; $i < count( $translations ); $i++ )
		// 	{
		// 		$str        = explode( ',' , $matches[ 1 ][ $i ] );

		// 		if( count( $str ) > 1 )
		// 		{
		// 			$content    = str_ireplace( $translations[ $i ] , call_user_func_array( array( 'JText' , 'sprintf' ) , $str )  , $content );
		// 		}
		// 		else
		// 		{
		// 			$content    = str_ireplace( $translations[ $i ] , JText::_( $matches[ 1 ][ $i ] ) , $content );
		// 		}
		// 	}
		// }


		return $output;
	}

	/**
	 * Returns the content of the mail with the entire structure
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getEmailContents( $mail )
	{
		// Load language.
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

		// Process mail contents
		$contents	= $this->getContents( $mail );
		$theme 		= Foundry::get( 'Themes' );
		$theme->set( 'contents' , $contents );

		$app 		= JFactory::getApplication();
		$assets 	= Foundry::assets();

		// Load up the mail params
		$params 		= Foundry::registry( $mail->params );
		

		// Determines if we should display the manage alerts
		$manageAlerts	= $params->get( 'manageAlerts' , true );

		// Set the logo for the generic email template
		$override 	= JPATH_ROOT . '/templates/' . $assets->getJoomlaTemplate() . '/html/com_easysocial/emails/logo.png';
		$logo		= rtrim( JURI::root() , '/' ) . '/components/com_easysocial/themes/wireframe/images/emails/logo.png';

		if( JFile::exists( $override ) )
		{
			$logo 	= rtrim( JURI::root() , '/' ) . '/templates/' . $assets->getJoomlaTemplate() . '/html/com_easysocial/emails/logo.png';
		}

		// Set the logo uri
		$theme->set( 'logo' , $logo );
		$theme->set( 'manageAlerts' , $manageAlerts );
		
		if( $mail->html )
		{
			$output 	= $theme->output( 'site/emails/html/template' );
		}
		else
		{

			$output 	= $theme->output( 'site/emails/text/template' );
			$output 	= strip_tags( $output );
		}

		return $output;
	}

	/**
	 * Sends an email out.
	 *
	 * @param	Array	An array of SocialTableMailer object.
	 * @return	bool	The state of sending the mails out.
	 */
	public function send( $mails = array() )
	{
		// Retrieve configs
		$config 	= Foundry::config();
		$jConfig 	= Foundry::jconfig();

		// If there's no email to send out, we should just return ehre.
		if( !$mails )
		{
			return false;
		}

		$defaultSenderName 	= $config->get( 'email.sender.name' , $jConfig->getValue( 'fromname' ) );
		$defaultSenderEmail	= $config->get( 'email.sender.email', $jConfig->getValue( 'mailfrom' ) );

		foreach( $mails as $mail )
		{
			// Set the sender's information
			$senderEmail 	= empty( $mail->sender_email ) ? $defaultSenderEmail : $mail->sender_email;
			$senderName		= empty( $mail->sender_name ) ? $defaultSenderName : $mail->sender_name;
			$sender 		= array( $senderEmail , $senderName );

			// Get the mailer
			$mailer 	= JFactory::getMailer();

			// Set the sender's info.
			$mailer->setSender( $sender );

			// Set the reply to info.
			$replyToEmail 	= empty( $mail->replyto_email ) ? $jConfig->getValue( 'mailfrom' ) : $mail->replyto_email;
			$mailer->addReplyTo( $replyToEmail );

			// Set the recipient properties.
			$mailer->addRecipient( $mail->recipient_email );

			// Set mail's subject.
			$title 		= $this->translate( $mail->title , $mail->params );
			$mailer->setSubject( $title );

			$output 	= $this->getEmailContents( $mail );

			if( $mail->html )
			{
				$mailer->isHtml( true );
			}

			// @debug
			// echo $output;exit;

			// Set the body output.
			$mailer->setBody( $output );

			// @TODO: support attachments in the future.
			//$this->mailer->addAttachment();

			// Try to send the mail.
			$state 	= $mailer->send();

			// The mail might not be from the queue.
			if( !is_null( $mail->id ) )
			{
				// Set the state for this mail.
				$mail->state 		= $state;
				$mail->response		= JText::_( 'COM_EASYSOCIAL_MAILER_MAIL_SENT_SUCCESSFULLY' );

				// If there's an error, we want to know what went wrong
				if( !$state )
				{
					$mail->response	= JText::_( 'COM_EASYSOCIAL_MAILER_UNABLE_TO_SEND_EMAIL' );
				}
				$mail->store();
			}
		}

		return true;
	}

	/**
	 * Resolves a given namespace
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function resolve( $path )
	{
		$config 	= Foundry::config();

		// Explode the parts as we need to get the location.
		$parts 		= explode( '/' , $path );

		// Get the location
		$location 	= array_shift( $parts );

		// Build the new path.
		// $type 		= $config->get( '')
		// $path 		= $location . $config->get
		// dump( $parts );
		// dump( $path );
	}

	/**
	 * Retrieve contents from the mail template or the `content` column from the #__social_mailer table.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTableMailer	The mailer object.
	 * @return	string				The email content.
	 */
	public function getContents( &$mail )
	{
		// Process content from template files.
		$theme		= Foundry::themes();
		$content	= $mail->content;

		// Get the params
		$obj 		= Foundry::json()->decode( $mail->params );

		foreach( $obj as $key => $value )
		{
			$theme->set( $key , $value );
		}

		// If content is empty, and mail template is set, we need to fetch the mail template.
		if( !$content && $mail->template )
		{
			// Build the proper namespace to the template file.
			$type 		= $mail->html ? 'html' : 'text';
			$parts 		= explode( '/' , $mail->template );

			// Get the location
			$location 	= array_shift( $parts );

			$base	= '';

			if( $location == 'apps' || $location == 'fields' )
			{
				$group = array_shift( $parts );
				$element = array_shift( $parts );

				if( $location == 'apps' )
				{
					Foundry::language()->loadApp( $group, $element );
				}

				if( $location == 'fields' )
				{
					Foundry::language()->loadField( $group, $element );
				}

				$base = $location . '/' . $group . '/' . $element;
			}

			if( $location == 'site' || $location == 'admin' )
			{
				$base = $location;
			}

			$namespace 	= $base . '/emails/' . $type . '/' . implode( '/' , $parts );

			$content 	= $theme->output( $namespace );
		}

		// Try to process parameters like %SOME_VARS% for the content.
		// $content 	= $this->translate( $content , $mail->params );

		return $content;
	}

	/**
	 * Executes when the cron executes
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function cron( $limit = 20 )
	{
		// Get our own configuration library.
		$config 	= Foundry::config();

		// Get a list of mails that needs to be processed.
		$model 		= Foundry::model( 'Mailer' );

		$mails 		= $model->setLimit( $limit )->getItems( array( 'state' => SOCIAL_MAILER_PENDING ) );

		// If there's nothing to process at all, skip this
		if( !$mails )
		{
			return false;
		}

		// Send all the mails out now
		$state 	= $this->send( $mails );

		return $state;
	}
}


/**
 * Mailer data object. Used when creating a mail object.
 *
 * @since	1.0
 * @access	public
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialMailerData extends JObject
{
	/**
	 * The sender's name
	 * @var	string
	 */
	public $sender_name		= null;

	/**
	 * The sender's email address.
	 * @var	string
	 */
	public $sender_email	= null;

	/**
	 * The reply to email address.
	 * @var string
	 */
	public $replyto_email	= null;

	/**
	 * The recipients name
	 * @var	string
	 */
	public $recipient_name	= null;

	/**
	 * The recipients email address
	 * @var	string
	 */
	public $recipient_email	= null;

	/**
	 * The title of the mail object.
	 * @var	string
	 */
	public $title			= null;

	/**
	 * The content of the mail object. (Optional if $template is supplied)
	 * @var	string
	 */
	public $content 		= null;

	/**
	 * The name of the template file. (Optional if $content is supplied)
	 * @var	string
	 */
	public $template		= null;

	/**
	 * The raw SocialRegistry data.
	 * @var	string
	 */
	public $params			= null;

	/**
	 * Determines if the email should be sent in html format.
	 * @var	bool
	 */
	public $html 			= null;

	/**
	 * Determines the priority of the email. (4 - Critical , 3 - High , 2 - Normal , 1 - Low )
	 * @var	int
	 */
	public $priority 		= null;

	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 */
	public function __construct()
	{
		// Get config.
		$config 				= Foundry::config();


		// Initialize default values for sender.
		$this->sender_name		= $config->get( 'email.sender.name' );
		$this->sender_email 	= $config->get( 'email.sender.email' );
		$this->replyto_email	= $config->get( 'email.replyto' );
	}

	public function setRecipient( $name , $email )
	{
		$this->recipient_name	= $name;
		$this->recipient_email	= $email;
	}

	public function setTitle( $title )
	{
		$this->title 	= $title;
	}

	public function setTemplate( $templateName , $params = array() , $html = null )
	{
		$this->template 	= $templateName;

		$this->setParams( $params );

		$this->setFormat( $html );
	}

	public function setParams( $params = array() )
	{
		if( is_object( $params ) && $params instanceof SocialRegistry )
		{
			$params		= $params->toString();
		}

		// Convert params to json string
		if( is_object( $params ) || is_array( $params ) )
		{
			// Encode parameters to get the JSON string.
			$json 		= Foundry::json();
			$params 	= $json->encode( $params );
		}

		$this->params	= $params;
	}

	public function setContent( $content , $params = array() , $html = null )
	{
		$this->content 	= $content;

		$this->setParams( $params );

		$this->setFormat( $html );
	}

	public function setFormat( $html = null )
	{
		// If it's not set, check the configuration.
		$this->html 	= $html;

		if( is_null( $html ) )
		{
			$config 		= Foundry::config();
			$this->html 	= $config->get( 'email.html' ) ? 1 : 0;
		}
	}

	public function setPriority( $priority )
	{
		$this->priority = $priority;
	}

	public function toArray()
	{
		$properties 	= get_object_vars( $this );

		return $properties;
	}
}
