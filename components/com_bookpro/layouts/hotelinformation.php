<?php 
    defined( '_JEXEC' ) or die( 'Restricted access' );
    AImporter::model('orderinfos','hotel','rooms','room');
    AImporter::helper('hotel','date','currency');

    $infomodel=new BookProModelOrderinfos();
    $infomodel->init(array('order_id'=>$displayData->id));

    $a_orderinfo=$infomodel->getData();
    $infos=HotelHelper::getRooms($displayData->id);
   
    $rooms = 0;
    $totaladult = 0;
    $totalchild = 0;
    foreach ($infos as $info) 
    {
        //if($a_orderinfo[$i]->type="HOTEL_ROOM"){
            //$room=$infos[$i];
           // $info->end = HotelHelper::checkOutDate($info->end);
           
            $numberday = DateHelper::getCountDay($info->start, $info->checkout);
            
            $rooms += $info->qty; 
            $hotel=HotelHelper::getHotelbyRoomID($info->obj_id);
            $checkin_date= DateHelper::formatDate($info->start);
            $checkout_date=DateHelper::formatDate($info->checkout);
            $totaladult+=$info->adult;
            $totalchild+=$info->child;

            //break;
        //}

    }
    

?>

<div class="row-fluid">
        <?php $rankstar=JURI::base()."/components/com_bookpro/assets/images/". $hotel->rank.'star.png'; ?>
			<h2><?php echo $hotel->title ?>
				<span><img src="<?php echo $rankstar; ?>"> </span>
	</h2>
    <div class="span6 form-inline">
        <label><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN') ?>:</label>
        <label><b><?php echo $checkin_date; ?></b></label>
    </div>
    <div class="span6 form-inline">
        <label>
            <?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT') ?>:
        </label>
        <label><b><?php echo $checkout_date; ?></b></label>
    </div>
</div>
<div class="row-fluid">
    <div class="span6 form-inline">
        <label>
            <b><?php echo JText::_('COM_BOOKPRO_NIGHT_NUMBER') ?>:</b>
        </label>
        <label><?php echo $numberday; ?></label>
    </div>
    <div class="span6 form-inline">
        <label>
            <b><?php echo JText::_('COM_BOOKPRO_ROOMS') ?>:</b>
        </label>
        <label><?php echo $rooms; ?></label>
    </div>
    
    <div class="span6 form-inline">
        <label>
            <b><?php echo JText::_('COM_BOOKPRO_TOTAL_ADULT') ?>:</b>
        </label>
        <label><?php echo $totaladult ?></label>
    </div>
    <div class="span6 form-inline">
        <label>
            <b><?php echo JText::_('COM_BOOKPRO_TOTAL_CHILD') ?>:</b>
        </label>
        <label><?php echo $totalchild ?></label>
    </div>
    
</div>