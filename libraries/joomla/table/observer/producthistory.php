<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Table class supporting modified pre-order tree traversal behavior.
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @link        http://docs.joomla.org/JTableObserver
 * @since       3.2
 */
class JTableObserverProducthistory extends JTableObserver
{
	/**
	 * Helper object for storing and deleting version history information associated with this table observer
	 *
	 * @var    JHelperproducthistory
	 * @since  3.2
	 */
	protected $producthistoryHelper;

	/**
	 * The pattern for this table's TypeAlias
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $typeAliasPattern = null;

	/**
	 * Not public, so marking private and deprecated, but needed internally in parseTypeAlias for
	 * PHP < 5.4.0 as it's not passing context $this to closure function.
	 *
	 * @var         JTableObserverproducthistory
	 * @since       3.2
	 * @deprecated  Never use this
	 * @private
	 */
	public static $_myTableForPregreplaceOnly;

	/**
	 * Creates the associated observer instance and attaches it to the $observableObject
	 * Creates the associated content history helper class instance
	 * $typeAlias can be of the form "{variableName}.type", automatically replacing {variableName} with table-instance variables variableName
	 *
	 * @param   JObservableInterface  $observableObject  The subject object to be observed
	 * @param   array                 $params            ( 'typeAlias' => $typeAlias )
	 *
	 * @return  JTableObserverproducthistory
	 *
	 * @since   3.2
	 */
	public static function createObserver(JObservableInterface $observableObject, $params = array())
	{
		$typeAlias = $params['typeAlias'];

		$observer = new self($observableObject);
        require_once JPATH_ROOT.'/libraries/cms/helper/producthistory.php';
		$observer->producthistoryHelper = new JHelperproducthistory($typeAlias);
		$observer->typeAliasPattern = $typeAlias;

		return $observer;
	}

	/**
	 * Post-processor for $table->store($updateNulls)
	 *
	 * @param   boolean  &$result  The result of the load
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function onAfterStore(&$result)
	{
		if ($result)
		{
			$this->parseTypeAlias();
			$aliasParts = explode('.', $this->producthistoryHelper->typeAlias);

			if (JComponentHelper::getParams($aliasParts[0])->get('save_history', 0))
			{
				$this->producthistoryHelper->store($this->table);
			}
		}
	}

	/**
	 * Pre-processor for $table->delete($pk)
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 * @throws  UnexpectedValueException
	 */
	public function onBeforeDelete($pk)
	{
		$this->parseTypeAlias();
		$aliasParts = explode('.', $this->producthistoryHelper->typeAlias);

		if (JComponentHelper::getParams($aliasParts[0])->get('save_history', 0))
		{
			$this->parseTypeAlias();
			$this->producthistoryHelper->deleteHistory($this->table);
		}
	}

	/**
	 * Internal method
	 * Parses a TypeAlias of the form "{variableName}.type", replacing {variableName} with table-instance variables variableName
	 * Storing result into $this->producthistoryHelper->typeAlias
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	protected function parseTypeAlias()
	{
		// Needed for PHP < 5.4.0 as it's not passing context $this to closure function
		static::$_myTableForPregreplaceOnly = $this->table;

		$this->producthistoryHelper->typeAlias = preg_replace_callback('/{([^}]+)}/',
			function($matches)
			{
				return JTableObserverproducthistory::$_myTableForPregreplaceOnly->{$matches[1]};
			},
			$this->typeAliasPattern
		);
	}
}
