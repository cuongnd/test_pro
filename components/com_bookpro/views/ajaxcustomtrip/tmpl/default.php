<?php
/*
 * $count_passengers
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHtmlBehavior::framework();
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('bootstrap.framework');

$document = JFactory::getDocument();

/* validate using jquery validate plugin */
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if ($local != 'en') {
	$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}

$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$app = JFactory::getApplication();
$input = $app->input;
$adult = $input->getInt('adult',0);


?>

                               
<?php for ($i = 0; $i < $adult; $i++) { ?>
  <div class="span10 passenger_itema">
		  <div class="row-fluid">
		   <div class="form-horizontal">
			 <div class="span3">
			 <div class="control-group  ">
                            <label class="control-label" for="firstname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>
                            </label>
                            <div class="controls">
                                <input class=" input-medium required firstname " type="text" 
                                       name="passenger[<?php echo $i ?>][firstname]" id="firstname" 
                                       value=""  />
                            </div>
                        </div>
			 </div>
			 <div class="span3">
			    <div class="control-group  ">
                            <label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>
                            </label>
                            <div class="controls">
                                <input class="input-medium required lastname" type="text" 
                                       name="passenger[<?php echo $i ?>][lastname]" 
                                       value="" />
                            </div>
                        </div>
			 </div>
			  <div class="span3">
			    <div class="control-group ">
                            <label class="control-label" for="birthday"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_BIRTHDAY'); ?>
                            </label>
                            <div class="controls">
                           
                                <input  class="input-small  required birthday hasDatepicker" type="text"  id="birthday"
                                       name="passenger[<?php echo $i ?>][birthday]" 
                                       value=""  />
                                 </div>
                        </div>
			 </div>
			 <div class="span3">
			     <div class="control-group">
                            <label class="control-label" for="type"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_TYPE'); ?>
                            </label>
                            <div class="controls input-small type" >
                           
                                  <?php echo $this->loadTypeGroup('passenger['.$i.'][group_id]','passenger_'.$i); ?>	
                               
                            </div>
                        </div>
			 </div>
			 </div>
			 </div>
		 
</div>
  
<?php } ?>
<?php
$document = JFactory::getDocument ();
// $document->addStyleSheet(JURI::base() . 'example.css');
$style = '
.form-horizontal .control-label {
		}		
		';
$document->addStyleDeclaration ( $style );
?>