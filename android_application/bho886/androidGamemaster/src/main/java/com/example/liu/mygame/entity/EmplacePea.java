package com.example.liu.mygame.entity;

import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.Rect;
import android.view.MotionEvent;

import com.example.liu.mygame.global.Config;
import com.example.liu.mygame.model.BaseModel;
import com.example.liu.mygame.model.TouchAble;
import com.example.liu.mygame.view.GameView;

public class EmplacePea extends BaseModel implements TouchAble {

    private int locationX;
    private int locationY;
    // ����
    private boolean isAlife;
    // �������򣨾��Σ�
    private Rect touchArea;

    public EmplacePea(int locationX, int locationY) {
        this.locationX = locationX;
        this.locationY = locationY;
        this.isAlife = true;
        // ��ʼ��������Ӧ��������
        touchArea = new Rect(0, 0, Config.deviceWidth, Config.deviceHeight);
    }

    @Override
    public void drawSelf(Canvas canvas, Paint paint) {
        // TODO Auto-generated method stub
        if (isAlife) {
            canvas.drawBitmap(Config.peaFrames[0], locationX, locationY, paint);
        }
    }

    @Override
    public boolean onTouch(MotionEvent event) {
        // TODO Auto-generated method stub
        int x = (int) event.getX();
        int y = (int) event.getY();

        // �������ĵط����ھ��������ڣ���ô��ʼ���ø���
        if (touchArea.contains(x, y)) {
            // ͼ�����
            // switch����Ҫ��Ӧ�����¼������¡�̧���϶�
            switch (event.getAction()) {
                case MotionEvent.ACTION_DOWN:
                    break;
                case MotionEvent.ACTION_MOVE:
                    // drawSelf�����Ѷ�����ô������Ҫ�ı��ʾλ�õ�����������ͬʱҲҪ�ı���Ӧ���������touchArea
                    locationX = x - Config.peaFrames[0].getWidth() / 2;
                    locationY = y - Config.peaFrames[0].getHeight() / 2;
                    break;
                case MotionEvent.ACTION_UP:
                    // �����Ժ���ƶ��е�ʵ�����������ڽ��������ض�������µĹ̶���ʵ��
                    isAlife = false;
                    // ����GameView����
                    GameView.getInstance().applay4Plant(locationX, locationY, this);
                    break;
            }
        }
        return false;
    }

    public int getLocationX() {
        return locationX;
    }

    public void setLocationX(int locationX) {
        this.locationX = locationX;
    }

    public int getLocationY() {
        return locationY;
    }

    public void setLocationY(int locationY) {
        this.locationY = locationY;
    }

    public boolean isAlife() {
        return isAlife;
    }

    public void setAlife(boolean isAlife) {
        this.isAlife = isAlife;
    }

}
