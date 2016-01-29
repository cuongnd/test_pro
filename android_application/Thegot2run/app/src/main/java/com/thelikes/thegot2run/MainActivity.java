package com.thelikes.thegot2run;



import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Menu;
import android.view.View;


public class MainActivity extends Activity {
	
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);	
	}

	public void play(View v)
	{
		Intent i=new Intent(this,Game.class);
		startActivity(i);
	}
	
	public void setting(View v)
	{
		Intent i=new Intent(this,Setting.class);
		startActivity(i);
	}
	
	public void exit(View v)
	{
		System.exit(0);
	}
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.main, menu);
		return true;
	}
	

	
	

}
