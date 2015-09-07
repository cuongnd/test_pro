
<?php

$base_link = 'index.php?option=com_bookpro&view=mypage&Itemid=' . JRequest::getVar('Itemid');
?>
<ul class="cmenu">
    <li><i class="icon-briefcase icon-large"></i> <span> <?php echo JHtml::link($base_link . '&form=order', JText::_('COM_BOOKPRO_BOOKINGS'), 'class="mypage_link"') ?>
        </span></li>
    <li><i class="icon-home icon-large"></i> <span> <?php echo JHtml::link($base_link . '&form=profile', JText::_('COM_BOOKPRO_PROFILE'), 'class="mypage_link"') ?>
        </span></li>

    <li><i class="icon-pencil icon-large"></i> <span> <?php echo JHtml::link('index.php?option=com_bookpro&view=review', JText::_('COM_BOOKPRO_REVIEWS'), 'class="mypage_link"') ?>
        </span></li>
        
    <li>
    	<i class="icon-lock icon-large"></i> 
    	<span> <?php echo JHtml::link('index.php?option=com_bookpro&view=review', JText::_('COM_BOOKPRO_CHANGE_REVIEW'), 'class="mypage_link"') ?>
        </span>
     </li>
    <li><i class="icon-off icon-large"></i> <span> <?php echo JHtml::link('index.php?option=com_bookpro&controller=customer&task=logout', JText::_('COM_BOOKPRO_CUSTOMER_LOGOUT'), 'class="mypage_link"') ?>
        </span></li>
</ul>
