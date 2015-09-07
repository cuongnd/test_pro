<?php
    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: view.html.php  23-06-2012 23:33:14
    **/
    // No direct access to this file
    defined('_JEXEC') or die('Restricted access');
    // import Joomla view library
    jimport('joomla.application.component.view');
    AImporter::model('order','cgroup','orderinfos');
    /**
    * HTML View class for the BookPro Component
    */
    class BookProViewexpediaformpayment extends JViewLegacy
    {
        // Overwriting JViewLegacy display method
        var $config=null;
        function display($tpl = null)
        {

            $this->config=AFactory::getConfig();

            if(!$this->order_id){
                $this->order_id=JRequest::getVar('order_id');
            }
            $orderModel=new BookProModelOrder();
            $orderModel->setId($this->order_id);
            $this->order=$orderModel->getObject();
            $infomodel=new BookProModelOrderinfos();


            $doc=JFactory::getDocument();
            $doc->setTitle("Payment Step");
            $dispatcher    = JDispatcher::getInstance();
            require_once (JPATH_SITE.'/administrator/components/com_bookpro/helpers/plugin.php');
            $payment_plugins = PluginHelper::getPluginsWithEvent( 'onBookproGetPaymentPlugins' );
            $plugins = array();
            if ($payment_plugins)
            {
                foreach ($payment_plugins as $plugin)
                {
                    $results = $dispatcher->trigger( "onBookproGetPaymentOptions", array( $plugin->element, $order ) );
                    if (in_array(true, $results, true))
                    {
                        $plugins[] = $plugin;
                    }
                }
            }
            if (count($plugins) == 1)
            {
                $plugins[0]->checked = true;
                ob_start();
                $this->getPaymentForm( $plugins[0]->element );
                $html = json_decode( ob_get_contents() );
                ob_end_clean();
                $this->assign( 'payment_form_div', $html->msg );
            }
            $this->assign('plugins', $plugins);

            parent::display($tpl);
        }

        function getPaymentForm($element='')
        {

            $values = JRequest::get('post');
            $html = '';
            $text = "";
            $user = JFactory::getUser();
            if (empty($element)) {
                $element = JRequest::getVar( 'payment_element' );
            }
            $results = array();
            $dispatcher	= JDispatcher::getInstance();
            JPluginHelper::importPlugin ('bookpro');

            $results = $dispatcher->trigger( "onBookproGetPaymentForm", array( $element, $values ) );
            for ($i=0; $i<count($results); $i++)
            {
                $result = $results[$i];
                $text .= $result;
            }

            $html = $text;

            // set response array
            $response = array();
            $response['msg'] = $html;

            // encode and echo (need to echo to send back to browser)
            echo json_encode($response);

            return;
        }


    }
