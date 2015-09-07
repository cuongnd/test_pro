<?php
$base_link='index.php?option=com_bookpro&view=mypage&Itemid='.JRequest::getVar('Itemid');
?>
<ul class="cmenu">
	<li><i class="icon-briefcase icon-large"></i> <span> <?php echo JHtml::link($base_link.'&form=order', JText::_('COM_BOOKPRO_BOOKINGS'),'class="mypage_link"') ?>
	</span></li>
	<li><i class="icon-home icon-large"></i> <span> <?php echo JHtml::link($base_link.'&form=profile', JText::_('COM_BOOKPRO_PROFILE'),'class="mypage_link"') ?>
	</span></li>
	<li class="messager"><i class="icon-pencil icon-large "></i> <span> <?php echo JHtml::link('index.php?option=com_bookpro&view=messages', JText::_('COM_BOOKPRO_MESSAGES'),'class="mypage_link"') ?>
	</span><span class="alert">1</span></li>
	<li><i class="icon-list icon-large"></i> <span> <?php echo JHtml::link('index.php?option=com_bookpro&view=reviews&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_REVIEWS'),'class="mypage_link"') ?>
	</span></li>
	<li><i class="icon-pencil icon-large"></i> <span> <?php echo JHtml::link('index.php?option=com_bookpro&view=review&Itemid='.JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_REVIEW'),'class="mypage_link"') ?>
	</span></li>
	    <li>
    	<i class="icon-lock icon-large"></i>
    	<span>  <?php echo JHtml::link($base_link.'&form=rewards', JText::_('COM_BOOKPRO_REWARDS'),'class="mypage_link"') ?>
        </span>
     </li>
	<li><i class="icon-lock icon-large"></i> <span> <?php echo JHtml::link($base_link.'&form=password', JText::_('COM_BOOKPRO_CHANGE_PASSWORD'),'class="mypage_link"') ?>
	</span></li>
	<li><i class="icon-off icon-large"></i> <span> <?php echo JHtml::link('index.php?option=com_bookpro&controller=customer&task=logout', JText::_('COM_BOOKPRO_CUSTOMER_LOGOUT'),'class="mypage_link"') ?>
	</span></li>
</ul>
<style>
    .messager .alert
    {
        color: #fff;
        background: #ff3333;
        border: 1px solid #ff3333;
        border-radius: 4px;
        padding: 0;
    }
</style>
