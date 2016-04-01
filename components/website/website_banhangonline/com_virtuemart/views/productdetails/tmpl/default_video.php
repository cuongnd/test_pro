<video controls="controls" height="500px" width="600px"  poster="<?php echo $this->product->data_preview_url ?>" preload="none" aria-describedby="full-descript">
    <source type="video/mp4" src="<?php echo $this->product->data_video_file_url ?>" />

    <track src="subs/TOS-arabic.srt" kind="subtitles" srclang="ar" label="Arabic" />
    <track src="subs/TOS-japanese.srt" kind="subtitles" srclang="jp" label="Japanese" />
    <track src="subs/TOS-english.srt" kind="subtitles" srclang="en" label="English" />
    <track src="subs/TOS-turkish.srt" kind="subtitles" srclang="tr" label="Turkish" />
    <track src="subs/TOS-ukrainian.srt" kind="subtitles" srclang="uk" label="Ukrainian" />

    You can download Tears of Steel at <a href="http://mango.blender.org/">mango.blender.org</a>.
</video>
