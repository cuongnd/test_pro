<?php
/**
 * @package 	Bookpro Hotel Module
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');
$total=count($displayData);
$products_per_row=4;
$count=$total;
?>


<div class="row-fluid">
    <?php
    $i = 0;
    foreach ($displayData as $item){?>
        <?php

        if( $i == 0 )
            echo '<div class="row-fluid">';?>
        <div class="span<?php echo (12/$products_per_row) ?>">
            <div class="content_hotels_products">

            <ul><li><?php echo $item['amenity'] ?></li></ul>


            </div>
        </div>
        <?php
        if (($i+1) % $products_per_row == 0) {
            echo '</div>';
            echo '<div class="row-fluid">';
        }
        if(($i+1) == $count) {
            echo "</div>";
        }
        if($total < $count)
        {
            if(($i+1) == $total) {
                echo "</div>";
            }
        }

        $i++;
    }
    ?>
</div>
	 
	 
	 
	 