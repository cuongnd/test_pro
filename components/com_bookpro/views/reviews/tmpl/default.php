<?php
JHtml::_('jquery.framework');
JHtml::_('behavior.modal','a.jbmodal');
$pagination=$this -> pagination;
?>

<div class="container-fluid">
	<div class="span3">
		<h4>
			<?php echo JText::sprintf('COM_BOOKPRO_CUSTOMER_WELCOME',JHTML::link('index.php?option=com_bookpro&view=mypage&form=profile',$this->user->name)) ?>
		</h4>
	    <?php
                $layout = new JLayoutFile('cmenu', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
                $html = $layout->render($this->customer);
                echo $html;
                ?>
	
	</div>
<div class="span9">
	<h3><?php echo Jtext::_('COM_BOOKPRO_REVIEW_LIST'); ?></h3>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="row-fluit">
       <div class="" style="float: right;">                 
       		<?php echo $this->pagination->getLimitBox(); ?>
       </div>
    </div>   
       <div class="row-fluit">
       <div class="span12"> 
        <table class="table-striped table" >
            <thead>
                <tr>
                    <th class="title">
                        <?php echo Jtext::_('COM_BOOKPRO_REVIEW_TITLE'); ?>
                    </th>
                    <th>
                        <?php echo Jtext::_('COM_BOOKPRO_REVIEW_OVERALL_RATING'); ?>
                    </th>         
                    <th>
                        <?php echo JText::_('COM_BOOKPRO_REVIEW_IMAGE'); ?>
                    </th>

                    <th>
                        <?php echo Jtext::_('COM_BOOKPRO_REVIEW_DATE'); ?>
                    </th>
                    <th>
                        <?php echo Jtext::_('COM_BOOKPRO_EDIT'); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="5">
                        <?php echo $pagination->getListFooter(); ?>
                    </td>                    
                </tr>
            </tfoot>
            <tbody>
                <?php
                foreach ($this->items as $i => $item) {
                    $rankstar = JURI::root() . "components/com_bookpro/assets/images/" . $item->rank . 'star.png';
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_bookpro&view=reviewdetail&id=' . $item->id . '&Itemid='.JRequest::getVar('Itemid')); ?>">
                                <?php echo $this->escape($item->title); ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_bookpro&view=reviewdetail&id=' . $item->id . '&Itemid='.JRequest::getVar('Itemid')); ?>">
                                <div style="text-align: left">
                                	<?php if($item->rank){?>
                                		<img src="<?php echo $rankstar; ?>">
                                	<?php }?>
                                </div>
                            </a>
                        </td>
                        <td>
                        <?php 
                        	$urlimagebasic = JURI::root() . 'components/com_bookpro/assets/images/imagesmd5/';
                        ?>
                        	<?php if($item->image!=''){ ?>
                            	<img  id="returnuploadimage" src="<?php echo $urlimagebasic.$item->image; ?>" style="with: 100px; height: 50px; padding-left:0px; padding-right:0px; border:1px solid #808080;" />
                            <?php }?>
                        </td>
                        

                        <td >
                     			<?php
									$date=''; 
									if($item->date){
										$date = JFactory::getDate($item->date)->format('Y-m-d');
									}
									?>
									<?php echo $date; ?>
                        </td>
                        
                        <td>
                        <?php if($item->state!=1){?>
                            <a href="<?php echo JRoute::_('index.php?option=com_bookpro&view=review&id=' . $item->id . '&Itemid='.JRequest::getVar('Itemid')); ?>">
                                <i class="icon-pencil icon-large"></i>
                            </a>
                        <?php }?>     
                        </td>
                        
                    </tr>
                <?php } ?>
            </tbody>
        </table>
	</div>
	</div>
</div>

</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
    <input type="hidden" name="view" value="reviews"/>
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid');?>">
    
</form>
