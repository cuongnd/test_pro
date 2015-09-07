createComponents

<?php if(count($this->errors)){ ?>
    <ul class="errors">
        <?php foreach($this->errors as $error){ ?>
            <li class="error"><?php echo $error ?></li>
        <?php } ?>
    </ul>
<?php } ?>

<input type="hidden" name="currentStep" value="createComponents">