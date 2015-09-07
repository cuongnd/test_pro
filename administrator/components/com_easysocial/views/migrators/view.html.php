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

// Include main views file.
Foundry::import( 'admin:/views/views' );

class EasySocialViewMigrators extends EasySocialAdminView
{
	/**
	 * Default user listings page.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	null
	 */
	public function display( $tpl = null )
	{
		// Set page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_MIGRATORS' ) );

				// Set page icon
		$this->setIcon( 'icon-jar jar-imac' );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_MIGRATORS' ) );

		echo parent::display( 'admin/migrators/default' );
	}

	/**
	 * Displays the JomSocial migration form
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function jomsocial()
	{
		// Set page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_HEADING_MIGRATORS_JOMSOCIAL' ) );

		// Set page icon
		$this->setIcon( 'icon-jar jar-imac' );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_MIGRATORS_JOMSOCIAL' ) );

		// Get the migrator library
		$migrator 	= Foundry::migrators( __FUNCTION__ );
		$installed	= $migrator->isInstalled();

		$version 	= $migrator->getVersion();

		if( $installed )
		{
			// Get custom fields from JomSocial
			$jsFields 	= $migrator->getCustomFields();

			// Get our own fields list
			$fieldsModel	= Foundry::model( 'Fields' );
			$fields			= $fieldsModel->getFieldApps( false );

			// lets reset the $fiels so that the index will be the element type.
			if( $fields )
			{
				$tmp = array();
				foreach( $fields as $field )
				{
					$tmp[ $field->element ] = $field;
				}
				$fields = $tmp;
			}

			$fieldsMap = $migrator->getFieldsMap();


			$this->set( 'fields'		, $fields );
			$this->set( 'jsFields'		, $jsFields );
			$this->set( 'fieldsMap'		, $fieldsMap );
		}

		$this->set( 'installed'		, $installed );
		$this->set( 'version'		, $version );

		parent::display( 'admin/migrators/jomsocial' );
	}









}
