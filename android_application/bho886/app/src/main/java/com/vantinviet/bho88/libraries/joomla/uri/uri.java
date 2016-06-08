package com.vantinviet.bho88.libraries.joomla.uri;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class uri {
    private static String link;
    private static uri ourInstance = new uri(link);

    public static uri getInstance(String link) {

        return ourInstance;
    }

    private uri(String link) {
    }
}
