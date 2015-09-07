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

class SocialDate
{
	private $date = null;

	private static $lang;

	public function __construct( $date = 'now', $withoffset = true )
	{		
		$name = Foundry::getInstance( 'Version' )->getCodename();

		require_once( dirname( __FILE__ ) . '/helpers/' . $name . '.php' );

		$classname	= 'SocialDate'.$name;

		$this->date = new $classname( $date , $withoffset );
	}

	/**
	 * Object initialisation for the class to fetch the appropriate user
	 * object.
	 *
	 * @since	1.0
	 * @access	public
	 * @param   null
	 * @return  SocialStream	The stream object.
	 */
	public static function factory( $date = 'now' , $withoffset = true )
	{
		return new self( $date , $withoffset );
	}

	public function toMySQL( $local = false )
	{
		return $this->date->toMySQL( $local );
	}

	public function toFormat( $format = 'DATE_FORMAT_LC2', $local = true )
	{
		$format 	= JText::_( $format );
	    return $this->date->toFormat( $format, $local );
	}

	/**
	 * Returns the lapsed time since NOW
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	string	The lapsed time.
	 */
	public function toLapsed()
	{
		// Load front end language strings as well since this lib requires it.
		Foundry::language()->load( 'com_easysocial' , JPATH_ROOT );

		$now 		= Foundry::date();
		$time		= $now->date->toUnix( true ) - $this->date->toUnix( true );

	    $tokens = array (
					        31536000 	=> 'COM_EASYSOCIAL_LAPSED_YEARS_COUNT',
					        2592000 	=> 'COM_EASYSOCIAL_LAPSED_MONTHS_COUNT',
					        604800 		=> 'COM_EASYSOCIAL_LAPSED_WEEKS_COUNT',
					        86400 		=> 'COM_EASYSOCIAL_LAPSED_DAYS_COUNT',
					        3600 		=> 'COM_EASYSOCIAL_LAPSED_HOURS_COUNT',
					        60 			=> 'COM_EASYSOCIAL_LAPSED_MINUTES_COUNT',
					        1 			=> 'COM_EASYSOCIAL_LAPSED_SECONDS_COUNT'
	    				);

		if( $time == 0 )
		{
			return JText::_( 'COM_EASYSOCIAL_LAPSED_NOW' );
		}

	    foreach( $tokens as $unit => $key )
		{
			if ($time < $unit)
			{
				continue;
			}

			$units	= floor( $time / $unit );

			$text 	= Foundry::string()->computeNoun( $key , $units );
			$text 	= JText::sprintf( $text , $units );

			return $text;
	    }

	}

	public function toElapsed($allowFuture=true)
	{
		// Get time difference
		$now = new SocialDate();

		$distanceMillis = ($now->date->toUnix(true) - $this->date->toUnix(true)) * 1000;

		if (!isset(self::$lang)) {

			// Load SocialDateElapsed class
			require_once(dirname(__FILE__) . '/elapsed.php');

			// Use extended language class if possible
			$langTag   = JFactory::getLanguage()->getTag();
			$langFile  = dirname(__FILE__) . '/elapsed/' . $langTag . '.php';
			$langClass = 'SocialDateElapsed_' . str_replace('-', '_', $langTag);

			// If the language file exist,
			if (JFile::exists($langFile)) {
				// load it.
				require_once($langFile);
			}

			// If the language class exist,
			if (class_exists($langClass)) {
				// create an instance from language class.
				self::$lang = new $langClass();
			} else {
				// else create an instance from base class.
				self::$lang = new SocialDateElapsed();
			}
		}

		$prefix = self::$lang->prefixAgo;
		$suffix = self::$lang->suffixAgo;

		if ($allowFuture) {
			if ($distanceMillis < 0) {
				$prefix = self::$lang->prefixFromNow;
				$suffix = self::$lang->suffixFromNow;
			}
		}

		$seconds = abs($distanceMillis) / 1000;
		$minutes = $seconds / 60;
		$hours   = $minutes / 60;
		$days    = $hours / 24;
		$years   = $days / 365;

		$words = null;

		    if ($seconds <  45) { $words = $this->substitute("seconds",   $seconds, $distanceMillis); }
		elseif ($seconds <  90) { $words = $this->substitute("minute" ,          1, $distanceMillis); }
		elseif ($minutes <  45) { $words = $this->substitute("minutes",   $minutes, $distanceMillis); }
		elseif ($minutes <  90) { $words = $this->substitute("hour"   ,          1, $distanceMillis); }
		elseif ($hours   <  24) { $words = $this->substitute("hours"  ,     $hours, $distanceMillis); }
		elseif ($hours   <  42) { $words = $this->substitute("day"    ,          1, $distanceMillis); }
		elseif ($days    <  30) { $words = $this->substitute("days"   ,      $days, $distanceMillis); }
		elseif ($days    <  45) { $words = $this->substitute("month"  ,          1, $distanceMillis); }
		elseif ($days    < 365) { $words = $this->substitute("months" , $days / 30, $distanceMillis); }
		elseif ($years   < 1.5) { $words = $this->substitute("year"   ,          1, $distanceMillis); }
		else                    { $words = $this->substitute("years"  ,     $years, $distanceMillis); };

		$separator = self::$lang->wordSeparator();

		return trim(implode($separator, array($prefix, $words, $suffix)));
	}

	private function substitute($func, $number, $distanceMillis) {
		$number = round($number);
		$string = self::$lang->$func($number, $distanceMillis);
		$value = isset(self::$lang->numbers[$number]) ? self::$lang->numbers[$number] : $number;
		return preg_replace('/%d/i', $value, $string);
	}

	public function __call( $method , $args )
	{
		return call_user_func_array( array( $this->date , $method ) , $args );
	}
}
