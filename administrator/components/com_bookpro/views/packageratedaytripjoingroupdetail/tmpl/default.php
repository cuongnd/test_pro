<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');
 
$document = JFactory::getDocument();  
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validdate_tour.js');  
JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(1);

JToolBarHelper::title(JText::_('COM_BOOKPRO_EDIT_RATE_MANAGER'), 'object');

AImporter::model('tours', 'packageratedetails', 'tourpackages', 'tourpackages', 'tour');
AImporter::helper('tour');

JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
?>
<?php
// date
$from = ARequest::getUserStateFromRequest('from', '', 'string');
$to = ARequest::getUserStateFromRequest('to', '', 'string');
$tour_id = ARequest::getUserStateFromRequest('tour_id', '', 'int');
$tourpackage_id = ARequest::getUserStateFromRequest('tourpackage_id', '', 'int');

if (!$from && !$to) {
    $from = JFactory::getDate()->format('Y-m-d');
    $todate = JFactory::getDate()->format('Y-m-d');
    $to = date('Y-m-d', strtotime($todate . '14 day'));
} elseif (!$to) {
    $todate = JFactory::getDate()->format($from);
    $to = date('Y-m-d', strtotime($todate . '14 day'));
} elseif (!$from) {
    $todate = JFactory::getDate()->format($to);
    $from = date('Y-m-d', strtotime($to . '-14 day'));
}
$fromdate1 = new JDate($from);
$todate1 = new JDate($to);

$fromToto = $fromdate1->diff($todate1)->days;
 
if ($tour_id) {
    $modelTour = new BookProModelTour();
    $modelTour->setId($tour_id);
    $tour = $modelTour->getObject();
}
?>  
<script>
    function ValidateForm()
    {
       var task = jQuery('input[name="task"]').val(); 
       if(!task){
            return validateDate('from', 'to');
       }else{
        return true;
       }
    }
</script>     
<div class="span10">
    <form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="return ValidateForm();">
        <div id="editcell">
            <?php
            if ($tour) {
                ?>
                <h3><?php echo JText::_('Tour :') . $tour->title; ?>, <?php echo JText::_('Tour package: ') . $tourpackage->packagetitle; ?></h3>  
                <?php
            }
            ?>     
            <fieldset id="filter-bar">
                <div class="filter-search fltlft form-inline">
                    <div class="filter-search fltlft">
                        <label for="From Airport"><?php echo JText::_('COM_BOOKPRO_FROM'); ?>: </label>
                        <?php echo JHtml::calendar($from, 'from', 'from', '%Y-%m-%d', 'readonly="readonly"') ?>     

                        <label for="To Airport"><?php echo JText::_('COM_BOOKPRO_TO'); ?>: </label>
                        <?php echo JHtml::calendar($to, 'to', 'to', '%Y-%m-%d', 'readonly="readonly"') ?>   
                    </div>
                    <div class="btn-group pull-left hidden-phone fltlft">
                        <button  class="btn">
                            <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
                        </button>
                    </div>
                </div>        
            </fieldset>    

            <div style="width:100%; overflow-x:auto; margin-top: 10px; height:350px;"> 
                <table cellspacing="0" cellpadding="0" border="0" class="table table-striped table-bordered">
                    <thead class="table-bordered">
                        <tr>
                            <th width="100px;"><div style="width:100px;"></div></th>
                            <?php
                            for ($i = 0; $i <= $fromToto; $i++) {
                                $datet = date('Y-m-d', strtotime($from . $i . ' day'));
                                ?>
                                <th>
                        <div style=" width:54px;border: 2px solid #FFFFFF;margin-left: 0 !important;margin-right: 0 !important;text-align: center; font-size:12px;"> 
                            <?php echo date('M d', strtotime($datet)); ?><br>
                            <?php echo date('Y', strtotime($datet)); ?>
                        </div>
                        </th>
                        <?php
                    }
                    ?>
                    </tr>     
                    </thead>

                        <tbody class="table-bordered">
                            <tr>
                                 <td class="center" width="100px;">    
                                        <div type="text" style=" height:34px; text-align:center; margin:1px 0 0px 0px;" value="">  
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_ADULT'); ?></strong>
                                        </div>

                                        <div type="text" style="height:34px; text-align:center; margin:1px 1px 1px 0px;" size="7" value="">    
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_TEEN'); ?></strong>
                                        </div>

                                        <div type="text" style="height:34px; text-align:center; margin:1px 1px 1px 0px;" size="7" value="">   
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD1'); ?></strong>
                                        </div>

                                        <div type="text" style="height:34px; text-align:center; margin:1px 0 0px 0px;" value="">     
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD2'); ?></strong>
                                        </div>

                                        <div type="text" style="height:34px; text-align:center; margin:1px 0 0px 0px;" value="">        
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD3'); ?></strong>
                                        </div>

                                        <div type="text" style="height:34px; text-align:center; margin:1px 1px 1px 0px;" size="7" value="">    
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_EXTRA_BED'); ?></strong>
                                        </div>  
                                        
                                        <div type="text" style="height:34px; text-align:center; margin:1px 1px 1px 0px; " size="7" value="">           
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_ADULT_PROMO'); ?></strong>
                                        </div>

                                        <div type="text" style="height:34px; text-align:center; margin:1px 0 0px 0px; " value="">         
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_TEEN_PROMO'); ?></strong>
                                        </div>

                                        <div type="text" style="height:34px; text-align:center; margin:1px 0 0px 0px; " value="">      
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD_PROMO'); ?></strong>
                                        </div>
                                              
                                        <div type="text" style="height:34px; text-align:center; margin:1px 1px 1px 0px;" size="7" value="">   
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_POST_TRANSFER'); ?></strong>
                                        </div>

                                        <div type="text" style="height:34px; text-align:center; margin:1px 1px 1px 0px;" size="7" value="">        
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_PRETRANSFER'); ?></strong>
                                        </div>
                                              
                                        <div style="height:22px;">  
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_AVAILABLE'); ?></strong>
                                       </div>
                                       
                                        <div style="height:22px;">
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_REQUEST'); ?></strong>
                                       </div>
                                       
                                        <div style="height:22px;">
                                        <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_GUARANTEED'); ?></strong>
                                       </div>
                                       
                                        <div style="height:22px;">
                                         <strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CLOSE'); ?></strong>
                                       </div>                    

                              
                                    </td>        
                                                                           
                                <?php
                                for ($k = 0; $k <= $fromToto; $k++) {
                                    $date = date('Y-m-d', strtotime($from . $k . ' day'));
                                    $packageratedaytripjoingroup = TourHelper::getPackageRatedaytripjoingroupByTourIdAndDate( $date, $tour_id);
                                    ?>
                                    <td class="center">    
                                        <input type="text" style="width:46px; height:22px; text-align:center; margin:1px 0 0px 0px; border: 2px solid #53a853 " value="<?php echo $packageratedaytripjoingroup->adult; ?>" id="<?php echo 'adult' . $packageratedaytripjoingroup->adult; ?>" name="adult[]">
                                        <br>

                                        <input type="text" style="border: 2px solid #2cbac3;width:46px; height:22px; text-align:center; margin:1px 1px 1px 0px;" size="7" value="<?php echo $packageratedaytripjoingroup->teen; ?>" id="<?php echo 'teen' . $packageratedaytripjoingroup->teen; ?>" name="teen[]">
                                        <br>

                                        <input type="text" style="width:46px; height:22px; text-align:center; margin:1px 1px 1px 0px; border: 2px solid #39a2e5" size="7" value="<?php echo $packageratedaytripjoingroup->child1; ?>" id="<?php echo 'child1' . $packageratedaytripjoingroup->child1; ?>" name="child1[]">
                                        <br>

                                        <input type="text" style="width:46px; height:22px; text-align:center; margin:1px 0 0px 0px; border: 2px solid #868686" value="<?php echo $packageratedaytripjoingroup->child2; ?>" id="<?php echo 'child2' . $packageratedaytripjoingroup->child2; ?>" name="child2[]">
                                        <br>

                                        <input type="text" style="width:46px; height:22px; text-align:center; margin:1px 0 0px 0px; border: 2px solid #fe6612" value="<?php echo $packageratedaytripjoingroup->child3; ?>" id="<?php echo 'child3' . $packageratedaytripjoingroup->child3; ?>" name="child3[]">
                                        <br>

                                        <input type="text" style="border: 2px solid #53a853;width:46px; height:22px; text-align:center; margin:1px 1px 1px 0px;border: 2px solid #e7e7e7" size="7" value="<?php echo $packageratedaytripjoingroup->extra_bed; ?>" id="<?php echo 'extra_bed' . $packageratedaytripjoingroup->extra_bed; ?>" name="extra_bed[]">
                                        <br>
                                                
                                        
                                        <input type="text" style="width:46px; height:22px; text-align:center; margin:1px 1px 1px 0px; " size="7" value="<?php echo $packageratedaytripjoingroup->adult_promo; ?>" id="<?php echo 'adult_promo' . $packageratedaytripjoingroup->adult_promo; ?>" name="adult_promo[]">
                                        <br>

                                        <input type="text" style="width:46px; height:22px; text-align:center; margin:1px 0 0px 0px; " value="<?php echo $packageratedaytripjoingroup->teen_promo; ?>" id="<?php echo 'teen_promo' . $packageratedaytripjoingroup->teen_promo; ?>" name="teen_promo[]">
                                        <br>

                                        <input type="text" style="width:46px; height:22px; text-align:center; margin:1px 0 0px 0px; " value="<?php echo $packageratedaytripjoingroup->child_promo; ?>" id="<?php echo 'child_promo' . $packageratedaytripjoingroup->child_promo; ?>" name="child_promo[]">
                                        <br>
                                        
                                        
                                        <input type="text" style="border: 2px solid #53a853;width:46px; height:22px; text-align:center; margin:1px 1px 1px 0px;border: 2px solid #1a4079" size="7" value="<?php echo $packageratedaytripjoingroup->pretransfer; ?>" id="<?php echo 'pretransfer' . $packageratedaytripjoingroup->extra_bed; ?>" name="pretransfer[]">
                                        <br>

                                        <input type="text" style="border: 2px solid #53a853;width:46px; height:22px; text-align:center; margin:1px 1px 1px 0px;border: 2px solid #e19400" size="7" value="<?php echo $packageratedaytripjoingroup->posttransfer; ?>" id="<?php echo 'posttransfer' . $packageratedaytripjoingroup->posttransfer; ?>" name="posttransfer[]">
                                        <br>


                                                    <span style="width:60px; height:22px;">
                                                    <?php 
                                                       // var_dump($packagerate->available); die;                           
                                                    
                                                        $check_available_1 = $check_available_0 ='';
                                                        if($packageratedaytripjoingroup->available == 1){
                                                            $check_available_1 = 'checked="checked"' ;
                                                        }elseif($packageratedaytripjoingroup->available != null && $packageratedaytripjoingroup->available == 0){
                                                            $check_available_0 = 'checked="checked"';
                                                        }
                                                    ?>
                                                            <strong>Y</strong><input type="radio" name="available<?php echo $k; ?>" value="1" <?php echo $check_available_1;?>>
                                                            <strong>N </strong>  <input type="radio" name="available<?php echo $k; ?>" value="0" <?php echo $check_available_0;?>>
                                                   </span>
                                                    <br>
                                                    <span style="width:60px; height:22px;">
                                                    <?php 
                                                        $check_request_1 = $check_request_0 ='';
                                                        if($packageratedaytripjoingroup->request == 1){      
                                                            $check_request_1 = 'checked="checked"' ;
                                                        }elseif($packageratedaytripjoingroup->request != null && $packageratedaytripjoingroup->request == 0){
                                                            $check_request_0 = 'checked="checked"';
                                                        }
                                                    ?>
                                                            <strong>Y</strong><input type="radio" name="request<?php echo $k; ?>" value="1" <?php echo $check_request_1;?>>
                                                            <strong>N </strong>  <input type="radio" name="request<?php echo $k; ?>" value="0" <?php echo $check_request_0;?>>
                                                   </span>
                                                    <br>
                                                    <span style="width:60px; height:22px;">
                                                    <?php 
                                                        $check_guaranteed_1 = $check_guaranteed_0 ='';
                                                        if($packageratedaytripjoingroup->guaranteed == 1){
                                                            $check_guaranteed_1 = 'checked="checked"' ;
                                                        }elseif($packageratedaytripjoingroup->guaranteed != null && $packageratedaytripjoingroup->guaranteed == 0){
                                                            $check_guaranteed_0 = 'checked="checked"';
                                                        }
                                                    ?>
                                                            <strong>Y</strong><input type="radio" name="guaranteed<?php echo $k; ?>" value="1" <?php echo $check_guaranteed_1;?>>
                                                            <strong>N </strong>  <input type="radio" name="guaranteed<?php echo $k; ?>" value="0" <?php echo $check_guaranteed_0;?>>
                                                   </span>
                                                    <br>  
                                                    <span style="width:60px; height:22px;">
                                                    <?php 
                                                        $check_close_1 = $check_close_0 ='';
                                                        if($packageratedaytripjoingroup->close == 1){
                                                            $check_close_1 = 'checked="checked"' ;
                                                        }elseif($packageratedaytripjoingroup->close != null && $packageratedaytripjoingroup->close == 0){
                                                            $check_close_0 = 'checked="checked"';
                                                        }
                                                    ?>
                                                            <strong>Y</strong><input type="radio" name="close<?php echo $k; ?>" value="1" <?php echo $check_close_1;?>>
                                                            <strong>N </strong>  <input type="radio" name="close<?php echo $k; ?>" value="0" <?php echo $check_close_0;?>>
                                                   </span>                    
                                                   <br>

                                        <input type="hidden" name="id[]" value="<?php echo $packageratedaytripjoingroup->id; ?>">
                                        <input type="hidden" name="date[]" value="<?php echo $date; ?>">
                                    </td>                                          
                                    <?php
                                }
                                ?>
                            </tr>
                        </tbody>      
                </table>          
            </div>

            <div class="clr"></div>
        </div>  
       

        <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="reset" value="0"/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="tourpackage_id" value="<?php echo $tourpackage_id; ?>" id="tourpackage_id"/>
        <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>" id="tour_id"/>	
        <input type="hidden" name="controller" value="<?php echo CONTROLLER_PACKAGE_RATEDAYTRIPJOINGROUP_DETAIL; ?>"/>
        <input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>	
</div>

