package com.vantinviet.bho88.libraries.joomla.cache.jstorage;

import android.os.Environment;

import com.vantinviet.bho88.libraries.joomla.filesystem.JFile;

import java.io.File;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class JCacheFile {
    public static final String CACHE_PATH = "cache";
    public static File root=Environment.getExternalStorageDirectory();
    public static String get_content_component(String md5_link) {
        String content="";
        content= JFile.read(md5_link, JCacheFile.CACHE_PATH);
        return content;
    }

    public static void set_content_component(String md5_link, String content) {
        String md5_link_file=md5_link + ".cache";
        JFile.write(md5_link_file,content, JCacheFile.CACHE_PATH);
    }
}
