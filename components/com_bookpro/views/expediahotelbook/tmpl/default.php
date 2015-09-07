<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper("currency", "date", 'form');
AImporter::css('customer');
AImporter::js('customer');
JHtmlBehavior::formvalidation();
JHtmlBehavior::modal('a.modal_hotel');
$doc = JFactory::getDocument();
$doc->addScript(Juri::root() . '/components/com_bookpro/assets/js/jquery.maskedinput-1.3.1.min');
$doc->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/expedia.css');
$action = 'index.php?option=com_bookpro&controller=hotel&Itemid=' . JRequest::getVar("Itemid");
$this->array_star=array(
    "star1"=>"1"
    ,"star1.5"=>"star1-5"
    ,"star2"=>"star2"
    ,"star2.5"=>"star2-5"
    ,"star3"=>"star3"
    ,"star3.5"=>"star3-5"
    ,"star4"=>"star4"
    ,"star4.5"=>"star4-5"
,"star5"=>"star5"
);

?>
<form name="frontForm" method="post" action="<?php echo $action ?>" class="form-validate">

    <div class="row-fluid">

        <div class="span8">
            <?php
            echo $this->loadTemplate('customer');
            ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo JText::_('COM_BOOKPRO_REVIEW_AND_BOOK_YOUR_TRIP') ?></h3>
                </div>
                <div class="panel-body">
                    <div class="row-fluid">
                        <b>Important Information request your book :</b>
                        <ul>
                            <li>Request this book first is not refundable and can not be changed or canceled .</li>
                        </ul>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row-fluid">
                        <div class="row-fluid checkbox"><label><input type="checkbox" name="accept">I have read and accept the <a href="#rules_limits" data-toggle="modal">rules & limits <i class="icon icon-new-windows"></i></a> , <a href="#">terms & conditions<i class="icon icon-new-windows"></i></a> and <a href="#">privacy policy<i class="icon icon-new-windows"></i></a> .<label></div>
                        <!-- Modal -->
                        <div id="rules_limits" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel">rules & limits header</h3>
                            </div>
                            <div class="modal-body">
                                <p>rules & limits body…</p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                            </div>
                        </div>

                        <div class="row-fluid">
                            <input type="submit" name="btnSubmit" value="<?php echo JText::_('COM_BOOKPRO_CONTINUE') ?>" class="btn btn-large btn-primary"/>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="span4 booking-info">
            <h2 class="headline-bar headline-bar-alt"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_TRIP_SUMARY'); ?></h2>

            <div class="booking-info-wapper">
                <?php echo $this->loadTemplate("sumary"); ?>
                <?php echo $this->loadTemplate("room"); ?>
            </div>
        </div>




        <?php
        $hidden = array('controller' => 'expediahotel', 'task' => 'step2', 'id' => $this->hotel->id, 'customer_id' => $this->customer->id);
        echo FormHelper::bookproHiddenField($hidden);
        echo JHtml::_('form.token');
        ?>
    </div>
</form>
<script type="text/javascript">
    jQuery(function ($) {

    });
</script>
<style type="text/css">

    .hoteltitle {
        color: #CF0F16;
        font-size: 12px;
        line-height: 19px;

    }

    .booking-info {
        border: 1px solid #CECECE;
        border-radius: 4px 4px 0 0;
    }

    .booking-info .headline-bar-alt {
        margin: 0;
    }

    .booking-info-wapper {
        padding: 10px;
    }
</style>



