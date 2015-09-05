+function (a) {
    function b(b) {
        function e(b) {
            x || (x = !0, r = b.getCanvas(), s = a(r).parent(), t = b.getOptions(), b.setData(f(b.getData())))
        }

        function f(b) {
            for (var c = 0, d = 0, e = 0, f = t.series.pie.combine.color, g = [], h = 0; h < b.length; ++h) {
                var i = b[h].data;
                a.isArray(i) && 1 == i.length && (i = i[0]), a.isArray(i) ? i[1] = !isNaN(parseFloat(i[1])) && isFinite(i[1]) ? +i[1] : 0 : i = !isNaN(parseFloat(i)) && isFinite(i) ? [1, +i] : [1, 0], b[h].data = [i]
            }
            for (var h = 0; h < b.length; ++h)c += b[h].data[0][1];
            for (var h = 0; h < b.length; ++h) {
                var i = b[h].data[0][1];
                i / c <= t.series.pie.combine.threshold && (d += i, e++, f || (f = b[h].color))
            }
            for (var h = 0; h < b.length; ++h) {
                var i = b[h].data[0][1];
                (2 > e || i / c > t.series.pie.combine.threshold) && g.push({
                    data: [[1, i]],
                    color: b[h].color,
                    label: b[h].label,
                    angle: i * Math.PI * 2 / c,
                    percent: i / (c / 100)
                })
            }
            return e > 1 && g.push({
                data: [[1, d]],
                color: f,
                label: t.series.pie.combine.label,
                angle: d * Math.PI * 2 / c,
                percent: d / (c / 100)
            }), g
        }

        function g(b, e) {
            function f() {
                y.clearRect(0, 0, j, k), s.children().filter(".pieLabel, .pieLabelBackground").remove()
            }

            function g() {
                var a = t.series.pie.shadow.left, b = t.series.pie.shadow.top, c = 10, d = t.series.pie.shadow.alpha, e = t.series.pie.radius > 1 ? t.series.pie.radius : u * t.series.pie.radius;
                if (!(e >= j / 2 - a || e * t.series.pie.tilt >= k / 2 - b || c >= e)) {
                    y.save(), y.translate(a, b), y.globalAlpha = d, y.fillStyle = "#000", y.translate(v, w), y.scale(1, t.series.pie.tilt);
                    for (var f = 1; c >= f; f++)y.beginPath(), y.arc(0, 0, e, 0, 2 * Math.PI, !1), y.fill(), e -= f;
                    y.restore()
                }
            }

            function i() {
                function b(a, b, c) {
                    0 >= a || isNaN(a) || (c ? y.fillStyle = b : (y.strokeStyle = b, y.lineJoin = "round"), y.beginPath(), Math.abs(a - 2 * Math.PI) > 1e-9 && y.moveTo(0, 0), y.arc(0, 0, e, f, f + a / 2, !1), y.arc(0, 0, e, f + a / 2, f + a, !1), y.closePath(), f += a, c ? y.fill() : y.stroke())
                }

                function c() {
                    function b(b, c, d) {
                        if (0 == b.data[0][1])return !0;
                        var f, g = t.legend.labelFormatter, h = t.series.pie.label.formatter;
                        f = g ? g(b.label, b) : b.label, h && (f = h(f, b));
                        var i = (c + b.angle + c) / 2, l = v + Math.round(Math.cos(i) * e), m = w + Math.round(Math.sin(i) * e) * t.series.pie.tilt, n = "<span class='pieLabel' id='pieLabel" + d + "' style='position:absolute;top:" + m + "px;left:" + l + "px;'>" + f + "</span>";
                        s.append(n);
                        var o = s.children("#pieLabel" + d), p = m - o.height() / 2, q = l - o.width() / 2;
                        if (o.css("top", p), o.css("left", q), 0 - p > 0 || 0 - q > 0 || k - (p + o.height()) < 0 || j - (q + o.width()) < 0)return !1;
                        if (0 != t.series.pie.label.background.opacity) {
                            var r = t.series.pie.label.background.color;
                            null == r && (r = b.color);
                            var u = "top:" + p + "px;left:" + q + "px;";
                            a("<div class='pieLabelBackground' style='position:absolute;width:" + o.width() + "px;height:" + o.height() + "px;" + u + "background-color:" + r + ";'></div>").css("opacity", t.series.pie.label.background.opacity).insertBefore(o)
                        }
                        return !0
                    }

                    for (var c = d, e = t.series.pie.label.radius > 1 ? t.series.pie.label.radius : u * t.series.pie.label.radius, f = 0; f < m.length; ++f) {
                        if (m[f].percent >= 100 * t.series.pie.label.threshold && !b(m[f], c, f))return !1;
                        c += m[f].angle
                    }
                    return !0
                }

                var d = Math.PI * t.series.pie.startAngle, e = t.series.pie.radius > 1 ? t.series.pie.radius : u * t.series.pie.radius;
                y.save(), y.translate(v, w), y.scale(1, t.series.pie.tilt), y.save();
                for (var f = d, g = 0; g < m.length; ++g)m[g].startAngle = f, b(m[g].angle, m[g].color, !0);
                if (y.restore(), t.series.pie.stroke.width > 0) {
                    y.save(), y.lineWidth = t.series.pie.stroke.width, f = d;
                    for (var g = 0; g < m.length; ++g)b(m[g].angle, t.series.pie.stroke.color, !1);
                    y.restore()
                }
                return h(y), y.restore(), t.series.pie.label.show ? c() : !0
            }

            if (s) {
                var j = b.getPlaceholder().width(), k = b.getPlaceholder().height(), l = s.children().filter(".legend").children().width() || 0;
                y = e, x = !1, u = Math.min(j, k / t.series.pie.tilt) / 2, w = k / 2 + t.series.pie.offset.top, v = j / 2, "auto" == t.series.pie.offset.left ? (t.legend.position.match("w") ? v += l / 2 : v -= l / 2, u > v ? v = u : v > j - u && (v = j - u)) : v += t.series.pie.offset.left;
                var m = b.getData(), n = 0;
                do n > 0 && (u *= d), n += 1, f(), t.series.pie.tilt <= .8 && g(); while (!i() && c > n);
                n >= c && (f(), s.prepend("<div class='error'>Could not draw pie with labels contained inside canvas</div>")), b.setSeries && b.insertLegend && (b.setSeries(m), b.insertLegend())
            }
        }

        function h(a) {
            if (t.series.pie.innerRadius > 0) {
                a.save();
                var b = t.series.pie.innerRadius > 1 ? t.series.pie.innerRadius : u * t.series.pie.innerRadius;
                a.globalCompositeOperation = "destination-out", a.beginPath(), a.fillStyle = t.series.pie.stroke.color, a.arc(0, 0, b, 0, 2 * Math.PI, !1), a.fill(), a.closePath(), a.restore(), a.save(), a.beginPath(), a.strokeStyle = t.series.pie.stroke.color, a.arc(0, 0, b, 0, 2 * Math.PI, !1), a.stroke(), a.closePath(), a.restore()
            }
        }

        function i(a, b) {
            for (var c = !1, d = -1, e = a.length, f = e - 1; ++d < e; f = d)(a[d][1] <= b[1] && b[1] < a[f][1] || a[f][1] <= b[1] && b[1] < a[d][1]) && b[0] < (a[f][0] - a[d][0]) * (b[1] - a[d][1]) / (a[f][1] - a[d][1]) + a[d][0] && (c = !c);
            return c
        }

        function j(a, c) {
            for (var d, e, f = b.getData(), g = b.getOptions(), h = g.series.pie.radius > 1 ? g.series.pie.radius : u * g.series.pie.radius, j = 0; j < f.length; ++j) {
                var k = f[j];
                if (k.pie.show) {
                    if (y.save(), y.beginPath(), y.moveTo(0, 0), y.arc(0, 0, h, k.startAngle, k.startAngle + k.angle / 2, !1), y.arc(0, 0, h, k.startAngle + k.angle / 2, k.startAngle + k.angle, !1), y.closePath(), d = a - v, e = c - w, y.isPointInPath) {
                        if (y.isPointInPath(a - v, c - w))return y.restore(), {
                            datapoint: [k.percent, k.data],
                            dataIndex: 0,
                            series: k,
                            seriesIndex: j
                        }
                    } else {
                        var l = h * Math.cos(k.startAngle), m = h * Math.sin(k.startAngle), n = h * Math.cos(k.startAngle + k.angle / 4), o = h * Math.sin(k.startAngle + k.angle / 4), p = h * Math.cos(k.startAngle + k.angle / 2), q = h * Math.sin(k.startAngle + k.angle / 2), r = h * Math.cos(k.startAngle + k.angle / 1.5), s = h * Math.sin(k.startAngle + k.angle / 1.5), t = h * Math.cos(k.startAngle + k.angle), x = h * Math.sin(k.startAngle + k.angle), z = [[0, 0], [l, m], [n, o], [p, q], [r, s], [t, x]], A = [d, e];
                        if (i(z, A))return y.restore(), {
                            datapoint: [k.percent, k.data],
                            dataIndex: 0,
                            series: k,
                            seriesIndex: j
                        }
                    }
                    y.restore()
                }
            }
            return null
        }

        function k(a) {
            m("plothover", a)
        }

        function l(a) {
            m("plotclick", a)
        }

        function m(a, c) {
            var d = b.offset(), e = parseInt(c.pageX - d.left), f = parseInt(c.pageY - d.top), g = j(e, f);
            if (t.grid.autoHighlight)for (var h = 0; h < z.length; ++h) {
                var i = z[h];
                i.auto != a || g && i.series == g.series || o(i.series)
            }
            g && n(g.series, a);
            var k = {pageX: c.pageX, pageY: c.pageY};
            s.trigger(a, [k, g])
        }

        function n(a, c) {
            var d = p(a);
            -1 == d ? (z.push({series: a, auto: c}), b.triggerRedrawOverlay()) : c || (z[d].auto = !1)
        }

        function o(a) {
            null == a && (z = [], b.triggerRedrawOverlay());
            var c = p(a);
            -1 != c && (z.splice(c, 1), b.triggerRedrawOverlay())
        }

        function p(a) {
            for (var b = 0; b < z.length; ++b) {
                var c = z[b];
                if (c.series == a)return b
            }
            return -1
        }

        function q(a, b) {
            function c(a) {
                a.angle <= 0 || isNaN(a.angle) || (b.fillStyle = "rgba(255, 255, 255, " + d.series.pie.highlight.opacity + ")", b.beginPath(), Math.abs(a.angle - 2 * Math.PI) > 1e-9 && b.moveTo(0, 0), b.arc(0, 0, e, a.startAngle, a.startAngle + a.angle / 2, !1), b.arc(0, 0, e, a.startAngle + a.angle / 2, a.startAngle + a.angle, !1), b.closePath(), b.fill())
            }

            var d = a.getOptions(), e = d.series.pie.radius > 1 ? d.series.pie.radius : u * d.series.pie.radius;
            b.save(), b.translate(v, w), b.scale(1, d.series.pie.tilt);
            for (var f = 0; f < z.length; ++f)c(z[f].series);
            h(b), b.restore()
        }

        var r = null, s = null, t = null, u = null, v = null, w = null, x = !1, y = null, z = [];
        b.hooks.processOptions.push(function (a, b) {
            b.series.pie.show && (b.grid.show = !1, "auto" == b.series.pie.label.show && (b.series.pie.label.show = b.legend.show ? !1 : !0), "auto" == b.series.pie.radius && (b.series.pie.radius = b.series.pie.label.show ? .75 : 1), b.series.pie.tilt > 1 ? b.series.pie.tilt = 1 : b.series.pie.tilt < 0 && (b.series.pie.tilt = 0))
        }), b.hooks.bindEvents.push(function (a, b) {
            var c = a.getOptions();
            c.series.pie.show && (c.grid.hoverable && b.unbind("mousemove").mousemove(k), c.grid.clickable && b.unbind("click").click(l))
        }), b.hooks.processDatapoints.push(function (a, b, c, d) {
            var f = a.getOptions();
            f.series.pie.show && e(a, b, c, d)
        }), b.hooks.drawOverlay.push(function (a, b) {
            var c = a.getOptions();
            c.series.pie.show && q(a, b)
        }), b.hooks.draw.push(function (a, b) {
            var c = a.getOptions();
            c.series.pie.show && g(a, b)
        })
    }

    var c = 10, d = .95, e = {
        series: {
            pie: {
                show: !1,
                radius: "auto",
                innerRadius: 0,
                startAngle: 1.5,
                tilt: 1,
                shadow: {left: 5, top: 15, alpha: .02},
                offset: {top: 0, left: "auto"},
                stroke: {color: "#fff", width: 1},
                label: {
                    show: "auto", formatter: function (a, b) {
                        return "<div style='font-size:x-small;text-align:center;padding:2px;color:" + b.color + ";'>" + a + "<br/>" + Math.round(b.percent) + "%</div>"
                    }, radius: 1, background: {color: null, opacity: 0}, threshold: 0
                },
                combine: {threshold: -1, color: null, label: "Other"},
                highlight: {opacity: .5}
            }
        }
    };
    a.plot.plugins.push({init: b, options: e, name: "pie", version: "1.1"})
}(jQuery), function (a, b, c) {
    function d() {
        for (var c = f.length - 1; c >= 0; c--) {
            var h = a(f[c]);
            if (h[0] == b || h.is(":visible")) {
                var n = h.width(), o = h.height(), p = h.data(j);
                !p || n === p.w && o === p.h ? g[k] = g[l] : (g[k] = g[m], h.trigger(i, [p.w = n, p.h = o]))
            } else p = h.data(j), p.w = 0, p.h = 0
        }
        null !== e && (e = b.requestAnimationFrame(d))
    }

    var e, f = [], g = a.resize = a.extend(a.resize, {}), h = "setTimeout", i = "resize", j = i + "-special-event", k = "delay", l = "pendingDelay", m = "activeDelay", n = "throttleWindow";
    g[l] = 250, g[m] = 20, g[k] = g[l], g[n] = !0, a.event.special[i] = {
        setup: function () {
            if (!g[n] && this[h])return !1;
            var b = a(this);
            f.push(this), b.data(j, {w: b.width(), h: b.height()}), 1 === f.length && (e = c, d())
        }, teardown: function () {
            if (!g[n] && this[h])return !1;
            for (var b = a(this), c = f.length - 1; c >= 0; c--)if (f[c] == this) {
                f.splice(c, 1);
                break
            }
            b.removeData(j), f.length || (cancelAnimationFrame(e), e = null)
        }, add: function (b) {
            function d(b, d, f) {
                var g = a(this), h = g.data(j);
                h.w = d !== c ? d : g.width(), h.h = f !== c ? f : g.height(), e.apply(this, arguments)
            }

            if (!g[n] && this[h])return !1;
            var e;
            return a.isFunction(b) ? (e = b, d) : (e = b.handler, void(b.handler = d))
        }
    }, b.requestAnimationFrame || (b.requestAnimationFrame = function () {
        return b.webkitRequestAnimationFrame || b.mozRequestAnimationFrame || b.oRequestAnimationFrame || b.msRequestAnimationFrame || function (a) {
                return b.setTimeout(a, g[k])
            }
    }()), b.cancelAnimationFrame || (b.cancelAnimationFrame = function () {
        return b.webkitCancelRequestAnimationFrame || b.mozCancelRequestAnimationFrame || b.oCancelRequestAnimationFrame || b.msCancelRequestAnimationFrame || clearTimeout
    }())
}(jQuery, this), function (a) {
    function b(a) {
        function b() {
            var b = a.getPlaceholder();
            0 != b.width() && 0 != b.height() && (a.resize(), a.setupGrid(), a.draw())
        }

        function c(a) {
            a.getPlaceholder().resize(b)
        }

        function d(a) {
            a.getPlaceholder().unbind("resize", b)
        }

        a.hooks.bindEvents.push(c), a.hooks.shutdown.push(d)
    }

    var c = {};
    a.plot.plugins.push({init: b, options: c, name: "resize", version: "1.0"})
}(jQuery), function (a) {
    function b(a, b) {
        return b * Math.floor(a / b)
    }

    function c(a, b, c, d) {
        if ("function" == typeof a.strftime)return a.strftime(b);
        var e = function (a, b) {
            return a = "" + a, b = "" + (null == b ? "0" : b), 1 == a.length ? b + a : a
        }, f = [], g = !1, h = a.getHours(), i = 12 > h;
        null == c && (c = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]), null == d && (d = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]);
        var j;
        j = h > 12 ? h - 12 : 0 == h ? 12 : h;
        for (var k = 0; k < b.length; ++k) {
            var l = b.charAt(k);
            if (g) {
                switch (l) {
                    case"a":
                        l = "" + d[a.getDay()];
                        break;
                    case"b":
                        l = "" + c[a.getMonth()];
                        break;
                    case"d":
                        l = e(a.getDate());
                        break;
                    case"e":
                        l = e(a.getDate(), " ");
                        break;
                    case"h":
                    case"H":
                        l = e(h);
                        break;
                    case"I":
                        l = e(j);
                        break;
                    case"l":
                        l = e(j, " ");
                        break;
                    case"m":
                        l = e(a.getMonth() + 1);
                        break;
                    case"M":
                        l = e(a.getMinutes());
                        break;
                    case"q":
                        l = "" + (Math.floor(a.getMonth() / 3) + 1);
                        break;
                    case"S":
                        l = e(a.getSeconds());
                        break;
                    case"y":
                        l = e(a.getFullYear() % 100);
                        break;
                    case"Y":
                        l = "" + a.getFullYear();
                        break;
                    case"p":
                        l = i ? "am" : "pm";
                        break;
                    case"P":
                        l = i ? "AM" : "PM";
                        break;
                    case"w":
                        l = "" + a.getDay()
                }
                f.push(l), g = !1
            } else"%" == l ? g = !0 : f.push(l)
        }
        return f.join("")
    }

    function d(a) {
        function b(a, b, c, d) {
            a[b] = function () {
                return c[d].apply(c, arguments)
            }
        }

        var c = {date: a};
        void 0 != a.strftime && b(c, "strftime", a, "strftime"), b(c, "getTime", a, "getTime"), b(c, "setTime", a, "setTime");
        for (var d = ["Date", "Day", "FullYear", "Hours", "Milliseconds", "Minutes", "Month", "Seconds"], e = 0; e < d.length; e++)b(c, "get" + d[e], a, "getUTC" + d[e]), b(c, "set" + d[e], a, "setUTC" + d[e]);
        return c
    }

    function e(a, b) {
        if ("browser" == b.timezone)return new Date(a);
        if (b.timezone && "utc" != b.timezone) {
            if ("undefined" != typeof timezoneJS && "undefined" != typeof timezoneJS.Date) {
                var c = new timezoneJS.Date;
                return c.setTimezone(b.timezone), c.setTime(a), c
            }
            return d(new Date(a))
        }
        return d(new Date(a))
    }

    function f(d) {
        d.hooks.processOptions.push(function (d) {
            a.each(d.getAxes(), function (a, d) {
                var f = d.options;
                "time" == f.mode && (d.tickGenerator = function (a) {
                    var c = [], d = e(a.min, f), g = 0, i = f.tickSize && "quarter" === f.tickSize[1] || f.minTickSize && "quarter" === f.minTickSize[1] ? k : j;
                    null != f.minTickSize && (g = "number" == typeof f.tickSize ? f.tickSize : f.minTickSize[0] * h[f.minTickSize[1]]);
                    for (var l = 0; l < i.length - 1 && !(a.delta < (i[l][0] * h[i[l][1]] + i[l + 1][0] * h[i[l + 1][1]]) / 2 && i[l][0] * h[i[l][1]] >= g); ++l);
                    var m = i[l][0], n = i[l][1];
                    if ("year" == n) {
                        if (null != f.minTickSize && "year" == f.minTickSize[1])m = Math.floor(f.minTickSize[0]); else {
                            var o = Math.pow(10, Math.floor(Math.log(a.delta / h.year) / Math.LN10)), p = a.delta / h.year / o;
                            m = 1.5 > p ? 1 : 3 > p ? 2 : 7.5 > p ? 5 : 10, m *= o
                        }
                        1 > m && (m = 1)
                    }
                    a.tickSize = f.tickSize || [m, n];
                    var q = a.tickSize[0];
                    n = a.tickSize[1];
                    var r = q * h[n];
                    "second" == n ? d.setSeconds(b(d.getSeconds(), q)) : "minute" == n ? d.setMinutes(b(d.getMinutes(), q)) : "hour" == n ? d.setHours(b(d.getHours(), q)) : "month" == n ? d.setMonth(b(d.getMonth(), q)) : "quarter" == n ? d.setMonth(3 * b(d.getMonth() / 3, q)) : "year" == n && d.setFullYear(b(d.getFullYear(), q)), d.setMilliseconds(0), r >= h.minute && d.setSeconds(0), r >= h.hour && d.setMinutes(0), r >= h.day && d.setHours(0), r >= 4 * h.day && d.setDate(1), r >= 2 * h.month && d.setMonth(b(d.getMonth(), 3)), r >= 2 * h.quarter && d.setMonth(b(d.getMonth(), 6)), r >= h.year && d.setMonth(0);
                    var s, t = 0, u = Number.NaN;
                    do if (s = u, u = d.getTime(), c.push(u), "month" == n || "quarter" == n)if (1 > q) {
                        d.setDate(1);
                        var v = d.getTime();
                        d.setMonth(d.getMonth() + ("quarter" == n ? 3 : 1));
                        var w = d.getTime();
                        d.setTime(u + t * h.hour + (w - v) * q), t = d.getHours(), d.setHours(0)
                    } else d.setMonth(d.getMonth() + q * ("quarter" == n ? 3 : 1)); else"year" == n ? d.setFullYear(d.getFullYear() + q) : d.setTime(u + r); while (u < a.max && u != s);
                    return c
                }, d.tickFormatter = function (a, b) {
                    var d = e(a, b.options);
                    if (null != f.timeformat)return c(d, f.timeformat, f.monthNames, f.dayNames);
                    var g, i = b.options.tickSize && "quarter" == b.options.tickSize[1] || b.options.minTickSize && "quarter" == b.options.minTickSize[1], j = b.tickSize[0] * h[b.tickSize[1]], k = b.max - b.min, l = f.twelveHourClock ? " %p" : "", m = f.twelveHourClock ? "%I" : "%H";
                    g = j < h.minute ? m + ":%M:%S" + l : j < h.day ? k < 2 * h.day ? m + ":%M" + l : "%b %d " + m + ":%M" + l : j < h.month ? "%b %d" : i && j < h.quarter || !i && j < h.year ? k < h.year ? "%b" : "%b %Y" : i && j < h.year ? k < h.year ? "Q%q" : "Q%q %Y" : "%Y";
                    var n = c(d, g, f.monthNames, f.dayNames);
                    return n
                })
            })
        })
    }

    var g = {xaxis: {timezone: null, timeformat: null, twelveHourClock: !1, monthNames: null}}, h = {
        second: 1e3,
        minute: 6e4,
        hour: 36e5,
        day: 864e5,
        month: 2592e6,
        quarter: 7776e6,
        year: 525949.2 * 60 * 1e3
    }, i = [[1, "second"], [2, "second"], [5, "second"], [10, "second"], [30, "second"], [1, "minute"], [2, "minute"], [5, "minute"], [10, "minute"], [30, "minute"], [1, "hour"], [2, "hour"], [4, "hour"], [8, "hour"], [12, "hour"], [1, "day"], [2, "day"], [3, "day"], [.25, "month"], [.5, "month"], [1, "month"], [2, "month"]], j = i.concat([[3, "month"], [6, "month"], [1, "year"]]), k = i.concat([[1, "quarter"], [2, "quarter"], [1, "year"]]);
    a.plot.plugins.push({init: f, options: g, name: "time", version: "1.0"}), a.plot.formatDate = c
}(jQuery), function (a) {
    "use strict";
    function b(b) {
        function d(b) {
            y = b.getOptions();
            var c = y.series.grow.valueIndex;
            if (y.series.grow.active === !0) {
                var d = !1, e = 0;
                if (y.series.grow.reanimate && u === j.PLOTTED_LAST_FRAME) {
                    q = !1, u = j.NOT_PLOTTED_YET, s = 0, x = b.getData();
                    var f = Math.min(x.length, v.length);
                    for (e = 0; f > e; e++)x[e].dataOld = v[e];
                    d = !0, r = !0
                }
                if (!q) {
                    for (d || (x = b.getData()), u = j.NOT_PLOTTED_YET, s = 0 | +new Date, v = [], e = 0; e < x.length; e++) {
                        var g = x[e];
                        if (g.dataOrg = a.extend(!0, [], g.data), v.push(g.dataOrg), !d)for (var h = 0; h < g.data.length; h++)g.data[h][c] = null === g.dataOrg[h][c] ? null : 0
                    }
                    b.setData(x), q = !0
                }
            }
        }

        function g(a) {
            r === !0 && h(a)
        }

        function h(a) {
            y = a.getOptions(), y.series.grow.active === !0 && (i(a.getData(), y), s = 0 | +new Date, p = e(m)), r = !1
        }

        function i(a, b) {
            for (var c = b.series.grow.duration, d = 0, e = a.length; e > d; d++) {
                var f = a[d].grow.duration;
                f > c && (c = f)
            }
            b.series.grow.duration = c
        }

        function l(a) {
            c("resize") && a.getPlaceholder().resize(n)
        }

        function m() {
            t = +new Date - s | 0;
            for (var a = 0, b = x.length; b > a; a++)for (var c = x[a], d = c.dataOld && c.dataOld.length > 0, f = 0, g = c.grow.growings.length; g > f; f++) {
                var h, i = c.grow.growings[f];
                d && "reinit" !== i.reanimate ? ("function" == typeof i.reanimate && (h = i.reanimate), h = "continue" === i.reanimate ? k.reanimate : k.none) : h = "function" == typeof i.stepMode ? i.stepMode : k[i.stepMode] || k.none, h(c, t, i, u)
            }
            w.setData(x), w.draw(), u === j.NOT_PLOTTED_YET && (u = j.PLOTTED_SOME_FRAMES), t < y.series.grow.duration ? p = e(m) : (u = j.PLOTTED_LAST_FRAME, p = null, w.getPlaceholder().trigger("growFinished"))
        }

        function n() {
            if (p) {
                for (var c = 0; c < x.length; c++) {
                    var d = x[c];
                    d.data = a.extend(!0, [], d.dataOrg)
                }
                b.setData(x), b.setupGrid()
            }
        }

        function o(a) {
            a.getPlaceholder().unbind("resize", n), p && (f(p), p = null)
        }

        var p, q = !1, r = !0, s = 0, t = 0, u = j.NOT_PLOTTED_YET, v = [], w = b, x = null, y = null;
        b.hooks.drawSeries.push(d), b.hooks.draw.push(g), b.hooks.bindEvents.push(l), b.hooks.shutdown.push(o)
    }

    function c(b) {
        for (var c = a.plot.plugins, d = 0, e = c.length; e > d; d++) {
            var f = c[d];
            if (f.name === b)return !0
        }
        return !1
    }

    function d() {
        for (var a = window.requestAnimationFrame, b = window.cancelAnimationFrame, c = +new Date, d = ["ms", "moz", "webkit", "o"], g = 0; g < d.length && !a; ++g)a = window[d[g] + "RequestAnimationFrame"], b = window[d[g] + "CancelAnimationFrame"] || window[d[g] + "CancelRequestAnimationFrame"];
        a || (a = function (a) {
            var b = +new Date, d = Math.max(0, 16 - (b - c)), e = window.setTimeout(function () {
                a(b + d)
            }, d);
            return c = b + d, e
        }), b || (b = function (a) {
            clearTimeout(a)
        }), e = a, f = b
    }

    var e, f, g = "growraf", h = "0.4.5", i = {
        series: {
            grow: {
                active: !1,
                duration: 1e3,
                valueIndex: 1,
                reanimate: !0,
                growings: [{valueIndex: 1, stepMode: "linear", stepDirection: "up", reanimate: "continue"}]
            }
        }
    }, j = {NOT_PLOTTED_YET: 0, PLOTTED_SOME_FRAMES: 1, PLOTTED_LAST_FRAME: 2}, k = {
        none: function (a, b, c, d) {
            if (d === j.NOT_PLOTTED_YET)for (var e = 0, f = a.data.length; f > e; e++)a.data[e][c.valueIndex] = a.dataOrg[e][c.valueIndex]
        }, linear: function (a, b, c) {
            for (var d = Math.min(b, a.grow.duration), e = 0, f = a.data.length; f > e; e++) {
                var g = a.dataOrg[e][c.valueIndex];
                null !== g ? "up" === c.stepDirection ? a.data[e][c.valueIndex] = g / a.grow.duration * d : "down" === c.stepDirection && (a.data[e][c.valueIndex] = g + (a.yaxis.max - g) / a.grow.duration * (a.grow.duration - d)) : a.data[e][c.valueIndex] = null
            }
        }, maximum: function (a, b, c) {
            for (var d = Math.min(b, a.grow.duration), e = 0, f = a.data.length; f > e; e++) {
                var g = a.dataOrg[e][c.valueIndex];
                null !== g ? "up" === c.stepDirection ? a.data[e][c.valueIndex] = g >= 0 ? Math.min(g, a.yaxis.max / a.grow.duration * d) : Math.max(g, a.yaxis.min / a.grow.duration * d) : "down" === c.stepDirection && (a.data[e][c.valueIndex] = g >= 0 ? Math.max(g, a.yaxis.max / a.grow.duration * (a.grow.duration - d)) : Math.min(g, a.yaxis.min / a.grow.duration * (a.grow.duration - d))) : a.data[e][c.valueIndex] = null
            }
        }, delay: function (a, b, c) {
            if (b >= a.grow.duration)for (var d = 0, e = a.data.length; e > d; d++)a.data[d][c.valueIndex] = a.dataOrg[d][c.valueIndex]
        }, reanimate: function (a, b, c) {
            for (var d = Math.min(b, a.grow.duration), e = 0, f = a.data.length; f > e; e++) {
                var g = a.dataOrg[e][c.valueIndex];
                if (null === g)a.data[e][c.valueIndex] = null; else if (a.dataOld) {
                    var h = a.dataOld[e][c.valueIndex];
                    a.data[e][c.valueIndex] = h + (g - h) / a.grow.duration * d
                }
            }
        }
    };
    d(), a.plot.plugins.push({init: b, options: i, name: g, version: h})
}(jQuery), function (a) {
    function b(a, b, c, d) {
        var e = "categories" == b.xaxis.options.mode, f = "categories" == b.yaxis.options.mode;
        if (e || f) {
            var g = d.format;
            if (!g) {
                var h = b;
                if (g = [], g.push({x: !0, number: !0, required: !0}), g.push({
                        y: !0,
                        number: !0,
                        required: !0
                    }), h.bars.show || h.lines.show && h.lines.fill) {
                    var i = !!(h.bars.show && h.bars.zero || h.lines.show && h.lines.zero);
                    g.push({
                        y: !0,
                        number: !0,
                        required: !1,
                        defaultValue: 0,
                        autoscale: i
                    }), h.bars.horizontal && (delete g[g.length - 1].y, g[g.length - 1].x = !0)
                }
                d.format = g
            }
            for (var j = 0; j < g.length; ++j)g[j].x && e && (g[j].number = !1), g[j].y && f && (g[j].number = !1)
        }
    }

    function c(a) {
        var b = -1;
        for (var c in a)a[c] > b && (b = a[c]);
        return b + 1
    }

    function d(a) {
        var b = [];
        for (var c in a.categories) {
            var d = a.categories[c];
            d >= a.min && d <= a.max && b.push([d, c])
        }
        return b.sort(function (a, b) {
            return a[0] - b[0]
        }), b
    }

    function e(b, c, e) {
        if ("categories" == b[c].options.mode) {
            if (!b[c].categories) {
                var g = {}, h = b[c].options.categories || {};
                if (a.isArray(h))for (var i = 0; i < h.length; ++i)g[h[i]] = i; else for (var j in h)g[j] = h[j];
                b[c].categories = g
            }
            b[c].options.ticks || (b[c].options.ticks = d), f(e, c, b[c].categories)
        }
    }

    function f(a, b, d) {
        for (var e = a.points, f = a.pointsize, g = a.format, h = b.charAt(0), i = c(d), j = 0; j < e.length; j += f)if (null != e[j])for (var k = 0; f > k; ++k) {
            var l = e[j + k];
            null != l && g[k][h] && (l in d || (d[l] = i, ++i), e[j + k] = d[l])
        }
    }

    function g(a, b, c) {
        e(b, "xaxis", c), e(b, "yaxis", c)
    }

    function h(a) {
        a.hooks.processRawData.push(b), a.hooks.processDatapoints.push(g)
    }

    var i = {xaxis: {categories: null}, yaxis: {categories: null}};
    a.plot.plugins.push({init: h, options: i, name: "categories", version: "1.0"})
}(jQuery), function (a) {
    function b(a) {
        function b(a, b) {
            for (var c = null, d = 0; d < b.length && a != b[d]; ++d)b[d].stack == a.stack && (c = b[d]);
            return c
        }

        function c(a, c, d) {
            if (null != c.stack && c.stack !== !1) {
                var e = b(c, a.getData());
                if (e) {
                    for (var f, g, h, i, j, k, l, m, n = d.pointsize, o = d.points, p = e.datapoints.pointsize, q = e.datapoints.points, r = [], s = c.lines.show, t = c.bars.horizontal, u = n > 2 && (t ? d.format[2].x : d.format[2].y), v = s && c.lines.steps, w = !0, x = t ? 1 : 0, y = t ? 0 : 1, z = 0, A = 0; ;) {
                        if (z >= o.length)break;
                        if (l = r.length, null == o[z]) {
                            for (m = 0; n > m; ++m)r.push(o[z + m]);
                            z += n
                        } else if (A >= q.length) {
                            if (!s)for (m = 0; n > m; ++m)r.push(o[z + m]);
                            z += n
                        } else if (null == q[A]) {
                            for (m = 0; n > m; ++m)r.push(null);
                            w = !0, A += p
                        } else {
                            if (f = o[z + x], g = o[z + y], i = q[A + x], j = q[A + y], k = 0, f == i) {
                                for (m = 0; n > m; ++m)r.push(o[z + m]);
                                r[l + y] += j, k = j, z += n, A += p
                            } else if (f > i) {
                                if (s && z > 0 && null != o[z - n]) {
                                    for (h = g + (o[z - n + y] - g) * (i - f) / (o[z - n + x] - f), r.push(i), r.push(h + j), m = 2; n > m; ++m)r.push(o[z + m]);
                                    k = j
                                }
                                A += p
                            } else {
                                if (w && s) {
                                    z += n;
                                    continue
                                }
                                for (m = 0; n > m; ++m)r.push(o[z + m]);
                                s && A > 0 && null != q[A - p] && (k = j + (q[A - p + y] - j) * (f - i) / (q[A - p + x] - i)), r[l + y] += k, z += n
                            }
                            w = !1, l != r.length && u && (r[l + 2] += k)
                        }
                        if (v && l != r.length && l > 0 && null != r[l] && r[l] != r[l - n] && r[l + 1] != r[l - n + 1]) {
                            for (m = 0; n > m; ++m)r[l + n + m] = r[l + m];
                            r[l + 1] = r[l - n + 1]
                        }
                    }
                    d.points = r
                }
            }
        }

        a.hooks.processDatapoints.push(c)
    }

    var c = {series: {stack: null}};
    a.plot.plugins.push({init: b, options: c, name: "stack", version: "1.2"})
}(jQuery), function (a) {
    var b = {
        tooltip: !1,
        tooltipOpts: {
            content: "%s | X: %x | Y: %y",
            xDateFormat: null,
            yDateFormat: null,
            shifts: {x: 10, y: 20},
            defaultTheme: !0,
            onHover: function () {
            }
        }
    }, c = function (a) {
        this.tipPosition = {x: 0, y: 0}, this.init(a)
    };
    c.prototype.init = function (b) {
        var c = this;
        b.hooks.bindEvents.push(function (b, d) {
            if (c.plotOptions = b.getOptions(), c.plotOptions.tooltip !== !1 && "undefined" != typeof c.plotOptions.tooltip) {
                c.tooltipOptions = c.plotOptions.tooltipOpts;
                var e = c.getDomElement();
                a(b.getPlaceholder()).bind("plothover", function (a, b, d) {
                    if (d) {
                        var f;
                        f = c.stringFormat(c.tooltipOptions.content, d), e.html(f).css({
                            left: c.tipPosition.x + c.tooltipOptions.shifts.x,
                            top: c.tipPosition.y + c.tooltipOptions.shifts.y
                        }).show(), "function" == typeof c.tooltipOptions.onHover && c.tooltipOptions.onHover(d, e)
                    } else e.hide().html("")
                }), d.mousemove(function (a) {
                    var b = {};
                    b.x = a.pageX, b.y = a.pageY, c.updateTooltipPosition(b)
                })
            }
        })
    }, c.prototype.getDomElement = function () {
        var b;
        return a("#flotTip").length > 0 ? b = a("#flotTip") : (b = a("<div />").attr("id", "flotTip"), b.appendTo("body").hide().css({position: "absolute"}), this.tooltipOptions.defaultTheme && b.css({
            background: "#fff",
            "z-index": "100",
            padding: "0.4em 0.6em",
            "border-radius": "0.5em",
            "font-size": "0.8em",
            border: "1px solid #111"
        })), b
    }, c.prototype.updateTooltipPosition = function (a) {
        this.tipPosition.x = a.x, this.tipPosition.y = a.y
    }, c.prototype.stringFormat = function (a, b) {
        var c = /%p\.{0,1}(\d{0,})/, d = /%s/, e = /%x\.{0,1}(\d{0,})/, f = /%y\.{0,1}(\d{0,})/;
        return "function" == typeof a && (a = a(b.series.data[b.dataIndex][0], b.series.data[b.dataIndex][1])), "undefined" != typeof b.series.percent && (a = this.adjustValPrecision(c, a, b.series.percent)), "undefined" != typeof b.series.label && (a = a.replace(d, b.series.label)), this.isTimeMode("xaxis", b) && this.isXDateFormat(b) && (a = a.replace(e, this.timestampToDate(b.series.data[b.dataIndex][0], this.tooltipOptions.xDateFormat))), this.isTimeMode("yaxis", b) && this.isYDateFormat(b) && (a = a.replace(f, this.timestampToDate(b.series.data[b.dataIndex][1], this.tooltipOptions.yDateFormat))), "number" == typeof b.series.data[b.dataIndex][0] && (a = this.adjustValPrecision(e, a, b.series.data[b.dataIndex][0])), "number" == typeof b.series.data[b.dataIndex][1] && (a = this.adjustValPrecision(f, a, b.series.data[b.dataIndex][1])), "undefined" != typeof b.series.xaxis.tickFormatter && (a = a.replace(e, b.series.xaxis.tickFormatter(b.series.data[b.dataIndex][0], b.series.xaxis))), "undefined" != typeof b.series.yaxis.tickFormatter && (a = a.replace(f, b.series.yaxis.tickFormatter(b.series.data[b.dataIndex][1], b.series.yaxis))), a
    }, c.prototype.isTimeMode = function (a, b) {
        return "undefined" != typeof b.series[a].options.mode && "time" === b.series[a].options.mode
    }, c.prototype.isXDateFormat = function () {
        return "undefined" != typeof this.tooltipOptions.xDateFormat && null !== this.tooltipOptions.xDateFormat
    }, c.prototype.isYDateFormat = function () {
        return "undefined" != typeof this.tooltipOptions.yDateFormat && null !== this.tooltipOptions.yDateFormat
    }, c.prototype.timestampToDate = function (b, c) {
        var d = new Date(b);
        return a.plot.formatDate(d, c)
    }, c.prototype.adjustValPrecision = function (a, b, c) {
        var d;
        return null !== b.match(a) && "" !== RegExp.$1 && (d = RegExp.$1, c = c.toFixed(d), b = b.replace(a, c)), b
    };
    var d = [], e = function (a) {
        d.push(new c(a))
    };
    a.plot.plugins.push({init: e, options: b, name: "tooltip", version: "0.6"})
}(jQuery), Date.CultureInfo = {
    name: "en-US",
    englishName: "English (United States)",
    nativeName: "English (United States)",
    dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    abbreviatedDayNames: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    shortestDayNames: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
    firstLetterDayNames: ["S", "M", "T", "W", "T", "F", "S"],
    monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
    abbreviatedMonthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    amDesignator: "AM",
    pmDesignator: "PM",
    firstDayOfWeek: 0,
    twoDigitYearMax: 2029,
    dateElementOrder: "mdy",
    formatPatterns: {
        shortDate: "M/d/yyyy",
        longDate: "dddd, MMMM dd, yyyy",
        shortTime: "h:mm tt",
        longTime: "h:mm:ss tt",
        fullDateTime: "dddd, MMMM dd, yyyy h:mm:ss tt",
        sortableDateTime: "yyyy-MM-ddTHH:mm:ss",
        universalSortableDateTime: "yyyy-MM-dd HH:mm:ssZ",
        rfc1123: "ddd, dd MMM yyyy HH:mm:ss GMT",
        monthDay: "MMMM dd",
        yearMonth: "MMMM, yyyy"
    },
    regexPatterns: {
        jan: /^jan(uary)?/i,
        feb: /^feb(ruary)?/i,
        mar: /^mar(ch)?/i,
        apr: /^apr(il)?/i,
        may: /^may/i,
        jun: /^jun(e)?/i,
        jul: /^jul(y)?/i,
        aug: /^aug(ust)?/i,
        sep: /^sep(t(ember)?)?/i,
        oct: /^oct(ober)?/i,
        nov: /^nov(ember)?/i,
        dec: /^dec(ember)?/i,
        sun: /^su(n(day)?)?/i,
        mon: /^mo(n(day)?)?/i,
        tue: /^tu(e(s(day)?)?)?/i,
        wed: /^we(d(nesday)?)?/i,
        thu: /^th(u(r(s(day)?)?)?)?/i,
        fri: /^fr(i(day)?)?/i,
        sat: /^sa(t(urday)?)?/i,
        future: /^next/i,
        past: /^last|past|prev(ious)?/i,
        add: /^(\+|after|from)/i,
        subtract: /^(\-|before|ago)/i,
        yesterday: /^yesterday/i,
        today: /^t(oday)?/i,
        tomorrow: /^tomorrow/i,
        now: /^n(ow)?/i,
        millisecond: /^ms|milli(second)?s?/i,
        second: /^sec(ond)?s?/i,
        minute: /^min(ute)?s?/i,
        hour: /^h(ou)?rs?/i,
        week: /^w(ee)?k/i,
        month: /^m(o(nth)?s?)?/i,
        day: /^d(ays?)?/i,
        year: /^y((ea)?rs?)?/i,
        shortMeridian: /^(a|p)/i,
        longMeridian: /^(a\.?m?\.?|p\.?m?\.?)/i,
        timezone: /^((e(s|d)t|c(s|d)t|m(s|d)t|p(s|d)t)|((gmt)?\s*(\+|\-)\s*\d\d\d\d?)|gmt)/i,
        ordinalSuffix: /^\s*(st|nd|rd|th)/i,
        timeContext: /^\s*(\:|a|p)/i
    },
    abbreviatedTimeZoneStandard: {GMT: "-000", EST: "-0400", CST: "-0500", MST: "-0600", PST: "-0700"},
    abbreviatedTimeZoneDST: {GMT: "-000", EDT: "-0500", CDT: "-0600", MDT: "-0700", PDT: "-0800"}
}, Date.getMonthNumberFromName = function (a) {
    for (var b = Date.CultureInfo.monthNames, c = Date.CultureInfo.abbreviatedMonthNames, d = a.toLowerCase(), e = 0; e < b.length; e++)if (b[e].toLowerCase() == d || c[e].toLowerCase() == d)return e;
    return -1
}, Date.getDayNumberFromName = function (a) {
    for (var b = Date.CultureInfo.dayNames, c = Date.CultureInfo.abbreviatedDayNames, d = (Date.CultureInfo.shortestDayNames, a.toLowerCase()), e = 0; e < b.length; e++)if (b[e].toLowerCase() == d || c[e].toLowerCase() == d)return e;
    return -1
}, Date.isLeapYear = function (a) {
    return a % 4 === 0 && a % 100 !== 0 || a % 400 === 0
}, Date.getDaysInMonth = function (a, b) {
    return [31, Date.isLeapYear(a) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][b]
}, Date.getTimezoneOffset = function (a, b) {
    return b ? Date.CultureInfo.abbreviatedTimeZoneDST[a.toUpperCase()] : Date.CultureInfo.abbreviatedTimeZoneStandard[a.toUpperCase()]
}, Date.getTimezoneAbbreviation = function (a, b) {
    var c, d = b ? Date.CultureInfo.abbreviatedTimeZoneDST : Date.CultureInfo.abbreviatedTimeZoneStandard;
    for (c in d)if (d[c] === a)return c;
    return null
}, Date.prototype.clone = function () {
    return new Date(this.getTime())
}, Date.prototype.compareTo = function (a) {
    if (isNaN(this))throw new Error(this);
    if (a instanceof Date && !isNaN(a))return this > a ? 1 : a > this ? -1 : 0;
    throw new TypeError(a)
}, Date.prototype.equals = function (a) {
    return 0 === this.compareTo(a)
}, Date.prototype.between = function (a, b) {
    var c = this.getTime();
    return c >= a.getTime() && c <= b.getTime()
}, Date.prototype.addMilliseconds = function (a) {
    return this.setMilliseconds(this.getMilliseconds() + a), this
}, Date.prototype.addSeconds = function (a) {
    return this.addMilliseconds(1e3 * a)
}, Date.prototype.addMinutes = function (a) {
    return this.addMilliseconds(6e4 * a)
}, Date.prototype.addHours = function (a) {
    return this.addMilliseconds(36e5 * a)
}, Date.prototype.addDays = function (a) {
    return this.addMilliseconds(864e5 * a)
}, Date.prototype.addWeeks = function (a) {
    return this.addMilliseconds(6048e5 * a)
}, Date.prototype.addMonths = function (a) {
    var b = this.getDate();
    return this.setDate(1), this.setMonth(this.getMonth() + a), this.setDate(Math.min(b, this.getDaysInMonth())), this
}, Date.prototype.addYears = function (a) {
    return this.addMonths(12 * a)
}, Date.prototype.add = function (a) {
    if ("number" == typeof a)return this._orient = a, this;
    var b = a;
    return (b.millisecond || b.milliseconds) && this.addMilliseconds(b.millisecond || b.milliseconds), (b.second || b.seconds) && this.addSeconds(b.second || b.seconds), (b.minute || b.minutes) && this.addMinutes(b.minute || b.minutes), (b.hour || b.hours) && this.addHours(b.hour || b.hours), (b.month || b.months) && this.addMonths(b.month || b.months), (b.year || b.years) && this.addYears(b.year || b.years), (b.day || b.days) && this.addDays(b.day || b.days), this
}, Date._validate = function (a, b, c, d) {
    if ("number" != typeof a)throw new TypeError(a + " is not a Number.");
    if (b > a || a > c)throw new RangeError(a + " is not a valid value for " + d + ".");
    return !0
}, Date.validateMillisecond = function (a) {
    return Date._validate(a, 0, 999, "milliseconds")
}, Date.validateSecond = function (a) {
    return Date._validate(a, 0, 59, "seconds")
}, Date.validateMinute = function (a) {
    return Date._validate(a, 0, 59, "minutes")
}, Date.validateHour = function (a) {
    return Date._validate(a, 0, 23, "hours")
}, Date.validateDay = function (a, b, c) {
    return Date._validate(a, 1, Date.getDaysInMonth(b, c), "days")
}, Date.validateMonth = function (a) {
    return Date._validate(a, 0, 11, "months")
}, Date.validateYear = function (a) {
    return Date._validate(a, 1, 9999, "seconds")
}, Date.prototype.set = function (a) {
    var b = a;
    return b.millisecond || 0 === b.millisecond || (b.millisecond = -1), b.second || 0 === b.second || (b.second = -1), b.minute || 0 === b.minute || (b.minute = -1), b.hour || 0 === b.hour || (b.hour = -1), b.day || 0 === b.day || (b.day = -1), b.month || 0 === b.month || (b.month = -1), b.year || 0 === b.year || (b.year = -1), -1 != b.millisecond && Date.validateMillisecond(b.millisecond) && this.addMilliseconds(b.millisecond - this.getMilliseconds()), -1 != b.second && Date.validateSecond(b.second) && this.addSeconds(b.second - this.getSeconds()), -1 != b.minute && Date.validateMinute(b.minute) && this.addMinutes(b.minute - this.getMinutes()), -1 != b.hour && Date.validateHour(b.hour) && this.addHours(b.hour - this.getHours()), -1 !== b.month && Date.validateMonth(b.month) && this.addMonths(b.month - this.getMonth()), -1 != b.year && Date.validateYear(b.year) && this.addYears(b.year - this.getFullYear()), -1 != b.day && Date.validateDay(b.day, this.getFullYear(), this.getMonth()) && this.addDays(b.day - this.getDate()), b.timezone && this.setTimezone(b.timezone), b.timezoneOffset && this.setTimezoneOffset(b.timezoneOffset), this
}, Date.prototype.clearTime = function () {
    return this.setHours(0), this.setMinutes(0), this.setSeconds(0), this.setMilliseconds(0), this
}, Date.prototype.isLeapYear = function () {
    var a = this.getFullYear();
    return a % 4 === 0 && a % 100 !== 0 || a % 400 === 0
}, Date.prototype.isWeekday = function () {
    return !(this.is().sat() || this.is().sun())
}, Date.prototype.getDaysInMonth = function () {
    return Date.getDaysInMonth(this.getFullYear(), this.getMonth())
}, Date.prototype.moveToFirstDayOfMonth = function () {
    return this.set({day: 1})
}, Date.prototype.moveToLastDayOfMonth = function () {
    return this.set({day: this.getDaysInMonth()})
}, Date.prototype.moveToDayOfWeek = function (a, b) {
    var c = (a - this.getDay() + 7 * (b || 1)) % 7;
    return this.addDays(0 === c ? c += 7 * (b || 1) : c)
}, Date.prototype.moveToMonth = function (a, b) {
    var c = (a - this.getMonth() + 12 * (b || 1)) % 12;
    return this.addMonths(0 === c ? c += 12 * (b || 1) : c)
}, Date.prototype.getDayOfYear = function () {
    return Math.floor((this - new Date(this.getFullYear(), 0, 1)) / 864e5)
}, Date.prototype.getWeekOfYear = function (a) {
    var b = this.getFullYear(), c = this.getMonth(), d = this.getDate(), e = a || Date.CultureInfo.firstDayOfWeek, f = 8 - new Date(b, 0, 1).getDay();
    8 == f && (f = 1);
    var g = (Date.UTC(b, c, d, 0, 0, 0) - Date.UTC(b, 0, 1, 0, 0, 0)) / 864e5 + 1, h = Math.floor((g - f + 7) / 7);
    if (h === e) {
        b--;
        var i = 8 - new Date(b, 0, 1).getDay();
        h = 2 == i || 8 == i ? 53 : 52
    }
    return h
}, Date.prototype.isDST = function () {
    return console.log("isDST"), "D" == this.toString().match(/(E|C|M|P)(S|D)T/)[2]
}, Date.prototype.getTimezone = function () {
    return Date.getTimezoneAbbreviation(this.getUTCOffset, this.isDST())
}, Date.prototype.setTimezoneOffset = function (a) {
    var b = this.getTimezoneOffset(), c = -6 * Number(a) / 10;
    return this.addMinutes(c - b), this
}, Date.prototype.setTimezone = function (a) {
    return this.setTimezoneOffset(Date.getTimezoneOffset(a))
}, Date.prototype.getUTCOffset = function () {
    var a, b = -10 * this.getTimezoneOffset() / 6;
    return 0 > b ? (a = (b - 1e4).toString(), a[0] + a.substr(2)) : (a = (b + 1e4).toString(), "+" + a.substr(1))
}, Date.prototype.getDayName = function (a) {
    return a ? Date.CultureInfo.abbreviatedDayNames[this.getDay()] : Date.CultureInfo.dayNames[this.getDay()]
}, Date.prototype.getMonthName = function (a) {
    return a ? Date.CultureInfo.abbreviatedMonthNames[this.getMonth()] : Date.CultureInfo.monthNames[this.getMonth()]
}, Date.prototype._toString = Date.prototype.toString, Date.prototype.toString = function (a) {
    var b = this, c = function (a) {
        return 1 == a.toString().length ? "0" + a : a
    };
    return a ? a.replace(/dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|zz?z?/g, function (a) {
        switch (a) {
            case"hh":
                return c(b.getHours() < 13 ? b.getHours() : b.getHours() - 12);
            case"h":
                return b.getHours() < 13 ? b.getHours() : b.getHours() - 12;
            case"HH":
                return c(b.getHours());
            case"H":
                return b.getHours();
            case"mm":
                return c(b.getMinutes());
            case"m":
                return b.getMinutes();
            case"ss":
                return c(b.getSeconds());
            case"s":
                return b.getSeconds();
            case"yyyy":
                return b.getFullYear();
            case"yy":
                return b.getFullYear().toString().substring(2, 4);
            case"dddd":
                return b.getDayName();
            case"ddd":
                return b.getDayName(!0);
            case"dd":
                return c(b.getDate());
            case"d":
                return b.getDate().toString();
            case"MMMM":
                return b.getMonthName();
            case"MMM":
                return b.getMonthName(!0);
            case"MM":
                return c(b.getMonth() + 1);
            case"M":
                return b.getMonth() + 1;
            case"t":
                return b.getHours() < 12 ? Date.CultureInfo.amDesignator.substring(0, 1) : Date.CultureInfo.pmDesignator.substring(0, 1);
            case"tt":
                return b.getHours() < 12 ? Date.CultureInfo.amDesignator : Date.CultureInfo.pmDesignator;
            case"zzz":
            case"zz":
            case"z":
                return ""
        }
    }) : this._toString()
}, Date.now = function () {
    return new Date
}, Date.today = function () {
    return Date.now().clearTime()
}, Date.prototype._orient = 1, Date.prototype.next = function () {
    return this._orient = 1, this
}, Date.prototype.last = Date.prototype.prev = Date.prototype.previous = function () {
    return this._orient = -1, this
}, Date.prototype._is = !1, Date.prototype.is = function () {
    return this._is = !0, this
}, Number.prototype._dateElement = "day", Number.prototype.fromNow = function () {
    var a = {};
    return a[this._dateElement] = this, Date.now().add(a)
}, Number.prototype.ago = function () {
    var a = {};
    return a[this._dateElement] = -1 * this, Date.now().add(a)
}, function () {
    for (var a, b = Date.prototype, c = Number.prototype, d = "sunday monday tuesday wednesday thursday friday saturday".split(/\s/), e = "january february march april may june july august september october november december".split(/\s/), f = "Millisecond Second Minute Hour Day Week Month Year".split(/\s/), g = function (a) {
        return function () {
            return this._is ? (this._is = !1, this.getDay() == a) : this.moveToDayOfWeek(a, this._orient)
        }
    }, h = 0; h < d.length; h++)b[d[h]] = b[d[h].substring(0, 3)] = g(h);
    for (var i = function (a) {
        return function () {
            return this._is ? (this._is = !1, this.getMonth() === a) : this.moveToMonth(a, this._orient)
        }
    }, j = 0; j < e.length; j++)b[e[j]] = b[e[j].substring(0, 3)] = i(j);
    for (var k = function (a) {
        return function () {
            return "s" != a.substring(a.length - 1) && (a += "s"), this["add" + a](this._orient)
        }
    }, l = function (a) {
        return function () {
            return this._dateElement = a, this
        }
    }, m = 0; m < f.length; m++)a = f[m].toLowerCase(), b[a] = b[a + "s"] = k(f[m]), c[a] = c[a + "s"] = l(a)
}(), Date.prototype.toJSONString = function () {
    return this.toString("yyyy-MM-ddThh:mm:ssZ")
}, Date.prototype.toShortDateString = function () {
    return this.toString(Date.CultureInfo.formatPatterns.shortDatePattern)
}, Date.prototype.toLongDateString = function () {
    return this.toString(Date.CultureInfo.formatPatterns.longDatePattern)
}, Date.prototype.toShortTimeString = function () {
    return this.toString(Date.CultureInfo.formatPatterns.shortTimePattern)
}, Date.prototype.toLongTimeString = function () {
    return this.toString(Date.CultureInfo.formatPatterns.longTimePattern)
}, Date.prototype.getOrdinal = function () {
    switch (this.getDate()) {
        case 1:
        case 21:
        case 31:
            return "st";
        case 2:
        case 22:
            return "nd";
        case 3:
        case 23:
            return "rd";
        default:
            return "th"
    }
}, function () {
    Date.Parsing = {
        Exception: function (a) {
            this.message = "Parse error at '" + a.substring(0, 10) + " ...'"
        }
    };
    for (var a = Date.Parsing, b = a.Operators = {
        rtoken: function (b) {
            return function (c) {
                var d = c.match(b);
                if (d)return [d[0], c.substring(d[0].length)];
                throw new a.Exception(c)
            }
        }, token: function () {
            return function (a) {
                return b.rtoken(new RegExp("^s*" + a + "s*"))(a)
            }
        }, stoken: function (a) {
            return b.rtoken(new RegExp("^" + a))
        }, until: function (a) {
            return function (b) {
                for (var c = [], d = null; b.length;) {
                    try {
                        d = a.call(this, b)
                    } catch (e) {
                        c.push(d[0]), b = d[1];
                        continue
                    }
                    break
                }
                return [c, b]
            }
        }, many: function (a) {
            return function (b) {
                for (var c = [], d = null; b.length;) {
                    try {
                        d = a.call(this, b)
                    } catch (e) {
                        return [c, b]
                    }
                    c.push(d[0]), b = d[1]
                }
                return [c, b]
            }
        }, optional: function (a) {
            return function (b) {
                var c = null;
                try {
                    c = a.call(this, b)
                } catch (d) {
                    return [null, b]
                }
                return [c[0], c[1]]
            }
        }, not: function (b) {
            return function (c) {
                try {
                    b.call(this, c)
                } catch (d) {
                    return [null, c]
                }
                throw new a.Exception(c)
            }
        }, ignore: function (a) {
            return a ? function (b) {
                var c = null;
                return c = a.call(this, b), [null, c[1]]
            } : null
        }, product: function () {
            for (var a = arguments[0], c = Array.prototype.slice.call(arguments, 1), d = [], e = 0; e < a.length; e++)d.push(b.each(a[e], c));
            return d
        }, cache: function (b) {
            var c = {}, d = null;
            return function (e) {
                try {
                    d = c[e] = c[e] || b.call(this, e)
                } catch (f) {
                    d = c[e] = f
                }
                if (d instanceof a.Exception)throw d;
                return d
            }
        }, any: function () {
            var b = arguments;
            return function (c) {
                for (var d = null, e = 0; e < b.length; e++)if (null != b[e]) {
                    try {
                        d = b[e].call(this, c)
                    } catch (f) {
                        d = null
                    }
                    if (d)return d
                }
                throw new a.Exception(c)
            }
        }, each: function () {
            var b = arguments;
            return function (c) {
                for (var d = [], e = null, f = 0; f < b.length; f++)if (null != b[f]) {
                    try {
                        e = b[f].call(this, c)
                    } catch (g) {
                        throw new a.Exception(c)
                    }
                    d.push(e[0]), c = e[1]
                }
                return [d, c]
            }
        }, all: function () {
            var a = arguments, b = b;
            return b.each(b.optional(a))
        }, sequence: function (c, d, e) {
            return d = d || b.rtoken(/^\s*/), e = e || null, 1 == c.length ? c[0] : function (b) {
                for (var f = null, g = null, h = [], i = 0; i < c.length; i++) {
                    try {
                        f = c[i].call(this, b)
                    } catch (j) {
                        break
                    }
                    h.push(f[0]);
                    try {
                        g = d.call(this, f[1])
                    } catch (k) {
                        g = null;
                        break
                    }
                    b = g[1]
                }
                if (!f)throw new a.Exception(b);
                if (g)throw new a.Exception(g[1]);
                if (e)try {
                    f = e.call(this, f[1])
                } catch (l) {
                    throw new a.Exception(f[1])
                }
                return [h, f ? f[1] : b]
            }
        }, between: function (a, c, d) {
            d = d || a;
            var e = b.each(b.ignore(a), c, b.ignore(d));
            return function (a) {
                var b = e.call(this, a);
                return [[b[0][0], r[0][2]], b[1]]
            }
        }, list: function (a, c, d) {
            return c = c || b.rtoken(/^\s*/), d = d || null, a instanceof Array ? b.each(b.product(a.slice(0, -1), b.ignore(c)), a.slice(-1), b.ignore(d)) : b.each(b.many(b.each(a, b.ignore(c))), px, b.ignore(d))
        }, set: function (c, d, e) {
            return d = d || b.rtoken(/^\s*/), e = e || null, function (f) {
                for (var g = null, h = null, i = null, j = null, k = [[], f], l = !1, m = 0; m < c.length; m++) {
                    i = null, h = null, g = null, l = 1 == c.length;
                    try {
                        g = c[m].call(this, f)
                    } catch (n) {
                        continue
                    }
                    if (j = [[g[0]], g[1]], g[1].length > 0 && !l)try {
                        i = d.call(this, g[1])
                    } catch (o) {
                        l = !0
                    } else l = !0;
                    if (l || 0 !== i[1].length || (l = !0), !l) {
                        for (var p = [], q = 0; q < c.length; q++)m != q && p.push(c[q]);
                        h = b.set(p, d).call(this, i[1]), h[0].length > 0 && (j[0] = j[0].concat(h[0]), j[1] = h[1])
                    }
                    if (j[1].length < k[1].length && (k = j), 0 === k[1].length)break
                }
                if (0 === k[0].length)return k;
                if (e) {
                    try {
                        i = e.call(this, k[1])
                    } catch (r) {
                        throw new a.Exception(k[1])
                    }
                    k[1] = i[1]
                }
                return k
            }
        }, forward: function (a, b) {
            return function (c) {
                return a[b].call(this, c)
            }
        }, replace: function (a, b) {
            return function (c) {
                var d = a.call(this, c);
                return [b, d[1]]
            }
        }, process: function (a, b) {
            return function (c) {
                var d = a.call(this, c);
                return [b.call(this, d[0]), d[1]]
            }
        }, min: function (b, c) {
            return function (d) {
                var e = c.call(this, d);
                if (e[0].length < b)throw new a.Exception(d);
                return e
            }
        }
    }, c = function (a) {
        return function () {
            var b = null, c = [];
            if (arguments.length > 1 ? b = Array.prototype.slice.call(arguments) : arguments[0]instanceof Array && (b = arguments[0]), !b)return a.apply(null, arguments);
            for (var d = 0, e = b.shift(); d < e.length; d++)return b.unshift(e[d]), c.push(a.apply(null, b)), b.shift(), c
        }
    }, d = "optional not ignore cache".split(/\s/), e = 0; e < d.length; e++)b[d[e]] = c(b[d[e]]);
    for (var f = function (a) {
        return function () {
            return arguments[0]instanceof Array ? a.apply(null, arguments[0]) : a.apply(null, arguments)
        }
    }, g = "each any all".split(/\s/), h = 0; h < g.length; h++)b[g[h]] = f(b[g[h]])
}(), function () {
    var a = function (b) {
        for (var c = [], d = 0; d < b.length; d++)b[d]instanceof Array ? c = c.concat(a(b[d])) : b[d] && c.push(b[d]);
        return c
    };
    Date.Grammar = {}, Date.Translator = {
        hour: function (a) {
            return function () {
                this.hour = Number(a)
            }
        }, minute: function (a) {
            return function () {
                this.minute = Number(a)
            }
        }, second: function (a) {
            return function () {
                this.second = Number(a)
            }
        }, meridian: function (a) {
            return function () {
                this.meridian = a.slice(0, 1).toLowerCase()
            }
        }, timezone: function (a) {
            return function () {
                var b = a.replace(/[^\d\+\-]/g, "");
                b.length ? this.timezoneOffset = Number(b) : this.timezone = a.toLowerCase()
            }
        }, day: function (a) {
            var b = a[0];
            return function () {
                this.day = Number(b.match(/\d+/)[0])
            }
        }, month: function (a) {
            return function () {
                this.month = 3 == a.length ? Date.getMonthNumberFromName(a) : Number(a) - 1
            }
        }, year: function (a) {
            return function () {
                var b = Number(a);
                this.year = a.length > 2 ? b : b + (b + 2e3 < Date.CultureInfo.twoDigitYearMax ? 2e3 : 1900)
            }
        }, rday: function (a) {
            return function () {
                switch (a) {
                    case"yesterday":
                        this.days = -1;
                        break;
                    case"tomorrow":
                        this.days = 1;
                        break;
                    case"today":
                        this.days = 0;
                        break;
                    case"now":
                        this.days = 0, this.now = !0
                }
            }
        }, finishExact: function (a) {
            a = a instanceof Array ? a : [a];
            var b = new Date;
            this.year = b.getFullYear(), this.month = b.getMonth(), this.day = 1, this.hour = 0, this.minute = 0, this.second = 0;
            for (var c = 0; c < a.length; c++)a[c] && a[c].call(this);
            if (this.hour = "p" == this.meridian && this.hour < 13 ? this.hour + 12 : this.hour, this.day > Date.getDaysInMonth(this.year, this.month))throw new RangeError(this.day + " is not a valid value for days.");
            var d = new Date(this.year, this.month, this.day, this.hour, this.minute, this.second);
            return this.timezone ? d.set({timezone: this.timezone}) : this.timezoneOffset && d.set({timezoneOffset: this.timezoneOffset}), d
        }, finish: function (b) {
            if (b = b instanceof Array ? a(b) : [b], 0 === b.length)return null;
            for (var c = 0; c < b.length; c++)"function" == typeof b[c] && b[c].call(this);
            if (this.now)return new Date;
            var d = Date.today(), e = !(null == this.days && !this.orient && !this.operator);
            if (e) {
                var f, g, h;
                return h = "past" == this.orient || "subtract" == this.operator ? -1 : 1, this.weekday && (this.unit = "day", f = Date.getDayNumberFromName(this.weekday) - d.getDay(), g = 7, this.days = f ? (f + h * g) % g : h * g), this.month && (this.unit = "month", f = this.month - d.getMonth(), g = 12, this.months = f ? (f + h * g) % g : h * g, this.month = null), this.unit || (this.unit = "day"), (null == this[this.unit + "s"] || null != this.operator) && (this.value || (this.value = 1), "week" == this.unit && (this.unit = "day", this.value = 7 * this.value), this[this.unit + "s"] = this.value * h), d.add(this)
            }
            return this.meridian && this.hour && (this.hour = this.hour < 13 && "p" == this.meridian ? this.hour + 12 : this.hour), this.weekday && !this.day && (this.day = d.addDays(Date.getDayNumberFromName(this.weekday) - d.getDay()).getDate()), this.month && !this.day && (this.day = 1), d.set(this)
        }
    };
    var b, c = Date.Parsing.Operators, d = Date.Grammar, e = Date.Translator;
    d.datePartDelimiter = c.rtoken(/^([\s\-\.\,\/\x27]+)/), d.timePartDelimiter = c.stoken(":"), d.whiteSpace = c.rtoken(/^\s*/), d.generalDelimiter = c.rtoken(/^(([\s\,]|at|on)+)/);
    var f = {};
    d.ctoken = function (a) {
        var b = f[a];
        if (!b) {
            for (var d = Date.CultureInfo.regexPatterns, e = a.split(/\s+/), g = [], h = 0; h < e.length; h++)g.push(c.replace(c.rtoken(d[e[h]]), e[h]));
            b = f[a] = c.any.apply(null, g)
        }
        return b
    }, d.ctoken2 = function (a) {
        return c.rtoken(Date.CultureInfo.regexPatterns[a])
    }, d.h = c.cache(c.process(c.rtoken(/^(0[0-9]|1[0-2]|[1-9])/), e.hour)), d.hh = c.cache(c.process(c.rtoken(/^(0[0-9]|1[0-2])/), e.hour)), d.H = c.cache(c.process(c.rtoken(/^([0-1][0-9]|2[0-3]|[0-9])/), e.hour)), d.HH = c.cache(c.process(c.rtoken(/^([0-1][0-9]|2[0-3])/), e.hour)), d.m = c.cache(c.process(c.rtoken(/^([0-5][0-9]|[0-9])/), e.minute)), d.mm = c.cache(c.process(c.rtoken(/^[0-5][0-9]/), e.minute)), d.s = c.cache(c.process(c.rtoken(/^([0-5][0-9]|[0-9])/), e.second)), d.ss = c.cache(c.process(c.rtoken(/^[0-5][0-9]/), e.second)), d.hms = c.cache(c.sequence([d.H, d.mm, d.ss], d.timePartDelimiter)), d.t = c.cache(c.process(d.ctoken2("shortMeridian"), e.meridian)), d.tt = c.cache(c.process(d.ctoken2("longMeridian"), e.meridian)), d.z = c.cache(c.process(c.rtoken(/^(\+|\-)?\s*\d\d\d\d?/), e.timezone)), d.zz = c.cache(c.process(c.rtoken(/^(\+|\-)\s*\d\d\d\d/), e.timezone)), d.zzz = c.cache(c.process(d.ctoken2("timezone"), e.timezone)), d.timeSuffix = c.each(c.ignore(d.whiteSpace), c.set([d.tt, d.zzz])), d.time = c.each(c.optional(c.ignore(c.stoken("T"))), d.hms, d.timeSuffix), d.d = c.cache(c.process(c.each(c.rtoken(/^([0-2]\d|3[0-1]|\d)/), c.optional(d.ctoken2("ordinalSuffix"))), e.day)), d.dd = c.cache(c.process(c.each(c.rtoken(/^([0-2]\d|3[0-1])/), c.optional(d.ctoken2("ordinalSuffix"))), e.day)), d.ddd = d.dddd = c.cache(c.process(d.ctoken("sun mon tue wed thu fri sat"), function (a) {
        return function () {
            this.weekday = a
        }
    })), d.M = c.cache(c.process(c.rtoken(/^(1[0-2]|0\d|\d)/), e.month)), d.MM = c.cache(c.process(c.rtoken(/^(1[0-2]|0\d)/), e.month)), d.MMM = d.MMMM = c.cache(c.process(d.ctoken("jan feb mar apr may jun jul aug sep oct nov dec"), e.month)), d.y = c.cache(c.process(c.rtoken(/^(\d\d?)/), e.year)), d.yy = c.cache(c.process(c.rtoken(/^(\d\d)/), e.year)), d.yyy = c.cache(c.process(c.rtoken(/^(\d\d?\d?\d?)/), e.year)), d.yyyy = c.cache(c.process(c.rtoken(/^(\d\d\d\d)/), e.year)), b = function () {
        return c.each(c.any.apply(null, arguments), c.not(d.ctoken2("timeContext")))
    }, d.day = b(d.d, d.dd), d.month = b(d.M, d.MMM), d.year = b(d.yyyy, d.yy), d.orientation = c.process(d.ctoken("past future"), function (a) {
        return function () {
            this.orient = a
        }
    }), d.operator = c.process(d.ctoken("add subtract"), function (a) {
        return function () {
            this.operator = a
        }
    }), d.rday = c.process(d.ctoken("yesterday tomorrow today now"), e.rday), d.unit = c.process(d.ctoken("minute hour day week month year"), function (a) {
        return function () {
            this.unit = a
        }
    }), d.value = c.process(c.rtoken(/^\d\d?(st|nd|rd|th)?/), function (a) {
        return function () {
            this.value = a.replace(/\D/g, "")
        }
    }), d.expression = c.set([d.rday, d.operator, d.value, d.unit, d.orientation, d.ddd, d.MMM]), b = function () {
        return c.set(arguments, d.datePartDelimiter)
    }, d.mdy = b(d.ddd, d.month, d.day, d.year), d.ymd = b(d.ddd, d.year, d.month, d.day), d.dmy = b(d.ddd, d.day, d.month, d.year), d.date = function (a) {
        return (d[Date.CultureInfo.dateElementOrder] || d.mdy).call(this, a)
    }, d.format = c.process(c.many(c.any(c.process(c.rtoken(/^(dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|zz?z?)/), function (a) {
        if (d[a])return d[a];
        throw Date.Parsing.Exception(a)
    }), c.process(c.rtoken(/^[^dMyhHmstz]+/), function (a) {
        return c.ignore(c.stoken(a))
    }))), function (a) {
        return c.process(c.each.apply(null, a), e.finishExact)
    });
    var g = {}, h = function (a) {
        return g[a] = g[a] || d.format(a)[0]
    };
    d.formats = function (a) {
        if (a instanceof Array) {
            for (var b = [], d = 0; d < a.length; d++)b.push(h(a[d]));
            return c.any.apply(null, b)
        }
        return h(a)
    }, d._formats = d.formats(["yyyy-MM-ddTHH:mm:ss", "ddd, MMM dd, yyyy H:mm:ss tt", "ddd MMM d yyyy HH:mm:ss zzz", "d"]), d._start = c.process(c.set([d.date, d.time, d.expression], d.generalDelimiter, d.whiteSpace), e.finish), d.start = function (a) {
        try {
            var b = d._formats.call({}, a);
            if (0 === b[1].length)return b
        } catch (c) {
        }
        return d._start.call({}, a)
    }
}(), Date._parse = Date.parse, Date.parse = function (a) {
    var b = null;
    if (!a)return null;
    try {
        b = Date.Grammar.start.call({}, a)
    } catch (c) {
        return null
    }
    return 0 === b[1].length ? b[0] : null
}, Date.getParseFunction = function (a) {
    var b = Date.Grammar.formats(a);
    return function (a) {
        var c = null;
        try {
            c = b.call({}, a)
        } catch (d) {
            return null
        }
        return 0 === c[1].length ? c[0] : null
    }
}, Date.parseExact = function (a, b) {
    return Date.getParseFunction(b)(a)
}, function (a) {
    "function" == typeof define && define.amd ? define(["jquery"], a) : a(jQuery)
}(function (a) {
    "use strict";
    var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H = {}, I = 0;
    b = function () {
        return {
            common: {
                type: "line",
                lineColor: "#00f",
                fillColor: "#cdf",
                defaultPixelsPerValue: 3,
                width: "auto",
                height: "auto",
                composite: !1,
                tagValuesAttribute: "values",
                tagOptionsPrefix: "spark",
                enableTagOptions: !1,
                enableHighlight: !0,
                highlightLighten: 1.4,
                tooltipSkipNull: !0,
                tooltipPrefix: "",
                tooltipSuffix: "",
                disableHiddenCheck: !1,
                numberFormatter: !1,
                numberDigitGroupCount: 3,
                numberDigitGroupSep: ",",
                numberDecimalMark: ".",
                disableTooltips: !1,
                disableInteraction: !1
            },
            line: {
                spotColor: "#f80",
                highlightSpotColor: "#5f5",
                highlightLineColor: "#f22",
                spotRadius: 1.5,
                minSpotColor: "#f80",
                maxSpotColor: "#f80",
                lineWidth: 1,
                normalRangeMin: void 0,
                normalRangeMax: void 0,
                normalRangeColor: "#ccc",
                drawNormalOnTop: !1,
                chartRangeMin: void 0,
                chartRangeMax: void 0,
                chartRangeMinX: void 0,
                chartRangeMaxX: void 0,
                tooltipFormat: new d('<span style="color: {{color}}">&#9679;</span> {{prefix}}{{y}}{{suffix}}')
            },
            bar: {
                barColor: "#3366cc",
                negBarColor: "#f44",
                stackedBarColor: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#66aa00", "#dd4477", "#0099c6", "#990099"],
                zeroColor: void 0,
                nullColor: void 0,
                zeroAxis: !0,
                barWidth: 4,
                barSpacing: 1,
                chartRangeMax: void 0,
                chartRangeMin: void 0,
                chartRangeClip: !1,
                colorMap: void 0,
                tooltipFormat: new d('<span style="color: {{color}}">&#9679;</span> {{prefix}}{{value}}{{suffix}}')
            },
            tristate: {
                barWidth: 4,
                barSpacing: 1,
                posBarColor: "#6f6",
                negBarColor: "#f44",
                zeroBarColor: "#999",
                colorMap: {},
                tooltipFormat: new d('<span style="color: {{color}}">&#9679;</span> {{value:map}}'),
                tooltipValueLookups: {map: {"-1": "Loss", 0: "Draw", 1: "Win"}}
            },
            discrete: {
                lineHeight: "auto",
                thresholdColor: void 0,
                thresholdValue: 0,
                chartRangeMax: void 0,
                chartRangeMin: void 0,
                chartRangeClip: !1,
                tooltipFormat: new d("{{prefix}}{{value}}{{suffix}}")
            },
            bullet: {
                targetColor: "#f33",
                targetWidth: 3,
                performanceColor: "#33f",
                rangeColors: ["#d3dafe", "#a8b6ff", "#7f94ff"],
                base: void 0,
                tooltipFormat: new d("{{fieldkey:fields}} - {{value}}"),
                tooltipValueLookups: {fields: {r: "Range", p: "Performance", t: "Target"}}
            },
            pie: {
                offset: 0,
                sliceColors: ["#3366cc", "#dc3912", "#ff9900", "#109618", "#66aa00", "#dd4477", "#0099c6", "#990099"],
                borderWidth: 0,
                borderColor: "#000",
                tooltipFormat: new d('<span style="color: {{color}}">&#9679;</span> {{value}} ({{percent.1}}%)')
            },
            box: {
                raw: !1,
                boxLineColor: "#000",
                boxFillColor: "#cdf",
                whiskerColor: "#000",
                outlierLineColor: "#333",
                outlierFillColor: "#fff",
                medianColor: "#f00",
                showOutliers: !0,
                outlierIQR: 1.5,
                spotRadius: 1.5,
                target: void 0,
                targetColor: "#4a2",
                chartRangeMax: void 0,
                chartRangeMin: void 0,
                tooltipFormat: new d("{{field:fields}}: {{value}}"),
                tooltipFormatFieldlistKey: "field",
                tooltipValueLookups: {
                    fields: {
                        lq: "Lower Quartile",
                        med: "Median",
                        uq: "Upper Quartile",
                        lo: "Left Outlier",
                        ro: "Right Outlier",
                        lw: "Left Whisker",
                        rw: "Right Whisker"
                    }
                }
            }
        }
    }, A = '.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}', c = function () {
        var b, c;
        return b = function () {
            this.init.apply(this, arguments)
        }, arguments.length > 1 ? (arguments[0] ? (b.prototype = a.extend(new arguments[0], arguments[arguments.length - 1]), b._super = arguments[0].prototype) : b.prototype = arguments[arguments.length - 1], arguments.length > 2 && (c = Array.prototype.slice.call(arguments, 1, -1), c.unshift(b.prototype), a.extend.apply(a, c))) : b.prototype = arguments[0], b.prototype.cls = b, b
    }, a.SPFormatClass = d = c({
        fre: /\{\{([\w.]+?)(:(.+?))?\}\}/g, precre: /(\w+)\.(\d+)/, init: function (a, b) {
            this.format = a, this.fclass = b
        }, render: function (a, b, c) {
            var d, e, f, g, h, i = this, k = a;
            return this.format.replace(this.fre, function () {
                var a;
                return e = arguments[1], f = arguments[3], d = i.precre.exec(e), d ? (h = d[2], e = d[1]) : h = !1, g = k[e], void 0 === g ? "" : f && b && b[f] ? (a = b[f], a.get ? b[f].get(g) || g : b[f][g] || g) : (j(g) && (g = c.get("numberFormatter") ? c.get("numberFormatter")(g) : o(g, h, c.get("numberDigitGroupCount"), c.get("numberDigitGroupSep"), c.get("numberDecimalMark"))), g)
            })
        }
    }), a.spformat = function (a, b) {
        return new d(a, b)
    }, e = function (a, b, c) {
        return b > a ? b : a > c ? c : a
    }, f = function (a, b) {
        var c;
        return 2 === b ? (c = Math.floor(a.length / 2), a.length % 2 ? a[c] : (a[c - 1] + a[c]) / 2) : a.length % 2 ? (c = (a.length * b + b) / 4, c % 1 ? (a[Math.floor(c)] + a[Math.floor(c) - 1]) / 2 : a[c - 1]) : (c = (a.length * b + 2) / 4, c % 1 ? (a[Math.floor(c)] + a[Math.floor(c) - 1]) / 2 : a[c - 1])
    }, g = function (a) {
        var b;
        switch (a) {
            case"undefined":
                a = void 0;
                break;
            case"null":
                a = null;
                break;
            case"true":
                a = !0;
                break;
            case"false":
                a = !1;
                break;
            default:
                b = parseFloat(a), a == b && (a = b)
        }
        return a
    }, h = function (a) {
        var b, c = [];
        for (b = a.length; b--;)c[b] = g(a[b]);
        return c
    }, i = function (a, b) {
        var c, d, e = [];
        for (c = 0, d = a.length; d > c; c++)a[c] !== b && e.push(a[c]);
        return e
    }, j = function (a) {
        return !isNaN(parseFloat(a)) && isFinite(a)
    }, o = function (b, c, d, e, f) {
        var g, h;
        for (b = (c === !1 ? parseFloat(b).toString() : b.toFixed(c)).split(""), g = (g = a.inArray(".", b)) < 0 ? b.length : g, g < b.length && (b[g] = f), h = g - d; h > 0; h -= d)b.splice(h, 0, e);
        return b.join("")
    }, k = function (a, b, c) {
        var d;
        for (d = b.length; d--;)if ((!c || null !== b[d]) && b[d] !== a)return !1;
        return !0
    }, l = function (a) {
        var b, c = 0;
        for (b = a.length; b--;)c += "number" == typeof a[b] ? a[b] : 0;
        return c
    }, n = function (b) {
        return a.isArray(b) ? b : [b]
    }, m = function (a) {
        var b;
        document.createStyleSheet ? document.createStyleSheet().cssText = a : (b = document.createElement("style"), b.type = "text/css", document.getElementsByTagName("head")[0].appendChild(b), b["string" == typeof document.body.style.WebkitAppearance ? "innerText" : "innerHTML"] = a)
    }, a.fn.simpledraw = function (b, c, d, e) {
        var f, g;
        if (d && (f = this.data("_jqs_vcanvas")))return f;
        if (void 0 === b && (b = a(this).innerWidth()), void 0 === c && (c = a(this).innerHeight()), a.fn.sparkline.hasCanvas)f = new E(b, c, this, e); else {
            if (!a.fn.sparkline.hasVML)return !1;
            f = new F(b, c, this)
        }
        return g = a(this).data("_jqs_mhandler"), g && g.registerCanvas(f), f
    }, a.fn.cleardraw = function () {
        var a = this.data("_jqs_vcanvas");
        a && a.reset()
    }, a.RangeMapClass = p = c({
        init: function (a) {
            var b, c, d = [];
            for (b in a)a.hasOwnProperty(b) && "string" == typeof b && b.indexOf(":") > -1 && (c = b.split(":"), c[0] = 0 === c[0].length ? -1 / 0 : parseFloat(c[0]), c[1] = 0 === c[1].length ? 1 / 0 : parseFloat(c[1]), c[2] = a[b], d.push(c));
            this.map = a, this.rangelist = d || !1
        }, get: function (a) {
            var b, c, d, e = this.rangelist;
            if (void 0 !== (d = this.map[a]))return d;
            if (e)for (b = e.length; b--;)if (c = e[b], c[0] <= a && c[1] >= a)return c[2];
            return void 0
        }
    }), a.range_map = function (a) {
        return new p(a)
    }, q = c({
        init: function (b, c) {
            var d = a(b);
            this.$el = d, this.options = c, this.currentPageX = 0, this.currentPageY = 0, this.el = b, this.splist = [], this.tooltip = null, this.over = !1, this.displayTooltips = !c.get("disableTooltips"), this.highlightEnabled = !c.get("disableHighlight")
        }, registerSparkline: function (a) {
            this.splist.push(a), this.over && this.updateDisplay()
        }, registerCanvas: function (b) {
            var c = a(b.canvas);
            this.canvas = b, this.$canvas = c, c.mouseenter(a.proxy(this.mouseenter, this)), c.mouseleave(a.proxy(this.mouseleave, this)), c.click(a.proxy(this.mouseclick, this))
        }, reset: function (a) {
            this.splist = [], this.tooltip && a && (this.tooltip.remove(), this.tooltip = void 0)
        }, mouseclick: function (b) {
            var c = a.Event("sparklineClick");
            c.originalEvent = b, c.sparklines = this.splist, this.$el.trigger(c)
        }, mouseenter: function (b) {
            a(document.body).unbind("mousemove.jqs"), a(document.body).bind("mousemove.jqs", a.proxy(this.mousemove, this)), this.over = !0, this.currentPageX = b.pageX, this.currentPageY = b.pageY, this.currentEl = b.target, !this.tooltip && this.displayTooltips && (this.tooltip = new r(this.options), this.tooltip.updatePosition(b.pageX, b.pageY)), this.updateDisplay()
        }, mouseleave: function () {
            a(document.body).unbind("mousemove.jqs");
            var b, c, d = this.splist, e = d.length, f = !1;
            for (this.over = !1, this.currentEl = null, this.tooltip && (this.tooltip.remove(), this.tooltip = null), c = 0; e > c; c++)b = d[c], b.clearRegionHighlight() && (f = !0);
            f && this.canvas.render()
        }, mousemove: function (a) {
            this.currentPageX = a.pageX, this.currentPageY = a.pageY, this.currentEl = a.target, this.tooltip && this.tooltip.updatePosition(a.pageX, a.pageY), this.updateDisplay()
        }, updateDisplay: function () {
            var b, c, d, e, f, g = this.splist, h = g.length, i = !1, j = this.$canvas.offset(), k = this.currentPageX - j.left, l = this.currentPageY - j.top;
            if (this.over) {
                for (d = 0; h > d; d++)c = g[d], e = c.setRegionHighlight(this.currentEl, k, l), e && (i = !0);
                if (i) {
                    if (f = a.Event("sparklineRegionChange"), f.sparklines = this.splist, this.$el.trigger(f), this.tooltip) {
                        for (b = "", d = 0; h > d; d++)c = g[d], b += c.getCurrentRegionTooltip();
                        this.tooltip.setContent(b)
                    }
                    this.disableHighlight || this.canvas.render()
                }
                null === e && this.mouseleave()
            }
        }
    }), r = c({
        sizeStyle: "position: static !important;display: block !important;visibility: hidden !important;float: left !important;",
        init: function (b) {
            var c, d = b.get("tooltipClassname", "jqstooltip"), e = this.sizeStyle;
            this.container = b.get("tooltipContainer") || document.body, this.tooltipOffsetX = b.get("tooltipOffsetX", 10), this.tooltipOffsetY = b.get("tooltipOffsetY", 12), a("#jqssizetip").remove(), a("#jqstooltip").remove(), this.sizetip = a("<div/>", {
                id: "jqssizetip",
                style: e,
                "class": d
            }), this.tooltip = a("<div/>", {
                id: "jqstooltip",
                "class": d
            }).appendTo(this.container), c = this.tooltip.offset(), this.offsetLeft = c.left, this.offsetTop = c.top, this.hidden = !0, a(window).unbind("resize.jqs scroll.jqs"), a(window).bind("resize.jqs scroll.jqs", a.proxy(this.updateWindowDims, this)), this.updateWindowDims()
        },
        updateWindowDims: function () {
            this.scrollTop = a(window).scrollTop(), this.scrollLeft = a(window).scrollLeft(), this.scrollRight = this.scrollLeft + a(window).width(), this.updatePosition()
        },
        getSize: function (a) {
            this.sizetip.html(a).appendTo(this.container), this.width = this.sizetip.width() + 1, this.height = this.sizetip.height(), this.sizetip.remove()
        },
        setContent: function (a) {
            return a ? (this.getSize(a), this.tooltip.html(a).css({
                width: this.width,
                height: this.height,
                visibility: "visible"
            }), void(this.hidden && (this.hidden = !1, this.updatePosition()))) : (this.tooltip.css("visibility", "hidden"), void(this.hidden = !0))
        },
        updatePosition: function (a, b) {
            if (void 0 === a) {
                if (void 0 === this.mousex)return;
                a = this.mousex - this.offsetLeft, b = this.mousey - this.offsetTop
            } else this.mousex = a -= this.offsetLeft, this.mousey = b -= this.offsetTop;
            this.height && this.width && !this.hidden && (b -= this.height + this.tooltipOffsetY, a += this.tooltipOffsetX, b < this.scrollTop && (b = this.scrollTop), a < this.scrollLeft ? a = this.scrollLeft : a + this.width > this.scrollRight && (a = this.scrollRight - this.width), this.tooltip.css({
                left: a,
                top: b
            }))
        },
        remove: function () {
            this.tooltip.remove(), this.sizetip.remove(), this.sizetip = this.tooltip = void 0, a(window).unbind("resize.jqs scroll.jqs")
        }
    }), B = function () {
        m(A)
    }, a(B), G = [], a.fn.sparkline = function (b, c) {
        return this.each(function () {
            var d, e, f = new a.fn.sparkline.options(this, c), g = a(this);
            if (d = function () {
                    var c, d, e, h, i, j, k;
                    return "html" === b || void 0 === b ? (k = this.getAttribute(f.get("tagValuesAttribute")), (void 0 === k || null === k) && (k = g.html()), c = k.replace(/(^\s*<!--)|(-->\s*$)|\s+/g, "").split(",")) : c = b, d = "auto" === f.get("width") ? c.length * f.get("defaultPixelsPerValue") : f.get("width"), "auto" === f.get("height") ? f.get("composite") && a.data(this, "_jqs_vcanvas") || (h = document.createElement("span"), h.innerHTML = "a", g.html(h), e = a(h).innerHeight() || a(h).height(), a(h).remove(), h = null) : e = f.get("height"), f.get("disableInteraction") ? i = !1 : (i = a.data(this, "_jqs_mhandler"), i ? f.get("composite") || i.reset() : (i = new q(this, f), a.data(this, "_jqs_mhandler", i))), f.get("composite") && !a.data(this, "_jqs_vcanvas") ? void(a.data(this, "_jqs_errnotify") || (alert("Attempted to attach a composite sparkline to an element with no existing sparkline"), a.data(this, "_jqs_errnotify", !0))) : (j = new (a.fn.sparkline[f.get("type")])(this, c, f, d, e), j.render(), void(i && i.registerSparkline(j)))
                }, a(this).html() && !f.get("disableHiddenCheck") && a(this).is(":hidden") || a.fn.jquery < "1.3.0" && a(this).parents().is(":hidden") || !a(this).parents("body").length) {
                if (!f.get("composite") && a.data(this, "_jqs_pending"))for (e = G.length; e; e--)G[e - 1][0] == this && G.splice(e - 1, 1);
                G.push([this, d]), a.data(this, "_jqs_pending", !0)
            } else d.call(this)
        })
    }, a.fn.sparkline.defaults = b(), a.sparkline_display_visible = function () {
        var b, c, d, e = [];
        for (c = 0, d = G.length; d > c; c++)b = G[c][0], a(b).is(":visible") && !a(b).parents().is(":hidden") ? (G[c][1].call(b), a.data(G[c][0], "_jqs_pending", !1), e.push(c)) : a(b).closest("html").length || a.data(b, "_jqs_pending") || (a.data(G[c][0], "_jqs_pending", !1), e.push(c));
        for (c = e.length; c; c--)G.splice(e[c - 1], 1)
    }, a.fn.sparkline.options = c({
        init: function (b, c) {
            var d, e, f, g;
            this.userOptions = c = c || {}, this.tag = b, this.tagValCache = {}, e = a.fn.sparkline.defaults, f = e.common, this.tagOptionsPrefix = c.enableTagOptions && (c.tagOptionsPrefix || f.tagOptionsPrefix), g = this.getTagSetting("type"), d = g === H ? e[c.type || f.type] : e[g], this.mergedOptions = a.extend({}, f, d, c)
        }, getTagSetting: function (a) {
            var b, c, d, e, f = this.tagOptionsPrefix;
            if (f === !1 || void 0 === f)return H;
            if (this.tagValCache.hasOwnProperty(a))b = this.tagValCache.key; else {
                if (b = this.tag.getAttribute(f + a), void 0 === b || null === b)b = H; else if ("[" === b.substr(0, 1))for (b = b.substr(1, b.length - 2).split(","), c = b.length; c--;)b[c] = g(b[c].replace(/(^\s*)|(\s*$)/g, "")); else if ("{" === b.substr(0, 1))for (d = b.substr(1, b.length - 2).split(","), b = {}, c = d.length; c--;)e = d[c].split(":", 2), b[e[0].replace(/(^\s*)|(\s*$)/g, "")] = g(e[1].replace(/(^\s*)|(\s*$)/g, "")); else b = g(b);
                this.tagValCache.key = b
            }
            return b
        }, get: function (a, b) {
            var c, d = this.getTagSetting(a);
            return d !== H ? d : void 0 === (c = this.mergedOptions[a]) ? b : c
        }
    }), a.fn.sparkline._base = c({
        disabled: !1, init: function (b, c, d, e, f) {
            this.el = b, this.$el = a(b), this.values = c, this.options = d, this.width = e, this.height = f, this.currentRegion = void 0
        }, initTarget: function () {
            var a = !this.options.get("disableInteraction");
            (this.target = this.$el.simpledraw(this.width, this.height, this.options.get("composite"), a)) ? (this.canvasWidth = this.target.pixelWidth, this.canvasHeight = this.target.pixelHeight) : this.disabled = !0
        }, render: function () {
            return this.disabled ? (this.el.innerHTML = "", !1) : !0
        }, getRegion: function () {
        }, setRegionHighlight: function (a, b, c) {
            var d, e = this.currentRegion, f = !this.options.get("disableHighlight");
            return b > this.canvasWidth || c > this.canvasHeight || 0 > b || 0 > c ? null : (d = this.getRegion(a, b, c), e !== d ? (void 0 !== e && f && this.removeHighlight(), this.currentRegion = d, void 0 !== d && f && this.renderHighlight(), !0) : !1)
        }, clearRegionHighlight: function () {
            return void 0 !== this.currentRegion ? (this.removeHighlight(), this.currentRegion = void 0, !0) : !1
        }, renderHighlight: function () {
            this.changeHighlight(!0)
        }, removeHighlight: function () {
            this.changeHighlight(!1)
        }, changeHighlight: function () {
        }, getCurrentRegionTooltip: function () {
            var b, c, e, f, g, h, i, j, k, l, m, n, o, p, q = this.options, r = "", s = [];
            if (void 0 === this.currentRegion)return "";
            if (b = this.getCurrentRegionFields(), m = q.get("tooltipFormatter"))return m(this, q, b);
            if (q.get("tooltipChartTitle") && (r += '<div class="jqs jqstitle">' + q.get("tooltipChartTitle") + "</div>\n"), c = this.options.get("tooltipFormat"), !c)return "";
            if (a.isArray(c) || (c = [c]), a.isArray(b) || (b = [b]), i = this.options.get("tooltipFormatFieldlist"), j = this.options.get("tooltipFormatFieldlistKey"), i && j) {
                for (k = [], h = b.length; h--;)l = b[h][j], -1 != (p = a.inArray(l, i)) && (k[p] = b[h]);
                b = k
            }
            for (e = c.length, o = b.length, h = 0; e > h; h++)for (n = c[h], "string" == typeof n && (n = new d(n)), f = n.fclass || "jqsfield", p = 0; o > p; p++)b[p].isNull && q.get("tooltipSkipNull") || (a.extend(b[p], {
                prefix: q.get("tooltipPrefix"),
                suffix: q.get("tooltipSuffix")
            }), g = n.render(b[p], q.get("tooltipValueLookups"), q), s.push('<div class="' + f + '">' + g + "</div>"));
            return s.length ? r + s.join("\n") : ""
        }, getCurrentRegionFields: function () {
        }, calcHighlightColor: function (a, b) {
            var c, d, f, g, h = b.get("highlightColor"), i = b.get("highlightLighten");
            if (h)return h;
            if (i && (c = /^#([0-9a-f])([0-9a-f])([0-9a-f])$/i.exec(a) || /^#([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i.exec(a))) {
                for (f = [], d = 4 === a.length ? 16 : 1, g = 0; 3 > g; g++)f[g] = e(Math.round(parseInt(c[g + 1], 16) * d * i), 0, 255);
                return "rgb(" + f.join(",") + ")"
            }
            return a
        }
    }), s = {
        changeHighlight: function (b) {
            var c, d = this.currentRegion, e = this.target, f = this.regionShapes[d];
            f && (c = this.renderRegion(d, b), a.isArray(c) || a.isArray(f) ? (e.replaceWithShapes(f, c), this.regionShapes[d] = a.map(c, function (a) {
                return a.id
            })) : (e.replaceWithShape(f, c), this.regionShapes[d] = c.id))
        }, render: function () {
            var b, c, d, e, f = this.values, g = this.target, h = this.regionShapes;
            if (this.cls._super.render.call(this)) {
                for (d = f.length; d--;)if (b = this.renderRegion(d))if (a.isArray(b)) {
                    for (c = [], e = b.length; e--;)b[e].append(), c.push(b[e].id);
                    h[d] = c
                } else b.append(), h[d] = b.id; else h[d] = null;
                g.render()
            }
        }
    }, a.fn.sparkline.line = t = c(a.fn.sparkline._base, {
        type: "line", init: function (a, b, c, d, e) {
            t._super.init.call(this, a, b, c, d, e), this.vertices = [], this.regionMap = [], this.xvalues = [], this.yvalues = [], this.yminmax = [], this.hightlightSpotId = null, this.lastShapeId = null, this.initTarget()
        }, getRegion: function (a, b) {
            var c, d = this.regionMap;
            for (c = d.length; c--;)if (null !== d[c] && b >= d[c][0] && b <= d[c][1])return d[c][2];
            return void 0
        }, getCurrentRegionFields: function () {
            var a = this.currentRegion;
            return {
                isNull: null === this.yvalues[a],
                x: this.xvalues[a],
                y: this.yvalues[a],
                color: this.options.get("lineColor"),
                fillColor: this.options.get("fillColor"),
                offset: a
            }
        }, renderHighlight: function () {
            var a, b, c = this.currentRegion, d = this.target, e = this.vertices[c], f = this.options, g = f.get("spotRadius"), h = f.get("highlightSpotColor"), i = f.get("highlightLineColor");
            e && (g && h && (a = d.drawCircle(e[0], e[1], g, void 0, h), this.highlightSpotId = a.id, d.insertAfterShape(this.lastShapeId, a)), i && (b = d.drawLine(e[0], this.canvasTop, e[0], this.canvasTop + this.canvasHeight, i), this.highlightLineId = b.id, d.insertAfterShape(this.lastShapeId, b)))
        }, removeHighlight: function () {
            var a = this.target;
            this.highlightSpotId && (a.removeShapeId(this.highlightSpotId), this.highlightSpotId = null), this.highlightLineId && (a.removeShapeId(this.highlightLineId), this.highlightLineId = null)
        }, scanValues: function () {
            var a, b, c, d, e, f = this.values, g = f.length, h = this.xvalues, i = this.yvalues, j = this.yminmax;
            for (a = 0; g > a; a++)b = f[a], c = "string" == typeof f[a], d = "object" == typeof f[a] && f[a]instanceof Array, e = c && f[a].split(":"), c && 2 === e.length ? (h.push(Number(e[0])), i.push(Number(e[1])), j.push(Number(e[1]))) : d ? (h.push(b[0]), i.push(b[1]), j.push(b[1])) : (h.push(a), null === f[a] || "null" === f[a] ? i.push(null) : (i.push(Number(b)), j.push(Number(b))));
            this.options.get("xvalues") && (h = this.options.get("xvalues")), this.maxy = this.maxyorg = Math.max.apply(Math, j), this.miny = this.minyorg = Math.min.apply(Math, j), this.maxx = Math.max.apply(Math, h), this.minx = Math.min.apply(Math, h), this.xvalues = h, this.yvalues = i, this.yminmax = j
        }, processRangeOptions: function () {
            var a = this.options, b = a.get("normalRangeMin"), c = a.get("normalRangeMax");
            void 0 !== b && (b < this.miny && (this.miny = b), c > this.maxy && (this.maxy = c)), void 0 !== a.get("chartRangeMin") && (a.get("chartRangeClip") || a.get("chartRangeMin") < this.miny) && (this.miny = a.get("chartRangeMin")), void 0 !== a.get("chartRangeMax") && (a.get("chartRangeClip") || a.get("chartRangeMax") > this.maxy) && (this.maxy = a.get("chartRangeMax")), void 0 !== a.get("chartRangeMinX") && (a.get("chartRangeClipX") || a.get("chartRangeMinX") < this.minx) && (this.minx = a.get("chartRangeMinX")), void 0 !== a.get("chartRangeMaxX") && (a.get("chartRangeClipX") || a.get("chartRangeMaxX") > this.maxx) && (this.maxx = a.get("chartRangeMaxX"))
        }, drawNormalRange: function (a, b, c, d, e) {
            var f = this.options.get("normalRangeMin"), g = this.options.get("normalRangeMax"), h = b + Math.round(c - c * ((g - this.miny) / e)), i = Math.round(c * (g - f) / e);
            this.target.drawRect(a, h, d, i, void 0, this.options.get("normalRangeColor")).append()
        }, render: function () {
            var b, c, d, e, f, g, h, i, j, k, l, m, n, o, q, r, s, u, v, w, x, y, z, A, B, C = this.options, D = this.target, E = this.canvasWidth, F = this.canvasHeight, G = this.vertices, H = C.get("spotRadius"), I = this.regionMap;
            if (t._super.render.call(this) && (this.scanValues(), this.processRangeOptions(), z = this.xvalues, A = this.yvalues, this.yminmax.length && !(this.yvalues.length < 2))) {
                for (e = f = 0, b = this.maxx - this.minx === 0 ? 1 : this.maxx - this.minx, c = this.maxy - this.miny === 0 ? 1 : this.maxy - this.miny, d = this.yvalues.length - 1, H && (4 * H > E || 4 * H > F) && (H = 0), H && (x = C.get("highlightSpotColor") && !C.get("disableInteraction"), (x || C.get("minSpotColor") || C.get("spotColor") && A[d] === this.miny) && (F -= Math.ceil(H)), (x || C.get("maxSpotColor") || C.get("spotColor") && A[d] === this.maxy) && (F -= Math.ceil(H), e += Math.ceil(H)), (x || (C.get("minSpotColor") || C.get("maxSpotColor")) && (A[0] === this.miny || A[0] === this.maxy)) && (f += Math.ceil(H), E -= Math.ceil(H)), (x || C.get("spotColor") || C.get("minSpotColor") || C.get("maxSpotColor") && (A[d] === this.miny || A[d] === this.maxy)) && (E -= Math.ceil(H))), F--, void 0 === C.get("normalRangeMin") || C.get("drawNormalOnTop") || this.drawNormalRange(f, e, F, E, c), h = [], i = [h], o = q = null, r = A.length, B = 0; r > B; B++)j = z[B], l = z[B + 1], k = A[B], m = f + Math.round((j - this.minx) * (E / b)), n = r - 1 > B ? f + Math.round((l - this.minx) * (E / b)) : E, q = m + (n - m) / 2, I[B] = [o || 0, q, B], o = q, null === k ? B && (null !== A[B - 1] && (h = [], i.push(h)), G.push(null)) : (k < this.miny && (k = this.miny), k > this.maxy && (k = this.maxy), h.length || h.push([m, e + F]), g = [m, e + Math.round(F - F * ((k - this.miny) / c))], h.push(g), G.push(g));
                for (s = [], u = [], v = i.length, B = 0; v > B; B++)h = i[B], h.length && (C.get("fillColor") && (h.push([h[h.length - 1][0], e + F]), u.push(h.slice(0)), h.pop()), h.length > 2 && (h[0] = [h[0][0], h[1][1]]), s.push(h));
                for (v = u.length, B = 0; v > B; B++)D.drawShape(u[B], C.get("fillColor"), C.get("fillColor")).append();
                for (void 0 !== C.get("normalRangeMin") && C.get("drawNormalOnTop") && this.drawNormalRange(f, e, F, E, c), v = s.length, B = 0; v > B; B++)D.drawShape(s[B], C.get("lineColor"), void 0, C.get("lineWidth")).append();
                if (H && C.get("valueSpots"))for (w = C.get("valueSpots"), void 0 === w.get && (w = new p(w)), B = 0; r > B; B++)y = w.get(A[B]), y && D.drawCircle(f + Math.round((z[B] - this.minx) * (E / b)), e + Math.round(F - F * ((A[B] - this.miny) / c)), H, void 0, y).append();
                H && C.get("spotColor") && null !== A[d] && D.drawCircle(f + Math.round((z[z.length - 1] - this.minx) * (E / b)), e + Math.round(F - F * ((A[d] - this.miny) / c)), H, void 0, C.get("spotColor")).append(), this.maxy !== this.minyorg && (H && C.get("minSpotColor") && (j = z[a.inArray(this.minyorg, A)], D.drawCircle(f + Math.round((j - this.minx) * (E / b)), e + Math.round(F - F * ((this.minyorg - this.miny) / c)), H, void 0, C.get("minSpotColor")).append()), H && C.get("maxSpotColor") && (j = z[a.inArray(this.maxyorg, A)], D.drawCircle(f + Math.round((j - this.minx) * (E / b)), e + Math.round(F - F * ((this.maxyorg - this.miny) / c)), H, void 0, C.get("maxSpotColor")).append())), this.lastShapeId = D.getLastShapeId(), this.canvasTop = e, D.render()
            }
        }
    }), a.fn.sparkline.bar = u = c(a.fn.sparkline._base, s, {
        type: "bar", init: function (b, c, d, f, j) {
            var k, l, m, n, o, q, r, s, t, v, w, x, y, z, A, B, C, D, E, F, G, H, I = parseInt(d.get("barWidth"), 10), J = parseInt(d.get("barSpacing"), 10), K = d.get("chartRangeMin"), L = d.get("chartRangeMax"), M = d.get("chartRangeClip"), N = 1 / 0, O = -1 / 0;
            for (u._super.init.call(this, b, c, d, f, j), q = 0, r = c.length; r > q; q++)F = c[q], k = "string" == typeof F && F.indexOf(":") > -1, (k || a.isArray(F)) && (A = !0, k && (F = c[q] = h(F.split(":"))), F = i(F, null), l = Math.min.apply(Math, F), m = Math.max.apply(Math, F), N > l && (N = l), m > O && (O = m));
            this.stacked = A, this.regionShapes = {}, this.barWidth = I, this.barSpacing = J, this.totalBarWidth = I + J, this.width = f = c.length * I + (c.length - 1) * J, this.initTarget(), M && (y = void 0 === K ? -1 / 0 : K, z = void 0 === L ? 1 / 0 : L), o = [], n = A ? [] : o;
            var P = [], Q = [];
            for (q = 0, r = c.length; r > q; q++)if (A)for (B = c[q], c[q] = E = [], P[q] = 0, n[q] = Q[q] = 0, C = 0, D = B.length; D > C; C++)F = E[C] = M ? e(B[C], y, z) : B[C], null !== F && (F > 0 && (P[q] += F), 0 > N && O > 0 ? 0 > F ? Q[q] += Math.abs(F) : n[q] += F : n[q] += Math.abs(F - (0 > F ? O : N)), o.push(F)); else F = M ? e(c[q], y, z) : c[q], F = c[q] = g(F), null !== F && o.push(F);
            this.max = x = Math.max.apply(Math, o), this.min = w = Math.min.apply(Math, o), this.stackMax = O = A ? Math.max.apply(Math, P) : x, this.stackMin = N = A ? Math.min.apply(Math, o) : w, void 0 !== d.get("chartRangeMin") && (d.get("chartRangeClip") || d.get("chartRangeMin") < w) && (w = d.get("chartRangeMin")), void 0 !== d.get("chartRangeMax") && (d.get("chartRangeClip") || d.get("chartRangeMax") > x) && (x = d.get("chartRangeMax")), this.zeroAxis = t = d.get("zeroAxis", !0), v = 0 >= w && x >= 0 && t ? 0 : 0 == t ? w : w > 0 ? w : x, this.xaxisOffset = v, s = A ? Math.max.apply(Math, n) + Math.max.apply(Math, Q) : x - w, this.canvasHeightEf = t && 0 > w ? this.canvasHeight - 2 : this.canvasHeight - 1, v > w ? (H = A && x >= 0 ? O : x, G = (H - v) / s * this.canvasHeight, G !== Math.ceil(G) && (this.canvasHeightEf -= 2, G = Math.ceil(G))) : G = this.canvasHeight, this.yoffset = G, a.isArray(d.get("colorMap")) ? (this.colorMapByIndex = d.get("colorMap"), this.colorMapByValue = null) : (this.colorMapByIndex = null, this.colorMapByValue = d.get("colorMap"), this.colorMapByValue && void 0 === this.colorMapByValue.get && (this.colorMapByValue = new p(this.colorMapByValue))), this.range = s
        }, getRegion: function (a, b) {
            var c = Math.floor(b / this.totalBarWidth);
            return 0 > c || c >= this.values.length ? void 0 : c
        }, getCurrentRegionFields: function () {
            var a, b, c = this.currentRegion, d = n(this.values[c]), e = [];
            for (b = d.length; b--;)a = d[b], e.push({
                isNull: null === a,
                value: a,
                color: this.calcColor(b, a, c),
                offset: c
            });
            return e
        }, calcColor: function (b, c, d) {
            var e, f, g = this.colorMapByIndex, h = this.colorMapByValue, i = this.options;
            return e = i.get(this.stacked ? "stackedBarColor" : 0 > c ? "negBarColor" : "barColor"), 0 === c && void 0 !== i.get("zeroColor") && (e = i.get("zeroColor")), h && (f = h.get(c)) ? e = f : g && g.length > d && (e = g[d]), a.isArray(e) ? e[b % e.length] : e
        }, renderRegion: function (b, c) {
            var d, e, f, g, h, i, j, l, m, n, o = this.values[b], p = this.options, q = this.xaxisOffset, r = [], s = this.range, t = this.stacked, u = this.target, v = b * this.totalBarWidth, w = this.canvasHeightEf, x = this.yoffset;
            if (o = a.isArray(o) ? o : [o], j = o.length, l = o[0], g = k(null, o), n = k(q, o, !0), g)return p.get("nullColor") ? (f = c ? p.get("nullColor") : this.calcHighlightColor(p.get("nullColor"), p), d = x > 0 ? x - 1 : x, u.drawRect(v, d, this.barWidth - 1, 0, f, f)) : void 0;
            for (h = x, i = 0; j > i; i++) {
                if (l = o[i], t && l === q) {
                    if (!n || m)continue;
                    m = !0
                }
                e = s > 0 ? Math.floor(w * (Math.abs(l - q) / s)) + 1 : 1, q > l || l === q && 0 === x ? (d = h, h += e) : (d = x - e, x -= e), f = this.calcColor(i, l, b), c && (f = this.calcHighlightColor(f, p)), r.push(u.drawRect(v, d, this.barWidth - 1, e - 1, f, f))
            }
            return 1 === r.length ? r[0] : r
        }
    }), a.fn.sparkline.tristate = v = c(a.fn.sparkline._base, s, {
        type: "tristate", init: function (b, c, d, e, f) {
            var g = parseInt(d.get("barWidth"), 10), h = parseInt(d.get("barSpacing"), 10);
            v._super.init.call(this, b, c, d, e, f), this.regionShapes = {}, this.barWidth = g, this.barSpacing = h, this.totalBarWidth = g + h, this.values = a.map(c, Number), this.width = e = c.length * g + (c.length - 1) * h, a.isArray(d.get("colorMap")) ? (this.colorMapByIndex = d.get("colorMap"), this.colorMapByValue = null) : (this.colorMapByIndex = null, this.colorMapByValue = d.get("colorMap"), this.colorMapByValue && void 0 === this.colorMapByValue.get && (this.colorMapByValue = new p(this.colorMapByValue))), this.initTarget()
        }, getRegion: function (a, b) {
            return Math.floor(b / this.totalBarWidth)
        }, getCurrentRegionFields: function () {
            var a = this.currentRegion;
            return {
                isNull: void 0 === this.values[a],
                value: this.values[a],
                color: this.calcColor(this.values[a], a),
                offset: a
            }
        }, calcColor: function (a, b) {
            var c, d, e = this.values, f = this.options, g = this.colorMapByIndex, h = this.colorMapByValue;
            return c = h && (d = h.get(a)) ? d : g && g.length > b ? g[b] : f.get(e[b] < 0 ? "negBarColor" : e[b] > 0 ? "posBarColor" : "zeroBarColor")
        }, renderRegion: function (a, b) {
            var c, d, e, f, g, h, i = this.values, j = this.options, k = this.target;
            return c = k.pixelHeight, e = Math.round(c / 2), f = a * this.totalBarWidth, i[a] < 0 ? (g = e, d = e - 1) : i[a] > 0 ? (g = 0, d = e - 1) : (g = e - 1, d = 2), h = this.calcColor(i[a], a), null !== h ? (b && (h = this.calcHighlightColor(h, j)), k.drawRect(f, g, this.barWidth - 1, d - 1, h, h)) : void 0
        }
    }), a.fn.sparkline.discrete = w = c(a.fn.sparkline._base, s, {
        type: "discrete", init: function (b, c, d, e, f) {
            w._super.init.call(this, b, c, d, e, f), this.regionShapes = {}, this.values = c = a.map(c, Number), this.min = Math.min.apply(Math, c), this.max = Math.max.apply(Math, c), this.range = this.max - this.min, this.width = e = "auto" === d.get("width") ? 2 * c.length : this.width, this.interval = Math.floor(e / c.length), this.itemWidth = e / c.length, void 0 !== d.get("chartRangeMin") && (d.get("chartRangeClip") || d.get("chartRangeMin") < this.min) && (this.min = d.get("chartRangeMin")), void 0 !== d.get("chartRangeMax") && (d.get("chartRangeClip") || d.get("chartRangeMax") > this.max) && (this.max = d.get("chartRangeMax")), this.initTarget(), this.target && (this.lineHeight = "auto" === d.get("lineHeight") ? Math.round(.3 * this.canvasHeight) : d.get("lineHeight"))
        }, getRegion: function (a, b) {
            return Math.floor(b / this.itemWidth)
        }, getCurrentRegionFields: function () {
            var a = this.currentRegion;
            return {isNull: void 0 === this.values[a], value: this.values[a], offset: a}
        }, renderRegion: function (a, b) {
            var c, d, f, g, h = this.values, i = this.options, j = this.min, k = this.max, l = this.range, m = this.interval, n = this.target, o = this.canvasHeight, p = this.lineHeight, q = o - p;
            return d = e(h[a], j, k), g = a * m, c = Math.round(q - q * ((d - j) / l)), f = i.get(i.get("thresholdColor") && d < i.get("thresholdValue") ? "thresholdColor" : "lineColor"), b && (f = this.calcHighlightColor(f, i)), n.drawLine(g, c, g, c + p, f)
        }
    }), a.fn.sparkline.bullet = x = c(a.fn.sparkline._base, {
        type: "bullet", init: function (a, b, c, d, e) {
            var f, g, i;
            x._super.init.call(this, a, b, c, d, e), this.values = b = h(b), i = b.slice(), i[0] = null === i[0] ? i[2] : i[0], i[1] = null === b[1] ? i[2] : i[1], f = Math.min.apply(Math, b), g = Math.max.apply(Math, b), f = void 0 === c.get("base") ? 0 > f ? f : 0 : c.get("base"), this.min = f, this.max = g, this.range = g - f, this.shapes = {}, this.valueShapes = {}, this.regiondata = {}, this.width = d = "auto" === c.get("width") ? "4.0em" : d, this.target = this.$el.simpledraw(d, e, c.get("composite")), b.length || (this.disabled = !0), this.initTarget()
        }, getRegion: function (a, b, c) {
            var d = this.target.getShapeAt(a, b, c);
            return void 0 !== d && void 0 !== this.shapes[d] ? this.shapes[d] : void 0
        }, getCurrentRegionFields: function () {
            var a = this.currentRegion;
            return {fieldkey: a.substr(0, 1), value: this.values[a.substr(1)], region: a}
        }, changeHighlight: function (a) {
            var b, c = this.currentRegion, d = this.valueShapes[c];
            switch (delete this.shapes[d], c.substr(0, 1)) {
                case"r":
                    b = this.renderRange(c.substr(1), a);
                    break;
                case"p":
                    b = this.renderPerformance(a);
                    break;
                case"t":
                    b = this.renderTarget(a)
            }
            this.valueShapes[c] = b.id, this.shapes[b.id] = c, this.target.replaceWithShape(d, b)
        }, renderRange: function (a, b) {
            var c = this.values[a], d = Math.round(this.canvasWidth * ((c - this.min) / this.range)), e = this.options.get("rangeColors")[a - 2];
            return b && (e = this.calcHighlightColor(e, this.options)), this.target.drawRect(0, 0, d - 1, this.canvasHeight - 1, e, e)
        }, renderPerformance: function (a) {
            var b = this.values[1], c = Math.round(this.canvasWidth * ((b - this.min) / this.range)), d = this.options.get("performanceColor");
            return a && (d = this.calcHighlightColor(d, this.options)), this.target.drawRect(0, Math.round(.3 * this.canvasHeight), c - 1, Math.round(.4 * this.canvasHeight) - 1, d, d)
        }, renderTarget: function (a) {
            var b = this.values[0], c = Math.round(this.canvasWidth * ((b - this.min) / this.range) - this.options.get("targetWidth") / 2), d = Math.round(.1 * this.canvasHeight), e = this.canvasHeight - 2 * d, f = this.options.get("targetColor");
            return a && (f = this.calcHighlightColor(f, this.options)), this.target.drawRect(c, d, this.options.get("targetWidth") - 1, e - 1, f, f)
        }, render: function () {
            var a, b, c = this.values.length, d = this.target;
            if (x._super.render.call(this)) {
                for (a = 2; c > a; a++)b = this.renderRange(a).append(), this.shapes[b.id] = "r" + a, this.valueShapes["r" + a] = b.id;
                null !== this.values[1] && (b = this.renderPerformance().append(), this.shapes[b.id] = "p1", this.valueShapes.p1 = b.id), null !== this.values[0] && (b = this.renderTarget().append(), this.shapes[b.id] = "t0", this.valueShapes.t0 = b.id), d.render()
            }
        }
    }), a.fn.sparkline.pie = y = c(a.fn.sparkline._base, {
        type: "pie", init: function (b, c, d, e, f) {
            var g, h = 0;
            if (y._super.init.call(this, b, c, d, e, f), this.shapes = {}, this.valueShapes = {}, this.values = c = a.map(c, Number), "auto" === d.get("width") && (this.width = this.height), c.length > 0)for (g = c.length; g--;)h += c[g];
            this.total = h, this.initTarget(), this.radius = Math.floor(Math.min(this.canvasWidth, this.canvasHeight) / 2)
        }, getRegion: function (a, b, c) {
            var d = this.target.getShapeAt(a, b, c);
            return void 0 !== d && void 0 !== this.shapes[d] ? this.shapes[d] : void 0
        }, getCurrentRegionFields: function () {
            var a = this.currentRegion;
            return {
                isNull: void 0 === this.values[a],
                value: this.values[a],
                percent: this.values[a] / this.total * 100,
                color: this.options.get("sliceColors")[a % this.options.get("sliceColors").length],
                offset: a
            }
        }, changeHighlight: function (a) {
            var b = this.currentRegion, c = this.renderSlice(b, a), d = this.valueShapes[b];
            delete this.shapes[d], this.target.replaceWithShape(d, c), this.valueShapes[b] = c.id, this.shapes[c.id] = b
        }, renderSlice: function (a, b) {
            var c, d, e, f, g, h = this.target, i = this.options, j = this.radius, k = i.get("borderWidth"), l = i.get("offset"), m = 2 * Math.PI, n = this.values, o = this.total, p = l ? 2 * Math.PI * (l / 360) : 0;
            for (f = n.length, e = 0; f > e; e++) {
                if (c = p, d = p, o > 0 && (d = p + m * (n[e] / o)), a === e)return g = i.get("sliceColors")[e % i.get("sliceColors").length], b && (g = this.calcHighlightColor(g, i)), h.drawPieSlice(j, j, j - k, c, d, void 0, g);
                p = d
            }
        }, render: function () {
            var a, b, c = this.target, d = this.values, e = this.options, f = this.radius, g = e.get("borderWidth");
            if (y._super.render.call(this)) {
                for (g && c.drawCircle(f, f, Math.floor(f - g / 2), e.get("borderColor"), void 0, g).append(), b = d.length; b--;)d[b] && (a = this.renderSlice(b).append(), this.valueShapes[b] = a.id, this.shapes[a.id] = b);
                c.render()
            }
        }
    }), a.fn.sparkline.box = z = c(a.fn.sparkline._base, {
        type: "box", init: function (b, c, d, e, f) {
            z._super.init.call(this, b, c, d, e, f), this.values = a.map(c, Number), this.width = "auto" === d.get("width") ? "4.0em" : e, this.initTarget(), this.values.length || (this.disabled = 1)
        }, getRegion: function () {
            return 1
        }, getCurrentRegionFields: function () {
            var a = [{field: "lq", value: this.quartiles[0]}, {field: "med", value: this.quartiles[1]}, {
                field: "uq",
                value: this.quartiles[2]
            }];
            return void 0 !== this.loutlier && a.push({
                field: "lo",
                value: this.loutlier
            }), void 0 !== this.routlier && a.push({
                field: "ro",
                value: this.routlier
            }), void 0 !== this.lwhisker && a.push({
                field: "lw",
                value: this.lwhisker
            }), void 0 !== this.rwhisker && a.push({field: "rw", value: this.rwhisker}), a
        }, render: function () {
            var a, b, c, d, e, g, h, i, j, k, l, m = this.target, n = this.values, o = n.length, p = this.options, q = this.canvasWidth, r = this.canvasHeight, s = void 0 === p.get("chartRangeMin") ? Math.min.apply(Math, n) : p.get("chartRangeMin"), t = void 0 === p.get("chartRangeMax") ? Math.max.apply(Math, n) : p.get("chartRangeMax"), u = 0;
            if (z._super.render.call(this)) {
                if (p.get("raw"))p.get("showOutliers") && n.length > 5 ? (b = n[0], a = n[1], d = n[2], e = n[3], g = n[4], h = n[5], i = n[6]) : (a = n[0], d = n[1], e = n[2], g = n[3], h = n[4]); else if (n.sort(function (a, b) {
                        return a - b
                    }), d = f(n, 1), e = f(n, 2), g = f(n, 3), c = g - d, p.get("showOutliers")) {
                    for (a = h = void 0, j = 0; o > j; j++)void 0 === a && n[j] > d - c * p.get("outlierIQR") && (a = n[j]), n[j] < g + c * p.get("outlierIQR") && (h = n[j]);
                    b = n[0], i = n[o - 1]
                } else a = n[0], h = n[o - 1];
                this.quartiles = [d, e, g], this.lwhisker = a, this.rwhisker = h, this.loutlier = b, this.routlier = i, l = q / (t - s + 1), p.get("showOutliers") && (u = Math.ceil(p.get("spotRadius")), q -= 2 * Math.ceil(p.get("spotRadius")), l = q / (t - s + 1), a > b && m.drawCircle((b - s) * l + u, r / 2, p.get("spotRadius"), p.get("outlierLineColor"), p.get("outlierFillColor")).append(), i > h && m.drawCircle((i - s) * l + u, r / 2, p.get("spotRadius"), p.get("outlierLineColor"), p.get("outlierFillColor")).append()), m.drawRect(Math.round((d - s) * l + u), Math.round(.1 * r), Math.round((g - d) * l), Math.round(.8 * r), p.get("boxLineColor"), p.get("boxFillColor")).append(), m.drawLine(Math.round((a - s) * l + u), Math.round(r / 2), Math.round((d - s) * l + u), Math.round(r / 2), p.get("lineColor")).append(), m.drawLine(Math.round((a - s) * l + u), Math.round(r / 4), Math.round((a - s) * l + u), Math.round(r - r / 4), p.get("whiskerColor")).append(), m.drawLine(Math.round((h - s) * l + u), Math.round(r / 2), Math.round((g - s) * l + u), Math.round(r / 2), p.get("lineColor")).append(), m.drawLine(Math.round((h - s) * l + u), Math.round(r / 4), Math.round((h - s) * l + u), Math.round(r - r / 4), p.get("whiskerColor")).append(), m.drawLine(Math.round((e - s) * l + u), Math.round(.1 * r), Math.round((e - s) * l + u), Math.round(.9 * r), p.get("medianColor")).append(), p.get("target") && (k = Math.ceil(p.get("spotRadius")), m.drawLine(Math.round((p.get("target") - s) * l + u), Math.round(r / 2 - k), Math.round((p.get("target") - s) * l + u), Math.round(r / 2 + k), p.get("targetColor")).append(), m.drawLine(Math.round((p.get("target") - s) * l + u - k), Math.round(r / 2), Math.round((p.get("target") - s) * l + u + k), Math.round(r / 2), p.get("targetColor")).append()), m.render()
            }
        }
    }), function () {
        document.namespaces && !document.namespaces.v ? (a.fn.sparkline.hasVML = !0, document.namespaces.add("v", "urn:schemas-microsoft-com:vml", "#default#VML")) : a.fn.sparkline.hasVML = !1;
        var b = document.createElement("canvas");
        a.fn.sparkline.hasCanvas = !(!b.getContext || !b.getContext("2d"))
    }(), C = c({
        init: function (a, b, c, d) {
            this.target = a, this.id = b, this.type = c, this.args = d
        }, append: function () {
            return this.target.appendShape(this), this
        }
    }), D = c({
        _pxregex: /(\d+)(px)?\s*$/i, init: function (b, c, d) {
            b && (this.width = b, this.height = c, this.target = d, this.lastShapeId = null, d[0] && (d = d[0]), a.data(d, "_jqs_vcanvas", this))
        }, drawLine: function (a, b, c, d, e, f) {
            return this.drawShape([[a, b], [c, d]], e, f)
        }, drawShape: function (a, b, c, d) {
            return this._genShape("Shape", [a, b, c, d])
        }, drawCircle: function (a, b, c, d, e, f) {
            return this._genShape("Circle", [a, b, c, d, e, f])
        }, drawPieSlice: function (a, b, c, d, e, f, g) {
            return this._genShape("PieSlice", [a, b, c, d, e, f, g])
        }, drawRect: function (a, b, c, d, e, f) {
            return this._genShape("Rect", [a, b, c, d, e, f])
        }, getElement: function () {
            return this.canvas
        }, getLastShapeId: function () {
            return this.lastShapeId
        }, reset: function () {
            alert("reset not implemented")
        }, _insert: function (b, c) {
            a(c).html(b)
        }, _calculatePixelDims: function (b, c, d) {
            var e;
            e = this._pxregex.exec(c), this.pixelHeight = e ? e[1] : a(d).height(), e = this._pxregex.exec(b), this.pixelWidth = e ? e[1] : a(d).width()
        }, _genShape: function (a, b) {
            var c = I++;
            return b.unshift(c), new C(this, c, a, b)
        }, appendShape: function () {
            alert("appendShape not implemented")
        }, replaceWithShape: function () {
            alert("replaceWithShape not implemented")
        }, insertAfterShape: function () {
            alert("insertAfterShape not implemented")
        }, removeShapeId: function () {
            alert("removeShapeId not implemented")
        }, getShapeAt: function () {
            alert("getShapeAt not implemented")
        }, render: function () {
            alert("render not implemented")
        }
    }), E = c(D, {
        init: function (b, c, d, e) {
            E._super.init.call(this, b, c, d), this.canvas = document.createElement("canvas"), d[0] && (d = d[0]), a.data(d, "_jqs_vcanvas", this), a(this.canvas).css({
                display: "inline-block",
                width: b,
                height: c,
                verticalAlign: "top"
            }), this._insert(this.canvas, d), this._calculatePixelDims(b, c, this.canvas), this.canvas.width = this.pixelWidth, this.canvas.height = this.pixelHeight, this.interact = e, this.shapes = {}, this.shapeseq = [], this.currentTargetShapeId = void 0, a(this.canvas).css({
                width: this.pixelWidth,
                height: this.pixelHeight
            })
        }, _getContext: function (a, b, c) {
            var d = this.canvas.getContext("2d");
            return void 0 !== a && (d.strokeStyle = a), d.lineWidth = void 0 === c ? 1 : c, void 0 !== b && (d.fillStyle = b), d
        }, reset: function () {
            var a = this._getContext();
            a.clearRect(0, 0, this.pixelWidth, this.pixelHeight), this.shapes = {}, this.shapeseq = [], this.currentTargetShapeId = void 0
        }, _drawShape: function (a, b, c, d, e) {
            var f, g, h = this._getContext(c, d, e);
            for (h.beginPath(), h.moveTo(b[0][0] + .5, b[0][1] + .5), f = 1, g = b.length; g > f; f++)h.lineTo(b[f][0] + .5, b[f][1] + .5);
            void 0 !== c && h.stroke(), void 0 !== d && h.fill(), void 0 !== this.targetX && void 0 !== this.targetY && h.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a)
        }, _drawCircle: function (a, b, c, d, e, f, g) {
            var h = this._getContext(e, f, g);
            h.beginPath(), h.arc(b, c, d, 0, 2 * Math.PI, !1), void 0 !== this.targetX && void 0 !== this.targetY && h.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a), void 0 !== e && h.stroke(), void 0 !== f && h.fill()
        }, _drawPieSlice: function (a, b, c, d, e, f, g, h) {
            var i = this._getContext(g, h);
            i.beginPath(), i.moveTo(b, c), i.arc(b, c, d, e, f, !1), i.lineTo(b, c), i.closePath(), void 0 !== g && i.stroke(), h && i.fill(), void 0 !== this.targetX && void 0 !== this.targetY && i.isPointInPath(this.targetX, this.targetY) && (this.currentTargetShapeId = a)
        }, _drawRect: function (a, b, c, d, e, f, g) {
            return this._drawShape(a, [[b, c], [b + d, c], [b + d, c + e], [b, c + e], [b, c]], f, g)
        }, appendShape: function (a) {
            return this.shapes[a.id] = a, this.shapeseq.push(a.id), this.lastShapeId = a.id, a.id
        }, replaceWithShape: function (a, b) {
            var c, d = this.shapeseq;
            for (this.shapes[b.id] = b, c = d.length; c--;)d[c] == a && (d[c] = b.id);
            delete this.shapes[a]
        }, replaceWithShapes: function (a, b) {
            var c, d, e, f = this.shapeseq, g = {};
            for (d = a.length; d--;)g[a[d]] = !0;
            for (d = f.length; d--;)c = f[d], g[c] && (f.splice(d, 1), delete this.shapes[c], e = d);
            for (d = b.length; d--;)f.splice(e, 0, b[d].id), this.shapes[b[d].id] = b[d]
        }, insertAfterShape: function (a, b) {
            var c, d = this.shapeseq;
            for (c = d.length; c--;)if (d[c] === a)return d.splice(c + 1, 0, b.id), void(this.shapes[b.id] = b)
        }, removeShapeId: function (a) {
            var b, c = this.shapeseq;
            for (b = c.length; b--;)if (c[b] === a) {
                c.splice(b, 1);
                break
            }
            delete this.shapes[a]
        }, getShapeAt: function (a, b, c) {
            return this.targetX = b, this.targetY = c, this.render(), this.currentTargetShapeId
        }, render: function () {
            var a, b, c, d = this.shapeseq, e = this.shapes, f = d.length, g = this._getContext();
            for (g.clearRect(0, 0, this.pixelWidth, this.pixelHeight), c = 0; f > c; c++)a = d[c], b = e[a], this["_draw" + b.type].apply(this, b.args);
            this.interact || (this.shapes = {}, this.shapeseq = [])
        }
    }), F = c(D, {
        init: function (b, c, d) {
            var e;
            F._super.init.call(this, b, c, d), d[0] && (d = d[0]), a.data(d, "_jqs_vcanvas", this), this.canvas = document.createElement("span"), a(this.canvas).css({
                display: "inline-block",
                position: "relative",
                overflow: "hidden",
                width: b,
                height: c,
                margin: "0px",
                padding: "0px",
                verticalAlign: "top"
            }), this._insert(this.canvas, d), this._calculatePixelDims(b, c, this.canvas), this.canvas.width = this.pixelWidth, this.canvas.height = this.pixelHeight, e = '<v:group coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '" style="position:absolute;top:0;left:0;width:' + this.pixelWidth + "px;height=" + this.pixelHeight + 'px;"></v:group>', this.canvas.insertAdjacentHTML("beforeEnd", e), this.group = a(this.canvas).children()[0], this.rendered = !1, this.prerender = ""
        }, _drawShape: function (a, b, c, d, e) {
            var f, g, h, i, j, k, l, m = [];
            for (l = 0, k = b.length; k > l; l++)m[l] = "" + b[l][0] + "," + b[l][1];
            return f = m.splice(0, 1), e = void 0 === e ? 1 : e, g = void 0 === c ? ' stroked="false" ' : ' strokeWeight="' + e + 'px" strokeColor="' + c + '" ', h = void 0 === d ? ' filled="false"' : ' fillColor="' + d + '" filled="true" ', i = m[0] === m[m.length - 1] ? "x " : "", j = '<v:shape coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '"  id="jqsshape' + a + '" ' + g + h + ' style="position:absolute;left:0px;top:0px;height:' + this.pixelHeight + "px;width:" + this.pixelWidth + 'px;padding:0px;margin:0px;"  path="m ' + f + " l " + m.join(", ") + " " + i + 'e"> </v:shape>'
        }, _drawCircle: function (a, b, c, d, e, f, g) {
            var h, i, j;
            return b -= d, c -= d, h = void 0 === e ? ' stroked="false" ' : ' strokeWeight="' + g + 'px" strokeColor="' + e + '" ', i = void 0 === f ? ' filled="false"' : ' fillColor="' + f + '" filled="true" ', j = '<v:oval  id="jqsshape' + a + '" ' + h + i + ' style="position:absolute;top:' + c + "px; left:" + b + "px; width:" + 2 * d + "px; height:" + 2 * d + 'px"></v:oval>'
        }, _drawPieSlice: function (a, b, c, d, e, f, g, h) {
            var i, j, k, l, m, n, o, p;
            if (e === f)return "";
            if (f - e === 2 * Math.PI && (e = 0, f = 2 * Math.PI), j = b + Math.round(Math.cos(e) * d), k = c + Math.round(Math.sin(e) * d), l = b + Math.round(Math.cos(f) * d), m = c + Math.round(Math.sin(f) * d), j === l && k === m) {
                if (f - e < Math.PI)return "";
                j = l = b + d, k = m = c
            }
            return j === l && k === m && f - e < Math.PI ? "" : (i = [b - d, c - d, b + d, c + d, j, k, l, m], n = void 0 === g ? ' stroked="false" ' : ' strokeWeight="1px" strokeColor="' + g + '" ', o = void 0 === h ? ' filled="false"' : ' fillColor="' + h + '" filled="true" ', p = '<v:shape coordorigin="0 0" coordsize="' + this.pixelWidth + " " + this.pixelHeight + '"  id="jqsshape' + a + '" ' + n + o + ' style="position:absolute;left:0px;top:0px;height:' + this.pixelHeight + "px;width:" + this.pixelWidth + 'px;padding:0px;margin:0px;"  path="m ' + b + "," + c + " wa " + i.join(", ") + ' x e"> </v:shape>')
        }, _drawRect: function (a, b, c, d, e, f, g) {
            return this._drawShape(a, [[b, c], [b, c + e], [b + d, c + e], [b + d, c], [b, c]], f, g)
        }, reset: function () {
            this.group.innerHTML = ""
        }, appendShape: function (a) {
            var b = this["_draw" + a.type].apply(this, a.args);
            return this.rendered ? this.group.insertAdjacentHTML("beforeEnd", b) : this.prerender += b, this.lastShapeId = a.id, a.id
        }, replaceWithShape: function (b, c) {
            var d = a("#jqsshape" + b), e = this["_draw" + c.type].apply(this, c.args);
            d[0].outerHTML = e
        }, replaceWithShapes: function (b, c) {
            var d, e = a("#jqsshape" + b[0]), f = "", g = c.length;
            for (d = 0; g > d; d++)f += this["_draw" + c[d].type].apply(this, c[d].args);
            for (e[0].outerHTML = f, d = 1; d < b.length; d++)a("#jqsshape" + b[d]).remove()
        }, insertAfterShape: function (b, c) {
            var d = a("#jqsshape" + b), e = this["_draw" + c.type].apply(this, c.args);
            d[0].insertAdjacentHTML("afterEnd", e)
        }, removeShapeId: function (b) {
            var c = a("#jqsshape" + b);
            this.group.removeChild(c[0])
        }, getShapeAt: function (a) {
            var b = a.id.substr(8);
            return b
        }, render: function () {
            this.rendered || (this.group.innerHTML = this.prerender, this.rendered = !0)
        }
    })
}), function () {
    !function (a) {
        return a.easyPieChart = function (b, c) {
            var d, e, f, g, h, i, j, k = this;
            return this.el = b, this.$el = a(b), this.$el.data("easyPieChart", this), this.init = function () {
                var b;
                return k.options = a.extend({}, a.easyPieChart.defaultOptions, c), b = parseInt(k.$el.data("percent"), 10), k.percentage = 0, k.canvas = a("<canvas width='" + k.options.size + "' height='" + k.options.size + "'></canvas>").get(0), k.$el.append(k.canvas), "undefined" != typeof G_vmlCanvasManager && null !== G_vmlCanvasManager && G_vmlCanvasManager.initElement(k.canvas), k.ctx = k.canvas.getContext("2d"), window.devicePixelRatio > 1.5 && (a(k.canvas).css({
                    width: k.options.size,
                    height: k.options.size
                }), k.canvas.width *= 2, k.canvas.height *= 2, k.ctx.scale(2, 2)), k.ctx.translate(k.options.size / 2, k.options.size / 2), k.$el.addClass("easyPieChart"), k.$el.css({
                    width: k.options.size,
                    height: k.options.size,
                    lineHeight: "" + k.options.size + "px"
                }), k.update(b), k
            }, this.update = function (a) {
                return k.options.animate === !1 ? f(a) : e(k.percentage, a)
            }, i = function () {
                var a, b, c;
                for (k.ctx.fillStyle = k.options.scaleColor, k.ctx.lineWidth = 1, c = [], a = b = 0; 24 >= b; a = ++b)c.push(d(a));
                return c
            }, d = function (a) {
                var b;
                return b = a % 6 === 0 ? 0 : .017 * k.options.size, k.ctx.save(), k.ctx.rotate(a * Math.PI / 12), k.ctx.fillRect(k.options.size / 2 - b, 0, .05 * -k.options.size + b, 1), k.ctx.restore()
            }, j = function () {
                var a;
                return a = k.options.size / 2 - k.options.lineWidth / 2, k.options.scaleColor !== !1 && (a -= .08 * k.options.size), k.ctx.beginPath(), k.ctx.arc(0, 0, a, 0, 2 * Math.PI, !0), k.ctx.closePath(), k.ctx.strokeStyle = k.options.trackColor, k.ctx.lineWidth = k.options.lineWidth, k.ctx.stroke()
            }, h = function () {
                return k.options.scaleColor !== !1 && i(), k.options.trackColor !== !1 ? j() : void 0
            }, f = function (b) {
                var c;
                return h(), k.ctx.strokeStyle = a.isFunction(k.options.barColor) ? k.options.barColor(b) : k.options.barColor, k.ctx.lineCap = k.options.lineCap, k.ctx.lineWidth = k.options.lineWidth, c = k.options.size / 2 - k.options.lineWidth / 2, k.options.scaleColor !== !1 && (c -= .08 * k.options.size), k.ctx.save(), k.ctx.rotate(-Math.PI / 2), k.ctx.beginPath(), k.ctx.arc(0, 0, c, 0, 2 * Math.PI * b / 100, !1), k.ctx.stroke(), k.ctx.restore()
            }, e = function (a, b) {
                var c, d, e;
                return d = 30, e = d * k.options.animate / 1e3, c = 0, k.options.onStart.call(k), k.percentage = b, k.animation && (clearInterval(k.animation), k.animation = !1), k.animation = setInterval(function () {
                    return k.ctx.clearRect(-k.options.size / 2, -k.options.size / 2, k.options.size, k.options.size), h.call(k), f.call(k, [g(c, a, b - a, e)]), c++, c / e > 1 ? (clearInterval(k.animation), k.animation = !1, k.options.onStop.call(k)) : void 0
                }, 1e3 / d)
            }, g = function (a, b, c, d) {
                var e, f;
                return e = function (a) {
                    return Math.pow(a, 2)
                }, f = function (a) {
                    return 1 > a ? e(a) : 2 - e(a / 2 * -2 + 2)
                }, a /= d / 2, c / 2 * f(a) + b
            }, this.init()
        }, a.easyPieChart.defaultOptions = {
            barColor: "#ef1e25",
            trackColor: "#f2f2f2",
            scaleColor: "#dfe0e0",
            lineCap: "round",
            size: 110,
            lineWidth: 3,
            animate: !1,
            onStart: a.noop,
            onStop: a.noop
        }, void(a.fn.easyPieChart = function (b) {
            return a.each(this, function (c, d) {
                var e;
                return e = a(d), e.data("easyPieChart") ? void 0 : e.data("easyPieChart", new a.easyPieChart(d, b))
            })
        })
    }(jQuery)
}.call(this), function (a) {
    function b(a, b, e) {
        var f = a[0], g = /er/.test(e) ? _indeterminate : /bl/.test(e) ? o : m, h = e == _update ? {
            checked: f[m],
            disabled: f[o],
            indeterminate: "true" == a.attr(_indeterminate) || "false" == a.attr(_determinate)
        } : f[g];
        if (/^(ch|di|in)/.test(e) && !h)c(a, g); else if (/^(un|en|de)/.test(e) && h)d(a, g); else if (e == _update)for (var i in h)h[i] ? c(a, i, !0) : d(a, i, !0); else b && "toggle" != e || (b || a[_callback]("ifClicked"), h ? f[_type] !== l && d(a, g) : c(a, g))
    }

    function c(b, c, e) {
        var k = b[0], p = b.parent(), q = c == m, r = c == _indeterminate, s = c == o, t = r ? _determinate : q ? n : "enabled", u = f(b, t + g(k[_type])), v = f(b, c + g(k[_type]));
        if (k[c] !== !0) {
            if (!e && c == m && k[_type] == l && k.name) {
                var w = b.closest("form"), x = 'input[name="' + k.name + '"]';
                x = w.length ? w.find(x) : a(x), x.each(function () {
                    this !== k && a(this).data(i) && d(a(this), c)
                })
            }
            r ? (k[c] = !0, k[m] && d(b, m, "force")) : (e || (k[c] = !0), q && k[_indeterminate] && d(b, _indeterminate, !1)), h(b, q, c, e)
        }
        k[o] && f(b, _cursor, !0) && p.find("." + j).css(_cursor, "default"), p[_add](v || f(b, c) || ""), p.attr("role") && !r && p.attr("aria-" + (s ? o : m), "true"), p[_remove](u || f(b, t) || "")
    }

    function d(a, b, c) {
        var d = a[0], e = a.parent(), i = b == m, k = b == _indeterminate, l = b == o, p = k ? _determinate : i ? n : "enabled", q = f(a, p + g(d[_type])), r = f(a, b + g(d[_type]));
        d[b] !== !1 && ((k || !c || "force" == c) && (d[b] = !1), h(a, i, p, c)), !d[o] && f(a, _cursor, !0) && e.find("." + j).css(_cursor, "pointer"), e[_remove](r || f(a, b) || ""), e.attr("role") && !k && e.attr("aria-" + (l ? o : m), "false"), e[_add](q || f(a, p) || "")
    }

    function e(b, c) {
        b.data(i) && (b.parent().html(b.attr("style", b.data(i).s || "")), c && b[_callback](c), b.off(".i").unwrap(), a(_label + '[for="' + b[0].id + '"]').add(b.closest(_label)).off(".i"))
    }

    function f(a, b, c) {
        return a.data(i) ? a.data(i).o[b + (c ? "" : "Class")] : void 0
    }

    function g(a) {
        return a.charAt(0).toUpperCase() + a.slice(1)
    }

    function h(a, b, c, d) {
        d || (b && a[_callback]("ifToggled"), a[_callback]("ifChanged")[_callback]("if" + g(c)))
    }

    var i = "iCheck", j = i + "-helper", k = "checkbox", l = "radio", m = "checked", n = "un" + m, o = "disabled";
    _determinate = "determinate", _indeterminate = "in" + _determinate, _update = "update", _type = "type", _click = "click", _touch = "touchbegin.i touchend.i", _add = "addClass", _remove = "removeClass", _callback = "trigger", _label = "label", _cursor = "cursor", _mobile = /ipad|iphone|ipod|android|blackberry|windows phone|opera mini|silk/i.test(navigator.userAgent), a.fn[i] = function (f, g) {
        var h = 'input[type="' + k + '"], input[type="' + l + '"]', n = a(), p = function (b) {
            b.each(function () {
                var b = a(this);
                n = n.add(b.is(h) ? b : b.find(h))
            })
        };
        if (/^(check|uncheck|toggle|indeterminate|determinate|disable|enable|update|destroy)$/i.test(f))return f = f.toLowerCase(), p(this), n.each(function () {
            var c = a(this);
            "destroy" == f ? e(c, "ifDestroyed") : b(c, !0, f), a.isFunction(g) && g()
        });
        if ("object" != typeof f && f)return this;
        var q = a.extend({
            checkedClass: m,
            disabledClass: o,
            indeterminateClass: _indeterminate,
            labelHover: !0
        }, f), r = q.handle, s = q.hoverClass || "hover", t = q.focusClass || "focus", u = q.activeClass || "active", v = !!q.labelHover, w = q.labelHoverClass || "hover", x = 0 | ("" + q.increaseArea).replace("%", "");
        return (r == k || r == l) && (h = 'input[type="' + r + '"]'), -50 > x && (x = -50), p(this), n.each(function () {
            var f = a(this);
            e(f);
            var g, h = this, n = h.id, p = -x + "%", r = 100 + 2 * x + "%", y = {
                position: "absolute",
                top: p,
                left: p,
                display: "block",
                width: r,
                height: r,
                margin: 0,
                padding: 0,
                background: "#fff",
                border: 0,
                opacity: 0
            }, z = _mobile ? {position: "absolute", visibility: "hidden"} : x ? y : {
                position: "absolute",
                opacity: 0
            }, A = h[_type] == k ? q.checkboxClass || "i" + k : q.radioClass || "i" + l, B = a(_label + '[for="' + n + '"]').add(f.closest(_label)), C = !!q.aria, D = i + "-" + Math.random().toString(36).substr(2, 6), E = '<div class="' + A + '" ' + (C ? 'role="' + h[_type] + '" ' : "");
            C && B.each(function () {
                E += 'aria-labelledby="', this.id ? E += this.id : (this.id = D, E += D), E += '"'
            }), E = f.wrap(E + "/>")[_callback]("ifCreated").parent().append(q.insert), g = a('<ins class="' + j + '"/>').css(y).appendTo(E), f.data(i, {
                o: q,
                s: f.attr("style")
            }).css(z), !!q.inheritClass && E[_add](h.className || ""), !!q.inheritID && n && E.attr("id", i + "-" + n), "static" == E.css("position") && E.css("position", "relative"), b(f, !0, _update), B.length && B.on(_click + ".i mouseover.i mouseout.i " + _touch, function (c) {
                var d = c[_type], e = a(this);
                if (!h[o]) {
                    if (d == _click) {
                        if (a(c.target).is("a"))return;
                        b(f, !1, !0)
                    } else v && (/ut|nd/.test(d) ? (E[_remove](s), e[_remove](w)) : (E[_add](s), e[_add](w)));
                    if (!_mobile)return !1;
                    c.stopPropagation()
                }
            }), f.on(_click + ".i focus.i blur.i keyup.i keydown.i keypress.i", function (a) {
                var b = a[_type], e = a.keyCode;
                return b == _click ? !1 : "keydown" == b && 32 == e ? (h[_type] == l && h[m] || (h[m] ? d(f, m) : c(f, m)), !1) : void("keyup" == b && h[_type] == l ? !h[m] && c(f, m) : /us|ur/.test(b) && E["blur" == b ? _remove : _add](t))
            }), g.on(_click + " mousedown mouseup mouseover mouseout " + _touch, function (a) {
                var c = a[_type], d = /wn|up/.test(c) ? u : s;
                if (!h[o]) {
                    if (c == _click ? b(f, !1, !0) : (/wn|er|in/.test(c) ? E[_add](d) : E[_remove](d + " " + u), B.length && v && d == s && B[/ut|nd/.test(c) ? _remove : _add](w)), !_mobile)return !1;
                    a.stopPropagation()
                }
            })
        })
    }
}(window.jQuery || window.Zepto),function (a) {
    var b = new Array, c = new Array;
    a.fn.doAutosize = function (b) {
        var c = a(this).data("minwidth"), d = a(this).data("maxwidth"), e = "", f = a(this), g = a("#" + a(this).data("tester_id"));
        if (e !== (e = f.val())) {
            var h = e.replace(/&/g, "&").replace(/\s/g, " ").replace(/</g, "<").replace(/>/g, ">");
            g.html(h);
            var i = g.width(), j = i + b.comfortZone >= c ? i + b.comfortZone : c, k = f.width(), l = k > j && j >= c || j > c && d > j;
            l && f.width(j)
        }
    }, a.fn.resetAutosize = function (b) {
        var c = a(this).data("minwidth") || b.minInputWidth || a(this).width(), d = a(this).data("maxwidth") || b.maxInputWidth || a(this).closest(".tagsinput").width() - b.inputPadding, e = a(this), f = a("<tester/>").css({
            position: "absolute",
            top: -9999,
            left: -9999,
            width: "auto",
            fontSize: e.css("fontSize"),
            fontFamily: e.css("fontFamily"),
            fontWeight: e.css("fontWeight"),
            letterSpacing: e.css("letterSpacing"),
            whiteSpace: "nowrap"
        }), g = a(this).attr("id") + "_autosize_tester";
        !a("#" + g).length > 0 && (f.attr("id", g), f.appendTo("body")), e.data("minwidth", c), e.data("maxwidth", d), e.data("tester_id", g), e.css("width", c)
    }, a.fn.addTag = function (d, e) {
        return e = jQuery.extend({focus: !1, callback: !0}, e), this.each(function () {
            var f = a(this).attr("id"), g = a(this).val().split(b[f]);
            if ("" == g[0] && (g = new Array), d = jQuery.trim(d), e.unique) {
                var h = a(g).tagExist(d);
                1 == h && a("#" + f + "_tag").addClass("not_valid")
            } else var h = !1;
            if ("" != d && 1 != h) {
                if (a("<span>").addClass("tag").append(a("<span>").text(d).append("  "), a("<a>", {
                        href: "#",
                        title: "Removing tag",
                        text: "x"
                    }).click(function () {
                        return a("#" + f).removeTag(escape(d))
                    })).insertBefore("#" + f + "_addTag"), g.push(d), a("#" + f + "_tag").val(""), e.focus ? a("#" + f + "_tag").focus() : a("#" + f + "_tag").blur(), a.fn.tagsInput.updateTagsField(this, g), e.callback && c[f] && c[f].onAddTag) {
                    var i = c[f].onAddTag;
                    i.call(this, d)
                }
                if (c[f] && c[f].onChange) {
                    var j = g.length, i = c[f].onChange;
                    i.call(this, a(this), g[j - 1])
                }
            }
        }), !1
    }, a.fn.removeTag = function (d) {
        return d = unescape(d), this.each(function () {
            var e = a(this).attr("id"), f = a(this).val().split(b[e]);
            for (a("#" + e + "_tagsinput .tag").remove(), str = "", i = 0; i < f.length; i++)f[i] != d && (str = str + b[e] + f[i]);
            if (a.fn.tagsInput.importTags(this, str), c[e] && c[e].onRemoveTag) {
                var g = c[e].onRemoveTag;
                g.call(this, d)
            }
        }), !1
    }, a.fn.tagExist = function (b) {
        return jQuery.inArray(b, a(this)) >= 0
    }, a.fn.importTags = function (b) {
        id = a(this).attr("id"), a("#" + id + "_tagsinput .tag").remove(), a.fn.tagsInput.importTags(this, b)
    }, a.fn.tagsInput = function (d) {
        var e = jQuery.extend({
            interactive: !0,
            defaultText: "add a tag",
            minChars: 0,
            width: "300px",
            height: "100px",
            autocomplete: {selectFirst: !1},
            hide: !0,
            delimiter: ",",
            unique: !0,
            removeWithBackspace: !0,
            placeholderColor: "#666666",
            autosize: !0,
            comfortZone: 20,
            inputPadding: 12
        }, d);
        return this.each(function () {
            e.hide && a(this).hide();
            var d = a(this).attr("id");
            (!d || b[a(this).attr("id")]) && (d = a(this).attr("id", "tags" + (new Date).getTime()).attr("id"));
            var f = jQuery.extend({
                pid: d,
                real_input: "#" + d,
                holder: "#" + d + "_tagsinput",
                input_wrapper: "#" + d + "_addTag",
                fake_input: "#" + d + "_tag"
            }, e);
            b[d] = f.delimiter, (e.onAddTag || e.onRemoveTag || e.onChange) && (c[d] = new Array, c[d].onAddTag = e.onAddTag, c[d].onRemoveTag = e.onRemoveTag, c[d].onChange = e.onChange);
            var g = '<div id="' + d + '_tagsinput" class="tagsinput"><div id="' + d + '_addTag">';
            if (e.interactive && (g = g + '<input id="' + d + '_tag" value="" data-default="' + e.defaultText + '" />'), g += '</div><div class="tags_clear"></div></div>', a(g).insertAfter(this), a(f.holder).css("width", e.width), a(f.holder).css("height", e.height), "" != a(f.real_input).val() && a.fn.tagsInput.importTags(a(f.real_input), a(f.real_input).val()), e.interactive) {
                if (a(f.fake_input).val(a(f.fake_input).attr("data-default")), a(f.fake_input).css("color", e.placeholderColor), a(f.fake_input).resetAutosize(e), a(f.holder).bind("click", f, function (b) {
                        a(b.data.fake_input).focus()
                    }), a(f.fake_input).bind("focus", f, function (b) {
                        a(b.data.fake_input).val() == a(b.data.fake_input).attr("data-default") && a(b.data.fake_input).val(""), a(b.data.fake_input).css("color", "#000000")
                    }), void 0 != e.autocomplete_url) {
                    autocomplete_options = {source: e.autocomplete_url};
                    for (attrname in e.autocomplete)autocomplete_options[attrname] = e.autocomplete[attrname];
                    void 0 !== jQuery.Autocompleter ? (a(f.fake_input).autocomplete(e.autocomplete_url, e.autocomplete), a(f.fake_input).bind("result", f, function (b, c) {
                        c && a("#" + d).addTag(c[0] + "", {focus: !0, unique: e.unique})
                    })) : void 0 !== jQuery.ui.autocomplete && (a(f.fake_input).autocomplete(autocomplete_options), a(f.fake_input).bind("autocompleteselect", f, function (b, c) {
                        return a(b.data.real_input).addTag(c.item.value, {focus: !0, unique: e.unique}), !1
                    }))
                } else a(f.fake_input).bind("blur", f, function (b) {
                    var c = a(this).attr("data-default");
                    return "" != a(b.data.fake_input).val() && a(b.data.fake_input).val() != c ? b.data.minChars <= a(b.data.fake_input).val().length && (!b.data.maxChars || b.data.maxChars >= a(b.data.fake_input).val().length) && a(b.data.real_input).addTag(a(b.data.fake_input).val(), {
                        focus: !0,
                        unique: e.unique
                    }) : (a(b.data.fake_input).val(a(b.data.fake_input).attr("data-default")), a(b.data.fake_input).css("color", e.placeholderColor)), !1
                });
                a(f.fake_input).bind("keypress", f, function (b) {
                    return b.which == b.data.delimiter.charCodeAt(0) || 13 == b.which ? (b.preventDefault(), b.data.minChars <= a(b.data.fake_input).val().length && (!b.data.maxChars || b.data.maxChars >= a(b.data.fake_input).val().length) && a(b.data.real_input).addTag(a(b.data.fake_input).val(), {
                        focus: !0,
                        unique: e.unique
                    }), a(b.data.fake_input).resetAutosize(e), !1) : void(b.data.autosize && a(b.data.fake_input).doAutosize(e))
                }), f.removeWithBackspace && a(f.fake_input).bind("keydown", function (b) {
                    if (8 == b.keyCode && "" == a(this).val()) {
                        b.preventDefault();
                        var c = a(this).closest(".tagsinput").find(".tag:last").text(), d = a(this).attr("id").replace(/_tag$/, "");
                        c = c.replace(/[\s]+x$/, ""), a("#" + d).removeTag(escape(c)), a(this).trigger("focus")
                    }
                }), a(f.fake_input).blur(), f.unique && a(f.fake_input).keydown(function (b) {
                    (8 == b.keyCode || String.fromCharCode(b.which).match(/\w+|[áéíóúÁÉÍÓÚñÑ,/]+/)) && a(this).removeClass("not_valid")
                })
            }
        }), this
    }, a.fn.tagsInput.updateTagsField = function (c, d) {
        var e = a(c).attr("id");
        a(c).val(d.join(b[e]))
    }, a.fn.tagsInput.importTags = function (d, e) {
        a(d).val("");
        var f = a(d).attr("id"), g = e.split(b[f]);
        for (i = 0; i < g.length; i++)a(d).addTag(g[i], {focus: !1, callback: !1});
        if (c[f] && c[f].onChange) {
            var h = c[f].onChange;
            h.call(d, d, g[i])
        }
    }
}(jQuery)