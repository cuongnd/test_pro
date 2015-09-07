<?php
$doc=JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'/templates/sprflat/html/mod_menu/assets/admin.css');
$doc->addScript(JUri::root().'/templates/sprflat/html/mod_menu/assets/admin.js');
$uri=JFactory::getURI();
$url=JUri::root();

?>

<div class="subhead">
    <div class="container-fluid">
        <div class="navbar-header">
            <button data-target=".bs-example-js-navbar-collapse" data-toggle="collapse" type="button" class="navbar-toggle collapsed">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="#" class="navbar-brand"><i class="glyphicon glyphicon-edit"></i><?php echo $url ?></a>
        </div>
        <div class="collapse navbar-collapse bs-example-js-navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle" href="#" id="drop1">
                        Dropdown
                        <span class="caret"></span>
                    </a>
                    <ul aria-labelledby="drop1" role="menu" class="dropdown-menu">
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Action</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Another action</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Something else here</a></li>
                        <li class="divider" role="presentation"></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Separated link</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle" href="#" id="drop2">
                        Dropdown
                        <span class="caret"></span>
                    </a>
                    <ul aria-labelledby="drop2" role="menu" class="dropdown-menu">
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Action</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Another action</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Something else here</a></li>
                        <li class="divider" role="presentation"></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Separated link</a></li>
                    </ul>
                </li>
            </ul>


            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown" id="fat-menu">
                    <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle" href="#" id="drop3">
                        Dropdown
                        <span class="caret"></span>
                    </a>
                    <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Action</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Another action</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Something else here</a></li>
                        <li class="divider" role="presentation"></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Separated link</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="fat-menu">
                    <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle" href="#" id="drop3">
                        Dropdown
                        <span class="caret"></span>
                    </a>
                    <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Action</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Another action</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Something else here</a></li>
                        <li class="divider" role="presentation"></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem">Separated link</a></li>
                    </ul>
                </li>
                <li class="dropdown" id="fat-menu">
                    <a aria-expanded="false" class="turn_off_setting" role="button" aria-haspopup="true"   href="#" id="drop3">
                        <i class="glyphicon glyphicon-off"></i>
                        Turn of setting
                    </a>
                </li>
                <li class="dropdown" id="fat-menu">
                    <a aria-expanded="false" class="preview" role="button" aria-haspopup="true"   href="#" id="drop3">
                        <i class="glyphicon glyphicon-eye-open"></i>
                        Preview
                    </a>
                </li>

                <li class="dropdown" id="fat-menu">
                    <a aria-expanded="false" role="button" aria-haspopup="true" data-toggle="dropdown" class="dropdown-toggle" href="#" id="drop3">
                        <i class="glyphicon glyphicon-user"></i>
                        hello admin
                        <span class="caret"></span>
                    </a>
                    <ul aria-labelledby="drop3" role="menu" class="dropdown-menu">
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem" ><i class="glyphicon glyphicon-user"></i>User profile</a></li>
                        <li role="presentation"><a href="https://twitter.com/fat" tabindex="-1" role="menuitem" ><i class="glyphicon glyphicon-cog"></i>Setting</a></li>
                        <li class="divider" role="presentation"></li>
                        <li role="presentation"><a href="<?php echo JUri::root() ?>/index.php?option=com_users&task=user.logout&<?php echo JSession::getFormToken() ?>=1" tabindex="-1" role="menuitem" ><i class="glyphicon glyphicon-off"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.nav-collapse -->
    </div><!-- /.container-fluid -->
</div>




