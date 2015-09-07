<?php
$doc=JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'/administrator/components/com_bookpro/assets/css/view-bookpro.css');
$user=JFactory::getUser();

?>
<!-- Start #sidebar -->
<div id=sidebar>
    <!-- Start .sidebar-inner -->
    <div class=sidebar-inner>
        <!-- Start #sideNav -->
        <ul id=sideNav class="nav nav-pills nav-stacked">
            <li class=top-search>
                <form>
                    <input name=search placeholder="Search ...">
                    <button type=submit><i class="ec-search s20"></i></button>
                </form>
            </li>
            <li><a href="index.php?option=com_bookpro">Dashboard <i class=im-screen></i></a></li>
            <li><a href="index.php?option=com_bookpro">Charts <i class=st-chart></i></a></li>
            <li><a href=#>Forms <i class=im-paragraph-justify></i></a>
                <ul class="nav sub">
                    <li><a href=forms.html><i class=ec-pencil2></i> Form Stuff</a></li>
                    <li><a href=form-validation.html><i class=im-checkbox-checked></i> Form Validation</a></li>
                    <li><a href=form-wizard.html><i class=im-wand></i> Form Wizard</a></li>
                    <li><a href=wysiwyg.html><i class=fa-pencil></i> WYSIWYG editor</a></li>
                </ul>
            </li>
            <li><a href=#>Tables <i class=im-table2></i></a>
                <ul class="nav sub">
                    <li><a href=tables.html><i class=en-arrow-right7></i> Static tables</a></li>
                    <li><a href=data-tables.html><i class=en-arrow-right7></i> Data tables</a></li>
                </ul>
            </li>
            <li><a href=#>UI Elements <i class=st-lab></i></a>
                <ul class="nav sub">
                    <li><a href=notifications.html><i class=fa-bell></i> Notifications</a></li>
                    <li><a href=panels.html><i class=br-window></i> Panels</a></li>
                    <li><a href=tiles.html><i class=im-windows8></i> Tiles</a></li>
                    <li><a href=elements.html><i class=st-cube></i> Elements</a></li>
                    <li><a href=icons.html><i class=im-stack></i> Icons</a></li>
                    <li><a href=buttons.html><i class=im-play2></i> Buttons</a></li>
                    <li><a href=calendar.html><i class=im-calendar2></i> Calendar</a></li>
                    <li><a href=grid.html><i class=st-grid></i> Grid</a></li>
                    <li><a href=typo.html><i class=im-font></i> Typography</a></li>
                    <li><a href=list.html><i class=fa-list></i> Lists</a></li>
                </ul>
            </li>
            <li><a href=#><i class=ec-mail></i> Email app</a>
                <ul class="nav sub">
                    <li><a href=email-inbox.html><i class=ec-archive></i> Inbox</a></li>
                    <li><a href=email-read.html><i class=br-eye></i> Read email</a></li>
                    <li><a href=email-write.html><i class=ec-pencil2></i> Write email</a></li>
                </ul>
            </li>
            <li><a href=file.html><i class=en-upload></i> File Manager</a></li>
            <li><a href=gallery.html><i class=im-images></i> Gallery</a></li>
            <li><a href=widgets.html><i class=st-diamond></i> Widgets</a></li>
            <li><a href=#><i class=ec-location></i> Maps</a>
                <ul class="nav sub">
                    <li><a href=maps-google.html><i class=im-map2></i> Google maps</a></li>
                    <li><a href=maps-vector.html><i class=en-location2></i> Vector maps</a></li>
                </ul>
            </li>
            <li><a href=#>Pages <i class=st-files></i></a>
                <ul class="nav sub">
                    <li><a href=timeline.html><i class=ec-clock></i> Timeline page</a></li>
                    <li><a href=invoice.html><i class=st-file></i> Invoice</a></li>
                    <li><a href=profile.html><i class=ec-user></i> Profile page</a></li>
                    <li><a href=search.html><i class=ec-search></i> Search page</a></li>
                    <li><a href=blank.html><i class=im-file4></i> Blank page</a></li>
                    <li><a href=login.html><i class=ec-locked></i> Login page</a></li>
                    <li><a href=lockscreen.html><i class=ec-locked></i> Lock screen</a></li>
                    <li><a href=#><i class=st-files></i> Error pages</a>
                        <ul class="nav sub">
                            <li><a href=400.html><i class=st-file-broken></i> Error 400</a></li>
                            <li><a href=401.html><i class=st-file-broken></i> Error 401</a></li>
                            <li><a href=403.html><i class=st-file-broken></i> Error 403</a></li>
                            <li><a href=404.html><i class=st-file-broken></i> Error 404</a></li>
                            <li><a href=405.html><i class=st-file-broken></i> Error 405</a></li>
                            <li><a href=500.html><i class=st-file-broken></i> Error 500</a></li>
                            <li><a href=503.html><i class=st-file-broken></i> Error 503</a></li>
                            <li><a href=offline.html><i class=st-window></i> Offline</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- End #sideNav -->
        <!-- Start .sidebar-panel -->
        <div class=sidebar-panel>
            <h4 class=sidebar-panel-title><i class=im-fire></i> Server usage</h4>
            <div class=sidebar-panel-content>
                <ul class=server-stats>
                    <li><span class=txt>Disk space</span> <span class=percent>78</span>
                        <div id=usage-sparkline class=sparkline>Loading...</div>
                        <div class=pie-chart data-percent=78></div>
                    </li>
                </ul>
                <ul class=server-stats>
                    <li><span class=txt>CPU</span> <span class=percent>56</span>
                        <div id=cpu-sparkline class=sparkline>Loading...</div>
                        <div class=pie-chart data-percent=56></div>
                    </li>
                </ul>
                <ul class=server-stats>
                    <li><span class=txt>Memory</span> <span class=percent>14</span>
                        <div id=ram-sparkline class=sparkline>Loading...</div>
                        <div class=pie-chart data-percent=14></div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- End .sidebar-panel -->
    </div>
    <!-- End .sidebar-inner -->
</div>
<!-- End #sidebar -->
<!-- Start #right-sidebar -->
<div id=right-sidebar class=hide-sidebar>
    <!-- Start .sidebar-inner -->
    <div class=sidebar-inner>
        <div class="sidebar-panel mt0">
            <div class="sidebar-panel-content fullwidth pt0">
                <div class=chat-user-list>
                    <form class="form-horizontal chat-search" role=form>
                        <div class=form-group>
                            <input class=form-control placeholder="Search for user...">
                            <button type=submit><i class="ec-search s16"></i></button>
                        </div>
                        <!-- End .form-group  -->
                    </form>
                    <ul class="chat-ui bsAccordion">
                        <li><a href=#>Favorites <span class="notification teal">4</span><i class=en-arrow-down5></i></a>
                            <ul class=in>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle <span class=has-message><i class=im-pencil></i></span></a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/54.jpg alt=@alagoon>Anthony Lagoon</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/52.jpg alt=@koridhandy>Kory Handy</a> <span class=status><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/50.jpg alt=@divya>Divia Manyan</a> <span class=status><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Online <span class="notification green">3</span><i class=en-arrow-down5></i></a>
                            <ul class=in>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/51.jpg alt=@kolage>Eric Hofman</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/55.jpg alt=@mikebeecham>Mike Beecham</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/53.jpg alt=@derekebradley>Darek Bradly</a> <span class="status online"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Offline <span class="notification red">5</span><i class=en-arrow-down5></i></a>
                            <ul>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/56.jpg alt=@laurengray>Lauren Grey</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/58.jpg alt=@frankiefreesbie>Frankie Freesibie</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/57.jpg alt=@joannefournier>Joane Fornier</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/59.jpg alt=@aiiaiiaii>Alia Alien</a> <span class="status offline"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class=chat-box>
                    <h5>Chad Engle</h5>
                    <a id=close-user-chat href=# class="btn btn-xs btn-primary"><i class=en-arrow-left4></i></a>
                    <ul class="chat-ui chat-messages">
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
                            <p class=chat-name>Chad Engle <span class=chat-time>15 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Hello Sugge check out the last order.</p>
                        </li>
                        <li class=chat-me>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/48.jpg alt="<?php echo $user->name ?>"></p>
                            <p class=chat-name><?php echo $user->name ?> <span class=chat-time>10 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Ok i will check it out.</p>
                        </li>
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/administrator/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
                            <p class=chat-name>Chad Engle <span class=chat-time>now</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Thank you, have a nice day</p>
                        </li>
                    </ul>
                    <div class=chat-write>
                        <form action=# class=form-horizontal role=form>
                            <div class=form-group>
                                <textarea name=sendmsg id=sendMsg class="form-control elastic" rows=1></textarea>
                                <a role=button class=btn id=attach_photo_btn><i class="fa-picture s20"></i></a>
                                <input type=file name=attach_photo id=attach_photo>
                            </div>
                            <!-- End .form-group  -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End .sidebar-inner -->
</div>
<!-- End #right-sidebar -->
<!-- Start #content -->
<div id=content>
    <!-- Start .content-wrapper -->
    <div class=content-wrapper>
        <div class=row>
            <!-- Start .row -->
            <!-- Start .page-header -->
            <div class="col-lg-12 heading">
                <h1 class=page-header><i class=im-screen></i> Main Dashboard</h1>
                <!-- Start .bredcrumb -->
                <ul id=crumb class=breadcrumb>
                </ul>
                <!-- End .breadcrumb -->
                <!-- Start .option-buttons -->
                <div class=option-buttons>
                    <div class=btn-toolbar role=toolbar>
                        <div class="btn-group dropdown"><a class="btn dropdown-toggle" data-toggle=dropdown id=dropdownMenu1><i class="br-grid s24"></i></a>
                            <div class="dropdown-menu pull-right" role=menu aria-labelledby=dropdownMenu1>
                                <div class=option-dropdown>
                                    <div class=shortcut-button><a href=#><i class=im-pie></i> <span>Earning Stats</span></a></div>
                                    <div class=shortcut-button><a href=#><i class="ec-images color-dark"></i> <span>Gallery</span></a></div>
                                    <div class=shortcut-button><a href=#><i class="en-light-bulb color-orange"></i> <span>Fresh ideas</span></a></div>
                                    <div class=shortcut-button><a href=#><i class="ec-link color-blue"></i> <span>Links</span></a></div>
                                    <div class=shortcut-button><a href=#><i class="ec-support color-red"></i> <span>Support</span></a></div>
                                    <div class=shortcut-button><a href=#><i class="st-lock color-teal"></i> <span>Lock area</span></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group dropdown"><a class="btn dropdown-toggle" data-toggle=dropdown id=dropdownMenu2><i class="ec-pencil s24"></i></a>
                            <div class="dropdown-menu pull-right" role=menu aria-labelledby=dropdownMenu2>
                                <div class=option-dropdown>
                                    <div class=row>
                                        <p class=col-lg-12>Quick post</p>
                                        <form class=form-horizontal role=form>
                                            <div class=form-group>
                                                <div class=col-lg-12>
                                                    <input class=form-control placeholder="Enter title">
                                                </div>
                                            </div>
                                            <!-- End .form-group  -->
                                            <div class=form-group>
                                                <div class=col-lg-12>
                                                    <textarea class="form-control wysiwyg" placeholder="Enter text"></textarea>
                                                </div>
                                            </div>
                                            <!-- End .form-group  -->
                                            <div class=form-group>
                                                <div class=col-lg-12>
                                                    <input class="form-control tags1" placeholder="Enter tags">
                                                </div>
                                            </div>
                                            <!-- End .form-group  -->
                                            <div class=form-group>
                                                <div class=col-lg-12>
                                                    <button class="btn btn-default btn-xs">Save Draft</button>
                                                    <button class="btn btn-success btn-xs pull-right">Publish</button>
                                                </div>
                                            </div>
                                            <!-- End .form-group  -->
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=btn-group><a class="btn dropdown-toggle" data-toggle=dropdown id=dropdownMenu3><i class="ec-help s24"></i></a>
                            <div class="dropdown-menu pull-right" role=menu aria-labelledby=dropdownMenu3>
                                <div class=option-dropdown>
                                    <p>First time visitor ? <a href=# id=app-tour class="btn btn-success ml15">Take app tour</a></p>
                                    <hr>
                                    <p>Or check the <a href=# class="btn btn-danger ml15">FAQ</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End .option-buttons -->
            </div>
            <!-- End .page-header -->
        </div>
        <!-- End .row -->
        <div class=outlet>
            <!-- Start .outlet -->
            <!-- Page start here ( usual with .row ) -->
            <div class=row>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <!-- col-lg-4 start here -->
                    <div class="panel panel-default plain profile-widget">
                        <!-- Start .panel -->
                        <div class="panel-heading white-bg pl0 pr0"><img class="profile-image img-responsive" src=assets/img/profile-cover.jpg alt="profile cover"></div>
                        <div class=panel-body>
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class=profile-avatar><img class=img-responsive src=assets/img/avatars/132.jpg alt=@roybarberuk></div>
                            </div>
                            <div class="col-lg-8 col-md-12 col-sm-12">
                                <div class=profile-name>SuggeElson <span class="label label-success">admin</span></div>
                                <div class=profile-quote>
                                    <p>Building new app with yeoman generator plus grunt and bower, it`s awesome</p>
                                </div>
                                <div class=profile-stats-info><a href=# class=tipB title=Views><i class=im-eye2></i> <strong>5600</strong></a> <a href=# class=tipB title=Comments><i class=im-bubble></i> <strong>75</strong></a> <a href=# class=tipB title=Likes><i class=im-heart></i> <strong>45</strong></a></div>
                            </div>
                        </div>
                        <div class="panel-footer white-bg">
                            <ul class=profile-info>
                                <li><i class=ec-mobile></i> +234 345 887</li>
                                <li><i class=ec-location></i> Spain, Barcelona</li>
                                <li><i class=ec-mail></i> suggeelson@suggelson.com</li>
                                <li><i class=im-office></i> Web developer</li>
                                <li><i class=fa-bitbucket></i> code@suggelab.com</li>
                            </ul>
                        </div>
                    </div>
                    <!-- End .panel -->
                </div>
                <!-- col-lg-4 end here -->
                <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
                    <!-- col-lg-12 start here -->
                    <div class="panel panel-default plain">
                        <!-- Start .panel -->
                        <div class="panel-heading white-bg">
                            <h4>Latest activity</h4>
                        </div>
                        <div class=panel-body>
                            <div class=timeline>
                                <!-- Start .timeline -->
                                <ul class=timeline-list>
                                    <li>
                                        <div class=timeline-time><small>just now</small></div>
                                        <div class=timeline-icon><i class=fa-bitbucket></i></div>
                                        <div class=timeline-content>
                                            <p><a href=#><strong>SuggeElson push 1 commit</strong></a></p>
                                            <p>Pushed 1 commit to <a href=#>SprFlat - admin template</a></p>
                                            <p><a href=#>385d312</a> - add new reload styles</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class=timeline-time><small>2 min ago</small></div>
                                        <div class=timeline-icon><i class=fa-dribbble></i></div>
                                        <div class=timeline-content>
                                            <p><a href=#><strong>SuggeElson add 1 new dribbble shot</strong></a></p>
                                            <p><img alt=Hammerdribbble src=assets/img/hammer.png width=150></p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class=timeline-time><small>17 min ago</small></div>
                                        <div class=timeline-icon><img class=timeline-avatar src=assets/img/avatars/48.jpg alt=SuggeElson></div>
                                        <div class=timeline-content>
                                            <p><a href=#><strong>SuggeElson upload 3 pictures</strong></a></p>
                                            <p><img src=assets/img/instagram/instagram.jpg alt=pic width=150> <img src=assets/img/instagram/instagram2.jpg alt=pic width=150> <img src=assets/img/instagram/instagram3.jpg alt=pic width=150></p>
                                        </div>
                                    </li>
                                    <li class=load-more><a href=# class="btn btn-primary">Load more</a></li>
                                </ul>
                            </div>
                            <!-- End .timeline -->
                        </div>
                    </div>
                    <!-- End .panel -->
                </div>
                <!-- col-lg-8 end here -->
            </div>
            <!-- Page End here -->
        </div>
        <!-- End .outlet -->
    </div>
    <!-- End .content-wrapper -->
    <div class=clearfix></div>
</div>
<!-- End #content -->