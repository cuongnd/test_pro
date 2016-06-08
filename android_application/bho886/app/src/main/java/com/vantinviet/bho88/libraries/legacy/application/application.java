package com.vantinviet.bho88.libraries.legacy.application;

import com.vantinviet.bho88.libraries.cms.menu.menu;

/**
 * Created by cuongnd on 6/7/2016.
 */
public class application {
    public menu getMenu() {
        menu menu = com.vantinviet.bho88.libraries.cms.menu.menu.getInstance();
        return  menu;
    }
}
