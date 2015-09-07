<div class="span12 additionnaltrip">
    <?php
    $this->interval_i = 0;
    ?>
    <?php foreach ($this->list_addone as $addone) { ?>
        <?php $this->a_addon = $addone ?>
        <?php echo $this->loadTemplate("additionnaltripitem") ?>
        <?php $this->interval_i++; ?>
    <?php } ?>
</div>

