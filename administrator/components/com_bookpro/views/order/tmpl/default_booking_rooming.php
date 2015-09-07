<div  class="container-fluid" >
    <?php echo $this->loadTemplate('booking_detail') ?>
    <div class="row">
        <button class="btn btn-primary btn-small pull-left"><span class="im-eye"></span>Room listing</button>
        <button class="btn btn-primary btn-small pull-left"><span class="im-eye"></span>building room</button>
    </div>

    <div class="row">
        <div class="panel panel-primary ">
            <!-- Start .panel -->
            <div class=panel-heading>
                <h4 class=panel-title></h4>
            </div>
            <div class=panel-body>
                <div class="row">
                    <div class="col-md-3">
                        <div class="panel panel-info">
                            <!-- Start .panel -->
                            <div class=panel-heading>
                                <h4 class=panel-title>Single room</h4>
                            </div>
                            <div class=panel-body>
                                <ul>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-info">
                            <!-- Start .panel -->
                            <div class=panel-heading>
                                <h4 class=panel-title>double room</h4>
                            </div>
                            <div class=panel-body>
                                <ul>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-info">
                            <!-- Start .panel -->
                            <div class=panel-heading>
                                <h4 class=panel-title>Twin room</h4>
                            </div>
                            <div class=panel-body>
                                <ul>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="panel panel-info">
                            <!-- Start .panel -->
                            <div class=panel-heading>
                                <h4 class=panel-title>triple room</h4>
                            </div>
                            <div class=panel-body>
                                <ul>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                    <li>item 1</li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary toggle panelClose showControls">
            <!-- Start .panel -->
            <div class=panel-heading>
                <h4 class=panel-title>Room 1</h4>
            </div>
            <div class=panel-body>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-xs-3">
                            <label class="room-select">
                                Single
                                <br/>
                                <input type="radio" class="noStyle" name="roomtype" id="single"
                                       value="single">
                            </label>
                        </div>
                        <div class="col-xs-3">
                            <label class="room-select">
                                Double
                                <br/>
                                <input type="radio" class="noStyle" name="roomtype" id="double"
                                       value="double">
                            </label>
                        </div>
                        <div class="col-xs-3">
                            <label class="room-select">
                                Twin
                                <br/>
                                <input type="radio" class="noStyle" name="roomtype" id="twin"
                                       value="twin">
                            </label>
                        </div>
                        <div class="col-xs-3">
                            <label class="room-select">
                                Triple
                                <br/>
                                <input type="radio" class="noStyle" name="roomtype" id="triple"
                                       value="triple">
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="adult_teener">Adult/Teener</label>
                                <br/>
                                <select name="adult_teener">
                                    <option value="0">0 person</option>
                                    <?php foreach ($this->data->data_passenger as $item){ ?>
                                        <option><?php echo "{$item->fisrtname} {$item->lastname}"   ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="children_infant">Children/Infant</label>
                                <br/>
                                <select name="adult_teener">
                                    <option value="0">0 person</option>
                                    <?php foreach ($this->data->data_passenger as $item){ ?>
                                        <option><?php echo "{$item->fisrtname} {$item->lastname}"   ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="children_infant">Select your room and master</label>
                        <br/>
                        <select name="adult_teener">
                            <option value="0">0 person</option>
                            <?php foreach ($this->data->data_passenger as $item){ ?>
                                <option><?php echo "{$item->fisrtname} {$item->lastname}"   ?></option>
                            <?php } ?>
                        </select>
                        <br/>
                                            <textarea style="width: 100%">

                                            </textarea>
                    </div>

                </div>
                <div class="col-md-4">
                    <h4>Passenger in room</h4>
                    <ul>
                        <li>item 1</li>
                        <li>item 1</li>
                        <li>item 1</li>
                        <li>item 1</li>
                    </ul>
                </div>
            </div>
            <div class="panel-footer teal-bg">
                <div class="container-fluid">
                    <button type="button" class="btn btn-danger  pull-right"><i class="im-plus"></i>Add</button>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <button type="button" class="btn btn-danger  pull-right"><i class="im-plus"></i>Add more room</button>
        </div>
    </div>


</div>
