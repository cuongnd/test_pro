<?php
defined('_JEXEC') or die();

JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
?>
<div class = "container-fluid">
    <div class = "span3">
        <?php
        $layout = new JLayoutFile('cmenu', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
        $html = $layout->render($this->customer);
        echo $html;
        ?>
    </div>
    <div class = "span9">
        <br>
        <a href = "index.php?option=com_bookpro&view=message&layout=edit" class = "btn btn-primary">New Message</a>
        <form action = "<?php echo JRoute::_('index.php?option=com_bookpro&view=messages'); ?>" method = "post" name = "adminForm" id = "adminForm">
            <div id = "filter-bar" class = "btn-toolbar">
                <div class = "btn-group pull-right hidden-phone">
                    <label>
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE_STATUS'); ?></label>
                            <select class="input-small" onchange="submitform()"  name="messager_state" >
                                <option value="close"><?php echo JText::_('Close') ?></option>
                                <option  value="open"><?php echo JText::_('Open') ?></option>
                            </select>

                </div>
            </div>
            <div class = "clearfix"></div>

            <table class = "table" style = "margin-top: 20px;">
                <thead>
                <tr>

                    <th class = "title" width = "10%">
                        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_MESSAGE_SUBJECT'), 'subject', $orderDir, $order); ?>
                    </th>
                    <th width = "20%">
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE'); ?>
                    </th>
                    <th width = "10%">
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE_USER_STATE'); ?>
                    </th>
                    <th width = "5%">
                        <?php echo JText::_('COM_BOOKPRO_CREATED_DATE'); ?>
                    </th>




                    <th width = "5%">
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE_REPLY'); ?>
                    </th>
                </tr>
                </thead>
                <tfoot>

                </tfoot>
                <tbody>

                <?php
                AImporter::model('customer');
                $modelCustomer=new BookProModelCustomer();
                $customer=$modelCustomer->getObjectByUserId();


                foreach ($this->items as $i => $item) {

                    $item->max_ordering = 0;
                    $ordering = ($listOrder == 'a.ordering');
                    ?>
                    <tr class = "row<?php echo $i % 2; ?>">
                        <td> <a href = "<?php echo JRoute::_(ARoute::view('message', null, null, array('parent_id' => $item->id, 'layout' => 'edit'))); ?>" class = ""><?php echo $item->subject; ?></a></td>

                        <td> <?php echo BookProHelper::sub_string(strip_tags($item->message), 120, $more = '...', $encode = 'utf-8'); ?> </td>

                        <td>
                            <select class="input-small" data-id="<?php echo $item->id ?>" name="user_state" >
                                <option <?php echo $item->user_state=='close'?'selected':'' ?>  value="close"><?php echo JText::_('Close') ?></option>
                                <option <?php echo $item->user_state=='open'?'selected':'' ?> value="open"><?php echo JText::_('Open') ?></option>
                            </select>
                        </td>
                        <td>
                            <?php
                            $date = new DateTime($item->created);
                            $date = $date->format('d-M-Y H:i');
                            echo $date;
                            ?>
                        </td>
                        <td> <a href = "<?php echo JRoute::_(ARoute::view('message', null, null, array('parent_id' => $item->id, 'layout' => 'edit'))); ?>" class = "btn btn-info">Reply</a> </td>
                    </tr>
                    <!-- End message  -->
                <?php
                }
                ?>
                </tbody>
            </table>
        </form>
        <!-- End form -->
    </div>
</div>
<script type="text/javascript">

    jQuery(document).ready(function($){
        $(document).on('change','select[name="user_state"]',function(){
            id=$(this).attr('data-id');
            user_state=$(this).val();
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'message',
                        task: 'change_user_state',
                        id:id,
                        user_state:user_state
                    }
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                }
            });

        });
    });

</script>