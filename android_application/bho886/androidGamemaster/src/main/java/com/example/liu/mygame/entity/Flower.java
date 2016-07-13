package com.example.liu.mygame.entity;

import android.graphics.Canvas;
import android.graphics.Paint;

import com.example.liu.mygame.global.Config;
import com.example.liu.mygame.model.BaseModel;
import com.example.liu.mygame.model.Plant;
import com.example.liu.mygame.view.GameView;

//�㶹����ʵ����
public class Flower extends BaseModel implements Plant {
    private int locationX;
    private int locationY;
    private boolean isAlife;
    // ͼƬ֡������±�
    private int frameIndex = 0;
    // һ�����ͨ���˱��ȷ���˴��Ƿ���ֲ��
    private int mapIndex;
    // ���Ʋ��������ʱ��
    private long lastBirthTime;
    // �ڶ��ٶȿ��ƣ���֡һ��
    private boolean swingSpeed;

    public Flower(int locationX, int locationY, int mapIndex) {
        this.locationX = locationX;
        this.locationY = locationY;
        this.mapIndex = mapIndex;
        isAlife = true;
        // ��ʼ��ʱ������ȷ������ʱ���뻨�Ĵ���ʱ��һ��
        lastBirthTime = System.currentTimeMillis();
        swingSpeed = false;
    }

    @Override
    public void drawSelf(Canvas canvas, Paint paint) {
        // TODO Auto-generated method stub
        if (isAlife) {
            // ��������bitmap����Ҫ�ڻ����Լ���ʱ���Լ���֡�������γɶ�̬Ч��
            // �������Config���Ѿ���ʼ������
            canvas.drawBitmap(Config.flowerFrames[frameIndex], locationX,
                    locationY, paint);
            // �ô˱���������仯
            // ͨ��������ȡģ���������������frameIndexֵ������7
            // ��frameIndexΪ8ʱ���Ϊ0����������Խ��
            if (!swingSpeed) {
                frameIndex = (++frameIndex) % 8;
                swingSpeed = false;
            } else {
                swingSpeed = true;
            }

            // �ô˴��ж���ȷ��ÿ10��һ������Ĳ���
            if (System.currentTimeMillis() - lastBirthTime > 10000) {
                lastBirthTime = System.currentTimeMillis();
                giveBirth2Sun();
            }
        }
    }

    // ��������
    // �������������Ȼ������������������ת��״̬���ƶ����Ϸ�����ı�־������һ��ʱ�䲻�����������ʧ
    // �����ڻ���λ����
    private void giveBirth2Sun() {
        // ����Ҫ�������ͼ�㼯�ϣ����ڵ����㣬��ô����Ҫ�������ϣ�����Ҫ����GameView.getInstance
        GameView.getInstance().giveBrith2Sun(locationX, locationY);
    }

    @Override
    public int getModelWidth() {
        // TODO Auto-generated method stub
        return Config.flowerFrames[0].getWidth();
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

    @Override
    public int getmapIndex() {
        // TODO Auto-generated method stub
        return mapIndex;
    }
}
