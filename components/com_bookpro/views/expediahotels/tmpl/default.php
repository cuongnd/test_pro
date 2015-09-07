<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 31 2012-07-10 15:26:53Z quannv $
 * */
// No direct access to this file

defined('_JEXEC') or die('Restricted access');
AImporter::helper('date', 'currency', 'hotel');
JHtmlBehavior::modal('a.modal_hotel');
//$query=JURI::buildQuery(array("option"=>"com_bookpro","controller"=>"hotel","view"=>"hotel","Itemid"=>JRequest::getVar("Itemid")));
?>
<style>
    .content_details_hotels{
        border:1px solid #ebebeb;
        border-radius:3px;

        margin-top:10px;
    }
    .container_details_hotels{
        padding-right:8px;
        padding-top:8px;
        padding-left:8px;
    }
    .button_book_now{
        background: #f62121; /* Old browsers */
        background: -moz-linear-gradient(top,  #f62121 0%, #d82020 41%, #cd0000 50%, #ba0000 100%)!important; /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f62121), color-stop(41%,#d82020), color-stop(50%,#cd0000), color-stop(100%,#ba0000))!important; /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  #f62121 0%,#d82020 41%,#cd0000 50%,#ba0000 100%)!important; /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  #f62121 0%,#d82020 41%,#cd0000 50%,#ba0000 100%)!important; /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  #f62121 0%,#d82020 41%,#cd0000 50%,#ba0000 100%)!important; /* IE10+ */
        background: linear-gradient(to bottom,  #f62121 0%,#d82020 41%,#cd0000 50%,#ba0000 100%)!important; /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f62121', endColorstr='#ba0000',GradientType=0 )!important; /* IE6-9 */
        color:#fff;
        text-shadow:none;
    }
    .button_book_now:hover{
        color:#fff;
    }
    .few_rooms_left{
        background: #ff5f03; /* Old browsers */
        background: -moz-linear-gradient(top,  #ff5f03 0%, #f98520 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ff5f03), color-stop(100%,#f98520)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  #ff5f03 0%,#f98520 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  #ff5f03 0%,#f98520 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  #ff5f03 0%,#f98520 100%); /* IE10+ */
        background: linear-gradient(to bottom,  #ff5f03 0%,#f98520 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff5f03', endColorstr='#f98520',GradientType=0 ); /* IE6-9 */

        padding-left:5px;
        padding-right:5px;
        padding-top:2px;
        padding-bottom:2px;
        color:#fff;
        border-radius:3px;
        border:1px solid #ff5d0e;
    }
    .hotel_sorting .controls  .control-label
    {
        margin-left:60px;
    }
    .content_faclity .facilities li{
        display:inline;
        padding-left:10px;
        padding-right:10px;
        padding-top:3px;
        padding-bottom:3px;
        white-space:nowrap;
        color:#fff;
        border-radius:3px;
        line-height: 25px;
        background: #ff9b3b; /* Old browsers */
        background: -moz-linear-gradient(top,  #ff9b3b 0%, #fe8103 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ff9b3b), color-stop(100%,#fe8103)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  #ff9b3b 0%,#fe8103 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  #ff9b3b 0%,#fe8103 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  #ff9b3b 0%,#fe8103 100%); /* IE10+ */
        background: linear-gradient(to bottom,  #ff9b3b 0%,#fe8103 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ff9b3b', endColorstr='#fe8103',GradientType=0 ); /* IE6-9 */

    } 
    .content_faclity .facilities{
        margin:0px;
    }
    #order_Dir
    {
        margin-left: 5px;
    }
</style>
<form name="frontForm" method="post"
      action='index.php?option=com_bookpro&view=hotels'>


    <div class="breadcrumb row-fluid" id="info_div">

        <!-- 
        <div class="span2" style="line-height: 28px;"><?php echo JText::_('COM_BOOKPRO_FILTER_RESULT') ?>
        </div>
        --> 
        <div class="span12" style="line-height: 25px;">

            <div class="row-fluid ">
                <div class="span3">
                    <p style="font-size:17px; color:#d11017;padding-left:10px; padding-top:0px;"><?php echo Jtext::sprintf('COM_BOOKPRO_RESULT', count($this->hotels),$this->dest->title); ?></p>
                </div>
                <div class="span9 pull-right">
                    <div class="row-fluid">
                        <div class="checkin span3">
                            <div style="color: #D11017;"><b><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN') ?></b></div>
                            <div class="textcheckin"><?php echo DateHelper::formatDate($this->cart->checkin_date); ?></div>
                        </div>
                        <div class="checkout  span3">
                            <div style="color: #D11017;"><b><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT') ?></b></div>
                            <div class="textcheckout"><?php echo DateHelper::formatDate($this->cart->checkout_date); ?></div>
                        </div>
                        <div class="form-horizontal hotel_sorting  span6">	

                            <div class="control-group input-append" style="margin-right: 10px;">

                                <label style="width: auto;" class="control-label">

                                    <?php echo JText::_('COM_BOOKPRO_SORT_BY') ?>

                                </label>
                                <div class="controls" style="margin-left:60px" >
                                    <?php echo $this->boxsort; ?>
                                    <?php echo $this->boxsortdir; ?>
                                </div>

                            </div>


                        </div>	
                    </div>

                </div>	
            </div>


        </div>


    </div>




    <?php if (count($this->hotels) > 0) { ?>

        <?php
        $i = 0;
        foreach ($this->hotels as $hotel) {
            ?>

            <?php
            $rankstar = JURI::base() . "/components/com_bookpro/assets/images/" . $hotel->rank . 'star.png';
            $link = JRoute::_('index.php?option=com_bookpro&controller=hotel&task=displayhotel&id=' . $hotel->id);
            $ipath = BookProHelper::getIPath($hotel->image);
            $thumb = AImage::thumb($ipath, 200, 140);
            ?>
            <?php
            if ($i == 0)
            //echo '<div class="row-fluid">';
                
                ?>
            <!-- 
            <div class="span<?php echo (12 / $this->products_per_row) ?>">
            -->	
            <div class="row-fluid show-grid content_details_hotels" style="padding-bottom: 5px;">
                <div class="container_details_hotels">
                    <div class="span2" style="padding-bottom:8px;">
                        <span class="blog_itemimage"><img class="thumbnail"
                                                          src="<?php echo $thumb ?>" alt="<?php echo $hotel->title ?>"> </span>

                    </div>
                    <div class="span8" style="border-right:1px solid #f6f6f6;padding-right:8px;">
                        <div class="row-fluid" style="padding-bottom:5px;">
                            <div style="font-size: 18px;font-weight: bold; display:inline">
                                <a href="<?php echo $link ?>"><?php echo $hotel->title ?> </a>
                            </div>
                            <div class="" style="display:inline;">
                                <img src="<?php echo $rankstar; ?>">
                            </div>
                            <!-- 
                            <div style="display:inline; float:right" class="few_rooms_left">Few rooms Left</div>
                            -->
                        </div>
                        <div>
                            <p style="display:inline;">
                            <div>
                                <?php
                                $city = BookProHelper::getObjectAddress($hotel->city_id);
                                echo $hotel->address1 . ', ' . $city->title . ', ' . $city->country
                                ?>

                                <?php echo HotelHelper::displayHotelMap($hotel->id) ?>
                            </div>
                            </p>

                            <p>
                                <?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN_TIME') ?>:<?php echo $hotel->checkin_time ?>&nbsp;&nbsp;&nbsp;
                                <?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT_TIME') ?>:<?php echo $hotel->checkout_time ?>
                            </p>
                            <div class="content_faclity">
                                <?php
                                $layout = new JLayoutFile('facilitytext', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
                                $html = $layout->render(HotelHelper::getFacilitiesByHotelID($hotel->id));
                                echo $html;
                                ?>
                            </div>


                        </div>


                    </div>

                    <div class="span2 text-right">

                        <p style="font-weight:bold;"><?php echo JText::_('COM_BOOKPRO_ROOM_PRICE_FROM') ?></p>
                        <h4 style="color:#d11017;font-size:130%;">
                            <?php echo CurrencyHelper::formatprice($hotel->price) ?>
                            <?php echo HotelHelper::displayAgentDiscount($hotel) ?></h4>

                        <a href="<?php echo $link ?>" class="btn button_book_now">
                            <?php echo JText::_('COM_BOOKPRO_BOOK_NOW') ?> </a>


                    </div>
                </div>
            </div>
            <!-- 	
            </div> -->
            <?php
            //if (($i + 1) % $this ->products_per_row == 0)
            //echo '<div class="row-fluid">';
            //if (($i + 1) == $this -> count)

            $i++;
            ?>
            <?php
        }
        ?>
    <?php } else { ?>

        <div>
    <?php echo JText::sprintf('No destination or hotel found for specified search criteria') ?>
        </div>

<?php } ?>
    <?php
    if ($this->pagination) {
        ?>	
        <div class="pagination">
        <?php echo $this->pagination->getPagesLinks() ?>
        </div>
        <?php } ?>
    <input type="hidden" name="option"
           value="<?php echo JRequest::getVar('option') ?>" /> 
    <input type="hidden" name="view" value="hotels" />
    <input type="hidden" name="<?php echo $this->token ?>" value="1" /> 
    <input type="hidden" name="task" value="">
</form>
<style type="text/css">
    .hotel_sorting select
    {
        width: auto;
    }
    .t3-mainbody
    {
        min-height: 500px;
    }
</style>

