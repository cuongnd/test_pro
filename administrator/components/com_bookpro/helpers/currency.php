<?php

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: currency.php 16 2012-06-26 12:45:19Z quannv $
 **/
class CurrencyHelper{

    public static function formatprice($value,$config=null,$currency_code=''){
        $config=AFactory::getConfig();
        $thousand=$config->currencySeperator;
        if ($value) {
            $value = number_format($value, 2, '.',$thousand);
            $length = JString::strlen($value);
            if (JString::substr($value, $length - 2) == '00')
                $newval= JString::substr($value, 0, $length - 3);
            elseif (JString::substr($value, $length - 1) == '0')
                $newval= JString::substr($value, 0, $length - 1);
            else
                $newval=$value;
        }
        $symbol=$config->currencySymbol;
        if($currency_code)
        {
            $symbol=$currency_code;
        }
        switch ($config->currencyDisplay){
            case 0:
                // 0 = '00Symb'
                $newval=$newval.$symbol;
                break;
            case 2:
                // 2 = 'Symb00'
                $newval=$symbol.$newval;
                break;
            case 3:
                // 3 = 'Symb 00'
                $newval=$symbol.' '.$newval;
                break;
            case 1:
            default :
                // 1 = '00 Symb'
                $newval=$newval.' '.$symbol;
                break;
        }
        return $value?$newval:JText::_('N/A');
    }
	public static function displayPrice($value,$discount){
		if($discount > 0){
			return '<span class="old_price">'.CurrencyHelper::formatprice($value).'</span>'.
			'<span class="discount_price">'.CurrencyHelper::formatprice($discount).'</span>';
		}else {
			return CurrencyHelper::formatprice($value);
		} 
	}
}