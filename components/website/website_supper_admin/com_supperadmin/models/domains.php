<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Methods supporting a list of component records.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.6
 */
class supperadminModeldomains extends JModelList
{
    /**
     * Constructor.
     *
     * @param   array  An optional associative array of configuration settings.
     * @see     JController
     * @since   1.6
     */
    protected $context = 'domains';
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
        $params = JComponentHelper::getParams('com_supperadmin');
        $this->setState('params', $params);

        // List state information.
        parent::populateState('website.name', 'asc');
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
                'a.*'
            )
        )
            ->from($db->quoteName('#__domain_website') . ' AS a')
            ->leftJoin('#__website AS website ON website.id=a.website_id')
            ->select('website.name AS website_name')
            ->group('a.id')
        ;

        // Filter by search in name or id
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = ' . (int) substr($search, 3));
            }else{
                $query->where('(a.domain LIKE '.$query->q("%$search%").' OR website.name LIKE '.$query->q("%$search%").' OR website.introtext LIKE '.$query->q("%$search%").' ) ');
            }
        }



        // Join over the users for the checked out user.

        // Add the list ordering clause.
        $query->order($db->escape($this->getState('list.ordering', 'website.name')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        //echo $query->dump();
        return $query;
    }
}
