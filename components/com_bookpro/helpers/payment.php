<?php

/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');


class BookProPaymentPlugin extends JPlugin 
{
    /**
     * @var $_element  string  Should always correspond with the plugin's filename, 
     *                         forcing it to be unique 
     */
    var $_element    = '';


    /**
     * Prepares the payment form
     * and returns HTML Form to be displayed to the user
     * generally will have a message saying, 'confirm entries, then click complete order'
     * 
     * Submit button target for onsite payments & return URL for offsite payments should be:
     * index.php?option=com_BookPro&view=billing&task=confirmPayment&orderpayment_type=xxxxxx
     * where xxxxxxx = $_element = the plugin's filename 
     *  
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _prePayment( $data )
    {
        // Process the payment
        
        $vars = new JObject();
        $vars->message = "Preprocessing successful. Double-check your entries.  Then, to complete your order, click Complete Order!";
        
        $html = $this->_getLayout('prepayment', $vars);
        return $html;
    }
    
    /**
     * Processes the payment form
     * and returns HTML to be displayed to the user
     * generally with a success/failed message
     * 
     * IMPORTANT: It is the responsibility of each payment plugin
     * to tell clear the user's cart (if the payment status warrants it) by using:
     * 
     * $this->removeOrderItemsFromCart( $order_id );
     * 
     * @param $data     array       form post data
     * @return string   HTML to display
     */
    function _postPayment( $data )
    {
        // Process the payment
        
        $vars = new JObject();
        $vars->message = "Payment processed successfully.  Hooray!";
        
        $html = $this->_getLayout('postpayment', $vars);
        return $html;
    }
    
    /**
     * Prepares the 'view' tmpl layout
     * when viewing a payment record
     *
     * @param $orderPayment     object       a valid TableOrderPayment object
     * @return string   HTML to display
     */
    function _renderView( $orderPayment )
    {
        // Load the payment from _orderpayments and render its html
        
        $vars = new JObject();
        $vars->full_name        = "";
        $vars->email            = "";
        $vars->payment_method   = $this->_paymentMethods();
        
        $html = $this->_getLayout('view', $vars);
        return $html;
    }
    
    /**
     * Prepares variables for the payment form
     *  
     * @param $data     array       form post data for pre-populating form
     * @return string   HTML to display
     */
    function _renderForm( $data )
    {
        // Render the form for collecting payment info
        
        $vars = new JObject();
        $vars->full_name        = "";
        $vars->email            = "";
        //$vars->payment_method   = $this->_paymentMethods();
        
        $html = $this->_getLayout('form', $vars);
        return $html;
    }
    
    /**
     * Verifies that all the required form fields are completed
     * if any fail verification, set 
     * $object->error = true  
     * $object->message .= '<li>x item failed verification</li>'
     * 
     * @param $submitted_values     array   post data
     * @return obj
     */
    function _verifyForm( $submitted_values )
    {
        $object = new JObject();
        $object->error = false;
        $object->message = '';
        return $object;
    }
    
    /************************************
     * Note to 3pd: 
     * 
     * You shouldn't need to override
     * any of the methods below here
     * 
     ************************************/
    
    /**
     * This method can be executed by a payment plugin after a succesful payment
     * to perform acts such as enabling file downloads, removing items from cart,
     * updating product quantities, etc
     * 
     * @param unknown_type $order_id
     * @return unknown_type
     */
    function setOrderPaymentReceived( $order_id )
    {
       //TODO use this method later to update the order table
    }
    
    /**
     * Given an order_id, will remove the order's items from the user's cart
     * 
     * @param unknown_type $order_id
     * @return unknown_type
     */
    function removeOrderItemsFromCart( $order_id )
    {
	    //TODO Now we clear the total session of the cart. May be this method would fine tune the process
    }
    
    /**
     * Tells extension that this is a payment plugin
     * 
     * @param $element  string      a valid payment plugin element 
     * @return boolean
     */
    function onBookProGetPaymentPlugins( $element )
    {
        $success = false;
        if ($this->_isMe($element)) 
        {
            $success = true;
        }
        return $success;    
    }
    
    function onBookProGetPaymentOptions($element, $order)
    {       
        // Check if this is the right plugin
        if (!$this->_isMe($element)) 
        {
            return null;
        }        
     
        $found = true;
        // if this payment method should be available for this order, return true
        // if not, return false.
        // by default, all enabled payment methods are valid, so return true here,
        // but plugins may override this         
        return $found;
    }

    
    
    /**
     * Wrapper for the internal _renderForm method
     * 
     * @param $element  string      a valid payment plugin element 
     * @param $data     array       form post data
     * @return html
     */
    function onBookProGetPaymentForm( $element, $data )
    {
        if (!$this->_isMe($element)) 
        {
            return null;
        }

        $html = $this->_renderForm( $data );

        return $html;
    }

    /**
     * Wrapper for the internal _verifyForm method
     * 
     * @param $element  string      a valid payment plugin element 
     * @param $data     array       form post data
     * @return html
     */
    function onBookProGetPaymentFormVerify( $element, $data )
    {
        if (!$this->_isMe($element)) 
        {
            return null;
        }

        $html = $this->_verifyForm( $data );

        return $html;
    }
    
    /**
     * Wrapper for the internal _renderView method
     * 
     * @param $element  string      a valid payment plugin element
     * @param $orderPayment  object      a valid TableOrderPayment object
     * @return html
     */
    function onBookProGetPaymentView( $element, $orderPayment )
    {
        if (!$this->_isMe($element)) 
        {
            return null;
        }

        $html = $this->_renderView( $orderPayment );

        return $html;
    }
    
    /**
     * Wrapper for the internal _prePayment method
     * which performs any necessary actions before payment
     *   
     * @param $element  string      a valid payment plugin element 
     * @param $data     array       form post data
     * @return html
     */
    function onBookProPrePayment( $element, $data )
    {
        if (!$this->_isMe($element)) 
        {
            return null;
        }

        $html = $this->_prePayment( $data );

        return $html;
    }

    /**
     * Wrapper for the internal _postPayment method
     * that processes the payment after user submits
     *   
     * @param $element  string      a valid payment plugin element 
     * @param $data     array       form post data
     * @return html
     */
    function onBookProPostPayment( $element, $data )
    {
        if (!$this->_isMe($element)) 
        {
            return null;
        }

        $html = $this->_postPayment( $data );

        return $html;
    }
    function _getLayoutPath($plugin, $group, $layout = 'default')
    {
    	$app = JFactory::getApplication();
    
    	// get the template and default paths for the layout
    	$templatePath = JPATH_SITE.'/templates/'.$app->getTemplate().'/html'.'/plugins/'.$group.'/'.$plugin.'/'.$layout.'.php';
    	$defaultPath = JPATH_SITE.'/plugins/'.$group.'/'.$plugin.'/'.$plugin.'/'.'tmpl'.'/'.$layout.'.php';
    
    	// if the site template has a layout override, use it
    	jimport('joomla.filesystem.file');
    	if (JFile::exists( $templatePath ))
    	{
    		return $templatePath;
    	}
    	else
    	{
    		return $defaultPath;
    	}
    }
    function _isMe( $row )
    {
    	$element = $this->_element;
    
    	$success = false;
    	if (is_object($row) && !empty($row->element) && $row->element == $element )
    	{
    		$success = true;
    	}
    
    	if (is_string($row) && $row == $element ) {
    		$success = true;
    	}
    
    	return $success;
    }
    function _getLayout($layout, $vars = false, $plugin = '', $group = 'bookpro' )
    {
    	if (empty($plugin))
    	{
    		$plugin = $this->_element;
    	}
    
    	ob_start();
    	$layout = $this->_getLayoutPath( $plugin, $group, $layout );
    	include($layout);
    	$html = ob_get_contents();
    	ob_end_clean();
    
    	return $html;
    }
}
