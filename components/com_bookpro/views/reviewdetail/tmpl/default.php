<?php
jimport('joomla.html.html.bootstrap');

?>

<style type="text/css">
    .controltop5{
        margin-top:5px;
    }
    
</style>

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
    <form method="POST" id="adminForm" name="adminForm" action="index.php" class="form-horizontal">
    
    <h3><?php echo JText::_('COM_BOOKPRO_REVIEW_DETAIL') ?></h3>
    
    <div class="control-group">
    	<label class="control-label">
    		<strong><?php echo JText::_('COM_BOOKPRO_REVIEW_YOUR_TRIP'); ?></strong>
    	</label>
    	<div class="controls controltop5">
    		<?php echo $this->tour; ?>
    	</div>
    </div>
    <div class="control-group">
			<label class="control-label" for="date"> 
				<strong><?php echo JText::_('COM_BOOKPRO_REVIEW_DATE') ?></strong>
			</label>
			<div class="controls controltop5">
			<?php
				$date=''; 
				if($this->obj->date){
					$date = JFactory::getDate($this->obj->date)->format('Y-m-d');
				}
				?>
				<?php echo $date; ?>
			</div>
		</div>
    <div class="control-group">
    	<label class="control-label">
    		<strong><?php echo JText::_('COM_BOOKPRO_REVIEW_TITLE'); ?></strong>
    	</label>
    	<div class="controls controltop5">
    		<?php echo $this->obj->title?>
    	</div>
    </div>
   <div class="control-group">
   		<label class="control-label">
   			<strong><?php echo JText::_('COM_BOOKPRO_REVIEW_OVERALL_RATING'); ?></strong>
   		</label>
   		<div class="controls controltop5">
   			<?php echo $this->rank; ?>
   		</div>
   </div>
   <div class="control-group">
			<label class="control-label" for="pickup">
				<strong><?php echo JText::_('COM_BOOKPRO_REVIEW_IMAGE') ?></strong>
			</label>
			<div class="controls">
	                        <?php 
	                        	$urlimagebasic = JURI::root() . 'components/com_bookpro/assets/images/imagesmd5/';
	                        ?>
                        	<?php if($this->obj->image!=''){ ?>
                            	<img  id="returnuploadimage" src="<?php echo $urlimagebasic.$this->obj->image; ?>" style="with: 200px; height: 150px; padding-left:0px; padding-right:0px; border:1px solid #808080;" />
                            <?php }?>
			</div>
		</div>
	<div class="control-group">
		<label class="control-label">
			<strong><?php echo JText::_('COM_BOOKPRO_REVIEW_YOUR_THOUGHTS') ?></strong>
		</label>
		<div class="controls">
			<textarea rows="5" cols="40" name="content" disabled="disabled"><?php echo $this->obj->content ?></textarea>
		</div>
	</div>	
    
    
    <div class="comment">
        <h4></h4>
        <p>A couple of sentences summarising your thoughts</p>
    </div>
     <input type="hidden" name="option" value="com_bookpro" />
    <input type="hidden" name="controller" value="review" />
    <input type="hidden" id="task" name="task" value="" />
    <input type="hidden" id="id" name="id" value="<?php echo $this->obj->id; ?>" />
    <?php echo JHTML::_('form.token'); ?>
    </form>
</div>