<?php
defined('_JEXEC') or die('Restricted access');
$config = AFactory::getConfig();

?>

<div class="row-fluid">
    <?php $rankstar = JURI::base() . "/components/com_bookpro/assets/images/" . $this->hotel['HotelSummary']['hotelRating'] . 'star.png'; ?>
    <h2 class="hoteltitle">
        <a href="index.php?option=com_bookpro&controller=expediahotel&task=displayhotel&hotel_id=<?php echo $this->hotel['@hotelId'] ?>"><?php echo $this->hotel['HotelSummary']['name'] ?><i class="icon icon-new-windows"></i></a>
        <span style="background-repeat: no-repeat" class="row-fluid star-rating <?php echo $this->array_star["star".$this->hotel['HotelSummary']['hotelRating']] ?>"></span>
    </h2>
    <div class="images"><img src="<?php echo $this->hotel['HotelImages']['HotelImage'][0]['thumbnailUrl'] ?>"/></div>
    <div class="hoteladd">
        <span class ="address"><?php echo $this->hotel['HotelSummary']['address1'] ?></span>
        <?php
        $obj = array(
            'longitude' => $this->hotel['HotelSummary']['latitude'],
            'latitude' => $this->hotel['HotelSummary']['longitude'],
            'address' => $this->hotel['HotelSummary']['address1'],
            'title' => $this->hotel['HotelSummary']['name'],
            'desc' => $this->hotel['HotelSummary']['shortDescription']
        );
        $obj = http_build_query($obj);
        $link = JUri::root() . 'index.php?option=com_bookpro&controller=expediahotel&task=displaymap&tmpl=component&' . $obj;
        ?>
        <span class="icon-map-marker" style="padding-right:3px"></span><a href="<?php echo $link ?>" class="modal_hotel" rel="{handler: 'iframe', size: {x: 570, y: 530}}"><?php echo JText::_("COM_BOOKPRO_VIEW_MAP") ?></a>
    </div>
</div>
