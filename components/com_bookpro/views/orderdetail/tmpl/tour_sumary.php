<style>
    .title_summary{
        text-transform:uppercase;
        font-weight:bold;
        font-size:14px;
        padding-left:10px;
    }
    .content_summary{
        background:#ecf1f5;
    }
    .details_summary{

        color:#990000;
        font-weight:bold;
        line-height:15px;
    }
</style>
<div style="background:#ecf1f5;">
    <div class="row-fluid">
        <div class="span5 title_summary">
            summary
        </div>
        <div class="span6 details_summary">
            <p style="padding-bottom:10px;">Total Price  :<?php echo CurrencyHelper::formatprice($this->sum); ?>  </p>
            <p>Deposit : <?php echo CurrencyHelper::formatprice(0); ?></p>
            <p>Balance :  <?php echo CurrencyHelper::formatprice(0); ?></p>
        </div>
    </div>
</div>