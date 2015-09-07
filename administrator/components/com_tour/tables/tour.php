<?php
defined ( '_JEXEC' ) or die ();
class TourTableTour extends JTable {
    public function __construct(&$db) {
        parent::__construct ( '#__skandal_tour_details', 'id', $db );
    }
    public function bind($array, $ignore = '') {//ràng buộc dữ liệu trước khi nó được lưu và csdl
        return parent::bind ( $array, $ignore );
    }
    public function store($updateNulls = false) {//lưu trữ bà ghi dữ liệu khi nhat nhấn nút submit
        return parent::store ( $updateNulls );
    }
    public function publish($pks = null, $state = 1, $userId = 0)
    {
        $k = $this->_tbl_key;
        JArrayHelper::toInteger($pks);
        $state = (int) $state;
        if (empty($pks))
        {
            if ($this->$k)
            {
                $pks = array($this->$k);
            }
            else
            {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
                return false;
            }
        }
        $where = $k . '=' . implode(' OR ' . $k . '=', $pks);
        $query = $this->_db->getQuery(true)
            ->update($this->_db->quoteName($this->_tbl))
            ->set($this->_db->quoteName('state') . ' = ' . (int) $state)
            ->where($where);
        $this->_db->setQuery($query);
        try
        {
            $this->_db->execute();
        }
        catch (RuntimeException $e)
        {
            $this->setError($e->getMessage());
            return false;
        }
        if (in_array($this->$k, $pks))
        {
            $this->state = $state;
        }
        $this->setError('');
        return true;
    }
}