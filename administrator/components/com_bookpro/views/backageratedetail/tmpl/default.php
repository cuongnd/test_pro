<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(1);

JToolBarHelper::title(JText::_('COM_BOOKPRO_EDIT_RATE_MANAGER'), 'object');

AImporter::model('tours','packageratedetails','package','packages','tour');
AImporter::helper('tour');
    
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
?>
<?php       
            // date
            $from    =   ARequest::getUserStateFromRequest('from', '', 'string');
            $to      =   ARequest::getUserStateFromRequest('to', '', 'string');
            $tour_id      =   ARequest::getUserStateFromRequest('tour_id', '', 'int');
            
            if(!$from && !$to){                    
                $from = JFactory::getDate()->format('Y-m-d');
                $todate = JFactory::getDate()->format('Y-m-d');
                $to = date('Y-m-d',strtotime($todate.'14 day')); 
            }elseif(!$to){    
                $todate = JFactory::getDate()->format($from);
                $to = date('Y-m-d',strtotime($todate.'14 day')); 
            }elseif(!$from){
                $todate = JFactory::getDate()->format($to);
                $from = date('Y-m-d',strtotime($to.'-14 day')); 
            }                           
            $fromdate1 = new JDate($from);        
            $todate1   = new JDate($to);

            $fromToto =  $fromdate1->diff($todate1)->days;         
            
            //List package
            $listspackage = array('tour_id'=>$tour_id);    
            $modelpackage = new BookProModelpackages();     
            $modelpackage->init($listspackage);
            $packages = $modelpackage->getData(); 
            
            //tour 
             if($tour_id){   
                $modelTour = new BookProModelTour();        
                $modelTour->setId($tour_id);
                $tour = $modeltour->getObject();          
             }
?>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">
  <div id="editcell">
    <?php
        if($tour){
    ?>
      <h3><?php echo JText::_('tour :').$tour->title; ?></h3>  
    <?php
        }
      ?>     
    <fieldset id="filter-bar">
        <div class="filter-search fltlft form-inline">
                <div class="filter-search fltlft">
                <label for="From Airport"><?php echo JText::_('COM_BOOKPRO_FROM'); ?>: </label>
                <?php echo JHtml::calendar($from, 'from', 'from','%Y-%m-%d','readonly="readonly"') ?>
                
                <label for="To Airport"><?php echo JText::_('COM_BOOKPRO_TO'); ?>: </label>
                <?php echo JHtml::calendar($to, 'to', 'to','%Y-%m-%d','readonly="readonly"') ?>
                
                </div>
                <div class="btn-group pull-left hidden-phone fltlft">
                    <button onclick="this.form.submit();" class="btn">
                        <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
                    </button>
                </div>
            </div>        
    </fieldset>    
    
<div style="width:100%; overflow-x:auto; margin-top: 10px; height:400px;"> 
    <table cellspacing="0" cellpadding="0" border="0" class="table table-striped table-bordered">
        <thead class="table-bordered">
            <tr>
            <th width="50px;"></th>
 <?php
           for($i=0; $i<= $fromToto; $i++){
                $datet = date('Y-m-d',strtotime($from.$i.' day'));              
 ?>
        <th>
            <div style=" width:54px;border: 2px solid #FFFFFF;margin-left: 0 !important;margin-right: 0 !important;text-align: center; font-size:12px;"> 
                            <?php  echo  date('M d',strtotime($datet)); ?><br>
                            <?php  echo  date('Y',strtotime($datet)); ?>
             </div>
         </th>
  <?php
           }
  ?>
                </tr>     
            </thead>
 <?php
                   if(count($packages)>0){
 ?>
                                  <?php
                                        for($j=0; $j<count($packages); $j++)
                                        { 
                                  ?>
                                          <tbody class="table-bordered">
                                            <tr>
                                                  <th width="50px;"> <?php echo $packages[$j]->title; ?> </th>                                        
                                        <?php  
                                        for($k=0; $k<= $fromToto; $k++){
                                            $date = date('Y-m-d',strtotime($from.$k.' day'));                                         
                                            $packagerateModel = new BookProModelpackageRatedetail();
                                            $packagerate = $packagerateModel->getObjectBypackageIdAndDate($packages[$j]->id,$date);
                                        ?>
                                                 <td class="center">    
                                                       <?php
                                                           $packagerest = $packages[$j]->quantity - TourHelper::getTotalBookpackage($packages[$j]->id,$date);
                                                       ?>     
                                                    <input type="text" style="width:46px; height:22px; text-align:center; margin:1px 0 0px 0px;" value="<?php echo $packagerest;?>" disabled="true" id="max_beds" size="7" name="max_beds">
                                                    <br>
                                                    <input type="text" style=" color:green;width:46px; height:22px; text-align:center; margin:1px 1px 1px 0px;" size="7" value="<?php echo $packagerate->rate; ?>" id="<?php echo $packagerate->id; ?>" name="rate[]">
                                                    <input type="hidden" name="id[]" value="<?php echo $packagerate->id; ?>">
                                                    <input type="hidden" name="package_id[]" value="<?php echo $packages[$j]->id; ?>">
                                                    <input type="hidden" name="date[]" value="<?php echo $date; ?>">
                                                 </td>                                          
                                         <?php   
                                         }                                                            
                                         ?>
                                    </tr>
                            </tbody>                     
                    <?php           
                            }                             
                       }          
                   ?>   
        </table>          
        </div>
		
		<div class="clr"></div>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="reset" value="0"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_package_RATE_DETAIL; ?>"/>
	<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
	<?php echo JHTML::_('form.token'); ?>
</form>	
</div>

