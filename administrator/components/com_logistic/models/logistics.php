<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/9/2015
 * Time: 12:02 PM
 */
class LogisticModelLogistics extends JModelList {
    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from('#__bookpro_logistic as a' );
        return $query;
    }

}