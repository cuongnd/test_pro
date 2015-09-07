<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 3/25/2015
 * Time: 1:10 PM
 */

defined('_JEXEC') or die('Restricted access');
class BookProModelReservations extends JModelList {


    protected function getListQuery() {
        $tour='TOUR';
        $id=JFactory::getApplication()->input->get('id', 0);
        $query=null;
        if(is_null($query)){
            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->select('a.*');
            $query->from('#__bookpro_orders as a' );
            $query->where('a.id='.$id. ' AND a.type= "TOUR"' );
            $query->join('LEFT','#__ueb3c_bookpro_orderinfo');
        }

       echo $query;
        return $query;

    }


}

?>