<?php
defined('_JEXEC') or die('Restricted access');
AImporter::model('destinations', 'agents', 'users', 'cpayorderstatus');
$model = new BookProModelUser();
$dataUsers = $model->getItems();

$id = JFactory::getApplication()->input->get('cid', 0);
$order = new BookProModelOrder();
$this->data = $order->getInfotourById($id);


//JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.framework');




foreach ($this->data->data_room as $k => $item) {

    if ($item->title == "Single room") {
        $single[] = $item->title;
    }
    if ($item->title == "Twin room") {
        $twin[] = $item->title;
    }
    if ($item->title == "Double room") {
        $double[] = $item->title;
    }
    if ($item->title == "triple room") {
        $triple[] = $item->title;
    }

}

?>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
// Change customer
        var id_order = "<?php echo $id; ?>";
        $('.i-customer').click(function () {
            if ($('.customer').children().hasClass("input-custommer")) {
                return;
            }

            data = $('.booking-details-info').find('.customer').text();
            $('.customer').html(' <input type="text" class="input-custommer-firstname" title="first name" value="<?php echo $this->data->firstname;?>" style="height:100%;width:35%"><input type="text" class="input-custommer-lastname" title="last name" value="<?php echo $this->data->lastname;?>" style="height:100%;width:35%"><i class="fa-save" title="save"></i><i class="im-cancel-circle cancel-customer" title="cancel"></i>');

            //button cacel
            $('.cancel-customer').click(function () {
                $('.customer').text(data);
            })
            //button save
            $('.fa-save').click(function () {
                lastname = $(".input-custommer-lastname").val();
                firstname = $(".input-custommer-firstname").val();
                $.ajax({
                    method: "GET",
                    url: "index.php?option=com_bookpro&controller=order&task=ajax_updatecustomer",
                    data: {
                        id_order: id_order,
                        lastname: lastname,
                        firstname: firstname
                    },
                    dataType: "text",
                    beforeSend: function () {
                        $('.customer').text('loading...');
                    },
                    success: function () {
                        $('.customer').text(firstname + ' ' + lastname);

                    },
                    error: function () {
                        alert('Error');
                    }

                })
            })
        })
// END change customer

// Change Assigned
        $('.i-assigned').click(function () {
            if ($('.assigned').children().hasClass("input-assigned")) {
                return;
            }
            olddataID =<?php echo ($this->data->assigned_id?$this->data->assigned_id:0); ?>;
            olddata = $('.booking-details-info').find('.assigned').text();

            html = "<select id='select-assigned' style='width:75%;height:100%'>";
            <?php foreach ($dataUsers as $dataUser):?>
            html += "<option value='<?php echo $dataUser->id; ?>' id='<?php echo $dataUser->id; ?>'>";
            html += "<?php echo $dataUser->name; ?>";
            html += "</option>";
            <?php endforeach;?>
            html += "</select>";
            html += "<i class='fa-save save-assigned' title='save'></i><i class='im-cancel-circle cancel-assigned' title='cancel'></i>";


            $('.assigned').html(html);
            $('#select-assigned').val(olddataID);
            // button cancel
            $('.cancel-assigned').click(function () {
                $('.assigned').text(olddata);
            })


            //button save
            $('.fa-save').click(function () {
                newval = $("#select-assigned").val();
                newval = $("#" + newval).text();
                id =<?php echo $id; ?>;
                assigned = $("#select-assigned").val();
                $.ajax({
                    method: "POST",
                    url: "index.php?option=com_bookpro&controller=order&task=ajax_updateassigned",
                    data: {
                        assigned: assigned,
                        id: id
                    },
                    dataType: "text",
                    beforeSend: function () {
                        $('.assigned').text('loading...');
                    },
                    success: function () {

                        $('.assigned').text(newval);
                    },
                    error: function () {
                        alert('Error');
                    }

                })
            })

        })
// END change Assigned

//Change order status
        oldselected = $(".orderstatus").val();
        oldhtml = $(".orderstatus").html();
        $(".orderstatus").change(function () {
            newselected = $(this).val();
            id =<?php echo $id; ?>;
            $.ajax({
                method: "POST",
                url: "index.php?option=com_bookpro&controller=order&task=ajax_update_orderstatus",
                data: {
                    newselected: newselected,
                    id: id
                },
                dataType: "text",
                beforeSend: function () {
                    $(".orderstatus").html("<option>loading...</option>");

                },
                success: function (html) {
                    $(".orderstatus").html(oldhtml);
                    $(".orderstatus").val(newselected);
                    $('#current-selected-paystatus').text($("#" + newselected).text());
                    alert("Success");

                },
                error: function () {
                    alert('Error');
                    $(".orderstatus").html(oldhtml);
                    $(oldselected).attr("selected='selected'")
                }

            })
        })
//END order status

    })
</script>


<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 3/24/2015
 * Time: 10:02 AM
 */

$lessInput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/less/view-reservation-default.less';
$cssOutput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/css/view-reservation-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/assets/css/view-reservation-default.css');
//
//JHTML::_('behavior.modal');

?>

<div class="container-fluid">
    <div role="tabpanel" class="booking-tab">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#general" aria-controls="home" role="tab" data-toggle="tab">general</a></li>
            <li role="presentation"><a href="#passenger" aria-controls="passenger" role="tab" data-toggle="tab">passenger</a></li>
            <li role="presentation"><a href="#rooming" aria-controls="rooming" role="tab" data-toggle="tab">rooming</a></li>
            <li role="presentation"><a href="#addons" aria-controls="addons" role="tab" data-toggle="tab">addons</a></li>
            <li role="presentation"><a href="#conversation" aria-controls="conversation" role="tab" data-toggle="tab">conversation</a></li>
            <li role="presentation"><a href="#payment" aria-controls="payment" role="tab" data-toggle="tab">payment</a></li>
            <li role="presentation"><a href="#operation" aria-controls="operation" role="tab" data-toggle="tab">operation</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="general">
                <?php echo $this->loadTemplate('booking_general') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="passenger">
                <?php echo $this->loadTemplate('booking_passenger1') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="rooming">
                <?php echo $this->loadTemplate('booking_rooming') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="addons">
                <?php echo $this->loadTemplate('booking_add_ons') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="conversation">
                <?php echo $this->loadTemplate('booking_conversation') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="payment">
                <?php echo $this->loadTemplate('booking_payment') ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="operation">
                <?php echo $this->loadTemplate('booking_operation') ?>
            </div>
        </div>

    </div>
    <?php
    echo $this->loadTemplate('tour_popup_add_adhoc');
    echo $this->loadTemplate('tour_popup_addnote');
    echo $this->loadTemplate('tour_popup_add_addpassenger');
    echo $this->loadTemplate('tour_popup_add_addons');
    echo $this->loadTemplate('tour_popup_add_addflight');
    ?>
</div>




































<?php
$this->setlayout('tour');

?>
<?php
echo $this->loadTemplate('default');
?>



<input type="hidden" name="order_id" value="<?php echo $this->order->id; ?>"/>
<?php echo FormHelper::bookproHiddenField(array('controller' => 'order', 'task' => '', 'Itemid' => JRequest::getInt('Itemid'))) ?>







