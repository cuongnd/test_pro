<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 7/22/14
 * Time: 10:28 AM
 */
class messageHelper
{
    //send email to customer
    function sendemail($customer_id,$message_id,$lastMessage=0)
    {
        $user=JFactory::getUser();
        AImporter::model('customer');
        $modelCustomer=new BookProModelCustomer();
        $customer=$modelCustomer->getCustomerByUserIdSystem($customer_id);
        $config = JFactory::getConfig();
        $data['fromname'] = $config->get('fromname');
        $data['mailfrom'] = $config->get('mailfrom');
        $mail=JFactory::getMailer();
        AImporter::model('message');
        $modelMessage=new BookProModelMessage();
        AImporter::model('message');
        $message=$modelMessage->getItem((int)$lastMessage);
        $data['subject']="you have new message [{$customer_id}-{$user->id}-{$message_id}]";
        $customer->email='cuong@ibookingonline.com';
        $body=$message->message;
        $body.="\n";
        $body.='<a href="'.JUri::base().'index.php?option=com_bookpro&view=message&parent_id='.$message_id.'&layout=edit">phease click here</a>';
        $data['body']=$body;
        if(!$mail->sendMail($data['mailfrom'], $data['fromname'], $customer->email, $data['subject'], $data['body'], 1))
        {
        }

    }
    //end send email to
}