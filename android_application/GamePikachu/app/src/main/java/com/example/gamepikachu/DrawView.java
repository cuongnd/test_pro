package com.example.gamepikachu;

import java.util.Random;
import java.util.concurrent.ArrayBlockingQueue;
import java.util.concurrent.BlockingQueue;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.media.MediaPlayer;
import android.view.MotionEvent;
import android.view.View;

public class DrawView extends View 
{
	private MediaPlayer no_;
	private MediaPlayer Click;
	private MediaPlayer pair;
	private MediaPlayer level;
	private MediaPlayer no_move;
	public DrawView(Context context) 
	{
	  	super(context);
		Init();
		NewGame();
		InitSound(context);
	}
	
	private void InitSound(Context c)
	{
		no_=MediaPlayer.create(c, R.raw.no);
		Click =MediaPlayer.create(c, R.raw.click);
		pair=MediaPlayer.create(c, R.raw.pair);
		level=MediaPlayer.create(c, R.raw.level);
		no_move=MediaPlayer.create(c, R.raw.no_move);
	}
	//so luong hinh pikachu
		final int CardNo=15;
		final int PicCount=6;
		//ma tran hinh 10x6
		final int GameWidth=9; 
		final int GameHight=10;
		
		//kich thuoc cua cac the hinh
		final int CardSizeW=44;
		final int CardSizeH=54;
		
		
		//mang chua id cua cac hinh
		private int[] ImagePath={	R.drawable.image1,R.drawable.image2,R.drawable.image3,
									R.drawable.image4,R.drawable.image5,R.drawable.image6,
									R.drawable.image7,R.drawable.image8,R.drawable.image9,
									R.drawable.image10,R.drawable.image11,R.drawable.image12,
									R.drawable.image13,R.drawable.image14,R.drawable.image15
								};
		//mang cac hinh duoc doc tu Imagepath
		private Bitmap[]CardImages;
		
		//luu gia tri cua cac the hinh vd:[0][0]=0 ngla co hinh tai vi tri [0][0] -1 la ko co hinh
		int [][]CardMatrix;
		
		//so the hinh con lai trong ma tran chua duoc an
		int RemainingCount;
		int BuocDiChuyen;
		//the hinh thu nhat da duoc chon
		Boolean CardSelected;
		Boolean DrawPath_ = false;
		//vi tri cua the hinh thu nhat
		int CardX,CardY;
		int CurX,CurY;
		int TongDiem=0;
		//chieu dai duong di tu the hinh thu 2 den the hinh thu 1
		int rCount;
		//duong di tu the hinh thu 2 den the hinh thu 1
		// (rX[0],rY[0]) -> (rX[01,rY[1]) -> ...->(rX[rCount],rX[rCount]) 
		int [] rX,rY;
		
		//chieu dai duong di tam trong qua trinh de quy
		int tCount;
		int [] tX,tY;
		Direction[] d;              //Hướng đi của đường đi tạm
		
		int []dX={1,0,-1,0};
		int []dY={0,1,0,-1};
		int Leve=0;
		private String str_time;
		
		
		private void Init()
		{
			//khoi tao mang bitmap
			CardImages=new Bitmap[CardNo];
			
			//tao va nap 15 hinh vao mang bitmap
			for(int i=0;i<CardNo;i++)
			{
				CardImages[i]=BitmapFactory.decodeResource(getResources(), ImagePath[i]);
			}
			
			//tao ma tran cac the hinh luu trang thai cua cac hinh
			CardMatrix=new int[GameHight+2][GameWidth+2];
			
			//tao cac mang de tim duong di trong qua trinh de quy
			int MAX=(GameHight+2)*(GameWidth+2);
			rX=new int[MAX];
			rY=new int[MAX];
			tX=new int[MAX];
			tY=new int[MAX];
			d = new Direction[MAX];
			
		}
		
		public void NewGame()
		{
			int i,j,k;
			
			//mang nay cho biet moi lan xuat hien may lan
			int[]CardCount=new int[CardNo];
			//thiet lap moi hinh xuat hien 4 lan tren ban co
			for(k=0  ;k < CardNo ; k++)
				CardCount[k]=PicCount;
			
			//tao doi tuong de khoi tao hinh ngau nhien
			Random rnd=new Random();
			
			//hien tai chua co hinh nao
			for( i=0; i<= GameHight + 1; i++ )
				for( j =0 ; j <=GameWidth + 1; j++)
					CardMatrix[i][j]=-1;//ko co hinh nao tat ca cac o dang trong
			
			//text check Dedlive
			/*
			CardMatrix[2][1]=1;
			CardMatrix[3][1]=1;
			CardMatrix[2][2]=2;CardMatrix[2][3]=3;
			CardMatrix[3][2]=3;CardMatrix[3][3]=2;
			*/
			
			
			//duyet qua tung o cua ma tran va thiet la hinh ngau nhien cho o do
			
			for(i=1;i<=GameHight;i++)
				for(j=1;j<=GameWidth;j++)
				{
					
					//phan khoi tao cac quan pikachu
					
					do
					{
						k=rnd.nextInt(CardNo);	
					}while(CardCount[k]==0);//new CardCount[k]==0 nghia la hinh thu k da s/d het
											//cac the hinh can tim k khac
					//the hinh o o(i,j) la hinh thu k
					CardMatrix[i][j]=k;
					CardCount[k]--;//hinh thu k da dung 1 the
					
				}
				
			//CardMatrix[2][3]=1;
			RemainingCount=GameHight*GameWidth;//cac the hinh chua duoc an
			CardSelected=false;//the hinh thu nhat chua duoc chon
			
		}

		//kich thuoc cua bitmap BG
		final int width =550;
		final int height = 750;
		
		//thuoc tinh de ve Rectangle
		private void DrawBG(Canvas canvas)
		{
			
				
			////////////
			int left,rigth,top,bottom;
			//tao bitmap BG
			Paint paint;
			canvas.drawColor(Color.BLACK);
			
			Bitmap bit;
			
			//GameHight la số dòng của bàn cờ
			//GameWidth là số cột của bàn cờ
			for(int i=0;i<=GameHight +1;i++)
				for(int j=0;j<=GameWidth +1;j++)
				{
					//tinh vi tri va kich thuoc hinh chu nhat
					left=j*CardSizeW;
					top=i*CardSizeH ;
					rigth=left+CardSizeW;
					bottom=top+CardSizeH;
					
					if(CardMatrix[i][j]== -1)
					{
						paint= new Paint();
						paint.setColor(Color.BLACK);
						paint.setStrokeWidth(2);        
						//paint.setStyle(Paint.Style.STROKE);
						//canvas.drawRect(left, top, rigth+1, bottom+1, paint);
						canvas.drawRect(left, top, rigth, bottom, paint);
					}
					
					else
					{
						paint= new Paint();
						bit=CardImages[CardMatrix[i][j]];
						paint.setStrokeWidth(2);        
						paint.setStyle(Paint.Style.STROKE);
						paint.setColor(Color.BLACK);
						canvas.drawRect(left, top, rigth, bottom, paint);
						canvas.drawBitmap(bit, left+1, top+1,null);
						
						//ve hinh chu nhat bao quanh khi image duoc chon
						if(CardSelected==true)
							if (CardX == j && CardY == i)
							{
								paint= new Paint();
								paint.setColor(Color.RED);
								paint.setStyle(Paint.Style.STROKE);
								paint.setStrokeWidth(4);
								canvas.drawRect(left, top, rigth, bottom, paint);
								//Click.start();
							}
						
					}
				}
			
		}
		
		public void DrawPath(Canvas c)
		{
			Paint p=new Paint();
			p.setColor(Color.RED);
			int i, x1, y1, x2, y2;
            x1 = rX[0] * CardSizeW + CardSizeW / 2;
            y1 = rY[0] * CardSizeH + CardSizeH / 2;     // (y1, x1) là tâm của thẻ hình thứ 2
            for (i = 1; i < rCount; i++)
            {
                x2 = rX[i] * CardSizeW + CardSizeW / 2;
                y2 = rY[i] * CardSizeH + CardSizeH / 2; // (y1, x1) là tâm của các ô đường đi đi qua
                c.drawLine(x1, y1, x2, y2, p);         // Vẽ đường thẳng nối 2 ô
                x1 = x2;
                y1 = y2;
            }
			
			
		}
		//lay toa do x,y khi touch vao hinh 
		
		@Override
		public void onDraw(final Canvas canvas) 
		{  
			DrawBG(canvas);
		    if (DrawPath_)
		    	DrawPath(canvas);
		    Paint tpaint=new Paint();
		    tpaint.setColor(Color.RED);
		    canvas.drawText("ĐIỂM CỦA BẠN: "+TongDiem, 50, 650, tpaint);
		}
		
		@Override
		public boolean onTouchEvent(MotionEvent event)
		{
			int dowx,dowy;
			switch(event.getAction())
			{
				case MotionEvent.ACTION_DOWN:
				{
						if(CardSelected==false)Click.start();
						dowx=(int)(event.getX()/44);
						dowy=(int)( (event.getY()) /54);
						if(dowx>=0 & dowx<=GameWidth & dowy>=0 & dowy<=GameHight)
						CardClick(dowx, dowy);
				}		
					break;
				default: break;
			}
			return true;
		}
		
		 // đếm xem đường đi tạm rẽ bao nhiêu lần
	    private int CountTurn(Direction[] d, int tCount)
	    {
	        int count = 0;
	        for (int i = 2; i < tCount; i++)    // duyệt qua các điểm trong đường đi
	        {
	            if (d[i - 1] != d[i]) count++;  // nếu hướng khác nhau nghĩa là có rẽ
	        }
	        return count;
	    }
	    
		 private void FindRoute(int x, int y, Direction direct)
	     {
	         if (x < 0 || x > GameWidth + 1) return;
	         if (y < 0 || y > GameHight + 1) return;    // nếu ra khỏi ma trận, thoát
	         if (CardMatrix[y][x] != -1) return;         // không phải ô trống, thoát (1)

	         tX[tCount] = x;                     // đưa ô [y,x] vào đường đi
	         tY[tCount] = y;
	         d[tCount] = direct;                 // ghi nhận là hướng đi đến ô [y,x] 

	         // nếu rẽ nhiều hơn 2 lần, thoát
	         if (CountTurn(d, tCount + 1) > 2) return;

	         tCount++;
	         CardMatrix[y][x] = -2;              // đánh dấu ô [y,x] đã đi qua
	                                             // lệnh (1) đảm bảo ô đã đi qua sẽ không đi lại nữa
	         if (x == CardX && y == CardY)       // nếu đã tìm đến vị trí hình thứ nhất
	         {
	             // kiểm tra xem đường đi mới tìm được có ngắn hơn đường đi tìm trong các lần trước không
	             if (tCount < rCount)
	             {
	                 // nếu ngắn hơn, ghi nhớ lại đường đi này
	                 rCount = tCount;
	                 for (int i = 0; i < tCount; i++)
	                 {
	                     rX[i] = tX[i];
	                     rY[i] = tY[i];
	                 }
	             }
	         }
	         else   // nếu chưa đi đến được ô hình thứ nhất -> đệ quy để đi đến 4 ô xung quanh
	         {
	             FindRoute(x - 1, y, Direction.Left);
	             FindRoute(x + 1, y, Direction.Right);
	             FindRoute(x, y - 1, Direction.Up);
	             FindRoute(x, y + 1, Direction.Down);
	         }

	         // ô [y,x] đã xét, quay lui nên loại ô [y,x] ra khỏi đường đi
	         tCount--;
	         // đánh dấu lại là ô [y,x] trống, có thể đi qua lại
	         CardMatrix[y][x] = -1;
	     }
		 
		//sau khi ham nay ket thuc ta duoc cac gia tri
		// rCount va rX[],rY[]
		private void FindRouteBFS(int x,int y)
		{
			int w=GameWidth+2;
			int h=GameHight+2;
			BlockingQueue<Node> queue=new ArrayBlockingQueue<Node>(w*h);
			int [] Trace=new int[w*h];
			for(int i=0;i<w*h;i++)
				Trace[i]=0;//tat ca cac hinh trong ma trang deu chua duoc tham
			
			Trace[y*w +x]=-1;//ghi nhan dinh da duoc tham
			queue.add(new Node(x,y,0));
			while(!queue.isEmpty())
			{
				Node n=queue.poll();
				if(n.x==CardX && n.y==CardY)
				{
					rCount=n.Level + 1;	
					
					for(int i=rCount-1; i>=0; i--)
					{
						rX[i]=n.x;
						rY[i]=n.y;
						int k=Trace[n.y * n.x];
						n.x=k % w;
						n.y=k/w;
					}
					return;
				}
				if(n.Level<3)
				{
					for(int i=0;i<4;i++)
					{
						x=n.x+ dX[i];
						y=n.y+ dY[i];
						 while (x >= 0 && x < w && y >= 0 && y < h && CardMatrix[y][x] == -1)
						 {
							 if (Trace[y * w + x] == 0)
							 {
								 Trace[y*w + x]=n.y*w+n.x;
								 queue.add(new Node(x,y,n.Level+1));
							 }
							 x=x+dX[i];
							 y=y+dY[i];
						 }
					}
				}
			}
		}
		private void CardClick(int x,int y)
		{
			if(CardMatrix[y][x] != -1)
			{
				if(!CardSelected)
				{
					CardSelected = true;
					CardX=x;
					CardY=y;
					invalidate();//mot cach goi gian tiep ham onDraw
				}
				else
					if(x != CardX || y!=CardY)
					{
						if(CardMatrix[y][x]==CardMatrix[CardY][CardX])
						{
							//EditText t=(EditText)findViewById(R.id.editText1);
							
								int temp=CardMatrix[y][x];
								CardMatrix[y][x]=-1;
								CardMatrix[CardY][CardX]=-1;
								rCount=Integer.MAX_VALUE;
								tCount=0;
								//FindRoute(x, y, Direction.None);
								FindRoute(x, y, Direction.None);
								//FindRouteBFS(x, y);
								CardMatrix[y][x]=CardMatrix[CardY][CardX]=temp;
							    //t.setText("rCount: "+String.valueOf(rCount));
								if(rCount != Integer.MAX_VALUE)//neu tim thay duong di
								{
									pair.start();
									TongDiem+=20;
									CurX = x;
									CurY = y;
									new Thread(new Runnable() 
									{
										public void run() 
										{
											DrawPath_=true;
											postInvalidate();
																						
											try 
			    	                 		 {
												Thread.sleep(1000);
											 } 
			    	                 		 catch (InterruptedException e) 
			    	                 		 {
												e.printStackTrace();
											 }
											DrawPath_=false;
											//danh dau 2 the nay da duoc an
											
											CardMatrix[CurY][CurX]= -1;
											CardMatrix[CardY][CardX]= -1;
											switch (Leve)
											{
												case 1:{Left();}break;
												case 2:{Right();}break;
												case 3:{Top();}break;
												case 4:{Bottom();}break;
												
												default: break;
											}
											
											//Right();
											//Left();
											//Top();
											//Bottom();
											RemainingCount -=2;//so the chua duoc an giam di 2 
											CardSelected=false;//tiep tuc chon con khac
											
											/*
											if(CheckDedLive()==true)
											{
												HoanVi();
												no_move.start();
											}
											*/
											if(RemainingCount==0)
											{
												level.start();
												NewGame();
												Leve++;
											}
											postInvalidate();
											
										}
									}).start();
											
								}
								else//neu ko tim thay duong di
								{
									CardSelected=false;
									no_.start();
									invalidate();
								}
							}
					else//2 the hinh ko giong nhau
					{
						CardSelected=false;
						no_.start();
						invalidate();
					}
					//ket thuc else dau
				}
			}
		}
		
		//chi thao tac voi the chon dau tien CardX, CardY
		public void Left()
		{
			for(int j=CardX;j<=GameWidth;j++)
				CardMatrix[CardY][j]=CardMatrix[CardY][j+1];
		}
		
		//chi thao tac voi the chon dau tien CardX,CardY
		public void Right()
		{
			for(int j=CardX;j>=1;j--)
			CardMatrix[CardY][j]=CardMatrix[CardY][j-1];
		}
		public void Top()
		{
			for(int i=CardY;i>=1;i--)
			CardMatrix[i][CardX]=CardMatrix[i-1][CardX];
		}
		public void Bottom()
		{
			for(int i=CardY;i<=GameHight;i++)
				CardMatrix[i][CardX]=CardMatrix[i+1][CardX];
		}
		public class Node
		{
			public int x,y;
			public int Level;
			public Node(int x,int y,int Level)
			{
				this.x=x;
				this.y=y;
				this.Level=Level;
			}
		}
		
		//khoi tao dong ho chay thoi gian
		public void runCountTime()
		{
		}
		
		//chua chay duoc
		/*
		public boolean CheckDedLive()
		{
			for(int j=1;j<GameHight;j++)
				for(int i=1;i<GameWidth;i++)
				{
					if(CardMatrix[j][i] !=-1)
					for(int m=j+1;m<=GameHight;m++)
						for(int n=i+1;i<=GameWidth;n++)
						{
							if(CardMatrix[j][i]==CardMatrix[m][n])
							{
								int temp=CardMatrix[m][n];
								CardMatrix[m][n]=-1;
								CardMatrix[j][i]=-1;
								rCount=Integer.MAX_VALUE;
								tCount=0;
								//FindRoute(x, y, Direction.None);
								FindRoute(j, i, Direction.None);
								//FindRouteBFS(x, y);
								CardMatrix[m][n]=CardMatrix[j][i]=temp;
							    //t.setText("rCount: "+String.valueOf(rCount));
								if(rCount != Integer.MAX_VALUE)
									return false;
							}
						}
				}
			return true;
		}
		
		//chua chay duoc
		public void HoanVi()
		{
			int temp=-1;
			for(int j=1;j<GameHight;j++)
				for(int i=1;i<GameWidth;i++)
				{
					if(CardMatrix[j][i] !=-1)
					{
						if(CardMatrix[j-1][i] !=-1)
						{
							temp=CardMatrix[j-1][i];
							CardMatrix[j-1][i]=CardMatrix[j][i];
						}
						if(CardMatrix[j+1][i] !=-1)
						{
							temp=CardMatrix[j+1][i];
							CardMatrix[j+1][i]=CardMatrix[j][i];
						}
						if(CardMatrix[j][i-1] !=-1)
						{
							temp=CardMatrix[j][i-1];
							CardMatrix[j-1][i]=CardMatrix[j][i-1];
						}
						if(CardMatrix[j][i+1] !=-1)
						{
							temp=CardMatrix[j][i+1];
							CardMatrix[j-1][i]=CardMatrix[j][i+1];
						}
					}
				
					for(int m=j+1;m<=GameHight;m++)
						for(int n=i+1;i<=GameWidth;n++)
						{
							if(CardMatrix[j][i] !=-1 & CardMatrix[m][n]==CardMatrix[j][i] & temp!=-1)
							{
								CardMatrix[m][n]=temp;
								return;
							}
						}
				}
		}
		
	*/
}
