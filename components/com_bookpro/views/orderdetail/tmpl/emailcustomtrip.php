<?php
defined('_JEXEC') or die ('Restricted access');
AImporter::model('tourpackage', 'tour', 'orderinfos', 'passengers', 'addons', 'roomtypes', 'packagerate');
AImporter::helper('tour');
JHtml::_('behavior.calendar');
$db = JFactory::getDbo();
$infomodel = new BookProModelOrderinfos ();
$infomodel->init(array(
    'order_id' => $this->order->id
));
$this->orderinfo = $infomodel->getData();

for ($i = 0; $i < count($this->orderinfo); $i++) {
    if ($this->orderinfo [$i]->type = "TOUR") {
        $info = $this->orderinfo [$i];
        unset ($this->orderinfo [$i]);
        break;
    }
}

$this->assignRef("info", $info);


?>

<h2>
    <span><?php echo JText::_("COM_BOOKPRO_BOOKING_INFORMATION") ?> </span>
</h2>
<?php
$app = JFactory::getApplication();
$input = $app->input;
?>
<table cellpadding = "0" cellspacing = "0" border = "0" width = "100%">
            <tbody>
            <tr><td>
                    <table cellpadding = "0" cellspacing = "0" border = "0" width = "100%">
                        <tbody>
                        <tr>
                            <td><span>Dear Mrs 3,</span></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><span>
				We acknowledge the reception of your below customized trip request you have just sent to Asianventure Tours. The expert team of our travel agency  will contact you shortly  to provide you with  the valuable information and proposal. In case,  you does not receive our  reply in time, you are kindly  suggested to resend your request  to our alternative e-mail :<a href = "mailto:asianventuretours@gmail.com" target = "_blank"> asianventuretours@gmail.com</a></span><br>
                                <br></td>
                        </tr>
                        <tr>
                            <td><span>Kind regards<br>
        Customer Support</span>
                            </td>
                        </tr>
                        <tr><td>
                                <table cellpadding = "0" cellspacing = "0" border = "0" width = "100%">
                                    <tbody>
                                    <tr>
                                        <td style = "border-top:#000000 solid 1px" width = "450">&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td></tr>
                        <tr><td style = "font-weight:bold;text-transform:uppercase;font-size:14px;padding-bottom:5px">
                                ASIANVENTURE TOURS Co. LTD - Member of USTOA, PATA, ASTA, SEATA
                            </td></tr>
                        <tr><td>
                                Specialist for Extraordinary Travel in Vietnam, Laos, Cambodia, <br>
                                Thailand, Myanmar and Yunnan ( China)<br>
                                H. office: 4th floor, Song Thao building, 69 Ba Trieu Str., Hanoi, Vietnam<br>
                                Tel:(84-4)39438550 Ext:110 Fax:(84-4)39438552 Hotline:(84)913283657<br>
                                E-mail add:
                                <a href = "mailto:info@asianventure.com" target = "_blank">info@asianventure.com</a>
                                or
                                <a href = "mailto:asianventuretours@gmail.com" target = "_blank">asianventuretours@gmail.com</a>
                                <br>
                                Website:
                                <a href = "http://www.asianventure.com" target = "_blank">www.asianventure.com</a>
                                <br>
                                Company license: 0102011825 International tour operator license: 0314<br>
                                Business hours: <span class = "aBn" data-term = "goog_871672939" tabindex = "0"><span class = "aQJ">8h15  to  17h30    from  Monday</span></span> to <span class = "aBn" data-term = "goog_871672940" tabindex = "0"><span class = "aQJ">Saturday</span></span> Morning<br>
                            </td></tr>
                        <tr><td style = "padding-bottom:15px;padding-top:20px;text-transform:uppercase;font-weight:bold">COPY OF YOUR CUSTOMIZED TRIP REQUEST</td></tr>
                        </tbody>
                    </table>
                </td></tr>

            </tbody>
        </table>


<form id = "tourBookForm" name = "tourBookForm" action = "index.php" method = "post">
    <div class = "mainfarm">



        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <div><a href="index.php?option=com_bookpro&controller=order&task=detail&order_id=<?php echo $this->order->id ?>"><?php echo JText::_('Order Detail') ?></a> </div>


    </div>

    <input type = "hidden" name = "option" value = "com_bookpro"/> <input
        type = "hidden" name = "controller" value = "order"/> <input type = "hidden"
                                                                     name = "task" value = "updateorder"/> <input type = "hidden"
                                                                                                                  name = "order_id" value = "<?php echo $this->order->id; ?>"/> <input
        type = "hidden" name = "<?php echo $this->token ?>" value = "1"/>
</form>



