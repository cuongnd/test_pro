<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('jquery.framework');
JHtml::_('behavior.framework');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.modal');
JHtml::_('formbehavior.chosen', 'select');

JText::script('ERROR');
JText::script('JGLOBAL_VALIDATION_FORM_FAILED');

$app = JFactory::getApplication();
$assoc = JLanguageAssociations::isEnabled();

//Ajax for parent items
$script = "jQuery(document).ready(function ($){
				$('#jform_menutype').change(function(){
					var menutype = $(this).val();
					$.ajax({
						url: 'index.php?option=com_menus&task=item.getParentItem&menutype=' + menutype,
						dataType: 'json'
					}).done(function(data) {
						$('#jform_parent_id option').each(function() {
							if ($(this).val() != '1') {
								$(this).remove();
							}
						});

						$.each(data, function (i, val) {
							var option = $('<option>');
							option.text(val.title).val(val.id);
							$('#jform_parent_id').append(option);
						});
						$('#jform_parent_id').trigger('liszt:updated');
					});
				});
			});";

// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration($script);

?>

<script type="text/javascript">
    Joomla.submitbutton = function (task, type) {
        if (task == 'item.setType' || task == 'item.setMenuType') {
            if (task == 'item.setType') {
                document.id('item-form').elements['jform[type]'].value = type;
                document.id('fieldtype').value = 'type';
            } else {
                document.id('item-form').elements['jform[menutype]'].value = type;
            }
            Joomla.submitform('item.setType', document.id('item-form'));
        } else if (task == 'item.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.id('item-form'));
        }
        else {
            // special case for modal popups validation response
            $$('#item-form .modal-value.invalid').each(function (field) {
                var idReversed = field.id.split("").reverse().join("");
                var separatorLocation = idReversed.indexOf('_');
                var name = idReversed.substr(separatorLocation).split("").reverse().join("") + 'name';
                document.id(name).addClass('invalid');
            });

            $('system-message').getElement('h4').innerHTML = Joomla.JText._('ERROR');
            $('system-message').getElement('div').innerHTML = Joomla.JText._('JGLOBAL_VALIDATION_FORM_FAILED');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_menus&layout=edit&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="item-form" class="form-validate">

    <?php
    if ($this->item->type == 'url') {
        $this->form->setFieldAttribute('alias', 'type', 'hidden');
    }
    ?>
    <?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div class="form-horizontal">

        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_MENUS_ITEM_DETAILS', true)); ?>
        <div class="row-fluid">
            <div class="span9">
                <div class="item-type">
                    <?php echo $this->loadtemplate('type'); ?>
                </div>
                <?php
                echo $this->form->getControlGroup('browserNav');
                echo $this->form->getControlGroup('template_style_id');
                ?>
            </div>
            <div class="span3">
                <?php
                // Set main fields.
                $this->fields = array(
                    'id',
                    'menu_type_id',
                    'parent_id',
                    'menuordering',
                    'published',
                    'hidden',
                    'home',
                    'access',
                    'language',
                    'note'

                );
                if ($this->item->type != 'component') {
                    $this->fields = array_diff($this->fields, array('home'));
                }
                ?>
                <?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>

        <?php
        $this->fieldsets = array();
        $this->ignore_fieldsets = array('aliasoptions', 'request');
        echo JLayoutHelper::render('joomla.edit.params', $this);
        ?>

        <?php if ($assoc) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'associations', JText::_('JGLOBAL_FIELDSET_ASSOCIATIONS', true)); ?>
            <?php echo $this->loadTemplate('associations'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>

        <?php if (!empty($this->modules)) : ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'modules', JText::_('COM_MENUS_ITEM_MODULE_ASSIGNMENT', true)); ?>
            <?php echo $this->loadTemplate('modules'); ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        <?php endif; ?>

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>

    <input type="hidden" name="task" value=""/>
    <?php echo $this->form->getInput('component_id'); ?>
    <?php echo JHtml::_('form.token'); ?>
    <input type="hidden" id="fieldtype" name="fieldtype" value=""/>
</form>
<script type="text/javascript">
    function aJaxGetOptionsMenuItem() {

        $ = jQuery;

        $('.menu-item-type').attr('')

        menu_type_id = $('select[name="jform[menu_type_id]"]').val();
        currentLink=$('input[name="jform[link]"]').val();
        type=$('input[name="jform[type]"]').val();
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function () {
                dataPost = {
                    option: 'com_menus',
                    task: 'item.aJaxGetOptionsMenuItem',
                    menu_type_id: menu_type_id,
                    currentLink:currentLink,
                    type:type

                }
                return dataPost;
            })(),
            beforeSend: function () {
                $('.div-loading').css({
                    display: "block"


                });
                // $('.loading').popup();
            },
            success: function (response) {
                $('.div-loading').css({
                    display: "none"


                });
                response= $.parseJSON(response);
                $('#jform_parent_id').html($(response.parent_id).html());
                $('#jform_parent_id').trigger('liszt:updated');
                if(response.clearLink==1)
                {
                    $('input[name="jform[link]"]').val('');
                }
                //$('#item-form').submit();

            }
        });

    }
</script>
<style type="text/css">
    .div-loading
    {
        display: none;
        background: url("<?php echo JUri::root() ?>/global_css_images_js/images/loading.gif") center center no-repeat;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%
    }
</style>