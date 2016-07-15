package com.vantinviet.phatthanhnghean;

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

        vtv_config.set_root_url("http://www.phatthanhnghean.vantinviet.com");
        JApplicationSite.start(this);
    }
}
