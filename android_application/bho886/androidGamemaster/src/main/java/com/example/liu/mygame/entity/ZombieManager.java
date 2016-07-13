package com.example.liu.mygame.entity;

import android.graphics.Canvas;
import android.graphics.Paint;

import com.example.liu.mygame.model.BaseModel;
import com.example.liu.mygame.view.GameView;

public class ZombieManager extends BaseModel {
    // һ����Ҫ��ʾ��������Ļ�ϵ�ʵ�����Ҫ�̳�BaseModel
    // ���Դ˴��Ľ�ʬ��������ʵ����Ҫ�̳�BaseModel
    // ����Ϊ����֮ǰ��flower��pea��������ͳһ
    // Ч����ǰ��ģʽ���ٹ�����
    // ������Ҳ���м̳�

    private boolean isAlife;
    // ���һֻ��ʬ�Ĳ���ʱ��
    private long lastBirthTime;

    public ZombieManager() {
        lastBirthTime = System.currentTimeMillis();
        isAlife = true;
    }

    @Override
    public void drawSelf(Canvas canvas, Paint paint) {
        // TODO Auto-generated method stub
        // �˴�����Ҫ���Ƴ�ͼƬ�����Բ���Ҫdraw�����ǿ��Խ����߼��ϵĴ���
        if (System.currentTimeMillis() - lastBirthTime > 15000) {
            lastBirthTime = System.currentTimeMillis();
            giveBirth2Zombie();
        }
    }

    private void giveBirth2Zombie() {
        // ��GameView������뽩ʬ
        GameView.getInstance().apply4AddZombie();
    }
}
