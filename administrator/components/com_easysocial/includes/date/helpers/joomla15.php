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

jimport( 'joomla.utilities.date' );

class SocialDateJoomla15 extends JDate 
{
	public function __construct($date = 'now', $withoffset = true)
	{
		parent::__construct( $date );
		
	    $offset = null;
	    
	    if( $withoffset )
	    {
	        $offset = self::getOffSet();
	        $this->setOffset( $offset );
	    }
	    
	}
	
	function getOffSet()
	{
		$app	=& JFactory::getApplication();
		$my		=& JFactory::getUser();
		
		// Timezone
		$tz		= '';
		
		// Daylight saving mode
		$config	= Foundry::config();

		$dst 	= $config->get( 'system.daylight_saving' );

		// @rule: If this is a logged in user, the time should be based on their offset.
		if( $my->id != 0 )
 		{
 			$tz		= $my->getParam( 'timezone' ) + $dst;
 		}

 		// @rule: If user did not set any timezone, we use the server's timezone
 		if( !$tz )
 		{
 			$tz		= $app->getCfg( 'offset' ) + $dst;
		}
		return $tz;
	}
}
