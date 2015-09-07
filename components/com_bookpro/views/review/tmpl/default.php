<?php
jimport('joomla.html.html.bootstrap');
//JToolBarHelper::save();
JHtmlBehavior::modal('a.jbmodal');
JHtmlBehavior::modal('a.modal');
JHtmlBehavior::formvalidation();
AImporter::css('general');
AImporter::js('view-images','common');
$cart = JModelLegacy::getInstance('ReviewCart', 'bookpro');
 
$cart->load();
?>

<style type="text/css">
    img {
        padding-right: 6px;
        padding-left: 9px;
    }
    
    input[type="radio"] {
        margin-left: -8px !important; 
    }
    
    div.from-inline label.inline {
        margin-right: 30px !important;
    }
    
    
</style>
<script type="text/javascript">       
 Joomla.submitbutton = function(task) {
      var form = document.adminForm;
      if (task == 'cancel') {
         form.task.value = task;
         form.submit();
         return;
      }
      if (document.formvalidator.isValid(form)) {
         form.task.value = task;
         form.submit();
       }
       else {
         alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>');
         return false;
       }
   }
	</script>
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
    
    <h3><?php echo JText::_('COM_BOOKPRO_REVIEW_WRITE_A_REVIEW') ?></h3>
    
    <div class="control-group">
    	<label class="control-label">
    		<?php echo JText::_('COM_BOOKPRO_REVIEW_YOUR_TRIP'); ?>
    	</label>
    	<div class="controls">
    		<?php echo $this->tour; ?>
    	</div>
    </div>
    <div class="control-group">
			<label class="control-label" for="date"> <?php echo JText::_('COM_BOOKPRO_REVIEW_DATE') ?>
			</label>
			<div class="controls">
			<?php
				$date=''; 
				if($this->obj->date){
					$date = JFactory::getDate($this->obj->date)->format('Y-m-d');
				}else{
					if($this->cart){
						if($this->cart->date){
							$date = $this->cart->date;		
						}
					}
				}
				?>
				<?php echo JHtml::calendar($date, 'date', 'date', '%Y-%m-%d') ?>
			</div>
		</div>
    <div class="control-group">
    	<label class="control-label"><?php echo JText::_('COM_BOOKPRO_REVIEW_TITLE'); ?></label>
    	<div class="controls">
    		<input type="text" name="title" value="<?php echo $this->obj->title?>"/>
    	</div>
    </div>
   <div class="control-group">
   		<label class="control-label">
   			<?php echo JText::_('COM_BOOKPRO_REVIEW_OVERALL_RATING'); ?>
   		</label>
   		<div class="controls from-inline">
   			<?php echo $this->rank; ?>
   		</div>
   </div>
   <div class="control-group">
    	<label class="control-label">
    		<?php echo JText::_('COM_BOOKPRO_COUNTRY'); ?>
    	</label>
    	<div class="controls">
    		<?php echo $this->country; ?>
    	</div>
    </div>
   <div class="control-group">
			<label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_REVIEW_IMAGE') ?>
			</label>
			<div class="controls">
				<?php AImporter::tpl('ajaximage', 'form', 'image',SITE_VIEWS); ?>
			</div>
		</div>
	<div class="control-group">
		<label class="control-label"><?php echo JText::_('COM_BOOKPRO_REVIEW_YOUR_THOUGHTS') ?></label>
		<div class="controls">
			<textarea rows="5" cols="40" name="content"><?php echo $this->obj->content ?></textarea>
		</div>
	</div>	
    
    
    <div class="comment">
        <h4></h4>
        <p>A couple of sentences summarising your thoughts</p>
    </div>
    <a href="javascript:void(0);" onclick="Joomla.submitbutton('save')">
    	<input type="button" name="button" value="Submit" class="btn btn-success">
    </a>
    
    <input type="hidden" name="firstname" id="firstname" value="<?php echo $cart->firstname; ?>" />
    <input type="hidden" name="lastname" id="lastname" value="<?php echo $cart->lastname; ?>" />
    <input type="hidden" name="email" id="email" value="<?php echo $cart->email; ?>" />
    <input type="hidden" name="option" value="com_bookpro" />
    <input type="hidden" name="controller" value="review" />
    <input type="hidden" id="task" name="task" value="" />
    <input type="hidden" id="Itemid" name="Itemid" value="<?php echo JRequest::getVar('Itemid');?>" />
    <input type="hidden" id="id" name="id" value="<?php echo $this->obj->id; ?>" />
    <?php echo JHTML::_('form.token'); ?>
    </form>
</div>