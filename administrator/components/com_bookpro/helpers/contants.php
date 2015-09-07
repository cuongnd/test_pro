<?php 
/******
* You may use and/or modify this script as long as you:
* 1. Keep my name & webpage mentioned
* 2. Don't use it for commercial purposes
*
* If you want to use this script without complying to the rules above, please contact me first at: marty@excudo.net
* 
* Author: Martijn Korse
* Website: http://devshed.excudo.net
*
* Date:  27-06-2008
***/


/**
 * Constant Array Class.
 *
 * PHP doesn't allow an array to be assigned to a constant; this is not possible:
 *    define("SOME_CONSTANT", array("some", "constant"));
 * This class simulates the idea of constant arrays and makes this example possible by means of this class.
 * In order to achieve this, the singleton pattern is used in combination with checks if certain values already exist
 *
 */
class ConstantArray
{
	/**
	 * Singleton instance
	 *
	 * @var ConstantArray
	 */
	protected static $_instance = null;

	/**
	 * This array is used to store all the 'constant arrays'
	 *
	 * @var array
	 */
	protected $_storage = array();

	/**
	 * Singleton pattern implementation makes "new" unavailable
	 *
	 * @return void
	 */
	private function __construct()
	{}

	/**
	 * Singleton pattern implementation makes "clone" unavailable
	 *
	 * @return void
	 */
	private function __clone()
	{}

	/**
	 * Returns an instance of ConstantArray
	 *
	 * Singleton pattern implementation
	 *
	 * @return ConstantArray
	 */
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Provides the same functionality as PHP's define() function,
	 * except that it allows to set arrays
	 *
	 * @param	string	$key
	 * @param	mixed	$array
	 *
	 * @return	void
	 */
	public static function define($key, $array)
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}

		if (isset(self::$_instance->_storage[$key]))
			trigger_error("cannot redefine <i>".$key."</i>", E_USER_ERROR);
		else
		{
			if (preg_match_all("/\[(.*)\]/U", $key, $matches))
			{
				$baseKey = substr($key, 0, strpos($key, "["));
				if (!isset(self::$_instance->_storage[$baseKey]))
				{
					$this->define($baseKey, array());
				}
				self::buildArray(self::$_instance->_storage[$baseKey], $matches[1], $array);
			}
			else
			{
				self::$_instance->_storage[$key] = $array;
			}
		}
	}
	/**
	 * Provides the same functionality as PHP's constant() function.
	 *
	 * There is one difference however:
	 * when an non-existent constant is used, an empty string is returned instead of the name of the constant
	 *
	 * @param	string	$key	name of the constant (array) we want to retrieve
	 * 
	 * @return	mixed	The value of requested constant.
	 * 			Or an empty string when the requested constant doesn't exist
	 */
	public static function constant($key)
	{
		if (preg_match_all("/\[(.*)\]/U", $key, $matches))
		{
			$baseKey = substr($key, 0, strpos($key, "["));
			if (!isset(self::$_instance->_storage[$baseKey]))
			{
				// we already know it doesn't exist, but this will generate the error we want to communicate and stop the execution in this function
				trigger_error("Use of undefined constant ".$baseKey , E_USER_NOTICE);
				return '';
			}
			else
			{
				$tempArr = self::$_instance->_storage[$baseKey];
				$keyString = "";
				foreach ($matches[1] AS $tempKey)
				{
					$keyString .= "[".$tempKey."]";
					if (!is_array($tempArr) || !isset($tempArr[$tempKey]))
					{
						trigger_error("Use of undefined constant ".$baseKey.$keyString , E_USER_NOTICE);
						return '';
					}
					$tempArr = $tempArr[$tempKey];
				}
				return $tempArr;
			}
		}
		elseif (!isset(self::$_instance->_storage[$key]))
		{
			trigger_error("Use of undefined constant ".$key , E_USER_NOTICE);
		}
		else
		{
			return self::$_instance->_storage[$key];
		}
	}

	/**
	 * Used only by the define method.
	 * This function constructs a nested array in an existing one.
	 * As it does so it creates empty arrays when a nested array doesn't exist or just takes the existing one as it
	 * goes deeper into the array it's creating.
	 * The last parameter will set as the value of the final key. But before it is set, first a check is done if the
	 * key doesn't already exist.
	 *
	 * @param	array	$arr		this array will always refer to a nested index of the protected var$this->_storage
	 * @param	array	$matches	A flat array of indexes.
	 * 								Every succeeding value is another nested index of the previous one
	 * @param	mixed	$value		the value that we want to set to the final index
	 *
	 * @return	void
	 *
	 * example;
	 * --------
	 * $arr		:: $this->_storage['var']
	 * $matches	:: array(0=>1, 1=>blaat, 2=>5
	 * $value	:: this is some text
	 * result	>> $this->_storage['var'][1][blaat][2] => "this is some text"
	 */
	private function buildArray(&$arr, $matches, $value)
	{
		$index = array_shift($matches);
		if (count($matches) > 0)
		{
			if (!isset($arr[$index]))
				$arr[$index] = array();
			self::buildArray($arr[$index], $matches, $value);
		}
		else
		{
			if (isset($arr[$index]))
				trigger_error("Constant ".$index." already defined", E_USER_NOTICE);
			$arr[$index] = $value;
		}
	}

	/**
	 * Magic function that allows us to directly access the defined arrays
	 *
	 * @param	string	$key
	 * @return	mixed
	 *
	 */
	public function __get($key)
	{
		// we don't check if the value exists. if it doesn't we just let the php error occur,
		// because that is exactly what we want in that case
		return $this->_storage[$key];
	}
}
?>