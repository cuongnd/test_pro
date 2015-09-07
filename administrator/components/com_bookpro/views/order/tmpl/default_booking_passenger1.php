<div class="container-fluid">
    <?php echo $this->loadTemplate('booking_detail') ?>
    <div class="row">
        <button class="btn btn-primary btn-small "><span class="fa-edit"></span>Edit Passenger</button>
        <button class="btn btn-primary btn-small "><span class="im-plus"></span>Add Passenger</button>
        <button class="btn btn-primary btn-small "><span class="im-remove"></span>Delete Passenger</button>
    </div>
    <div class="row">
        <table class="adminlist table-striped table sortingtable">
            <thead>
            <tr>
                <th colspan="11" style="background: #e8e8e8;">&nbsp;</th>
            </tr>
            <tr>
                <th><input type="checkbox"></th>
                <th>ID
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>CUSTOMER NAME
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>TYPE
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>TOUR NAME
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>SER. CLASS
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>TOUR DATE
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>DEP. CODE
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>TOTAL VALUE
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>ASSIGN
                    <div class="icon-sorting" position="1"></div>
                </th>
                <th>ACTION
                    <div class="icon-sorting" position="1"></div>
                </th>
            </tr>

            </thead>

            <tbody>

            <?php foreach ($this->data->data_passenger as $item): ?>
                <tr>

                    <td><input type="checkbox"></td>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo $item->lastname . ' ' . $item->firstname; ?></td>
                    <td>Guest</td>
                    <td><?php echo $this->data->title; ?></td>
                    <td><?php echo $this->data->title_packagetype; ?></td>
                    <td><?php echo JHtml::_('date', $this->data->start); ?></td>
                    <td><?php echo $this->data->code; ?></td>
                    <td><?php echo $this->data->total; ?></td>
                    <td>Hien</td>
                    <td>
                        <button class="btn btn-primary btn-small pull-right"><span class="fa-edit"></span></button>
                        <button class="btn btn-primary btn-small pull-right"><span class="im-remove"></span>
                        </button>
                    </td>
                </tr>


            <?php endforeach; ?>

            <tr>
                <td colspan="11" style="background: #ffffff;">&nbsp;</td>
            </tr>
            </tbody>
        </table>
    </div>

</div>