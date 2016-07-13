package com.example.liu.mygame.view;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.Point;
import android.graphics.Typeface;
import android.view.MotionEvent;
import android.view.SurfaceHolder;
import android.view.SurfaceView;

import com.example.liu.mygame.entity.Bullet;
import com.example.liu.mygame.entity.EmplaceFlower;
import com.example.liu.mygame.entity.EmplacePea;
import com.example.liu.mygame.entity.Flower;
import com.example.liu.mygame.entity.Pea;
import com.example.liu.mygame.entity.SeedFlower;
import com.example.liu.mygame.entity.SeedPea;
import com.example.liu.mygame.entity.Sun;
import com.example.liu.mygame.entity.Zombie;
import com.example.liu.mygame.entity.ZombieManager;
import com.example.liu.mygame.global.Config;
import com.example.liu.mygame.model.BaseModel;
import com.example.liu.mygame.model.Plant;
import com.example.liu.mygame.model.TouchAble;

import java.util.ArrayList;

//������������������Ϸ���������·���
//�������ֿ��������ƶ�����Ϸ���԰����о������һ�������� ���÷�ͼ�� ÿ�λ��Ƶ�ʱ��Ҫ����Y�����������
//�����Ȼ������Ŀ϶�������ǰ��� ����Y��С������ǰ�漴����Ļ�ϱ�Ե����ľ���
//������Ϸ�϶���ͨ����Ϸ���������п���
public class GameView extends SurfaceView implements SurfaceHolder.Callback,
        Runnable {

    private Canvas canvas;
    private Paint paint;
    private SurfaceHolder surfaceHolder;
    private boolean gameRunFlag;
    private Context context;// ���ڴ��ͼƬ��ַ

    // ��GameView�����ܹ������е�ʵ�嶼�����﷢�����󲢴���
    private static GameView gameView;

    private ArrayList<BaseModel> deadList;// �����������ʵ��,���϶����ֺ�ʵ��᲻��ʾ�����ǻ���������Ҫ��������
    private ArrayList<BaseModel> gameLayout3;// ��ŵ���ͼ���е�ʵ�壻
    private ArrayList<BaseModel> gameLayout2;// ��ŵڶ�ͼ���е�ʵ��
    private ArrayList<BaseModel> gameLayout1;// ��ŵ�һͼ���е�ʵ��

    // �ܵ���������
    // ��Щ������һ����װ������һ��forѭ�����д�������
    private ArrayList<BaseModel> gameLayout4plant0;
    private ArrayList<BaseModel> gameLayout4plant1;
    private ArrayList<BaseModel> gameLayout4plant2;
    private ArrayList<BaseModel> gameLayout4plant3;
    private ArrayList<BaseModel> gameLayout4plant4;

    // ���彩ʬ�ܵ�
    private ArrayList<BaseModel> gamelayout4zombie0;
    private ArrayList<BaseModel> gamelayout4zombie1;
    private ArrayList<BaseModel> gamelayout4zombie2;
    private ArrayList<BaseModel> gamelayout4zombie3;
    private ArrayList<BaseModel> gamelayout4zombie4;

    // ���彩ʬ��������ͨ���˿�������ʹ��ʬʵ���ƶ�
    private ZombieManager zombieManager;

    public GameView(Context context) {
        super(context);
        // TODO GameView
        this.context = context;
        paint = new Paint();
        surfaceHolder = getHolder();
        surfaceHolder.addCallback(this);
        gameRunFlag = true;

        gameView = this;

        if (Config.game == false) {
            canvas.drawBitmap(Config.gameOver, 0, 0, paint);
        }
    }

    @Override
    public void surfaceCreated(SurfaceHolder holder) {
        // TODO surfaceCreated
        // ����bitmap��ͼƬ��
        createElement();
        new Thread(this).start();

    }

    private void createElement() {
        // TODO createElement
        // ��ֲ���뽩ʬ�ĸ߶Ȳֵ
        Config.heightYDistance = Config.zombieFrames[0].getHeight()
                - Config.flowerFrames[0].getHeight();
        // ��seedBank��X���긳��ֵ
        Config.seedBankLocationX = (Config.deviceWidth - Config.seedBank
                .getWidth()) / 2;
        // ��ʼ������ͼ��
        gameLayout3 = new ArrayList<BaseModel>();

        // ���˷���������ʱ��ᴴ����Ƭ����
        gameLayout2 = new ArrayList<BaseModel>();
        SeedFlower seedFlower = new SeedFlower(
                (Config.deviceWidth - Config.seedBank.getWidth()) / 2
                        + Config.seedFlower.getWidth() / 3
                        + Config.seedBank.getWidth() / 7,
                Config.seedBank.getHeight() / 10);
        SeedPea seedPea = new SeedPea(
                (Config.deviceWidth - Config.seedBank.getWidth()) / 2
                        + Config.seedFlower.getWidth() / 7
                        + Config.seedBank.getWidth() / 7 * 2,
                Config.seedBank.getHeight() / 10);

        gameLayout2.add(seedFlower);
        gameLayout2.add(seedPea);

        // ��Ӱ���״̬�е�ֲ��
        gameLayout1 = new ArrayList<BaseModel>();
        deadList = new ArrayList<BaseModel>();

        gameLayout4plant0 = new ArrayList<BaseModel>();
        gameLayout4plant1 = new ArrayList<BaseModel>();
        gameLayout4plant2 = new ArrayList<BaseModel>();
        gameLayout4plant3 = new ArrayList<BaseModel>();
        gameLayout4plant4 = new ArrayList<BaseModel>();

        // ��ʬ�ܵ���ʼ��
        gamelayout4zombie0 = new ArrayList<BaseModel>();
        gamelayout4zombie1 = new ArrayList<BaseModel>();
        gamelayout4zombie2 = new ArrayList<BaseModel>();
        gamelayout4zombie3 = new ArrayList<BaseModel>();
        gamelayout4zombie4 = new ArrayList<BaseModel>();

        // ��ʼ����ʬ������
        zombieManager = new ZombieManager();

        // ����ֲ��ĺ���λ��
        for (int i = 0; i < 5; i++) {
            for (int j = 0; j < 9; j++) {
                Config.plantPoints.put(i * 10 + j, new Point(
                        (j + 2) * Config.deviceWidth / 11 - Config.deviceWidth
                                / 11 / 2, (i + 1) * Config.deviceHeight / 6));
                if (j == 0) {
                    Config.raceWayYpoints[i] = (i + 1) * Config.deviceHeight
                            / 6;
                }
            }
        }
    }

    @Override
    public void surfaceChanged(SurfaceHolder holder, int format, int width,
                               int height) {
        // TODO surfaceChanged

    }

    @Override
    public void surfaceDestroyed(SurfaceHolder holder) {
        // TODO surfaceDestroyed

    }

    // ���еĶ���֡�������run����������
    // ���ƶ���֡��ʱ��Ҫע�����Ƚ������ݸ��� Ȼ���ڸ���ͼ��
    @Override
    public void run() {
        // TODO run
        while (gameRunFlag) {
            synchronized (surfaceHolder) {
                try {
                    // Ϊ���γɶ���Ч��������Ҫ������Ļ
                    // ��������ܶ��߳�ͬʱ����
                    canvas = surfaceHolder.lockCanvas();
                    // ���뱳������ײ�ͼ��
                    canvas.drawBitmap(Config.gameBK, 0, 0, paint);
                    // �����Ϸ�ֲ�����������ڶ���ͼ�㣬�������ڱ���֮��
                    canvas.drawBitmap(Config.seedBank,
                            Config.seedBankLocationX, 0, paint);
                    // ���ݸ��Ĳ���
                    updateData();
                    // ����ֲ�￨Ƭ���ڶ��㣩
                    ondraw(canvas);
                } catch (Exception e) {
                    // TODO: handle exception
                } finally {
                    // �������ύ
                    surfaceHolder.unlockCanvasAndPost(canvas);
                    // CanvasAndPost����Ҫ���н��� ���ܳ�����ʲô���������û�ֱ����������ʾ����
                    // �Է���һ�Ļ� ����try catch
                }
            }
            // �����������ÿ��ѭ��������50�������һֱѭ����ϵͳ��Դ�˷�
            // ʹ��50�����ԭ������42֡���������۾ͻ���Ϊ�������ģ���1��42��ͼƬ��ÿ��ѭ������50���뼴20֡
            // �����sleep����synchronized�еĻ�����ֳ���ÿ�α���������˯��Ȼ���ٴα���û�и����������¼����л���ɿ���
            try {
                Thread.sleep(40);
            } catch (InterruptedException e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
            }

        }
    }

    private void updateData() {
        // �ڴ˷����н������ݸ���
        // ���deadList
        deadList.clear();
        // ������һͼ��
        for (BaseModel model : gameLayout1) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        // �����ڶ�ͼ��
        for (BaseModel model : gameLayout2) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        // ��������ͼ��
        for (BaseModel model : gameLayout3) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }

        // ���������ܵ��ϵĽ�ʬ
        for (BaseModel model : gamelayout4zombie0) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        for (BaseModel model : gamelayout4zombie1) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        for (BaseModel model : gamelayout4zombie2) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        for (BaseModel model : gamelayout4zombie3) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        for (BaseModel model : gamelayout4zombie4) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        // ���������ܵ��ϵ�ֲ��
        for (BaseModel model : gameLayout4plant0) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        for (BaseModel model : gameLayout4plant1) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        for (BaseModel model : gameLayout4plant2) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        for (BaseModel model : gameLayout4plant3) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        for (BaseModel model : gameLayout4plant4) {
            if (!model.isAlife()) {
                deadList.add(model);
            }
        }
        // ����deadList����
        for (BaseModel model : deadList) {
            // �ڸ���ͼ���б��а������Ƴ�
            gameLayout1.remove(model);
            gameLayout2.remove(model);
            gameLayout3.remove(model);
            gamelayout4zombie0.remove(model);
            gamelayout4zombie1.remove(model);
            gamelayout4zombie2.remove(model);
            gamelayout4zombie3.remove(model);
            gamelayout4zombie4.remove(model);
        }
    }

    private void ondraw(Canvas canvas) {
        // TODO ondraw
        // �ڴ˷����н��л�ͼ��ҵ
        // ������Ϸ�Ĳ�ν��л��ƣ��Ȼ���Ϸ������·��ľ���
        // �����Ѿ�д�õķֲ�˳��

        // ���Ƴ�����ֵ
        Paint paint2 = new Paint();
        paint2.setTypeface(Typeface.DEFAULT_BOLD);
        paint2.setTextSize(15);
        canvas.drawText(Config.sunlight + "", Config.deviceWidth * 2 / 7,
                Config.deviceHeight / 8, paint2);

        // ��ʬ�������е�drawSelfʵ�ֽ�ʬ�ƶ�
        zombieManager.drawSelf(canvas, paint);

        // �ܵ�Ӧ�ô��ڵ��Ĳ�ʷ����Ϸ��Ȼ��Ƴ���
        // ���������ܵ�����drawSelf�������л���ֲ��
        // �˴�Ҳ���Խ��з����ĳ��� ����˵Ӧ�ð���Щ�ظ��Ĵ������Ϊһ���������ò�ͬ��ֵ��ȥ
        for (BaseModel model : gameLayout4plant0) {
            model.drawSelf(canvas, paint);
        }
        for (BaseModel model : gameLayout4plant1) {
            model.drawSelf(canvas, paint);
        }
        for (BaseModel model : gameLayout4plant2) {
            model.drawSelf(canvas, paint);
        }
        for (BaseModel model : gameLayout4plant3) {
            model.drawSelf(canvas, paint);
        }
        for (BaseModel model : gameLayout4plant4) {
            model.drawSelf(canvas, paint);
        }

        // �����㣨���⣩
        for (BaseModel model : gameLayout3) {
            model.drawSelf(canvas, paint);
        }

        // �ڶ���
        for (BaseModel model : gameLayout2) {
            model.drawSelf(canvas, paint);
        }

        // ���������ܵ�����drawSelf�������л��ƽ�ʬ
        // �˴�Ҳ���Խ��з����ĳ��� ����˵Ӧ�ð���Щ�ظ��Ĵ������Ϊһ���������ò�ͬ��ֵ��ȥ
        // �ڶ�����ֲ�￨Ƭ����ʬ�ھ�����һ�е�ʱ��Ӧ�ÿ��Ե�סֲ�￨Ƭ
        for (BaseModel model : gamelayout4zombie0) {
            model.drawSelf(canvas, paint);
        }
        for (BaseModel model : gamelayout4zombie1) {
            model.drawSelf(canvas, paint);
        }
        for (BaseModel model : gamelayout4zombie2) {
            model.drawSelf(canvas, paint);
        }
        for (BaseModel model : gamelayout4zombie3) {
            model.drawSelf(canvas, paint);
        }
        for (BaseModel model : gamelayout4zombie4) {
            model.drawSelf(canvas, paint);
        }

        // ��һ��
        // gameLayout1��gameLayout2�Ĳ��Ҫ�߹ʷ��ں���
        for (BaseModel model : gameLayout1) {
            model.drawSelf(canvas, paint);
        }

		/*
         * private m=200; Paint paint3 = new Paint(); paint3.setAlpha(100);
		 * canvas.drawRect(100, 100, 200, m, paint3); m-=5; ���ð�͸��Ч��
		 * m�������ǿ����������͸��Ч������ȥ�� m�ı仯��С�Ϳ������Ϊ��ֲ�����ȴʱ��
		 */
    }

    // ��������д������Ӧ�¼�
    @Override
    public boolean onTouchEvent(MotionEvent event) {
        // TODO onTouchEvent
        for (BaseModel model : gameLayout1) {
            if (model instanceof TouchAble) {
                if (((TouchAble) model).onTouch(event)) {
                    return true;
                }
            }
        }
        for (BaseModel model : gameLayout2) {
            if (model instanceof TouchAble) {
                if (((TouchAble) model).onTouch(event)) {
                    return true;
                }
            }
        }
        for (BaseModel model : gameLayout3) {
            if (model instanceof TouchAble) {
                if (((TouchAble) model).onTouch(event)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static GameView getInstance() {
        return gameView;
    }

    public void applay4EmplacePlant(int locationX, int locationY,
                                    BaseModel model) {
        // TODO applay4EmplacePlant
        synchronized (surfaceHolder) {
            if (gameLayout1.size() < 1) {
                if (model instanceof SeedPea) {
                    gameLayout1.add(new EmplacePea(locationX, locationY));
                } else {
                    gameLayout1.add(new EmplaceFlower(locationX, locationY));
                }
            }
        }

    }

    public void applay4Plant(int locationX, int locationY, BaseModel baseModel) {
        // TODO applay4Plant
        synchronized (surfaceHolder) {
            Point point;
            for (Integer key : Config.plantPoints.keySet()) {
                // �Ҿ���locationX��locationY������Ҵ���Ŀ�������
                point = Config.plantPoints.get(key);
                if ((Math.abs(locationX - point.x) < Config.deviceWidth / 11 / 2)
                        && (Math.abs(locationY - point.y) < Config.deviceHeight / 6 / 2)) {
                    // �ܵ���ʾ
                    int raceWayIndex = 6;
                    for (int i = 0; i < Config.raceWayYpoints.length; i++) {
                        // �����ܵ���Yֵ
                        if (point.y == Config.raceWayYpoints[i]) {
                            // ���Yֵ�����ô�ܵ�ȷ��
                            raceWayIndex = i;
                        }
                    }
                    if (isPlantExist(key, raceWayIndex)) {
                        // �ܵ��������¼�
                        switch (raceWayIndex) {
                            case 0:
                                if (baseModel instanceof EmplacePea) {
                                    gameLayout4plant0.add(new Pea(point.x, point.y,
                                            key));
                                    Config.sunlight -= 100;
                                } else {
                                    gameLayout4plant0.add(new Flower(point.x,
                                            point.y, key));
                                    Config.sunlight -= 50;
                                }
                                break;
                            case 1:
                                if (baseModel instanceof EmplacePea) {
                                    gameLayout4plant1.add(new Pea(point.x, point.y,
                                            key));
                                    Config.sunlight -= 100;
                                } else {
                                    gameLayout4plant1.add(new Flower(point.x,
                                            point.y, key));
                                    Config.sunlight -= 50;
                                }
                                break;
                            case 2:
                                if (baseModel instanceof EmplacePea) {
                                    gameLayout4plant2.add(new Pea(point.x, point.y,
                                            key));
                                    Config.sunlight -= 100;
                                } else {
                                    gameLayout4plant2.add(new Flower(point.x,
                                            point.y, key));
                                    Config.sunlight -= 50;
                                }
                                break;
                            case 3:
                                if (baseModel instanceof EmplacePea) {
                                    gameLayout4plant3.add(new Pea(point.x, point.y,
                                            key));
                                    Config.sunlight -= 100;
                                } else {
                                    gameLayout4plant3.add(new Flower(point.x,
                                            point.y, key));
                                    Config.sunlight -= 50;
                                }
                                break;
                            case 4:
                                if (baseModel instanceof EmplacePea) {
                                    gameLayout4plant4.add(new Pea(point.x, point.y,
                                            key));
                                    Config.sunlight -= 100;
                                } else {
                                    gameLayout4plant4.add(new Flower(point.x,
                                            point.y, key));
                                    Config.sunlight -= 50;
                                }
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
    }

    // �жϴ˴��Ƿ��Ѿ���ֲ��ķ���
    // key�Ǳ�ʾ��raceWayIndex����ȷ��Ӧ����һ���ܵ�
    private boolean isPlantExist(int key, int raceWayIndex) {
        switch (raceWayIndex) {
            case 0:
                for (BaseModel model : gameLayout4plant0) {
                    // ���� �����������Ǽ̳���Plant�ӿڵ�����(��Ϊ�ӵ������ⲻ�̳�Plant��������)
                    if (model instanceof Plant) {
                        // Ȼ��˴���key�봫���key���
                        if (key == ((Plant) model).getmapIndex()) {
                            // ��ô���ش˴�������ֲֲ��
                            return false;
                        }
                    }
                }
                break;
            case 1:
                for (BaseModel model : gameLayout4plant1) {
                    // ���� �����������Ǽ̳���Plant�ӿڵ�����(��Ϊ�ӵ������ⲻ�̳�Plant��������)
                    if (model instanceof Plant) {
                        // Ȼ��˴���key�봫���key���
                        if (key == ((Plant) model).getmapIndex()) {
                            // ��ô���ش˴�������ֲֲ��
                            return false;
                        }
                    }
                }
                break;
            case 2:
                for (BaseModel model : gameLayout4plant2) {
                    // ���� �����������Ǽ̳���Plant�ӿڵ�����(��Ϊ�ӵ������ⲻ�̳�Plant��������)
                    if (model instanceof Plant) {
                        // Ȼ��˴���key�봫���key���
                        if (key == ((Plant) model).getmapIndex()) {
                            // ��ô���ش˴�������ֲֲ��
                            return false;
                        }
                    }
                }
                break;
            case 3:
                for (BaseModel model : gameLayout4plant3) {
                    // ���� �����������Ǽ̳���Plant�ӿڵ�����(��Ϊ�ӵ������ⲻ�̳�Plant��������)
                    if (model instanceof Plant) {
                        // Ȼ��˴���key�봫���key���
                        if (key == ((Plant) model).getmapIndex()) {
                            // ��ô���ش˴�������ֲֲ��
                            return false;
                        }
                    }
                }
                break;
            case 4:
                for (BaseModel model : gameLayout4plant4) {
                    // ���� �����������Ǽ̳���Plant�ӿڵ�����(��Ϊ�ӵ������ⲻ�̳�Plant��������)
                    if (model instanceof Plant) {
                        // Ȼ��˴���key�봫���key���
                        if (key == ((Plant) model).getmapIndex()) {
                            // ��ô���ش˴�������ֲֲ��
                            return false;
                        }
                    }
                }
                break;
            default:
                break;
        }
        return true;
    }

    // ��Flower�������ڲ�������
    public void giveBrith2Sun(int locationX, int locationY) {
        // TODO giveBrith2Sun
        // ��������ס
        synchronized (surfaceHolder) {
            gameLayout3.add(new Sun(locationX, locationY));
        }
    }

    // ��Pea�������ڲ����ӵ�
    public void giveBirth2Bullet(int locationX, int locationY) {
        // TODO Auto-generated method stub
        // ��������ס
        synchronized (surfaceHolder) {// ����
            Point point;
            for (Integer key : Config.plantPoints.keySet()) {
                // �Ҿ���locationX��locationY������Ҵ���Ŀ�������
                point = Config.plantPoints.get(key);
                if ((Math.abs(locationX - point.x) < Config.deviceWidth / 11 / 2)
                        && (Math.abs(locationY - point.y) < Config.deviceHeight / 6 / 2)) {
                    // �ܵ���ʾ
                    int raceWayIndex = 6;
                    for (int i = 0; i < Config.raceWayYpoints.length; i++) {
                        // �����ܵ���Yֵ
                        if (point.y == Config.raceWayYpoints[i]) {
                            // ���Yֵ�����ô�ܵ�ȷ��
                            raceWayIndex = i;
                        }
                    }

                    switch (raceWayIndex) {
                        case 0:
                            gameLayout4plant0.add(new Bullet(locationX, locationY));
                            break;
                        case 1:
                            gameLayout4plant1.add(new Bullet(locationX, locationY));
                            break;
                        case 2:
                            gameLayout4plant2.add(new Bullet(locationX, locationY));
                            break;
                        case 3:
                            gameLayout4plant3.add(new Bullet(locationX, locationY));
                            break;
                        case 4:
                            gameLayout4plant4.add(new Bullet(locationX, locationY));
                            break;
                        default:
                            break;
                    }
                }
            }
        }
    }

    // ��ʬ������������Ӧ�����뽩ʬ
    public void apply4AddZombie() {
        // TODO apply4AddZombie
        // �Ƚ���
        synchronized (surfaceHolder) {
            int raceWay = 0;
            // 0~4��������������ܵ���ʼ��
            // Math.random()��������0~1�Ĳ�����1�����double������
            raceWay = (int) (Math.random() * 5);
            // Config.deviceWidth + 20��Ϊ���ý�ʬ��������Ļ
            // Config.raceWayYpoints[raceWay] - Config.heightYDistance
            // ��Ϊ���ý�ʬ��ֲ��ĵ׶���
            switch (raceWay) {
                case 0:
                    gamelayout4zombie0
                            .add(new Zombie(Config.deviceWidth + 20,
                                    Config.raceWayYpoints[raceWay]
                                            - Config.heightYDistance, raceWay));
                    break;
                case 1:
                    gamelayout4zombie1
                            .add(new Zombie(Config.deviceWidth + 20,
                                    Config.raceWayYpoints[raceWay]
                                            - Config.heightYDistance, raceWay));
                    break;
                case 2:
                    gamelayout4zombie2
                            .add(new Zombie(Config.deviceWidth + 20,
                                    Config.raceWayYpoints[raceWay]
                                            - Config.heightYDistance, raceWay));
                    break;
                case 3:
                    gamelayout4zombie3
                            .add(new Zombie(Config.deviceWidth + 20,
                                    Config.raceWayYpoints[raceWay]
                                            - Config.heightYDistance, raceWay));
                    break;
                case 4:
                    gamelayout4zombie4
                            .add(new Zombie(Config.deviceWidth + 20,
                                    Config.raceWayYpoints[raceWay]
                                            - Config.heightYDistance, raceWay));
                    break;
                default:
                    break;
            }
        }
    }

    // ������ײ��⣬��ײ���ķ������ǽ�ʬ
    public void checkCollision(Zombie zombie, int raceWay) {
        // TODO Auto-generated method stub
        synchronized (surfaceHolder) {
            switch (raceWay) {
                case 0:
                    for (BaseModel model : gameLayout4plant0) {
                        if (Math.abs((model.getLocationX() + model.getModelWidth() / 2)
                                - (zombie.getLocationX() + zombie.getModelWidth() / 2)) < Math
                                .abs((model.getModelWidth() + zombie
                                        .getModelWidth()) / 2)) {
                            if (model instanceof Plant) {
                                // ֲ����
                                model.setAlife(false);
                            } else if (model instanceof Bullet) {
                                // �ӵ���
                                model.setAlife(false);
                                // ��ʬ��
                                zombie.setAlife(false);
                                model.setAlife(true);
                            }
                        }
                    }
                    break;
                case 1:
                    for (BaseModel model : gameLayout4plant1) {
                        if (Math.abs((model.getLocationX() + model.getModelWidth() / 2)
                                - (zombie.getLocationX() + zombie.getModelWidth() / 2)) < Math
                                .abs((model.getModelWidth() + zombie
                                        .getModelWidth()) / 2)) {
                            if (model instanceof Plant) {
                                // ֲ����
                                model.setAlife(false);
                            } else if (model instanceof Bullet) {
                                // �ӵ���
                                model.setAlife(false);
                                // ��ʬ��
                                zombie.setAlife(false);
                                model.setAlife(true);
                            }
                        }
                    }
                    break;
                case 2:
                    for (BaseModel model : gameLayout4plant2) {
                        if (Math.abs((model.getLocationX() + model.getModelWidth() / 2)
                                - (zombie.getLocationX() + zombie.getModelWidth() / 2)) < Math
                                .abs((model.getModelWidth() + zombie
                                        .getModelWidth()) / 2)) {
                            if (model instanceof Plant) {
                                // ֲ����
                                model.setAlife(false);
                            } else if (model instanceof Bullet) {
                                // �ӵ���
                                model.setAlife(false);
                                // ��ʬ��
                                zombie.setAlife(false);
                                model.setAlife(true);
                            }
                        }
                    }
                    break;
                case 3:
                    for (BaseModel model : gameLayout4plant3) {
                        if (Math.abs((model.getLocationX() + model.getModelWidth() / 2)
                                - (zombie.getLocationX() + zombie.getModelWidth() / 2)) < Math
                                .abs((model.getModelWidth() + zombie
                                        .getModelWidth()) / 2)) {
                            if (model instanceof Plant) {
                                // ֲ����
                                model.setAlife(false);
                            } else if (model instanceof Bullet) {
                                // �ӵ���
                                model.setAlife(false);
                                // ��ʬ��
                                zombie.setAlife(false);
                                model.setAlife(true);
                            }
                        }
                    }
                    break;
                case 4:
                    for (BaseModel model : gameLayout4plant4) {
                        if (Math.abs((model.getLocationX() + model.getModelWidth() / 2)
                                - (zombie.getLocationX() + zombie.getModelWidth() / 2)) < Math
                                .abs((model.getModelWidth() + zombie
                                        .getModelWidth()) / 2)) {
                            if (model instanceof Plant) {
                                // ֲ����
                                model.setAlife(false);
                            } else if (model instanceof Bullet) {
                                // �ӵ���
                                model.setAlife(false);
                                // ��ʬ��
                                zombie.setAlife(false);
                                model.setAlife(true);
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
        }
    }

}
