package com.vantinviet.bho88.libraries.joomla;

import android.content.Context;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageManager;

import com.vantinviet.bho88.configuration.configuration;
import com.vantinviet.bho88.configuration.configuration_countdown;
import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.joomla.application.JApplication;
import com.vantinviet.bho88.libraries.joomla.uri.JUri;
import com.vantinviet.bho88.libraries.legacy.request.JRequest;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class JFactory {

    private static Object config;
    private static Context context;
    private static JApplication application;

    public static JMenu getMenu() {
        return JMenu.getInstance();
    }

    public static JUri getUri(String link) {
        return JUri.getInstance(link);

    }

    public static configuration getConfig() {
        Context context= JFactory.getContext();
        String app_name= JFactory.getAppLable(context);
        configuration config;
        switch (app_name) {
            case "countdown":
                config= new configuration_countdown();
                break;
            default:
                config= new configuration_countdown();
                break;
        }
        return config;
    }

    public static void setContext(Context context) {
        JFactory.context = context;
    }

    public static Context getContext() {
        return JFactory.context;
    }

    public static String getAppLable(Context context) {
        PackageManager packageManager = context.getPackageManager();
        ApplicationInfo applicationInfo = null;
        try {
            applicationInfo = packageManager.getApplicationInfo(context.getApplicationInfo().packageName, 0);
        } catch (final PackageManager.NameNotFoundException e) {
        }
        return (String) (applicationInfo != null ? packageManager.getApplicationLabel(applicationInfo) : "Unknown");
    }

    public static JRequest getRequest() {
        return JRequest.getInstance();
    }

    public static JApplication getApplication() {
        return JApplication.getInstance();
    }
}
