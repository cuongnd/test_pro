package com.vantinviet.bho88;

import android.os.Environment;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.UnsupportedEncodingException;

/**
 * Created by cuongnd on 12/17/2015.
 */
public class config {
    public  static  String get_version()
    {
        String content="1";
        if(content!="")
        {
            return content;
        }
        File root = new File(Environment.getExternalStorageDirectory(), "cache");
        if (!root.exists()) {
            root.mkdirs();
        }
        File cofig_file = new File(root, "config.xml");
        if (cofig_file.exists()) {

            try {
                BufferedReader br = new BufferedReader(new FileReader(cofig_file));
                try {
                    StringBuilder sb = new StringBuilder();
                    String line = br.readLine();

                    while (line != null) {
                        sb.append(line);
                        sb.append(System.lineSeparator());
                        line = br.readLine();
                    }
                    content = sb.toString();
                } finally {
                    br.close();
                }


            } catch (FileNotFoundException e) {
                return "";
            } catch (UnsupportedEncodingException e) {
                return "";
            } catch (IOException e) {
                return "";
            }

        }
        return content;
    }
}
