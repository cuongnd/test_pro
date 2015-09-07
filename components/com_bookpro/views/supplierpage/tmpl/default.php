<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.html.html' );
AImporter::helper('date','bookpro','currency','hotel');
?>
    <?php
        $layout = new JLayoutFile('suppliermenu', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
        $html = $layout->render(array());
        echo $html;
        $checkedorder = JRequest::getVar('form','order');
    ?>
                                                         
<div class="row-fluid">
    <div class="span12">
            <?php
             if($checkedorder=='order'){
         ?>
        <div class="span6">
        <?php
             }
        ?>
            <?php echo $this->loadTemplate(JRequest::getVar('form','ordernew'));
        ?> 
        
        <?php
             if($checkedorder=='order'){
         ?>
         </div>
        <div class="span6">
            <fieldset>
                <legend><?php echo JText::_('COM_BOOKPRO_COUPON_MANAGER'); ?></legend>
                <a href="<?php echo JURI::base().'index.php?option=com_bookpro&view=coupons&Itemid='.JRequest::getVar('Itemid');?>">
                    <img alt="" src="images/defaul-img.png"> 
                </a>
            </fieldset>
        </div>        
        <?php
             }
        ?>
    </div>
  </div>  
        <?php
             if($checkedorder=='order'){
         ?>    
<div class="row-fluid">         
    <div class="span12">
        <div class="span6">
            <fieldset>
                <legend><?php echo JText::_('COM_BOOKPRO_HOTEL_MANAGER'); ?></legend>
                <a href="<?php echo JURI::base().'index.php?option=com_bookpro&view=registerhotels&Itemid='.JRequest::getVar('Itemid');?>">
                    <img alt="" src="images/defaul-img.png"> 
                </a>
            </fieldset>                
        </div>
        <div class="span6">
            <fieldset>
                <legend><?php echo JText::_('Google'); ?></legend>
                <a href="">
                    <img alt="" src="images/defaul-img.png"> 
                </a>
            </fieldset>    
        </div>
    </div>
   </div> 
   <?php 
        }
      ?>



