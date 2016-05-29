<?php

/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 28/05/2016
 * Time: 3:24 CH
 */
class productsconfig
{

    const TYPE_PRODCUT = 'product';

    public static function get_list_type(){
        return array(
            static::TYPE_PRODCUT
        );
    }
    public static function get_com_products_config(){
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__ecommerce_config')
            ->where('website_id='.(int)$website->website_id)
            ;
        $db->setQuery($query);
        $config=$db->loadObject();
        $params = new JRegistry;
        $params->loadString($config->params);
        $config->params=$params;
        return $config;
    }

}