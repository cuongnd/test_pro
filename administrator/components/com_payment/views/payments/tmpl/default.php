<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/9/2015
 * Time: 11:58 AM
 */
?>
<table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
        </tr>
      </thead>
      <tbody>
            <?php foreach($this->items as $item): ?>
                <tr>
                    <th><?php echo $item->id;?></th>
                    <th><a href="<?php echo JUri::root(); ?>administrator/index.php?option=com_payment&view=payment"><?php echo $item->title;?></a></th>
                </tr>
            <?php endforeach;?>
      </tbody>
    </table>