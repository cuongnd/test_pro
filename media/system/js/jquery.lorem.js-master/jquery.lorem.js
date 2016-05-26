/*!
 * jquery.lorem.js 0.0.3 - https://github.com/yckart/jquery.lorem.js
 * Lorem your ipsum dolor.
 *
 * Copyright (c) 2012 Yannick Albert (http://yckart.com)
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php).
 * 2013/02/18
 **/
;(function ($, document) {
    'use strict';
    var defaults = {
        image: {
            path: 'http://lorempixel.com/${g}/${w}/${h}/${c}',
            width: 240,
            height: 160
        },
        words: 30,
        data: ["lorem", "ipsum", "dolor", "sit", "amet,", "consectetur", "adipiscing", "elit", "ut", "aliquam,", "purus", "sit", "amet", "luctus", "venenatis,", "lectus", "magna", "fringilla", "urna,", "porttitor", "rhoncus", "dolor", "purus", "non", "enim", "praesent", "elementum", "facilisis", "leo,", "vel", "fringilla", "est", "ullamcorper", "eget", "nulla", "facilisi", "etiam", "dignissim", "diam", "quis", "enim", "lobortis", "scelerisque", "fermentum", "dui", "faucibus", "in", "ornare", "quam", "viverra", "orci", "sagittis", "eu", "volutpat", "odio", "facilisis", "mauris", "sit", "amet", "massa", "vitae", "tortor", "condimentum", "lacinia", "quis", "vel", "eros", "donec", "ac", "odio", "tempor", "orci", "dapibus", "ultrices", "in", "iaculis", "nunc", "sed", "augue", "lacus,", "viverra", "vitae", "congue", "eu,", "consequat", "ac", "felis", "donec", "et", "odio", "pellentesque", "diam", "volutpat", "commodo", "sed", "egestas", "egestas", "fringilla", "phasellus", "faucibus", "scelerisque", "eleifend", "donec", "pretium", "vulputate", "sapien", "nec", "sagittis", "aliquam", "malesuada", "bibendum", "arcu", "vitae", "elementum"]
    };

    Array.prototype.randomImage = function (last) {
        if (last) this.push(last);
        last = this.splice(Math.floor(Math.random() * this.length), 1);
        return last[0] === 'g' ? this.randomImage() : last;
    };

    function Plugin(elem, options) {
        var data = elem.getAttribute('data-lorem');

        if (elem.nodeName.toLowerCase() === 'img') {
            if (elem.getAttribute('src')) return;
            var c = data.replace(/,*/g, '').split(' '),
                w = elem.width || (elem.width = options.image.width),
                h = elem.height || (elem.height = options.image.height),
                text = elem.title || elem.alt || w + ' x ' + h;

            elem.src = options.image.path.replace('${g}/', ($.inArray('g', c) !== -1 ? 'g/' : ''))
                .replace('${w}', w).replace('${h}', h)
                .replace('${c}', c.randomImage())
                .replace('${t}', text);
        } else {

            var splits = data.split('-'),
                node = splits[0].slice(1) || 'noop',
                count = node !=='noop' ? splits[0].charAt(0) : 1;

            while (count--) {
                var content = '',
                    len = splits[1] || Number(splits[0]) || options.words,
                    clone = document.createElement(node);

                while (len--) content += options.data[Math.round(Math.random() * options.data.length)] + ' ';
                content = (content.charAt(0).toUpperCase() + content.slice(1)).slice(0, -1) + '.';

                clone.innerHTML = content;
                clone.className += splits[2] || '';

                elem.appendChild(node ? clone : document.createTextNode(content));
            }
        }
    }

    var cfg = $.fn.lorem = function (options) {
        options = $.extend({}, $.fn.lorem.options, options);
        return this.each(function () {
            new Plugin(this, options);
        });
    };

    cfg.options = defaults;
}(jQuery, document));