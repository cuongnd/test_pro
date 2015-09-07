<?php
$rankstar = JURI::root() . "/components/com_bookpro/assets/images/" . $this->hotel['hotelRating'] . 'star.png';
$link = JRoute::_('index.php?option=com_bookpro&controller=expediahotel&task=displayhotel&hotel_id=' . $this->hotel['hotelId']);
$thumb = AImage::thumb('http://media1.expedia.com' . $this->hotel->thumbNailUrl, 200, 140);

?>
<input type="hidden" name="hotelid" value="<?php echo $this->hotel['hotelId'] ?>">
<div class="container_details_hotels">
    <div class="span3" style="padding-bottom: 8px;">
        <span class="blog_itemimage" style="<?php if($this->hotel['thumbNailUrl']){ ?>background-image: url('<?php echo 'http://media.expedia.com' . str_replace('_t', '_b', $this->hotel['thumbNailUrl']) ?>') <?php } ?>"> </span>
    </div>
    <div class="span7" style="border-right: 1px solid #f6f6f6; padding-right: 8px;">
        <div class="row-fluid" style="padding-bottom: 5px;">
            <h2 style="margin: 0" class="row-fluid">
                <a style="float: left" href="<?php echo $link ?>"><?php echo $this->hotel['name'] ?> </a>
            </h2>
            <br/>
            <span style="background-repeat: no-repeat" class="row-fluid star-rating <?php echo $this->array_star["star".$this->hotel['hotelRating']] ?>"></span>
            <div class="row-fluid"><?php echo JText::sprintf('COM_BOOKPRO_HOTEL_REVIEW_COUNT',$this->hotel['tripAdvisorRating']) ?></div>
           
        </div>
        <div>
          
            <div>
                <?php

                echo $this->hotel['address1'] . ', ' . $this->hotel['city'] . ', ' . $this->hotel['countryCode']
                ?>
                <?php
                $obj = array(
                    'longitude' => $this->hotel['latitude'],
                    'latitude' => $this->hotel['longitude'],
                    'address' => $this->hotel['address1'],
                    'title' => $this->hotel['name'],
                    'desc' => $this->hotel['shortDescription']
                );
                $obj = http_build_query($obj);
                $map_link = JUri::root() . 'index.php?option=com_bookpro&controller=expediahotel&task=displaymap&tmpl=component&' . $obj;
                ?>
              <!--  <span class="icon-map-marker" style="padding-right:3px"></span><a href="<?php echo $map_link ?>" class="modal_hotel" rel="{handler: 'iframe', size: {x: 570, y: 530}}"><?php echo JText::_("COM_BOOKPRO_VIEW_MAP") ?></a>-->

            </div>
        


        </div>
    </div>
    <div class="span2 text-right">
        <p style="font-weight: bold;"><?php echo JText::_('COM_BOOKPRO_ROOM_PRICE_FROM') ?></p>
        <?php $avg = ($this->hotel['highRate'] + $this->hotel['lowRate']) / 2 ?>
        <h4><?php echo CurrencyHelper::formatprice($avg,null,$this->cart->currency_code) ?></h4>
        <h4><?php echo JText::_('COM_BOOKPRO_EXPEDIA_AVG_NIGHT') ?></h4>
        <a href="<?php echo $link ?>" class="btn button_book_now">
            <?php echo JText::_('COM_BOOKPRO_BOOK_NOW') ?> </a>
    </div>
</div>


<style>
    .blog_itemimage {
        background-position: center center;
        background-size: 155px 114px;
        display: block;
        height: 110px;
        width: 150px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }

    .content_details_hotels:hover {
        background: #eee;
    }
</style>
