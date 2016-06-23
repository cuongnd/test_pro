package com.vantinviet.bho88;


import org.andengine.engine.camera.Camera;
import org.andengine.engine.options.EngineOptions;
import org.andengine.engine.options.ScreenOrientation;
import org.andengine.engine.options.resolutionpolicy.FillResolutionPolicy;
import org.andengine.entity.primitive.Line;
import org.andengine.entity.scene.Scene;
import org.andengine.entity.scene.background.Background;
import org.andengine.entity.util.FPSLogger;
import org.andengine.opengl.vbo.VertexBufferObjectManager;
import org.andengine.ui.activity.SimpleBaseGameActivity;

import java.util.Random;

public class Main3Activity extends SimpleBaseGameActivity {

    private Camera camera;
    private static final long RANDOM_SEED = 1234567890;

    private static final int CAMERA_WIDTH = 720;
    private static final int CAMERA_HEIGHT = 480;

    private static final int LINE_COUNT = 100;
    @Override
    protected void onCreateResources() {

    }

    @Override
    protected Scene onCreateScene()
    {
        Scene scene = new Scene();
        scene.setBackground(new Background(0.09804f, 0.6274f, 0.8784f));

        this.mEngine.registerUpdateHandler(new FPSLogger());



        final Random random = new Random(RANDOM_SEED);

        final VertexBufferObjectManager vertexBufferObjectManager = this.getVertexBufferObjectManager();
        for(int i = 0; i < LINE_COUNT; i++) {
            final float x1 = random.nextFloat() * CAMERA_WIDTH;
            final float x2 = random.nextFloat() * CAMERA_WIDTH;
            final float y1 = random.nextFloat() * CAMERA_HEIGHT;
            final float y2 = random.nextFloat() * CAMERA_HEIGHT;
            final float lineWidth = random.nextFloat() * 5;

            final Line line = new Line(x1, y1, x2, y2, lineWidth, vertexBufferObjectManager);

            line.setColor(random.nextFloat(), random.nextFloat(), random.nextFloat());

            scene.attachChild(line);
        }

        return scene;

    }

    @Override
    public EngineOptions onCreateEngineOptions()
    {
        camera = new Camera(0, 0, CAMERA_WIDTH, CAMERA_HEIGHT);
        EngineOptions engineOptions = new EngineOptions(true, ScreenOrientation.LANDSCAPE_FIXED,
                new FillResolutionPolicy(), camera);
        return engineOptions;
    }
}
