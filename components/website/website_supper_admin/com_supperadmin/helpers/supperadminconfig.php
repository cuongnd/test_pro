<?php

/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 28/05/2016
 * Time: 3:24 CH
 */
class supperadminconfig
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
    public static function get_table_by_view($view){
        $list_table=array(
            '#__website'=>'websites,website',
            '#__domain_website'=>'domains,domain',
            '#__usergroups'=>'usergroups,usergroup',
            '#__users'=>'users,user',
            '#__viewlevels'=>'levels,level',
        );
        foreach($list_table as $table=>$views)
        {
            $views=explode(',',$views);
            foreach($views as $item_view)
            {
                if($item_view==$view)
                {
                    return $table;
                }
            }
        }
        return '';
    }

}