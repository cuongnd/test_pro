package com.example.liu.mygame.entity;

import android.graphics.Canvas;
import android.graphics.Paint;

import com.example.liu.mygame.global.Config;
import com.example.liu.mygame.model.BaseModel;

public class Bullet extends BaseModel {

    // λ��
    private int locationX;
    private int locationY;
    // ����
    private boolean isAlife;
    // �ӵ�����ʱ��
    private long birthTime = 0l;

    // X�����ϵ��ٶȷ���
    // ����֡����ȷ���ƶ�ʱ�䣬Ȼ����ȷ���ƶ���ʽ
    private float SpeedX = 10;

    public Bullet(int locationX, int locationY) {
        this.locationX = locationX + 40;
        this.locationY = locationY + 20;
        this.isAlife = true;
        // ��ȡϵͳʱ��
        birthTime = System.currentTimeMillis();
    }

    @Override
    public void drawSelf(Canvas canvas, Paint paint) {
        // TODO Auto-generated method stub
        if (isAlife) {
            locationX += SpeedX;

            if (locationX > Config.deviceWidth) {
                isAlife = false;
            }
        }

        canvas.drawBitmap(Config.bullet, locationX, locationY, paint);

    }

    @Override
    public int getModelWidth() {
        // TODO Auto-generated method stub
        return Config.bullet.getWidth();
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
