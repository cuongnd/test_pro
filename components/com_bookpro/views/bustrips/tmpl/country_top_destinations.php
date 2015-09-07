<?php

/**
 * @package     Bookpro Hotel Module
 * @author         Nguyen Dinh Cuong
 * @link         http://ibookingonline.com
 * @copyright     Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');
$total=count($this->destinations);
JHtml::_('behavior.modal','a.jbmodal');
AImporter::helper('dest','tour','string');

?>
<div class="top_destinations">
    <div style="padding-bottom:10px;">
        <span class="title_destinations text-left">Top Tour Destinations</span>
        <div class="pagination pagination-centered"></div>
        <div style="border-bottom: 4px solid #cdcdcd;position:relative; top:-13px;"></div>
    </div>
    <div class="row-fluid">
        <div class="span6">


            <table class="table_car"  data-limit-navigation="2" data-page-size="3">
                <tbody>
                <?php

                $total_item_in_row=2;
                ?>
                <?php for ($i=0;$i<count($this->top_destination);$i=$i+$total_item_in_row){ ?>
                    <tr>
                        <?php for($j=0;$j<$total_item_in_row;$j++){ ?>
                        <?php
                        $dest=$this->top_destination[$i+$j];
                        $link = JRoute::_('index.php?option=com_bookpro&view=bustrips&layout=city&dest_id=' . $dest -> id . '&Itemid=' . JRequest::getVar('Itemid'));
                        ?>
                        <td>
                            <div><span class="blog_itemimage"><img class="thumbnail" src="<?php echo JUri::root().'/'.$dest->dest_image; ?>" alt="<?php echo $dest->dest_title ?>"></span></div>
                            <div class="content"><p style="line-height:15px;font-size:11px;"><b><?php echo $dest->parent_dest_to_title ?></b>,<?php echo $dest->dest_title; ?> <?php echo $dest->total_car; ?> car rental .</p></div>
                        </td>
                        <?php } ?>
                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>
        <div class="span6">
            <img src="images/map-1.jpg">
        </div>
    </div>
</div>







     
     
     