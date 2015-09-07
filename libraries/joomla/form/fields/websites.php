<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('text');

/**
 * Form Field class for the Joomla Platform.
 * Provides and input field for e-mail addresses
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.email.html#input.email
 * @see         JFormRuleEmail
 * @since       11.1
 */
class JFormFieldWebsites extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Websites';

	/**
	 * Method to get the field input markup for e-mail addresses.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
        $supperAdmin=JFactory::isSupperAdmin();
        if(!$supperAdmin)
            return;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__website');
        $query->select('id,title');
        $db->setQuery($query);
        $listWebsite=$db->loadObjectList();
        $query=$db->getQuery(true);
        $query->from('#__domain_website');
        $query->select('id,domain,website_id');
        $db->setQuery($query);
        $listWebsiteDomain=$db->loadObjectList();
        foreach($listWebsiteDomain as $domainWebsite)
        {
            foreach($listWebsite as $key=> $website)
            {
                if($website->id==$domainWebsite->website_id)
                {
                    $listWebsite[$key]->listSite[]=$domainWebsite->domain;
                }
            }
        }
        $options1[] = JHtml::_('select.option', '','Select Website');
        $options1[] = JHtml::_('select.option', '-1','Run for all');
        $options1[] = JHtml::_('select.option', '0','None');
        $options2=array();
        foreach($listWebsite as $key=>$website)
        {
            $title=$listWebsite[$key]->title.'('.implode($website->listSite).')';
            $options2[] = JHtml::_('select.option',$website->id, $title);
        }
        $options=array_merge($options1,$options2);
        $attribute=array();
        $attribute[]=$this->onchange?'onchange="'.$this->onchange.'"':'';
        $attribute=implode(' ',$attribute);

        $html = JHtml::_('select.genericlist', $options, $this->name,$attribute, 'value', 'text', $this->value, $this->id);

        return $html;
	}
}
