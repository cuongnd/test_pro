<?php

/**
 * Popup element to select customer.
 * 
 * @version		$Id: customer.php 15 2012-06-26 12:42:47Z quannv $
 * @package		ARTIO Booking
 * @subpackage  elements
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal', 'a.modal');

class JElementCustomer extends JElement
{

    /**
     * Display button to open popup window. 
     * 
     * @param int $value
     */
    function fetchElement($value)
    {
        $customerModel = new BookingModelCustomer();
        $customerModel->setId($value);
        $customer = $customerModel->getObject();
        
        $html = '<div style="float: left; height: 19px; padding-top: 3px;">';
        $html .= '<input style="color: #000000;" size="60" type="text" id="customer_name" value="' . BookingHelper::formatName($customer, true) . '" disabled="disabled" />';
        $html .= '</div>';
        $html .= '<div class="button2-left">';
        $html .= '<div class="blank">';
        $html .= '<a class="modal" title="' . JText::_('Select a Customer') . '"  href="' . ARoute::browse(CONTROLLER_CUSTOMER, true) . '" rel="{handler: \'iframe\', size: {x: 800, y: 600}}">' . JText::_('Select') . '</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<input type="hidden" id="customer_id" name="customer" value="' . $value . '" />';
        return $html;
    }
}

?>