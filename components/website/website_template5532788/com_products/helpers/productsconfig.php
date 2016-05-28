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

}