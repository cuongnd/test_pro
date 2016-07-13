package com.example.liu.mygame;

import android.app.Activity;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.view.MotionEvent;

import com.example.liu.mygame.global.Config;
import com.example.liu.mygame.tools.DeviceTools;
import com.example.liu.mygame.view.GameView;

public class MainActivity extends Activity {

    private GameView gameview;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        init();

        gameview = new GameView(this);
        setContentView(gameview);
    }

    // ��ʼ����Ϸ��Դ
    private void init() {
        // TODO Auto-generated method stub
        // ��ȡ��Ļ��С�ߴ�
        Config.deviceWidth = DeviceTools.getDeviceInfo(this)[0];
        Config.deviceHeight = DeviceTools.getDeviceInfo(this)[1];

        // �õ�ԭʼͼƬ
        Config.gameBK = BitmapFactory.decodeResource(getResources(),
                R.drawable.bk);

        // ��ȡ���ű�
        Config.scaleWidth = Config.deviceWidth
                / (float) Config.gameBK.getWidth();
        Config.scaleHeight = Config.deviceHeight
                / (float) Config.gameBK.getHeight();

        // ����ͼƬ������ΪĿ��ͼƬ
        Config.gameBK = DeviceTools.resizeBitmap(Config.gameBK);
        Config.seedBank = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.seedbank));

        // ���Ƴ���Ƭ�����ܽ��еȱ�����Ҫ����Ŀ���С���������
        Config.seedFlower = DeviceTools.resizeBitmap(BitmapFactory
                        .decodeResource(getResources(), R.drawable.seed_flower),
                Config.seedBank.getWidth() / 10,
                Config.seedBank.getHeight() * 8 / 10);
        Config.seedPea = DeviceTools.resizeBitmap(BitmapFactory.decodeResource(
                getResources(), R.drawable.seed_pea), Config.seedBank
                .getWidth() / 10, Config.seedBank.getHeight() * 8 / 10);

        // ��ʼ������ͼƬ
        Config.sun = DeviceTools.resizeBitmap(BitmapFactory.decodeResource(
                getResources(), R.drawable.sun));

        // ��ʼ���ӵ�ͼƬ
        Config.bullet = DeviceTools.resizeBitmap(BitmapFactory.decodeResource(
                getResources(), R.drawable.bullet));

        // ��ʼ��gameOverͼƬ
        Config.gameOver = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.gameover));

        // ��ʼ����̬ͼƬ֡
        Config.flowerFrames[0] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_1_01));
        Config.flowerFrames[1] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_1_02));
        Config.flowerFrames[2] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_1_03));
        Config.flowerFrames[3] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_1_04));
        Config.flowerFrames[4] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_1_05));
        Config.flowerFrames[5] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_1_06));
        Config.flowerFrames[6] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_1_07));
        Config.flowerFrames[7] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_1_08));

        Config.peaFrames[0] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_2_01));
        Config.peaFrames[1] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_2_02));
        Config.peaFrames[2] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_2_03));
        Config.peaFrames[3] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_2_04));
        Config.peaFrames[4] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_2_05));
        Config.peaFrames[5] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_2_06));
        Config.peaFrames[6] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_2_07));
        Config.peaFrames[7] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.p_2_08));

        Config.zombieFrames[0] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.z_1_01));
        Config.zombieFrames[1] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.z_1_02));
        Config.zombieFrames[2] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.z_1_03));
        Config.zombieFrames[3] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.z_1_04));
        Config.zombieFrames[4] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.z_1_05));
        Config.zombieFrames[5] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.z_1_06));
        Config.zombieFrames[6] = DeviceTools.resizeBitmap(BitmapFactory
                .decodeResource(getResources(), R.drawable.z_1_07));
    }

    // ��дonTouch������Ӧ�¼�������ֵΪgameview�����ɵ�onTouch�¼�ֵ
    @Override
    public boolean onTouchEvent(MotionEvent event) {
        // TODO Auto-generated method stub
        return gameview.onTouchEvent(event);
    }

    // ����
    @Override
    protected void onDestroy() {
        super.onDestroy();
    }

    // ֹͣ
    @Override
    protected void onPause() {
        super.onPause();
    }

    // ����
    @Override
    protected void onResume() {
        super.onResume();
    }
}
