<?php
defined('_JEXEC') or die('Restricted access');


$app = JFactory::getApplication();
$doc = JFactory::getDocument();
JHtml::_('behavior.framework');



?>
<form class="form-inline" action='<?php echo JRoute::_($action) ?>' method="post" id="frm_tour_search"
      name="tour_search">
    <div class="row col-md-12">
        <?php if ($keyword_param) { ?>
            <div class="form-group col-md-3">
                <!--<span class="glyphicon glyphicon-pencil"></span>-->
                <input type="text" class="form-control" value="<?php echo $cart->filter['keyword'] ?>"
                       placeholder="keyword">
            </div>
        <?php } ?>

        <?php if ($category) { ?>
            <div class="form-group col-md-3">
                <span class="glyphicon glyphicon-th"></span>
                <?php echo $cats ?>
            </div>
        <?php } ?>

        <?php if ($duration_param) { ?>
            <div class="form-group col-md-3">
                <span class="glyphicon glyphicon-th"></span>
                <?php echo $duration ?>
            </div>
        <?php } ?>

        <?php if ($country_param) { ?>
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-th"></span>
            <?php echo $country ?>
        </div>
    </div>
    <div class="row col-md-12" style="margin: 10px 0">
        <div class="col-md-4">
            <span class="glyphicon glyphicon-th"></span>
            <?php echo $dests ?>
        </div>
        <?php } else {?>
        <div class="col-md-4"></div>
        <?php }?>
        <div class="col-md-5" style="margin-bottom: 10px">
            <table>
                <tr>
                    <td style="padding: 5px 10px">Adults( 12+ )</td>
                    <td style="padding: 5px 10px">Child( 2-11 )</td>
                    <td style="padding: 5px 10px">Infant( <2)</td>
                </tr>
                <tr>
                    <td style="padding: 0px 10px">
                        <select class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </td>
                    <td style="padding: 0px 10px">
                        <select class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </td>
                    <td style="padding: 0px 10px">
                        <select class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-3">
            <a href=""
               style="float: right; margin-right: 60px; color: red;margin-top: 15px;font-size: 18px">GO&nbsp;<span
                    class="glyphicon glyphicon-play-circle"></span> </a>
        </div>
    </div>
    <input type="hidden" name="option" value="com_bookpro">
    <input type="hidden" name="controller" value="tour">
    <input type="hidden" name="task" value="searchadv">
    <?php echo JHtmlForm::token() ?>
</form>

<script type="text/javascript">
    jQuery(document).ready(function ($) {

        $("#btn-toursearch").click(function () {
            $("#frm_tour_search").submit();
        });

        returnDestinations();

        $("select#country_id").change(function () {
            returnDestinations();
        });

        function returnDestinations() {
            $selected_country = $("select#country_id").val();
            if ($selected_country > 0) {
                $.ajax({
                    type: "GET",
                    url: "index.php?option=com_bookpro&controller=customer&task=getcity&format=raw",
                    data: "country_id=" + $selected_country,
                    beforeSend: function () {
                        $("select#dest_id")
                            .html('<option><?php echo JText::_('MOD_LOADING')?></option>');
                    },
                    success: function (result) {
                        $("select#dest_id").html(result);
                        $("select#dest_id").val("<?php echo $cart->filter['dest_id']?>");
                    }
                });

            }
        }
    });
</script>
