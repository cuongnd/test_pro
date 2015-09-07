<div class='header'><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MENU') ?></div>
<ul>
    <li><span> <?php echo JHtml::link('index.php?option=com_bookpro&view=mypage&form=order&Itemid=' . JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_ORDER'), 'class="mypage_link"') ?>
        </span></li>

    <li><span> <?php echo JHtml::link('index.php?option=com_bookpro&view=messages&Itemid=' . JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_MESSAGE'), 'class="mypage_link"') ?>
        </span></li>
    <li><span> <?php echo JHtml::link('index.php?option=com_bookpro&view=mypage&form=profile&Itemid=' . JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_PROFILE'), 'class="mypage_link"') ?>
        </span></li>
    <li><span> <?php echo JHtml::link('index.php?option=com_bookpro&view=mypage&form=password&Itemid=' . JRequest::getVar('Itemid'), JText::_('COM_BOOKPRO_CHANGE_PASSWORD'), 'class="mypage_link"') ?>
        </span></li>.
    <li><span> <?php echo JHtml::link('index.php?option=com_users&task=logout', JText::_('COM_BOOKPRO_CUSTOMER_LOGOUT'), 'class="mypage_link"') ?>
        </span></li>
</ul>
