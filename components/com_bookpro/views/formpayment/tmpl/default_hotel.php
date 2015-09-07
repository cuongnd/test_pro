<?php 
    defined( '_JEXEC' ) or die( 'Restricted access' );
    AImporter::model('tourpackage','tour','orderinfos','passengers','hotel','rooms','room');
    AImporter::helper('tour','hotel','date','currency');
    AImporter::css('tour');

    $infomodel=new BookProModelOrderinfos();
    $infomodel->init(array('order_id'=>$this->order->id));
    $this->orderinfo=$infomodel->getList();
    for ($i = 0; $i < count($this->orderinfo); $i++) 
    {
        if($this->orderinfo[$i]->type="HOTEL_ROOM"){
            $room=$this->orderinfo[$i];
            unset($this->orderinfo[$i]);
            break;
        }

    }
    $infos=HotelHelper::getRooms($this->order->id);

    $roomModel=new BookProModelRoom();
    $roomModel->setId($room->obj_id);
    $this->room=$roomModel->getObject();

    $hotelModel=new BookProModelHotel();
    $hotelModel->setId($this->room->hotel_id);
    $this->hotel=$hotelModel->getObject();

    //$passengersModel= new BookProModelPassengers();
    //$passengersModel->init(array('order_id'=>$this->order->id));
    //$passengers=$passengersModel->getData();

?>

<div class="row-fluid">
    <div class="span6">
        <h3><?php echo JText::_('Order Number') ?>:<?php echo $this->order->order_number; ?></h3>
        <?php

            $layout = new JLayoutFile('hotelinformation', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
            $html = $layout->render($this->order);
            echo $html;
        ?>
    </div>
    <div class="span6">
        
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo JText::_('Room Name') ?></th>
                    <th><?php echo JText::_('Checkin') ?></th>
                    <th><?php echo JText::_('Checkout') ?></th>
                    <th><?php echo JText::_('Number room'); ?></th>
                    <th><?php echo JText::_('Adults'); ?></th>
                    <th><?php echo JText::_('Children'); ?></th>
                    <th><?php echo JText::_('Total Price') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($infos)){ 
                        foreach ($infos as $info){
							
                            $numberday = DateHelper::getCountDay($info->start, $info->checkout);
                            $room = $info->room;

                            $start = new JDate($info->start);
                           
                            ?>
                            <tr>
                                <td><?php echo $room->title; ?></td>
                                <td>
                                    <?php echo DateHelper::formatDate($info->start); ?>
                                </td>
                                <td>
                                    <?php echo DateHelper::formatDate($info->checkout); ?>
                                </td>	
                                <td>
                                    <?php echo $info->qty; ?>
                                </td>					
                                <td>
                                    <?php echo $info->adult; ?>
                                </td>	
                                <td>
                                    <?php echo $info->child; ?>
                                </td>	
                                <td>
                                    <?php echo CurrencyHelper::formatprice($info->price); ?>
                                </td>
                            </tr>
                           
                        <?php } ?>
                    <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row-fluid">

    <?php 
        //$layout = new JLayoutFile('passengers', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
        //$html = $layout->render($passengers);
        //echo $html;
        //$layout = new JLayoutFile('rooms', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
        //$html = $layout->render($rooms);
        //echo $html;

    ?>
	</div>
