<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('list');

// Import the com_menus helper.
require_once realpath(JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

/**
 * Supports an HTML select list of menus
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @since       1.6
 */
class JFormFieldMenu extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'Menu';

	/**
	 * Method to get the list of menus for the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getOptions()
	{
        require_once JPATH_ROOT.'/administrator/components/com_website/helpers/website.php';
        $listMenuType=JHtml::_('menu.menus');
        $listWebsite=websiteHelperBackend::getForAllWebsiteType();
        $option=array();
        foreach($listWebsite as $website)
        {
            $option[] = JHTML::_('select.option', '<OPTGROUP>',$website->title);
            foreach($listMenuType as $menuType)
            {
                if($menuType->website_id==$website->id)
                {
                    $option[] = JHTML::_('select.option', $menuType->id,$menuType->title);
                }
            }


        }

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $option);

		return $options;
	}
}
