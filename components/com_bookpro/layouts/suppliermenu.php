<?php
AImporter::css('bookpro');
$user = JFactory::getUser();
$baseUrl=JUri::base().'index.php?option=com_bookpro';
?>

<h2><?php echo JText::_('SUPPLIER MANAGEMENT')?> </h2>

<div class="row-fluid">
<div class="span3 container-fluid">
<h4><?php echo JText::sprintf('COM_BOOKPRO_CUSTOMER_WELCOME',JHTML::link('index.php?option=com_bookpro&view=mypage&form=profile',$user->name))?></h4>

<span><button class="btn btn-primary" onclick="window.location.href='<?php echo JUri::base();?>index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken(); ?>=1'"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LOGOUT'); ?></button></span>
</div>
<div class="span9">
<div class="menu breadcrumb">
  <ul class="supplier-menu">
    <li><span><?php echo JHtml::link($baseUrl.'&view=supplierpage&layout=order&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_ORDER'),'class="btn"')?></span></li>
    <li><span><?php echo JHtml::link($baseUrl.'&view=supplierpage&form=profile&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_PROFILE'),'class="btn"')?></span></li>
    <li><span><?php echo JHtml::link($baseUrl.'&view=supplierpage&form=password&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_CHANGE_PASSWORD'),'class="btn"') ?></span></li>.
    <li><span><?php echo JHtml::link($baseUrl.'&view=registerhotels&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_HOTEL_MANAGER'),'class="btn"')  ?></span></li>
    <li><span><?php echo JHtml::link($baseUrl.'&view=scripthotel&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_WIDGET_BOOKING'),'class="btn"') ?></span></li>
    <li><span><?php echo JHtml::link($baseUrl.'&view=coupons&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_COUPON_MANAGER'),'class="btn"')?></span></li>
    <li><span><?php echo JHtml::link($baseUrl.'&view=supplierbooking&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_MAKE_BOOKING'),'class="btn"')?></span></li>
	<li></li>
    
 </ul>
 <br>
</div>
</div>

</div>

