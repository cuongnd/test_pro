package com.vantinviet.bho88.libraries.joomla.uri;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class JUri {
    private static String link;
    private static JUri ourInstance = new JUri(link);

    public static JUri getInstance(String link) {

        return ourInstance;
    }

    private JUri(String link) {
    }
}
