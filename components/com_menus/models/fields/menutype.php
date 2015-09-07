<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class JFormFieldMenutype extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'menutype';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
        $app=JFactory::getApplication();
		$html 		= array();
		$recordId	= (int) $this->form->getValue('id');
		$size		= ($v = $this->element['size']) ? ' size="' . $v . '"' : '';
		$class		= ($v = $this->element['class']) ? ' class="' . $v . '"' : 'class="text_area"';
        $doc=JFactory::getDocument();
		// Get a reverse lookup of the base link URL to Title
		$model 	= JModelLegacy::getInstance('menutypes', 'menusModel');
        $menu_type_id=(int) $this->form->getValue('menu_type_id');
		$rlu 	= $model->getReverseLookup($menu_type_id);
		switch ($this->value)
		{
			case 'url':
				$value = JText::_('COM_MENUS_TYPE_EXTERNAL_URL');
				break;

			case 'alias':
				$value = JText::_('COM_MENUS_TYPE_ALIAS');
				break;

			case 'separator':
				$value = JText::_('COM_MENUS_TYPE_SEPARATOR');
				break;

			case 'heading':
				$value = JText::_('COM_MENUS_TYPE_HEADING');
				break;

			default:
				$link	= $this->form->getValue('link');
				// Clean the link back to the option, view and layout
				$value	= JText::_(JArrayHelper::getValue($rlu, MenusHelper::getLinkKey($link)));
				break;
		}

		// Load the javascript and css
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal');

		$html[] = '<span class="input-append"><input type="text" disabled="disabled" readonly="readonly" id="' . $this->id . '" value="' . $value . '"' . $size . $class . ' /><a class="btn btn-primary menu-item-type" href="#modal_menu_item_type" data-toggle="modal"><i class="icon-list icon-white"></i> '.JText::_('JSELECT').'</a></span>';
		$html[] = '<input class="input-small" type="hidden" name="' . $this->name . '" value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '" />';
        ob_start();
        ?>
        <!-- Modal -->
        <div id="modal_menu_item_type" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Modal header</h3>
            </div>
            <div class="modal-body" style="text-align: center">
                <img src="<?php echo JUri::root() ?>/global_css_images_js/images/loading.gif">
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary">Save changes</button>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                menu_type_id = $('select[name="jform[menu_type_id]"]').val();
                recordId= <?php echo $app->input->getInt('id',0) ?>;
                $('#modal_menu_item_type').on('show', function (event) {

                    modal_menu_item_type=$(event.target).attr('id');
                    if(modal_menu_item_type=='modal_menu_item_type')
                    {
                        $.ajax({
                            type: "GET",
                            url: 'index.php',
                            data: (function () {
                                dataPost = {
                                    option: 'com_menus',
                                    task: 'menutypes.aJaxGetMenuItemType',
                                    menu_type_id: menu_type_id,
                                    recordId: recordId
                                }
                                return dataPost;
                            })(),
                            beforeSend: function () {

                                // $('.loading').popup();
                            },
                            success: function (response) {
                                sethtmlfortag(response);

                            }
                        });
                    }

                });
                function sethtmlfortag(respone_array)
                {
                    if(respone_array !== null && typeof respone_array !== 'object')
                        respone_array = $.parseJSON(respone_array);
                    $.each(respone_array, function(index, respone) {
                        if(typeof(respone.type) !== 'undefined')
                        {
                            $(respone.key.toString()).val(respone.contents);
                        }else {
                            $(respone.key.toString()).html(respone.contents);
                        }
                    });
                }
            });

        </script>
        <?php
        $htmlModal=ob_get_clean();
        $html[]=$htmlModal;
        //index.php?option=com_menus&view=menutypes&tmpl=component&recordId='.$recordId

		return implode("\n", $html);
	}
}
