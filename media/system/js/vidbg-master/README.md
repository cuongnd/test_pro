# Vidbg.js

HTML5 jQuery Video Background plugin.

# Working With Wordpress

If you plan on using Vidbg.js with Wordpress, make your life a little bit easier and download the plugin I made on the [Wordpress Repo](https://wordpress.org/plugins/video-background/).

# Compatibility

* All modern web browsers
* IE9+
* Video will play on desktops and laptops and will resort to the fallback image (poster) on mobile devices (tablets, phones, etc.)

# Instructions

To get started, [download the script](https://github.com/blakedotvegas/supreme_theme/archive/master.zip). Once you have downloaded the script, include it in your project:

`<script src="vidbg.min.js"></script>`

## Initializing the Script

There is two ways to initialize the script; from HTML, and from javascript. Both methods will be shown below.

### Initialized from HTML

```html
<div class="vidbg-box" style="width: 650px; height: 338px;"
  data-vidbg-bg="mp4: http://example.com/video.mp4, webm: path/to/video.webm, poster: path/to/poster.jpg"
  data-vidbg-options="loop: true, muted: true, overlay: true, overlayColor: #000, overlayAlpha: 0.3">
</div>
```

### Initialized from Javascript

```js
$('.vidbg-box').vidbg({
  'mp4': 'http://example.com/video.mp4',
  'webm': 'path/to/video.webm',
  'poster': 'path/to/fallback-image.png',
}, {
  // options
});
```

It is important to note that supplying both `.webm` and `.mp4` will highly increase browser compatibility and it is highly recommended.

## Options

```js
{
  volume: 1,
  playbackRate: 1,
  muted: true,
  loop: true,
  position: '50% 50%',
  resizing: true,
  overlay: false,
  overlayColor: '#000',
  overlayAlpha: '0.3',
}
```

## Resizing

When resizing is set to true (default) the video will resize automatically when the window is expanded or compressed.

## Overlay

Setting `overlay` to `true` will add an RGBA background over your video. This is helpful when your video is primarily white and you have white text on top. `overlay` is set to `false` by default. If set to true, `overlayColor` and `overlayAlpha` are used. `overlayColor` is the color of the overlay in HEX and `overlayAlpha` is the opacity of the overlay specified between `0.0-1`.

# License

Vidbg.js is licensed under The MIT License. You can view it [here](https://github.com/blakedotvegas/vidbg/blob/master/LICENSE).
