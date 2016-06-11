package com.vantinviet.bho88.libraries.cms.application;

/**
 * Created by cuongnd on 6/10/2016.
 */
public class JApplicationSite {
    private static JApplicationSite ourInstance = new JApplicationSite();

    public static JApplicationSite getInstance() {
        return ourInstance;
    }

    private JApplicationSite() {
    }
}
