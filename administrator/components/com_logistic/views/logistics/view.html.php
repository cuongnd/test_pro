<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/9/2015
 * Time: 11:57 AM
 */
defined('_JEXEC') or die;
class LogisticViewLogistics extends JViewLegacy
{
    protected $items;
    public function display($tpl=null){
        $this->items         = $this->get('Items');
        //$this->addToolbar();  //các nút thêm xóa sửa(hàm addToolbar ở bên dưới)
        parent::display($tpl);
    }

}