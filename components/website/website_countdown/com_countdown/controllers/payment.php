<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * config controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 * @since       1.6
 */
class CountdownControllerPayment extends JControllerLegacy
{
    public function sms_payment(){
// 1. Nhan du lieu request tu iNET gui qua
        $app=JFactory::getApplication();
        $input=$app->input;
        $code               = $input->getString("code",'');            // Ma chinh
        $subCode            = $input->getString("subCode",'');         // Ma phu
        $mobile             = $input->getString("mobile",'');          // So dien thoai +84
        $serviceNumber      = $input->getString("serviceNumber",'');   // Dau so 8x85
        $info               = $input->getString("info",'');            // Noi dung tin nhan
        $ipremote           = $input->getString("REMOTE_ADDR",'');      // IP server goi qua truyen du lieu

        // 2. Ghi log va kiem tra du lieu
        // Tim file log.txt tai thu muc chua file php xu ly sms nay
        // kiem tra de biet ban da nhan du thong tin ve tin nhan hay chua
        $text = $code." - ".$subCode." - ".$mobile." - ".$serviceNumber." - ".$ipremote." - ".$info;


        // 2. Kiem tra bao mat du lieu tu iNET gui qua
        // Lien he voi iNET de lay IP nay
       /* if($_SERVER['REMOTE_ADDR'] != '210.211.127.168'||$_SERVER['REMOTE_ADDR'] != '210.211.127.172') { // 210.211.127.168
            echo $_SERVER['REMOTE_ADDR'];
            echo "Authen Error";
            exit;
        }*/

        // 3. Xu ly du lieu cua ban tai day
        // ket noi csdl
        // xu ly du lieu


        // 5. Tra ve tin nha gom kieu tin nhan (0) va noi dung tin nhan
        // Xuong dong trong tin nhan su dung \n
        $noidung = "Hi ".$mobile."! \nCam on ban da su dung dich vu";
        echo "0|".$noidung;
        die;
    }


}
