package com.vantinviet.bho88.libraries.joomla.form.fields;

import android.text.InputType;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsoluteLayout;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.beardedhen.androidbootstrap.BootstrapEditText;
import com.beardedhen.androidbootstrap.BootstrapLabel;
import com.beardedhen.androidbootstrap.api.defaults.DefaultBootstrapBrand;
import com.beardedhen.androidbootstrap.api.defaults.DefaultBootstrapSize;
import com.google.android.libraries.mediaframework.exoplayerextensions.Video;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.form.JFormField;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;
import com.vantinviet.bho88.libraries.utilities.JUtilities;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JFormFieldPlayer extends JFormField{
    static Map<String, JFormFieldPlayer> map_form_field_text = new HashMap<String, JFormFieldPlayer>();
    private AbsoluteLayout control_linear_layout;

    public JFormFieldPlayer(JSONObject field, String type, String name, String group, String value){
        this.type=type;
        this.name=name;
        this.group=group;
        this.option=field;
        this.value=value;
    }
    public JFormFieldPlayer(){
    }


    @Override
    public View getInput() {
        LinearLayout linear_layout = new LinearLayout(context);
        JSONObject option=this.option;
        boolean show_label=true;
        try {
            show_label = option.has("show_label")?option.getBoolean("show_label"):false;
        } catch (JSONException e) {
            e.printStackTrace();
        }

        if(show_label){
            BootstrapLabel label_text = new BootstrapLabel(context);
            label_text.setText(this.label);
            label_text.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT));
            ((LinearLayout) linear_layout).addView(label_text);
        }
        BootstrapEditText input_number = new BootstrapEditText(context);
        input_number.setBootstrapBrand(DefaultBootstrapBrand.PRIMARY);
        input_number.setBootstrapSize(DefaultBootstrapSize.LG);
        input_number.setInputType(InputType.TYPE_CLASS_NUMBER);
        input_number.setText(this.value);
        input_number.setTag(this.key);
        input_number.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));

        this.key_id= JUtilities.getRandomInt(0,999999);
        input_number.setId(this.key_id);



        this.control_linear_layout = new AbsoluteLayout (context);

        TextView stateTitletv = new TextView(context);

        stateTitletv.setText("tv1");






        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.FILL_PARENT);
        params.setMargins(0, 100, 0, 10);




        stateTitletv.setLayoutParams(params);
        LinearLayout content_control_linear_layout=new LinearLayout(context);
        ((LinearLayout ) content_control_linear_layout).addView(stateTitletv);
        JApplication app=JFactory.getApplication();
        // this is Layout to which you want to add
        final VideoListItem[] videoListItems = getVideoListItems();
        final String[] videoTitles = new String[videoListItems.length];
        for (int i = 0; i < videoListItems.length; i++) {
            videoTitles[i] = videoListItems[i].title;
        }

        View  itemView = LayoutInflater.from(context).inflate(com.google.android.libraries.mediaframework.R.layout.playback_control_layer,null, false);




        ((LinearLayout) linear_layout).addView(itemView);





        linear_layout.setGravity(LinearLayout.TEXT_ALIGNMENT_GRAVITY);
        return (View)linear_layout;
    }

    public VideoListItem[] getVideoListItems() {
        return new VideoListItem[] {
                new VideoListItem("No ads (DASH)",
                        new Video("http://www.youtube.com/api/manifest/dash/id/bf5bb2419360daf1/source/youtub" +
                                "e?as=fmp4_audio_clear,fmp4_sd_hd_clear&sparams=ip,ipbits,expire,source,id,as&ip=" +
                                "0.0.0.0&ipbits=0&expire=19000000000&signature=51AF5F39AB0CEC3E5497CD9C900EBFEAEC" +
                                "CCB5C7.8506521BFC350652163895D4C26DEE124209AA9E&key=ik0",
                                Video.VideoType.DASH,
                                "bf5bb2419360daf1"),
                        null),
                new VideoListItem("Skippable preroll (DASH)",
                        new Video("http://www.youtube.com/api/manifest/dash/id/bf5bb2419360daf1/source/youtub" +
                                "e?as=fmp4_audio_clear,fmp4_sd_hd_clear&sparams=ip,ipbits,expire,source,id,as&ip=" +
                                "0.0.0.0&ipbits=0&expire=19000000000&signature=51AF5F39AB0CEC3E5497CD9C900EBFEAEC" +
                                "CCB5C7.8506521BFC350652163895D4C26DEE124209AA9E&key=ik0",
                                Video.VideoType.DASH,
                                "bf5bb2419360daf1"),
                        "https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/124319096/external/" +
                                "single_ad_samples&ciu_szs=300x250&impl=s&gdfp_req=1&env=vp&output=vast" +
                                "&unviewed_position_start=1&cust_params=deployment%3Ddevsite%26sample_ct" +
                                "%3Dskippablelinear&correlator="),
                new VideoListItem("Unskippable preroll (DASH)",
                        new Video("http://www.youtube.com/api/manifest/dash/id/bf5bb2419360daf1/source/youtub" +
                                "e?as=fmp4_audio_clear,fmp4_sd_hd_clear&sparams=ip,ipbits,expire,source,id,as&ip=" +
                                "0.0.0.0&ipbits=0&expire=19000000000&signature=51AF5F39AB0CEC3E5497CD9C900EBFEAEC" +
                                "CCB5C7.8506521BFC350652163895D4C26DEE124209AA9E&key=ik0",
                                Video.VideoType.DASH,
                                "bf5bb2419360daf1"),
                        "https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/124319096/external/" +
                                "single_ad_samples&ciu_szs=300x250&impl=s&gdfp_req=1&env=vp&output=vast" +
                                "&unviewed_position_start=1&cust_params=deployment%3Ddevsite%26sample_ct" +
                                "%3Dlinear&correlator="),
                new VideoListItem("Ad rules - Pre-, Mid-, and Post-rolls (DASH)",
                        new Video("http://www.youtube.com/api/manifest/dash/id/bf5bb2419360daf1/source/youtub" +
                                "e?as=fmp4_audio_clear,fmp4_sd_hd_clear&sparams=ip,ipbits,expire,source,id,as&ip=" +
                                "0.0.0.0&ipbits=0&expire=19000000000&signature=51AF5F39AB0CEC3E5497CD9C900EBFEAEC" +
                                "CCB5C7.8506521BFC350652163895D4C26DEE124209AA9E&key=ik0",
                                Video.VideoType.DASH,
                                "bf5bb2419360daf1"),
                        "https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/124319096/external/" +
                                "ad_rule_samples&ciu_szs=300x250&ad_rule=1&impl=s&gdfp_req=1&env=vp" +
                                "&output=vast&unviewed_position_start=1&cust_params=deployment%3Ddevsite" +
                                "%26sample_ar%3Dpremidpostpod&cmsid=496&vid=short_onecue&correlator="),
                new VideoListItem("No ads (mp4)",
                        new Video("http://rmcdn.2mdn.net/MotifFiles/html/1248596/android_1330378998288.mp4",
                                Video.VideoType.MP4),
                        null),
                new VideoListItem("No ads - BBB (HLS)",
                        new Video("http://googleimadev-vh.akamaihd.net/i/big_buck_bunny/bbb-,480p,720p,1080p" +
                                ",.mov.csmil/master.m3u8",
                                Video.VideoType.HLS),
                        null),
                new VideoListItem("Ad rules - Apple test (HLS)",
                        new Video("https://devimages.apple.com.edgekey.net/streaming/examples/bipbop_4x3/" +
                                "bipbop_4x3_variant.m3u8 ",
                                Video.VideoType.HLS),
                        "https://pubads.g.doubleclick.net/gampad/ads?sz=640x480&iu=/124319096/external/" +
                                "ad_rule_samples&ciu_szs=300x250&ad_rule=1&impl=s&gdfp_req=1&env=vp" +
                                "&output=vast&unviewed_position_start=1&cust_params=deployment%3Ddevsite" +
                                "%26sample_ar%3Dpremidpostpod&cmsid=496&vid=short_onecue&correlator="),
        };
    }

    public String getValue(){
        JApplication app= JFactory.getApplication();
        BootstrapEditText output_box = (BootstrapEditText) app.activity.findViewById(this.key_id);
        return output_box.getText().toString();
    }
    public static class VideoListItem {

        /**
         * The title of the video.
         */
        public final String title;

        /**
         * The actual content video (contains its URL, media type - either DASH or mp4,
         * and an optional media type).
         */
        public final Video video;

        /**
         * The URL of the VAST document which represents the ad.
         */
        public final String adUrl;

        /**
         * @param title The title of the video.
         * @param video The actual content video (contains its URL, media type - either DASH or mp4,
         *                  and an optional media type).
         * @param adUrl The URL of the VAST document which represents the ad.
         */
        public VideoListItem(String title, Video video, String adUrl) {
            this.title = title;
            this.video = video;
            this.adUrl = adUrl;
        }
    }


}
