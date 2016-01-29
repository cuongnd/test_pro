package hoc.android.gamepuzzle;



import hoc.android.gamepuzzle.view.GameBoard;
import hoc.android.gamepuzzle.view.ListPictureAdapter;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.List;


import com.google.ads.AdRequest;
import com.google.ads.AdView;



import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.res.Configuration;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Point;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.preference.PreferenceManager;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.*;
import android.view.View.OnClickListener;
import android.view.animation.AccelerateInterpolator;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.view.animation.TranslateAnimation;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TableLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ViewFlipper;

public class MainAppActivity extends Activity implements OnClickListener,OnItemClickListener{
    /** Called when the activity is first created. */
	/**
	 * @Objective : param
	 * @author MobileteamVN
	 */
	private ViewFlipper viewFliper;
	private Context aContext;
	private int maxValueWidth;
	public TextView tvTitle;
	public Button btn;
	public ListView lv;
	public int typeList;
	public int positionList;
	
	public SharedPreferences prefs;
	public SharedPreferences.Editor editor;
	
	Boolean bplay;
	Button playMusic;
	float xCurrent,xLast;
	
	 TableLayout tableLayout;
	 private GameBoard board;
	   private Bitmap bitmap; // temporary holder for puzzle picture
	   private boolean numbersVisible = false; // Whether a title is displayed that
	                                  // shows the correct location of the
	                                 // tiles.
	//xCurrent=xLast=0;
	//Spring
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        requestWindowFeature(Window.FEATURE_NO_TITLE);// no title app
        aContext = getBaseContext();
		setContentView(R.layout.mainslide);
		viewFliper = (ViewFlipper)findViewById(R.id.vfMainFliper1);					
		Display display = ((WindowManager) getSystemService(Context.WINDOW_SERVICE)).getDefaultDisplay(); 
		maxValueWidth = display.getWidth()*3/4;					
		tableLayout = (TableLayout) findViewById(R.id.parentLayout);    
		prefs = PreferenceManager.getDefaultSharedPreferences(this);
		
		
		showMainMenu(false);	
		
		
    }

	/**
	 * @param     : N/A
	 * @Objective : Show Main Screen
	 */
	private void showMainMenu(boolean statusBack) {		
		if(statusBack)slide_back(SLIDE_MAIN_SCREEN, true);
		else slide(SLIDE_MAIN_SCREEN,true);			
		btn=(Button) findViewById(R.id.btnAnimal);
        btn.setOnClickListener(this);
        btn=(Button) findViewById(R.id.btnFlower);
        btn.setOnClickListener(this);
        btn=(Button) findViewById(R.id.btnFood);
        btn.setOnClickListener(this);
        btn=(Button) findViewById(R.id.btnNatural);
        btn.setOnClickListener(this);
        
	}
	
	/**
	 * @param     : N/A
	 * @Objective : Show List Picture
	 */
	private void showListPicture(boolean statusBack,int type) {		
		if(statusBack)slide_back(SLIDE_LIST_PICTURE_SCREEN, true);
		else slide(SLIDE_LIST_PICTURE_SCREEN,true);
		/* AdView adview = (AdView)findViewById(R.id.adView1);
	        // Initiate a generic request to load it with an ad
	        AdRequest re = new AdRequest();
	        //re.setTesting(true);
	        re.setGender(AdRequest.Gender.FEMALE); 
	        adview.loadAd(re);*/
	        
		ListView lv=(ListView) findViewById(R.id.lvPictureAsia);
        System.out.println(""+type );               
        lv.setAdapter(new ListPictureAdapter(this,type));
        lv.setOnItemClickListener(this);			
	}

	/**
	 * @param     : N/A
	 * @Objective : Show List Picture
	 */
	private void showViewPicture(boolean statusBack,int type,int position) {		
		if(statusBack)slide_back(SLIDE_VIEW_PICTURE_SCREEN, true);
		else slide(SLIDE_VIEW_PICTURE_SCREEN,true);		
		openOptionsMenu();
		ImageView img=(ImageView)findViewById(R.id.imgViewPicture);
		/* AdView adview = (AdView)findViewById(R.id.adView2);
	        // Initiate a generic request to load it with an ad
	        AdRequest re = new AdRequest();
	        //re.setTesting(true);
	        re.setGender(AdRequest.Gender.FEMALE); 
	        adview.loadAd(re);*/
		try {
			switch (type) {
			case MAIN_MENU_ANIMAL:				
				img.setImageBitmap(createScaledBitmap(pictureAnimal[position]));
				break;
			case MAIN_MENU_FOOD :						
				img.setImageBitmap(createScaledBitmap(pictureFood[position]));
				break;
			case MAIN_MENU_FLOWER:
				img.setImageBitmap(createScaledBitmap(pictureFlower[position]));
				break;
			case MAIN_MENU_NATURAL:
				img.setImageBitmap(createScaledBitmap(pictureNatural[position]));	
				break;
			default:								
				break;
			}
			
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IllegalArgumentException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}
	/**
	 * @param     : N/A
	 * @Objective : Show screen Puzzle
	 */
	private void showPuzzlePicture(boolean statusBack,int type,int position) {		
		if(statusBack)slide_back(SLIDE_PUZZLE_SCREEN, true);
		else slide(SLIDE_PUZZLE_SCREEN,true);
		
		 try {
			 switch (type) {
				case MAIN_MENU_ANIMAL:				
					 bitmap = createScaledBitmap(pictureAnimal[position]);
					 break;
				case MAIN_MENU_FOOD :						
					 bitmap = createScaledBitmap(pictureFood[position]);
					 break;
				case MAIN_MENU_FLOWER:
					 bitmap = createScaledBitmap(pictureFlower[position]);
					 break;
				case MAIN_MENU_NATURAL:
					 bitmap = createScaledBitmap(pictureNatural[position]);		
					 break;
				default:								
					break;
				}
			
				} catch (FileNotFoundException e) {
					// TODO Auto-generated catch block
					System.out.println("Errror 1:");
					e.printStackTrace();
					
					//finish();
				} catch (IllegalArgumentException e) {
					// TODO Auto-generated catch block
					System.out.println("Errror 2:");
					e.printStackTrace();
					//finish();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					System.out.println("Errror 3:");
					e.printStackTrace();
					//finish();
				}		      
		      createGameBoard(SettingsActivity.getGridSize(this));
	}
	/* (non-Javadoc)
	    * Returns a scaled image of the bitmap at the given location.  This helps
	    * prevent OutOfMemory exceptions when loading large images from the SD
	    * card.
	    */
	   private Bitmap createScaledBitmap(int drawable) 
	         throws FileNotFoundException, IOException, 
	               IllegalArgumentException {	      
		  Bitmap bitmap = BitmapFactory.decodeResource(getResources(), drawable); 
	      return bitmap;
	   }
	   /* (non-Javadoc)
	    * Basic wrapper method for creating the game board and setting the number
	    * visibility.
	    * @param gridSize row and column count (3 = 3x3; 4 = 4x4; 5 = 5x5; etc.)
	    */
	   private final void createGameBoard(short gridSize) {
	      DisplayMetrics metrics = new DisplayMetrics();
	      getWindowManager().getDefaultDisplay().getMetrics(metrics);
	        
	      TableLayout tableLayout;
	      tableLayout = (TableLayout) findViewById(R.id.parentLayout);  
	      //viewFliper.removeAllViewsInLayout();
	      tableLayout.removeAllViews();//AllViews();//ViewAt(R.layout.board);//AllViews();
	      
	      board = GameBoard.createGameBoard(this, 
	            bitmap, 
	            tableLayout,
	            (int) (metrics.widthPixels * metrics.density),
	            (int) (metrics.heightPixels * metrics.density),
	            gridSize);
	      numbersVisible = SettingsActivity.isNumbersVisible(this);
	      board.setNumbersVisible(numbersVisible);///numbersVisible
//	      bitmap.recycle(); // free memory for this copy of the picture since the
	                     // picture is stored by the GameBoard class
	   }
	   /**
		 * @param     : N/A
		 * @Objective : share picture to facebook, twitter,gmail
		 */
	   public void sharePicture(){
		   Intent sharingIntent = new Intent(Intent.ACTION_SEND);
		   //Uri screenshotUri = Uri.parse();
		   sharingIntent.setType("image/jpg");//png
		   sharingIntent.putExtra(Intent.EXTRA_STREAM,pictureAnimal[0]);		  
		   startActivity(Intent.createChooser(sharingIntent, "Share image using"));
	   }
	   /**
		 * @param     : N/A
		 * @Objective : share picture to facebook, twitter,gmail
		 */
	   public void saveWallPaper(){
		   typeList=prefs.getInt(PREF_TYPE, -1);
		   positionList=prefs.getInt(PREF_POSITION, -1);
		   InputStream is;
		   switch (typeList) {
			case MAIN_MENU_ANIMAL:	
				is = getResources().openRawResource(pictureAnimal[positionList]);
				break;
			case MAIN_MENU_FOOD :	
				is = getResources().openRawResource(pictureFood[positionList]);				
				break;
			case MAIN_MENU_FLOWER:
				is = getResources().openRawResource(pictureFlower[positionList]);				
				break;
			case MAIN_MENU_NATURAL:
				is = getResources().openRawResource(pictureNatural[positionList]);				
				break;
			default:	
				is = getResources().openRawResource(pictureAnimal[0]);
				break;
			}		   
			Bitmap bmp = BitmapFactory.decodeStream(is);
			try {
				getApplicationContext().setWallpaper(bmp);
				Toast.makeText(aContext, "Set Wall paper success", Toast.LENGTH_SHORT).show();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
	   }
	   /**
		 * @param     : N/A
		 * @Objective : save picture to SD card
		 */
	   public void savePicture(){
		   File file;
		   typeList=prefs.getInt(PREF_TYPE, -1);
		   positionList=prefs.getInt(PREF_POSITION, -1);
	        String mSavedFName = new String((new StringBuilder(String.valueOf(Environment.getExternalStorageDirectory()
	        		.getPath()))).append("/").append("puzzle_pic_").append(typeList+"_"+positionList).append(".jpg").toString());
	        file = new File(mSavedFName);
	        if(file.exists())
	            file.delete();
	        try {
				file.createNewFile();
				 FileOutputStream fileoutputstream = new FileOutputStream(file);
				 
				 switch (typeList) {
					case MAIN_MENU_ANIMAL:				
						createScaledBitmap(pictureAnimal[positionList]).compress(android.graphics.Bitmap.CompressFormat.JPEG, 100, fileoutputstream);
						 break;
					case MAIN_MENU_FOOD :						
						createScaledBitmap(pictureFood[positionList]).compress(android.graphics.Bitmap.CompressFormat.JPEG, 100, fileoutputstream);
						 break;
					case MAIN_MENU_FLOWER:
						createScaledBitmap(pictureFlower[positionList]).compress(android.graphics.Bitmap.CompressFormat.JPEG, 100, fileoutputstream);
						 break;
					case MAIN_MENU_NATURAL:
						createScaledBitmap(pictureNatural[positionList]).compress(android.graphics.Bitmap.CompressFormat.JPEG, 100, fileoutputstream);
						 break;
					default:								
						break;
					}
				 Toast.makeText(aContext, "Image has saved to SD card", Toast.LENGTH_SHORT).show();
				 System.out.println("Save OK");
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
	        
	   }
	   /**
		 * @param     : N/A
		 * @Objective : vote App in android Market
		 */
	   public void voteApplication(){
		   Intent intent = new Intent("android.intent.action.VIEW");
	       intent.setData(Uri.parse("https://market.android.com/details?id=vn.sunnet.game.ken.ailatrieuphu"));
	       startActivity(intent);

	   }
	   // create Dialog	  
	   @Override
	   protected Dialog onCreateDialog(int id) {
	      Dialog dialog;
	      AlertDialog.Builder builder = new AlertDialog.Builder(this);
	      switch(id) {
	     
	      case DIALOG_COMPLETED_ID:
	         builder.setMessage(createCompletionMessage())
	         .setCancelable(false)
	         .setNeutralButton(R.string.ok, new DialogInterface.OnClickListener() {
	            public void onClick(DialogInterface dialog, int which) {
	               dialog.dismiss();
	               //board.shuffleTiles();
	               		   			
	               typeList=prefs.getInt(PREF_TYPE, -1);
	               positionList=prefs.getInt(PREF_POSITION, -1);
	               showViewPicture(false, typeList, positionList);
	            }
	         });
	         dialog = builder.create();
	         break;
	      default:
	         dialog = null;
	      }
	      return dialog;
	   }
	   
	   //TODO When updating to ICS-level API, replace this with Fragment
	   @Override
	   protected void onPrepareDialog(int id, Dialog dialog) {
	      switch (id) {
	      case DIALOG_COMPLETED_ID:
	         ((AlertDialog) dialog).setMessage(createCompletionMessage());
	         break;
	      }
	   }
	   
	   /* (non-Javadoc)
	    * Return a 'congratulatory' message that also contains the number of moves.
	    */
	   private String createCompletionMessage() {
		   typeList=prefs.getInt(PREF_TYPE, -1);
		   positionList=prefs.getInt(PREF_POSITION, -1);
		   int updateMove;
		   switch (typeList) {
			case MAIN_MENU_FOOD:
				updateMove=prefs.getInt("PREF_"+pictureFood[positionList], -1);
				if(updateMove==-1||updateMove>board.getMoveCount()){
					editor = prefs.edit();
		  			editor.putInt("PREF_"+pictureFood[positionList], board.getMoveCount());
		  			editor.commit();
				}
				
				break;
			case MAIN_MENU_FLOWER:
				updateMove=prefs.getInt("PREF_"+pictureFlower[positionList], -1);
				if(updateMove==-1||updateMove>board.getMoveCount()){
					editor = prefs.edit();
		  			editor.putInt("PREF_"+pictureFlower[positionList], board.getMoveCount());
		  			editor.commit();
				}
							
				break;
			case MAIN_MENU_NATURAL:
				updateMove=prefs.getInt("PREF_"+pictureNatural[positionList], -1);
				if(updateMove==-1||updateMove>board.getMoveCount()){
					editor = prefs.edit();
		  			editor.putInt("PREF_"+pictureNatural[positionList], board.getMoveCount());
		  			editor.commit();
				}
				
				break;
			case MAIN_MENU_ANIMAL:
				updateMove=prefs.getInt("PREF_"+pictureAnimal[positionList], -1);
				if(updateMove==-1||updateMove>board.getMoveCount()){
					editor = prefs.edit();
		  			editor.putInt("PREF_"+pictureAnimal[positionList], board.getMoveCount());
		  			editor.commit();
				}
				
				break;	
			default:
				break;
			}
		   
	      String completeMsg="Congratulation! You have completed with "+String.valueOf(board.getMoveCount())+" actions.";
	      return completeMsg;
	   }
   
	/**
	 * 
	 * @param     : newSlide : Which slide will be diplayed next
	 * 							n2c			 : is need to check for current slide is the same with new one
	 * @Objective : Slide screen to new selected slide
	 * @category:
	 */
	private void slide(int newSlide, boolean n2c) {
		if (n2c && newSlide == currentScreen) return; 
		viewFliper.setInAnimation(inFromRightAnimation());
		viewFliper.setOutAnimation(outToLeftAnimation());
		viewFliper.setDisplayedChild(newSlide);
		lastScreen = currentScreen;
		currentScreen = newSlide;
	}
	
    /**
     * @param: N/A
	 * @Objective : Slide style of new screen (Appear)
	 * @category:
	 */
    private Animation inFromRightAnimation() {
		Animation inFromRight = new TranslateAnimation(
				Animation.RELATIVE_TO_PARENT,  +1.0f, Animation.RELATIVE_TO_PARENT,  0.0f,
				Animation.RELATIVE_TO_PARENT,  0.0f, Animation.RELATIVE_TO_PARENT,   0.0f
				);
		inFromRight.setDuration(500);
		inFromRight.setInterpolator(new AccelerateInterpolator());
		return inFromRight;
	}

	/**
	 * @param: N/A
	 * @Objective : Slide style of old screen (away)
	 * @category:
	 */
	private Animation outToLeftAnimation() {
		Animation outtoLeft = new TranslateAnimation(
				Animation.RELATIVE_TO_PARENT,  0.0f, Animation.RELATIVE_TO_PARENT,  -1.0f,
				Animation.RELATIVE_TO_PARENT,  0.0f, Animation.RELATIVE_TO_PARENT,   0.0f
				);
		outtoLeft.setDuration(500);
		outtoLeft.setInterpolator(new AccelerateInterpolator());
		return outtoLeft;
	}
	
	//Slide back
	private void slide_back(int newSlide, boolean n2c) {
		if (n2c && newSlide == currentScreen) return; 
		viewFliper.setInAnimation(inFromLeftAnimation());
		viewFliper.setOutAnimation(outToRightAnimation());
		viewFliper.setDisplayedChild(newSlide);
		lastScreen = currentScreen;
		currentScreen = newSlide;
	}
	/**
     * @param: N/A
	 * @Objective : Slide style of new screen (Appear)
	 * @category:
	 */
    private Animation inFromLeftAnimation() {
		Animation inFromRight = new TranslateAnimation(
				Animation.RELATIVE_TO_PARENT,  -1.0f, Animation.RELATIVE_TO_PARENT,  0.0f,
				Animation.RELATIVE_TO_PARENT,  0.0f, Animation.RELATIVE_TO_PARENT,   0.0f
				);
		inFromRight.setDuration(500);
		inFromRight.setInterpolator(new AccelerateInterpolator());
		return inFromRight;
	}

	/**
	 * @param: N/A
	 * @Objective : Slide style of old screen (away)
	 * @category:
	 */
	private Animation outToRightAnimation() {
		Animation outtoLeft = new TranslateAnimation(
				Animation.RELATIVE_TO_PARENT,  0.0f, Animation.RELATIVE_TO_PARENT,  +1.0f,
				Animation.RELATIVE_TO_PARENT,  0.0f, Animation.RELATIVE_TO_PARENT,   0.0f
				);
		outtoLeft.setDuration(500);
		outtoLeft.setInterpolator(new AccelerateInterpolator());
		return outtoLeft;
	}
	@Override
	public boolean onTouchEvent(MotionEvent event) {
		// TODO Auto-generated method stub
		//return super.onTouchEvent(event);
		//Manually handle the event.		
		if (event.getAction() == MotionEvent.ACTION_DOWN)
        {
            //Remember the time and press position
//            Log.w("Motion Event","Action down");
            xLast=event.getX();
            
        }
		if (event.getAction() == MotionEvent.ACTION_UP)
        {
            //Get the time and position and check what that was :)
//            Log.w("Motion Event","Action up");
            xCurrent=event.getX();	   
            if(xCurrent > xLast) BackEventTouch();
            //else showBonus();
        }
        if (event.getAction() == MotionEvent.ACTION_MOVE)
        {
            //Check if user is actually longpressing, not slow-moving 
            // if current position differs much then press positon then discard whole thing
            // If position change is minimal then after 0.5s that is a longpress. You can now process your other gestures 
            //Log.e("test","Action move");
            	                
        }
        return false;
	}
	
	/**
	 * 
	 * @param     : N/A
	 * @Objective : Event handler for button clicked.
	 * @Author    : Nguyen The Vinh
	 */
	@Override
	public void onClick(View v) {
		int viewID = v.getId();
		switch (viewID) {		
		case R.id.btnAnimal:		
			editor = prefs.edit();
			editor.putInt(PREF_TYPE, MAIN_MENU_ANIMAL);
			editor.commit();
			showListPicture(false,MAIN_MENU_ANIMAL);
			break;
		case R.id.btnFlower:			
			editor = prefs.edit();
			editor.putInt(PREF_TYPE, MAIN_MENU_FLOWER);
			editor.commit();
			showListPicture(false,MAIN_MENU_FLOWER);
			break;
		case R.id.btnFood:			
			editor = prefs.edit();
			editor.putInt(PREF_TYPE, MAIN_MENU_FOOD);
			editor.commit();
			showListPicture(false,MAIN_MENU_FOOD);
			break;
		case R.id.btnNatural:			
			editor = prefs.edit();
			editor.putInt(PREF_TYPE, MAIN_MENU_NATURAL);
			editor.commit();
			showListPicture(false,MAIN_MENU_NATURAL);
			break;
			
		
		}
	}
	/**
	 * 
	 * @param     : event, keycode of downed button
	 * @Objective : Handle keyevent on main activity
	 */
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			if (currentScreen == SLIDE_LIST_PICTURE_SCREEN) {
				
				showMainMenu(true);
				return true;
			}else if(currentScreen == SLIDE_PUZZLE_SCREEN){
				
				typeList =prefs.getInt(PREF_TYPE, -1);
				showListPicture(true,typeList);
				return true;
			}else if(currentScreen == SLIDE_VIEW_PICTURE_SCREEN){
				
				typeList =prefs.getInt(PREF_TYPE, -1);
				showListPicture(true,typeList);				
				return true;
			}
		}
		return super.onKeyDown(keyCode, event);
	}
	
	// event KEY_BACK Touch
	public void BackEventTouch(){
		if (currentScreen == SLIDE_LIST_PICTURE_SCREEN) {
			showMainMenu(true);
			
		}							
		
	}
	/**
	 * 
	 * @param     : Param from Android API
	 * @Objective : Event Handler for OnItemClick event from some ListView
	 */
	@Override
	public void onItemClick(AdapterView<?> arg0, View clickedView, int position, long arg3) {
		// TODO Auto-generated method stub
		// chua xu ly view
		
		if (currentScreen == SLIDE_MAIN_SCREEN){
					
		} else if (currentScreen == SLIDE_LIST_PICTURE_SCREEN){			
			
			typeList =prefs.getInt(PREF_TYPE, -1);			
			editor = prefs.edit();
			editor.putInt(PREF_POSITION, position);
			editor.commit();
			showPuzzlePicture(false, typeList, position);
			//Intent i=new Intent(MainAppActivity.this, PuzzleActivity.class);
			//startActivity(i);
		}
		
	}
    @Override
	protected void onStop() {
		super.onStop();
		
		
	}
	@Override
	protected void onDestroy() {
		super.onDestroy();
		
	}
	@Override
	protected void onResume() {
		super.onResume();
		//TextView tv=(TextView) findViewById(R.id.tvTest);
		//tv.setText("Resume");
		//showListPicture(true, MAIN_MENU_ANIMAL);
	}
	/**
	 * 
	 * @param     : newConfig: new configuration details
	 * @Objective : Event Handler onConfigurationChanged
	 */
	@Override
	public void onConfigurationChanged(Configuration newConfig) {
		super.onConfigurationChanged(newConfig);
	}
	
	/**
	 * 
	 * @param     : N/A
	 * @Objective : Create Android's style main menu
	 * @Author    : 
	 */
	 
	@Override
	public void openOptionsMenu() {
		// TODO Auto-generated method stub
		super.openOptionsMenu();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// TODO Auto-generated method stub
		menu.add(0,MENU_SAVE , 0,"Save");
		menu.add(0,MENU_WALL_PAPER , 0,"Set Wall Paper");
		menu.add(0,MENU_VOTE, 0,"Vote");
		return super.onCreateOptionsMenu(menu);		
	}
	
	
	@Override
	public boolean onPrepareOptionsMenu(Menu menu) {
		// TODO Auto-generated method stub
		if (currentScreen==SLIDE_VIEW_PICTURE_SCREEN) {
			menu.setGroupVisible(0, true);			
		}else menu.setGroupVisible(0, false);
		return super.onPrepareOptionsMenu(menu);
	}
	/**
	 * 
	 * @param     : item: Which menu item is clicked
	 * @Objective : Event Handler for menu item clicked (Android's stype menu)
	 * @Author    : 
	 */
	public boolean onOptionsItemSelected(MenuItem item) {
		int id = item.getItemId();		
		switch (id) {
		case MENU_SAVE:
			savePicture();
			return true;
		case MENU_WALL_PAPER:
			saveWallPaper();
			//sharePicture();
			return true;
		case MENU_VOTE:
			voteApplication();		
			return true;
		
		}
		return false;
	}
	//////////////////////////////////////////////////////////////////////////////////
	
	
	/**
	 * @Objective : param
	 * @category:
	 */
	private int 						 currentScreen 								=-1;
	private int 						 lastScreen 									=-1;
	private static final int SLIDE_MAIN_SCREEN				= 0;
	private static final int SLIDE_LIST_PICTURE_SCREEN   = 1;
	private static final int SLIDE_PUZZLE_SCREEN		= 2;
	private static final int SLIDE_VIEW_PICTURE_SCREEN		= 3;
	
	public final static int MAIN_MENU_ANIMAL=0;
	public final static int MAIN_MENU_FLOWER=1;
	public final static int MAIN_MENU_FOOD=2;
	public final static int MAIN_MENU_NATURAL=3;
	

	public final static int MENU_SAVE=0;
	public final static int MENU_WALL_PAPER=1;
	public final static int MENU_VOTE=2;
	
	
	public final static String MAIN_MENU="menu";
	
		
	public static final int IMAGEREQUESTCODE = 8242008;
	public static final int DIALOG_PICASA_ERROR_ID = 0;
	public static final int DIALOG_GRID_SIZE_ID = 1;
	public static final int DIALOG_COMPLETED_ID = 2;
	
	public static final String PREF_TYPE="type";
	public static final String PREF_POSITION="position";
	
	
	public static int[] pictureAnimal ={ 
		R.drawable.p1,R.drawable.p2,R.drawable.p3,R.drawable.p4,
		R.drawable.p5,R.drawable.p6,R.drawable.p7,R.drawable.p8,
		R.drawable.p9,R.drawable.p10,R.drawable.p11,R.drawable.p12,
		R.drawable.p13,R.drawable.p14,R.drawable.p15,R.drawable.p16,
		};
	public static int[] pictureFlower ={ R.drawable.f1, R.drawable.f2, R.drawable.f3, R.drawable.f4, 
	R.drawable.f5, R.drawable.f6, R.drawable.f7, R.drawable.f8, R.drawable.f9, R.drawable.f10, R.drawable.f11,
	R.drawable.f12, R.drawable.f13, R.drawable.f14, R.drawable.f15, R.drawable.f16, R.drawable.f17, R.drawable.f18,
	 R.drawable.f19, R.drawable.f20, R.drawable.f21, R.drawable.f22, R.drawable.f23, R.drawable.f24, R.drawable.f25,
		};
	
	public static int[] pictureFood ={ 
		R.drawable.e1,R.drawable.e2,R.drawable.e3,R.drawable.e4,R.drawable.e5,R.drawable.e6,R.drawable.e7,R.drawable.e8,
		R.drawable.e9,R.drawable.e10,R.drawable.e11,R.drawable.e12,R.drawable.e13,R.drawable.e14,R.drawable.e15,
		};
	public static  int[] pictureNatural ={ 
		R.drawable.n1,R.drawable.n2,R.drawable.n3,R.drawable.n4,R.drawable.n5,R.drawable.n6,R.drawable.n7,R.drawable.n8,
		R.drawable.n9,R.drawable.n10,R.drawable.n11,R.drawable.n12,R.drawable.n13,R.drawable.n14,R.drawable.n15,
		R.drawable.n16,
		R.drawable.n17,R.drawable.n18,R.drawable.n19,
		};
	
	 public final static int[] ICON_pictureAnimal ={ 
			R.drawable.pi1,R.drawable.pi2,R.drawable.pi3,R.drawable.pi4,
			R.drawable.pi5,R.drawable.pi6,R.drawable.pi7,R.drawable.pi8,
			R.drawable.pi9,R.drawable.pi10,R.drawable.pi11,R.drawable.pi12,
			R.drawable.pi13,R.drawable.pi14,R.drawable.pi15,R.drawable.pi16,
			};
	 public final static int[] ICON_pictureFlower ={ R.drawable.fi1, R.drawable.fi2, R.drawable.fi3, R.drawable.fi4, 
			R.drawable.fi5, R.drawable.fi6, R.drawable.fi7, R.drawable.fi8, R.drawable.fi9, R.drawable.fi10, R.drawable.fi11,
			R.drawable.fi12, R.drawable.fi13, R.drawable.fi14, R.drawable.fi15, R.drawable.fi16, R.drawable.fi17, R.drawable.fi18,
			R.drawable.fi19, R.drawable.fi20, R.drawable.fi21, R.drawable.fi22, R.drawable.fi23, R.drawable.fi24, R.drawable.fi25,
			};
	 public final static int[] ICON_pictureFood ={ 
			R.drawable.ei1,R.drawable.ei2,R.drawable.ei3,R.drawable.ei4,R.drawable.ei5,R.drawable.ei6,R.drawable.ei7,R.drawable.ei8,
			R.drawable.ei9,R.drawable.ei10,R.drawable.ei11,R.drawable.ei12,R.drawable.ei13,R.drawable.ei14,R.drawable.ei15,		
			};
	 public final static int[] ICON_pictureNatural ={ 
			R.drawable.ni1,R.drawable.ni2,R.drawable.ni3,R.drawable.ni4,R.drawable.ni5,R.drawable.ni6,R.drawable.ni7,R.drawable.ni8,
			R.drawable.ni9,R.drawable.ni10,R.drawable.ni11,R.drawable.ni12,R.drawable.ni13,R.drawable.ni14,R.drawable.ni15,
			R.drawable.ni16,
			R.drawable.ni17,R.drawable.ni18,R.drawable.ni19,
			};
}