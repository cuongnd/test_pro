<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 12/3/2015
 * Time: 3:04
 */
?>
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
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle <span class=has-message><i class=im-pencil></i></span></a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/54.jpg alt=@alagoon>Anthony Lagoon</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/52.jpg alt=@koridhandy>Kory Handy</a> <span class=status><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/50.jpg alt=@divya>Divia Manyan</a> <span class=status><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Online <span class="notification green">3</span><i class=en-arrow-down5></i></a>
                            <ul class=in>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/51.jpg alt=@kolage>Eric Hofman</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/55.jpg alt=@mikebeecham>Mike Beecham</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/53.jpg alt=@derekebradley>Darek Bradly</a> <span class="status online"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Offline <span class="notification red">5</span><i class=en-arrow-down5></i></a>
                            <ul>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/56.jpg alt=@laurengray>Lauren Grey</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/58.jpg alt=@frankiefreesbie>Frankie Freesibie</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/57.jpg alt=@joannefournier>Joane Fornier</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/59.jpg alt=@aiiaiiaii>Alia Alien</a> <span class="status offline"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class=chat-box>
                    <h5>Chad Engle</h5>
                    <a id=close-user-chat href=# class="btn btn-xs btn-primary"><i class=en-arrow-left4></i></a>
                    <ul class="chat-ui chat-messages">
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
                            <p class=chat-name>Chad Engle <span class=chat-time>15 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Hello Sugge check out the last order.</p>
                        </li>
                        <li class=chat-me>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/48.jpg alt=SuggeElson></p>
                            <p class=chat-name>SuggeElson <span class=chat-time>10 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Ok i will check it out.</p>
                        </li>
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
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
<div id=right-sidebar-tool class=hide-sidebar>
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
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle <span class=has-message><i class=im-pencil></i></span></a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/54.jpg alt=@alagoon>Anthony Lagoon</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/52.jpg alt=@koridhandy>Kory Handy</a> <span class=status><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/50.jpg alt=@divya>Divia Manyan</a> <span class=status><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Online <span class="notification green">3</span><i class=en-arrow-down5></i></a>
                            <ul class=in>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/51.jpg alt=@kolage>Eric Hofman</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/55.jpg alt=@mikebeecham>Mike Beecham</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/53.jpg alt=@derekebradley>Darek Bradly</a> <span class="status online"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Offline <span class="notification red">5</span><i class=en-arrow-down5></i></a>
                            <ul>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/56.jpg alt=@laurengray>Lauren Grey</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/58.jpg alt=@frankiefreesbie>Frankie Freesibie</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/57.jpg alt=@joannefournier>Joane Fornier</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/59.jpg alt=@aiiaiiaii>Alia Alien</a> <span class="status offline"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class=chat-box>
                    <h5>Chad Engle</h5>
                    <a id=close-user-chat href=# class="btn btn-xs btn-primary"><i class=en-arrow-left4></i></a>
                    <ul class="chat-ui chat-messages">
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
                            <p class=chat-name>Chad Engle <span class=chat-time>15 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Hello Sugge check out the last order.</p>
                        </li>
                        <li class=chat-me>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/48.jpg alt=SuggeElson></p>
                            <p class=chat-name>SuggeElson <span class=chat-time>10 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Ok i will check it out.</p>
                        </li>
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
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