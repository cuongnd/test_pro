package com.vantinviet.saleonline;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;

import com.vantinviet.vtv.VTVConfig;
import com.vantinviet.vtv.libraries.cms.application.JApplicationSite;
import com.vantinviet.vtv.libraries.joomla.JFactory;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        VTVConfig vtv_config= JFactory.getVTVConfig();
        System.out.println("sdfsdfsdfsdfsdf");
        vtv_config.set_root_url("http://www.banhangonline88.com");
        JApplicationSite.start(this);
        System.out.println("hello sale online");
    }
}
