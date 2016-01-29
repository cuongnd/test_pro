<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Utilities
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * JArrayHelper is an array utility class for doing all sorts of odds and ends with arrays.
 *
 * @package     Joomla.Platform
 * @subpackage  Utilities
 * @since       11.1
 */
abstract class JObjectHelper
{
    /*
     * item{
     *  item1{
     *      a_item=>3
     *    },
     * item2:{
     *      b_item=5
     * }
     * }
     * =>return_item{
     *  'a_item'=3,
     *  'b_item'=5
     * }
     */

    public function toObject($objects,&$return_object)
    {
        if(!is_object($return_object))
        {
            $return_object=new stdClass();
        }

        if(is_object($objects))
        {

            foreach($objects as $key=>$value)
            {
                if(is_object($value))
                {
                    JObjectHelper::toObject($value,$return_object);
                }else{
                    $return_object->$key=$value;
                }
            }
        }
    }
    /*
     * item{
     *  item1{
     *      a_item=>3
     *    },
     * item2:{
     *      b_item=5
     * }
     * }
     * =>return_item{
     *  'item1.a_item'=3,
     *  'item2.b_item'=5
     * }
     */
    public function parse_object($objects,&$return_object,$path_key='')
    {
        if(!is_object($return_object))
        {
            $return_object=new stdClass();
        }
        if(is_object($objects)||is_array($objects))
        {
            foreach($objects as $key=>$value)
            {
                if(is_object($value)||is_array($value))
                {
                    JObjectHelper::parse_object($value,$return_object,$key);
                }else{
                    $path_key1=$path_key!=''?$path_key.'.'.$key:$key;
                    $return_object->$path_key1=$value;
                }
            }
        }
    }
}
