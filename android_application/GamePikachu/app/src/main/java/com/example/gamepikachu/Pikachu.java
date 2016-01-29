package com.example.gamepikachu;

import com.example.gamepikachu.DrawView;

import android.graphics.Color;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.app.Activity;
import android.view.Menu;
import android.widget.RelativeLayout;
public class Pikachu extends Activity 
{
	
	@Override
	protected void onCreate(Bundle savedInstanceState) 
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.layout_pikachu);
		//khoi tao 1 lan duy nhat
		// MediaPlayer bgSound =MediaPlayer.create(this, R.raw.bgm);
		 DrawView drawView = new DrawView(this);
		 
		// drawView.setBackgroundResource(R.drawable.bg3);
	     setContentView(drawView);
	     //bgSound.start();
	     drawView.requestFocus();
	     
		
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) 
	{
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.layout_pikachu, menu);
		return true;
	}
}
