<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_products
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Methods supporting a list of component records.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_products
 * @since       1.6
 */
class ProductsModelInventories extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array  An optional associative array of configuration settings.
     * @see     JController
     * @since   1.6
     */
    protected $context = 'extensions';
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'title', 'a.name',
                'domain', 'a.domain',
                'name', 'a.name',
                'checked_out', 'a.checked_out',
                'checked_out_time', 'a.checked_out_time',
                'state', 'a.state',
                'enabled', 'a.enabled',
                'ordering', 'a.ordering',
            );
        }

        parent::__construct($config);
    }



    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string $ordering An optional ordering field.
     * @param   string $direction An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null)
    {
        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);


        $state = $this->getUserStateFromRequest($this->context . '.filter.enabled', 'filter_enabled', '', 'string');
        $this->setState('filter.enabled', $state);
        $website_id = $this->getUserStateFromRequest($this->context . '.filter.website_id', 'filter_website_id', '', 'int');
        $this->setState('filter.website_id', $website_id);


        // Load the parameters.
        $params = JComponentHelper::getParams('com_products');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('a.id', 'asc');
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseQuery
     */
    protected function getListQuery()
    {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $app=JFactory::getApplication();
        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select',
                'a.id,a.name,a.type,a.element,a.published,a.folder,a.enabled,a.access,website.name AS website_name'
            )
        )
            ->from($db->quoteName('#__extensions') . ' AS a')
            ->leftJoin('#__website AS website ON website.id=a.website_id')
            ->group('a.id')
        ;
        $website_id=$this->getState('filter.website_id');
        if($website_id)
        {
            $query->where('a.website_id='.(int)$website_id);
        }
        $element_type=$this->getState('filter.element_type');
        if($element_type)
        {
            $query->where('a.type='.$query->q($element_type));
        }
        // Join over the users for the checked out user.
        // Add the list ordering clause.
        $query->order($db->escape($this->getState('list.ordering', 'a.id')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        echo $query->dump();
        return $query;
    }
    public function getItems()
    {
        $items= parent::getItems(); // TODO: Change the autogenerated stub
        return $items;
    }
}
