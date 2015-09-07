formBase sucsecfull
<br/>
please click next to continus, if you want auto matic setup website, please check chexbox auto setup
<input type="hidden" name="currentStep" value="formBase">
<?php if(count($this->errors)){ ?>
    <ul class="errors">
    <?php foreach($this->errors as $error){ ?>
        <li class="error"><?php echo $error ?></li>
    <?php } ?>
    </ul>
<?php } ?>
