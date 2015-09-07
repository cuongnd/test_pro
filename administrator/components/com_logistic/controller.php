<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/9/2015
 * Time: 11:56 AM
 */
defined('_JEXEC') or die;
class logisticController extends JControllerLegacy
{
    protected $default_view = 'logistics';
    public function display($cachable = false, $urlparams = false)
    {
        $view = $this->input->get ( 'view', 'logistics' );
        $layout = $this->input->get ( 'layout', 'default' );
        parent::display();
        return $this;
    }
}