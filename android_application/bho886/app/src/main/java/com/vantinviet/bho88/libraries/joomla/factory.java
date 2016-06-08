package com.vantinviet.bho88.libraries.joomla;

import android.content.Context;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageManager;

import com.vantinviet.bho88.MainActivity;
import com.vantinviet.bho88.R;
import com.vantinviet.bho88.configuration.configuration;
import com.vantinviet.bho88.configuration.configuration_countdown;
import com.vantinviet.bho88.libraries.cms.menu.menu;
import com.vantinviet.bho88.libraries.joomla.uri.uri;

import java.util.Objects;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class factory {

    private static Object config;
    private static Context context;

    public static menu getMenu() {
        return menu.getInstance();
    }

    public static uri getUri(String link) {
        return uri.getInstance(link);

    }

    public static configuration getConfig() {
        Context context=factory.getContext();
        String app_name=factory.getAppLable(context);
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
        factory.context = context;
    }

    public static Context getContext() {
        return factory.context;
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
}
