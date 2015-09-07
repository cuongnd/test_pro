<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/9/2015
 * Time: 11:56 AM
 */
defined('_JEXEC') or die;
class LanguageController extends JControllerLegacy
{
    protected $default_view = 'languages';
    public function display($cachable = false, $urlparams = false)
    {
        $view = $this->input->get ( 'view', 'languages' );
        $layout = $this->input->get ( 'layout', 'default' );
        parent::display();
        return $this;
    }
}