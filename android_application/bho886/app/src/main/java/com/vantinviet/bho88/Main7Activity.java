package com.vantinviet.bho88;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;

import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

public class Main7Activity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main7);
        ScheduledExecutorService scheduler =
                Executors.newSingleThreadScheduledExecutor();

        scheduler.scheduleAtFixedRate
                (new Runnable() {
                    public void run() {
                        // call service
                        System.out.println("hello123");
                    }
                }, 0, 10, TimeUnit.MINUTES);

    }


}
