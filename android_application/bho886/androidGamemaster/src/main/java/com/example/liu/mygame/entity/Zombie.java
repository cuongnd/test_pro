package com.example.liu.mygame.entity;

import android.graphics.Canvas;
import android.graphics.Paint;

import com.example.liu.mygame.global.Config;
import com.example.liu.mygame.model.BaseModel;
import com.example.liu.mygame.view.GameView;

public class Zombie extends BaseModel {
    private int locationX;
    private int locationY;
    private boolean isAlife;
    // ��ʬλ�ڵ��ܵ�����Ϊ�˽�ʬֻ�������ڵ��ܵ��ڵ�ֲ��ӵ��Ƚ�����ײ���
    private int raceWay;
    // ��Ϊ��ʬ���ƶ��е� ������Ҫ�ж���֡���±�
    private int frameIndex = 0;
    // �ƶ��ٶȣ�ÿһ֡�ƶ�3����
    private int peedX = 3;

    public Zombie(int locationX, int locationY, int raceWay) {
        this.locationX = locationX;
        this.locationY = locationY;
        isAlife = true;
        this.raceWay = raceWay;
    }

    // ��ĳ�ܵ����������ʬ��ͬʱ���һ��ʱ�����һֻ��ʬ
    @Override
    public void drawSelf(Canvas canvas, Paint paint) {
        // TODO Auto-generated method stub
        if (locationX < 0) {
            Config.game = false;
        }
        if (isAlife) {
            canvas.drawBitmap(Config.zombieFrames[frameIndex], locationX,
                    locationY, paint);
            frameIndex = (++frameIndex) % 7;
            locationX -= peedX;
            // ��ײ��⣬��ʬ����Ĵ���ײ���
            GameView.getInstance().checkCollision(this, raceWay);
        }

    }

    @Override
    public int getModelWidth() {
        // TODO Auto-generated method stub
        return Config.zombieFrames[0].getWidth();
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
