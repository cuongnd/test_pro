<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 4/20/2015
 * Time: 2:56 PM  margin-left: 12%;
 */
$icon_class			= $params->get('Icon','0');
?>
<script src="<?php echo JUri::root().'modules/mod_toolbar_pro/assets/mod_toolbar_pro.js'?>"></script>

<style>
    body .class-ul{
        position: absolute;
        border: 1px solid rgba(0, 0, 0, 0.15);
        list-style: none;
        z-index: 99999;
        display: none;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.176);

    }
</style>
<div class="class-icon" style="margin: 5px; background: blue">
    <a id="icon" href="#" style="color: #fff"><i class="<?php echo $icon_class ?> open_data"></i></a>
    <ul class="class-ul">
        <li>test 1</li>
        <li>test 2</li>
    </ul>
</div>
