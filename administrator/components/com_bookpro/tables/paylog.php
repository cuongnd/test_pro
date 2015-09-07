<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class TablePayLog extends JTable {

    /**
     * Primary Key
     *
     * @var int
     */
    var $id;
    var $title;
    var $order_id;
    var $ordering;
    var $gateway;
    var $state;
    var $amount;
    var $tx_id;
    var $created;
    var $created_by;

    /**
     * Constructor
     *
     * @param
     *        	object Database connector object
     */
    function __construct(& $db) {
        parent::__construct('#__' . PREFIX . '_paylog', 'id', $db);
    }

    function init() {
        $this->created = NULL;
    }

    public function check() {
        //get user

        if ((int) $this->created_by) {
            $user = JFactory::getUser((int) $this->created_by);
        } else {
            $user = JFactory::getUser();
        }
        $this->created_by = $user->id;

        if (!$this->id) {
            $date = JFactory::getDate();
            $this->created = $date->toSql(); 
        }

        return true;
    }

    public function publish($pks = null, $state = 1, $userId = 0) {
        $k = $this->_tbl_key;

        // Sanitize input.
        JArrayHelper::toInteger($pks);
        $userId = (int) $userId;
        $state = (int) $state;

        // If there are no primary keys set check to see if the instance key is set.
        if (empty($pks)) {
            if ($this->$k) {
                $pks = array($this->$k);
            }
            // Nothing to set publishing state on, return false.
            else {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }

        // Build the WHERE clause for the primary keys.
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);

        // Determine if there is checkin support for the table.
        if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
            $checkin = '';
        } else {
            $checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
        }

        // Update the publishing state for rows with the given primary keys.
        $this->_db->setQuery(
                'UPDATE ' . $this->_db->quoteName($this->_tbl) .
                ' SET ' . $this->_db->quoteName('state') . ' = ' . (int) $state .
                ' WHERE (' . $where . ')' .
                $checkin
        );

        try {
            $this->_db->execute();
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());
            return false;
        }

        // If checkin is supported and all rows were adjusted, check them in.
        if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
            // Checkin the rows.
            foreach ($pks as $pk) {
                $this->checkin($pk);
            }
        }

        // If the JTable instance value is in the list of primary keys that were set, set the instance.
        if (in_array($this->$k, $pks)) {
            $this->state = $state;
        }

        $this->setError('');
        return true;
    }

}