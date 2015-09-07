<?php
/**
 * Bookpro check class
 *
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: tourhelper.php 105 2012-08-30 13:20:09Z quannv $
 */

class GalleryHelper
{
    public function getListType($field,$attribute)
    {
       $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('gallery.type');
        $query->from('#__bookpro_gallery as gallery');
        $query->group('gallery.type');
        $db->setQuery($query);
        $list=$db->loadObjectList();
        $options[]=JHtml::_('select.option',0,'select type');
        if(count($list)){
            for ($i = 0; $i < count($list); $i++) {

                $gallery=$list[$i];
                $options[]=JHtml::_('select.option',$gallery->type,$gallery->type);

            }
        }
        return JHtmlSelect::genericlist($options, $field,$attribute,'value','text',1);
    }

}