<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('jquery.framework');
$input = JFactory::getApplication()->input;
// Checking if loaded via index.php or component.php
$tmpl = $input->getCmd('tmpl', '');
$app = JFactory::getApplication();
$document = JFactory::getDocument();
?>

<script type="text/javascript">


    setmenutype = function (type) {

        $ = jQuery;
        $('.div-loading').css({
            display: "block"


        });
        Joomla.submitbutton('item.setType', type);
        //window.location="index.php?option=com_menus&view=item&task=item.setType&layout=edit&type="+('item.setType', type);

        /*
         $.ajax({
         type: "GET",
         url: 'index.php',
         data: (function () {
         dataPost = {
         option: 'com_menus',
         task: 'item.aJaxSetType',
         type: type
         }
         return dataPost;
         })(),
         beforeSend: function () {

         $('.div-loading').css({
         display: "block"


         });
         },
         success: function (response) {
         sethtmlfortag(response);
         $('.div-loading').css({
         display: "none"

         });

         }
         });
         */
        $('#modal_menu_item_type').modal('hide');
        function sethtmlfortag(respone_array) {
            if (respone_array !== null && typeof respone_array !== 'object')
                respone_array = $.parseJSON(respone_array);
            $.each(respone_array, function (index, respone) {
                if (typeof(respone.type) !== 'undefined') {
                    $(respone.key.toString()).val(respone.contents);
                } else {
                    $(respone.key.toString()).html(respone.contents);
                }
            });
        }


        //window.location="index.php?option=com_menus&view=item&task=item.setType&layout=edit&type="+('item.setType', type);
    }
</script>
<div class="row-fluid">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?php $i=0 ?>
        <?php foreach ($this->types as $key => $type) { ?>
            <li class="<?php echo $i==0?'active':'' ?>">
                <a href="#<?php echo $key ?>" role="tab" data-toggle="tab">
                    <icon class="fa fa-home"></icon> <?php echo $key ?>
                </a>
            </li>
            <?php $i++ ?>
        <?php } ?>


    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <?php $i = 0; ?>
        <?php $j = 0; ?>
        <?php foreach ($this->types as $key => $type) { ?>

            <div class="tab-pane fade <?php echo $i==0?'active':'' ?>  in" id="<?php echo $key ?>">


                <?php echo JHtml::_('bootstrap.startAccordion', 'collapseTypes-'.$key, array('active' => 'slide1')); ?>
                
                <?php foreach ($type as $name => $list) : ?>
                    <?php echo JHtml::_('bootstrap.addSlide', 'collapseTypes', $name, 'collapse' . $j); ?>
                    <ul class="nav nav-tabs nav-stacked">
                        <?php foreach ($list as $title => $item) : ?>

                            <li>
                                <a class="choose_type" href="#" title="<?php echo JText::_($item->description); ?>"
                                   onclick="javascript:setmenutype('<?php echo base64_encode(json_encode(array('id' => $this->recordId,'backend'=>$key=='backend'?1:0, 'title' => (isset($item->type) ? $item->type : $item->title), 'request' => $item->request))); ?>')">
                                    <?php echo $title; ?>
                                    <small class="muted"><?php echo JText::_($item->description); ?></small>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php echo JHtml::_('bootstrap.endSlide'); ?>

                    <?php $j++; ?>
                <?php endforeach; ?>
                <?php echo JHtml::_('bootstrap.endAccordion'); ?>


            </div>
            <?php $i++; ?>
        <?php } ?>


    </div>

</div>


<script type="text/javascript">
    $ = jQuery;
    $('#collapseTypes').on('shown', function (event) {

    })
</script>
