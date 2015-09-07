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
         
<div class="row-fluid ">
    <?php
    $i = 0;
    foreach ($this->destinations as $dest){

    
  	
    $link = JRoute::_('index.php?option=com_bookpro&view=destination&dest_id=' . $dest -> id . '&Itemid=' . JRequest::getVar('Itemid'));
    
    $ipath = BookProHelper::getIPath($dest->image);
    
    $thumb = AImage::thumb($ipath, 55, 55);

    if( $i == 0 )
        echo '<div class="row-fluid">';?>
    <div class="span<?php echo (12/$this->products_per_row) ?>">
        <div class="content_hotels_products" style="padding-right:8px;padding-bottom:10px;">
    
            <div class="row-fluid">

                <div class="span4">
                
                    <span class="blog_itemimage"><img class="thumbnail"
                         src="<?php echo $thumb; ?>" alt="<?php echo $dest->title ?>"> 
						
					</span>
               
                </div>
				<div class="content_hotels_detail span8">
                <h3 class="title_top_destination">
                    <a href="<?php echo $link ?>"><?php echo $dest->title; ?> </a>
                </h3>
				<p style="line-height:15px;font-size:11px;"><?php echo $dest->totalTour; ?> tours including <?php echo AString::wordLimit(TourHelper::getActivityHtmlByDest($dest->id),3); ?>.</p>
            </div>
				
            </div>
            
        </div>
    </div>
    
    <?php 
    if (($i+1) % $this->products_per_row == 0) {
                    echo '</div>';
                    echo '<div class="row-fluid">';
                }
                if(($i+1) == $this->count) {
                    echo "</div>";
                }
                if($total < $this->count)
                {
                    if(($i+1) == $total) {
                        echo "</div>";
                        }
                }
               
                    $i++;
             }
             ?>
             
           
     </div>
     
     
     
     