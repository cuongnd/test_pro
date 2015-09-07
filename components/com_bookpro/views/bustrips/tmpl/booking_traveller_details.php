<?php
$user=JFactory::getUser();
$spanLeft=$user->id?'span12':'span8';
$spanRight='span4';
AImporter::model('customer');
$cmodel=new BookProModelCustomer();
$currentCustomer=$cmodel->getObjectByUserId();

?>
<div class="row-fluid traveller-detail">
    <div class="row-fluid header"><h3><?php echo JText::_('Traveller detail') ?></h3></div>
    <div class="row-fluid body">
        <div class="sub-wrapper-content">
            <div class="<?php echo $spanLeft ?> input-info form-vertical">
                <div class="row-fluid">
                    <div class="control-group span3">
                        <label class="control-label" for="gender"><?php echo JText::_('Gender'); ?>
                        </label>
                        <div class="controls">
                            <select class="input-small">
                                <option value="male"><?php echo JText::_('Male') ?></option>
                                <option value="female"><?php echo JText::_('Female') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group span4">
                        <label class="control-label" for="firstname"><?php echo JText::_('First name'); ?>
                        </label>
                        <div class="controls">
                            <input type="text" class="input-medium required" value="<?php echo $currentCustomer->firsname ?>" name="firstname" >
                        </div>
                    </div>
                    <div class="control-group span5">
                        <label class="control-label" for="lastname"><?php echo JText::_('Last name'); ?>
                        </label>
                        <div class="controls">
                            <input type="text" class="input-medium required" value="<?php echo $currentCustomer->lastname ?>" name="lastname" >
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="control-group span7">
                        <label class="control-label" for="phone"><?php echo JText::_('Mobile phone'); ?>
                        </label>
                        <div class="controls">
                            <div class="plus pull-left">+</div>
                            <div class="code pull-left">code</div>
                            <div class="pull-left"><input type="text" class="input-medium  required" value="<?php echo $currentCustomer->phone ?>" name="phone" placeholder="<?php echo JText::_('Enter number phone') ?>" ></div>
                        </div>
                    </div>
                    <div class="control-group span5">
                        <label class="control-label" for="gender"><?php echo JText::_('Email'); ?>
                        </label>
                        <div class="controls">
                            <input type="email" value="<?php echo $currentCustomer->email ?>" class="input-medium required" name="email"  >
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="control-group span7">
                        <label class="control-label" for="birthday"><?php echo JText::_('Birthday'); ?>
                        </label>
                        <div class="controls select-date">
                            <div class="pull-left">
                                <select name="birthday-day" class="input-small required">
                                    <option value=""><?php echo JText::_('Date') ?></option>
                                    <?php for($i=1;$i<=31;$i++){ ?>
                                    <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="pull-left">
                                <select name="birthday-moth" class="input-small pull-left month required">
                                    <option value=""><?php echo JText::_('Month') ?></option>
                                    <?php for($i=1;$i<=12;$i++){ ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php
                            $now=JFactory::getDate()->year;
                            ?>
                            <div class="pull-left">
                                <select name="birthday-year" class="input-small pull-left year required">
                                    <option value=""><?php echo JText::_('Year') ?></option>
                                    <?php for($i=$now-18;$i>=$now-80;$i--){ ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="control-group span5">
                        <label class="control-label" for="nationality"><?php echo JText::_('Nationality'); ?>
                        </label>
                        <div class="controls">
                            <select name="nationality" class="nationality required">
                                <option value=""><?php echo JText::_('Nationality') ?></option>
                                <?php for($i=1;$i<count($this->listNationality);$i++){ ?>
                                    <?php
                                    $country=$this->listNationality[$i];
                                    ?>
                                    <option value="<?php echo $country->id ?>"><?php echo $country->title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <?php if(!$user->id){ ?>
        <div class="<?php echo $spanRight ?> login-account">
            <div class="sub-wrapper-content">
                <div class="header">
                    <?php echo JText::_('Login')?>
                </div>
                <div class="body">
                    <div class="control-group">
                        <label class="control-label" for="username"><?php echo JText::_('Username'); ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="username" class="required" placeholder="<?php echo JText::_('User name') ?>">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="password"><?php echo JText::_('Password'); ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="password" class="required" placeholder="<?php echo JText::_('Password') ?>">
                        </div>
                    </div>
                    <div class="login-error"> </div>
                    <div>
                        <input type="button" class="login btn-primary input-submit-login" value="<?php echo JText::_('Sign in-Book faster ') ?>">
                    </div>

                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
