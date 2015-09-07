<style>
    .title_payment_details{
        font-weight:bold;
    }
    .form_payment_details .check_payment{
        font-weight:bold;
        padding-right:30px;
    }
    .card_number{
        border:1px solid #0c0c0c!important;
        box-shadow:none!important;
        height:15px!important;
        margin-top:4px;
        margin-bottom:4px;
    }
    .cvv_paymment_details{

        text-transform:uppercase;
    }
    .card_number_1{
        border:1px solid #0c0c0c!important;
        box-shadow:none!important;
        height:15px!important;

    }
    .form_date{
        margin-bottom:5px;
    }
    .confirm_paymment{
        font-weight:bold;
    }
    .content_payment_details{
        border:2px solid #cccccc;
        padding:10px;
    }
    .pay_now{
        background:#95a5a5;
        text-transform:uppercase;
        color:#fff;
        border:none;
        box-shadow:none!important;
    }
    .pay_now:hover{
        background:#95a5a5;
        color:#fff;
    }
    .clr{
        clear:both;
    }
    .content_title_payment_details{
        text-transform:uppercase;
        color:#990000;
        font-size:14px;
        font-weight:bold;
    }	
</style>
<div class="payment_details">
    <?php echo JText::_('COM_BOOKPRO_PAYMENT_COMPLETE') ?>

</div>
<script type="text/javascript">
    setTimeout(function() {
        window.location.href = 'index.php';
    }, 2000);
</script>