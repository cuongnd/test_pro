package com.example.cuongnd.scheduling;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;

import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        ScheduledExecutorService scheduler =
                Executors.newSingleThreadScheduledExecutor();

        scheduler.scheduleAtFixedRate
                (new Runnable() {
                    public void run() {
                        System.out.println("helllo123");
                        // call service
                    }
                }, 0, 3, TimeUnit.SECONDS);
    }
}
