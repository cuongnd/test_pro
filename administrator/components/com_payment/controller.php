<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/9/2015
 * Time: 11:56 AM
 */
defined('_JEXEC') or die;
class PaymentController extends JControllerLegacy
{
    protected $default_view = 'payments';
    public function display($cachable = false, $urlparams = false)
    {
        $view = $this->input->get ( 'view', 'payments' );
        $layout = $this->input->get ( 'layout', 'default' );
        parent::display();
        return $this;
    }
}