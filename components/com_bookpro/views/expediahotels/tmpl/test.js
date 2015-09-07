$.widget("custom.categoryAutocomplete", $.ui.autocomplete, {_renderMenu: function (e, d) {
    var h = this, f = "", i = this.options;
    $.each(d, function (d, b) {
        if (b.category != f) {
            var c = "H";
            switch (b.categoryLabel) {
                case "cities":
                    c = "H";
                    break;
                case "airports":
                    c = "N";
                    break;
                case "landmarks":
                    c = "M";
                    break;
                case "hotels":
                    c = "K"
            }
            var g = "ui-autocomplete-category";
            0 === d && (g = "ui-autocomplete-category-first");
            var a = i.template, a = a.replace(/\{categoryClass\}/g, g), a = a.replace(/\{categoryIcon\}/g, c), a = a.replace(/\{categoryName\}/g, b.category);
            e.append(a);
            f = b.category
        }
        h._renderItem(e, b)
    })
}});
Function.prototype.method = function (a, b) {
    if (this.prototype[a])throw{name: "Method Overwrite Error", message: "There is already a property named '" + a + "' on " + this};
    this.prototype[a] = b;
    return this
};
Date.method("addDays", function (a) {
    var b = new Date(this.getTime());
    b.setDate(this.getDate() + a);
    return b
});
Date.method("format", function (a) {
    return $.datepicker.formatDate(a || "mm/dd/yy", this)
});
var datepickerUtils = {checkoutChanged: !1, isInRange: function (a) {
    var b = !0;
    try {
        var c = $.datepicker.parseDate(a.datepicker("option", "dateFormat"), a.val()), d = $.datepicker._getInst(a[0]), b = $.datepicker._isInRange(d, c)
    } catch (e) {
        b = !1
    }
    return b
}, getCheckoutLimits: function (a, b) {
    var c = a ? new Date(a) : new Date, d = new Date(c.getTime());
    c.setDate(c.getDate() + b.minNights);
    d.setDate(d.getDate() + b.maxNights);
    return{min: c, max: d}
}, updateDatesHandler: function (a, b, c) {
    return function (d) {
        var d = this.value || d, e;
        try {
            e = $.datepicker.parseDate(a.datepicker("option",
                "dateFormat"), d)
        } catch (g) {
        }
        if (e) {
            var f = b.datepicker("getDate"), d = null;
            if (!datepickerUtils.checkoutChanged || f <= e)d = new Date(e), d.setDate(d.getDate() + c.initialNights);
            e = datepickerUtils.getCheckoutLimits(e, c);
            b.datepicker("option", {minDate: e.min, maxDate: e.max});
            d && b.datepicker("setDate", d)
        }
        c.both.onChange.call(a);
        c.both.onChange.call(b)
    }
}};
$.widget("custom.synchronizedDatepicker", {_init: function () {
    var a = this.options, b = $(this.element[0]), c = $(this.options.other);
    b.datepicker(a.both);
    c.datepicker(a.both);
    datepickerUtils.checkoutChanged = !1;
    b.datepicker("option", {minDate: a.minDate, maxDate: a.maxDate, onSelect: datepickerUtils.updateDatesHandler(b, c, a)}).change(datepickerUtils.updateDatesHandler(b, c, a));
    var d = datepickerUtils.getCheckoutLimits(b.datepicker("getDate"), a);
    c.datepicker("option", {minDate: d.min, maxDate: d.max, onSelect: function () {
        datepickerUtils.checkoutChanged = !0;
        a.both.onChange.call(b);
        a.both.onChange.call(c);
        c.change()
    }});
    c.change(function () {
        b.val() || (b.datepicker("setDate", c.datepicker("getDate").addDays(0 - a.initialNights)), b.change())
    })
}});
var urlParameters = {objectFromQueryString: function (b) {
    if ("?" === b.slice(0, 1) || "#" === b.slice(0, 1))b = b.slice(1);
    var a = window.location.pathname;
    a === b.slice(0, a.length) && (b = b.slice(a.length));
    for (var b = b.split("&"), a = {}, c = 0; c < b.length; c++) {
        var d = b[c].split("=");
        if (2 <= d.length) {
            var e = decodeURIComponent(d[1]);
            a[d[0]] && $.isArray(a[d[0]]) ? a[d[0]].push(e) : a[d[0]] = a[d[0]] ? [a[d[0]], e] : e
        }
    }
    return a
}, fuzzySearch: function (b, a) {
    if ("*" === a.slice(-1)) {
        var a = a.slice(0, -1), c = {};
        $.each(b, function (b, e) {
            a === b.slice(0, a.length) &&
            (c[b] = e)
        });
        return c
    }
    return b[a]
}, queryStringFromObject: function (b) {
    var a = [], c;
    for (c in b)if ($.isArray(b[c]))for (var d = 0; d < b[c].length; d++)a.push(c + "=" + encodeURIComponent(b[c][d])); else b[c] && a.push(c + "=" + encodeURIComponent(b[c]));
    return"?" + a.join("&")
}, parseValue: function (b, a) {
    var c = urlParameters.objectFromQueryString(b);
    return urlParameters.fuzzySearch(c, a)
}, updateQueryString: function (b, a) {
    var c = urlParameters.objectFromQueryString(b), d;
    for (d in a)null === a[d] ? delete c[d] : a[d] && (c[d] = a[d]);
    return urlParameters.queryStringFromObject(c)
}};
function doPopup(e, d, c) {
    if (null === d || "" === d)d = "widgetPopupWin";
    popwindow = c ? window.open(e, d, "width=600,height=600,scrollbars=yes") : window.open(e, d);
    window.focus && popwindow.focus()
}
var datepickerUtils = datepickerUtils || {};
datepickerUtils.synchronizeDates = function (e, d) {
    var c = e.datepicker("getDate");
    e.is(":disabled") && d.val("");
    c && d.val(c.getMonth() + 1 + "/" + c.getDate() + "/" + c.getFullYear())
};
$(document).ready(function () {
    var e = $("html").attr("lang") || "en", d = $("#disambig"), c = $("#SearchBox_DestinationName"), h = $("#SearchBox_TargetId"), l = $("#DestinationField label"), i = $("#DestinationField input"), j = $("#SearchBoxForm"), f = $("#checkin"), g = $("#checkout"), u = $("#standardCheckin"), v = $("#standardCheckout");
    datepickerUtils.datepickerOptions = {other: g, minNights: 1, maxNights: 28, initialNights: 2, minDate: "-1d", maxDate: "+500d", both: {buttonText: "L", changeMonth: !0, numberOfMonths: 2, showOn: "both", prevText: "C",
        nextText: "D", onChange: function () {
            datepickerUtils.synchronizeDates(this, this.siblings('input[id^="standard"]'))
        }}};
    if (window.configuredDateFormat) {
        var m = configuredDateFormat;
        $.each({"%e": "d", "%d": "dd", "%j": "oo", "%a": "D", "%A": "DD", "%m": "mm", "%b": "M", "%B": "MM", "%y": "y", "%Y": "yy", "%D": "mm/dd/y"}, function (a, b) {
            m = m.replace(a, b)
        });
        datepickerUtils.datepickerOptions.both.dateFormat = m
    }
    $.datepicker.setDefaults($.datepicker.regional[e]);
    f.synchronizedDatepicker(datepickerUtils.datepickerOptions);
    f.change(function () {
        datepickerUtils.synchronizeDates(f,
            u)
    });
    g.change(function () {
        datepickerUtils.synchronizeDates(g, v)
    });
    $.each([f, g], function () {
        this.val() || this.removeAttr("disabled")
    });
    j.bind("inputAdded",function () {
        var a = function (a) {
            if (13 === a.which)return!1
        };
        j.find(':input[type!="hidden"]').keyup(a).keydown(a)
    }).trigger("inputAdded");
    $("#lang, #currency").change(function (a) {
        var b = {};
        b[a.target.name] = a.target.value;
        -1 < window.location.pathname.indexOf("/templates/cindex/") ? window.location.pathname = appConfig.landing.baseCachedUrl + $("#currency").val() +
            "/" + $("#lang").val() : window.location.search = urlParameters.updateQueryString(window.location.search, b)
    });
    var e = $("#rooms-and-guests").contents().filter(function () {
        return 8 === this.nodeType
    }), w = 0 < e.length ? e[0].nodeValue : null, s = function (a) {
        var b = w, b = b.replace(/\{roomNumber\}/g, a + 1);
        return b = b.replace(/\{sysRoomNumber\}/g, a)
    }, o = function (a) {
        var a = a || !1, b = $("#rooms-and-guests > li").filter(function () {
            return!$(this).hasClass("add-row")
        });
        a && (b.remove(), b.each(function (a, b) {
            var c = $(s(a));
            $(b).find("select").each(function (a, b) {
                $(c.find("select")[a]).val($(b).val())
            });
            0 === a && c.addClass("first");
            $("#rooms-and-guests .add-row").before(c)
        }), $("#rooms-and-guests .children select").change());
        b = $("#rooms-and-guests > li").filter(function () {
            return!$(this).hasClass("add-row")
        });
        $("#roomsCount").val(b.length);
        a = $("#rooms-and-guests .add-row button");
        a.removeAttr("disabled");
        a.removeClass("disabled");
        8 <= b.length ? (a.attr("disabled", !0), a.addClass("disabled")) : 1 >= b.length && b.find(".remove-row").remove();
        1 < b.length && (a = $('<button class="remove-row">X</button>').click(function (a) {
            a.preventDefault();
            a = $(this).closest("li");
            a.hasClass("first") && a.next().addClass("first");
            a.remove();
            o(!0)
        }), b.filter(":not(:has(.remove-row))").find("> fieldset").append(a));
        j.trigger("inputAdded")
    };
    $("#rooms-and-guests .add-row button").click(function (a) {
        a.preventDefault();
        a = $("#rooms-and-guests > li").filter(function () {
            return!$(this).hasClass("add-row")
        }).length;
        newRoom = $(s(a));
        $("#rooms-and-guests .add-row").before(newRoom);
        newRoom.find(".children select").change();
        o()
    });
    $("#rooms-and-guests").change(function (a) {
        var b =
            a.target;
        if (b.id.match(/rooms\d\.childrenCount/)) {
            a = $(b).val();
            b = $(b).closest("fieldset").find(".children-ages");
            1 > a ? b.hide() : b.show();
            b = b.find("select");
            b.hide().attr("disabled", !0);
            for (var n = 0; n < a; n++)$(b[n]).show().attr("disabled", !1)
        }
    });
    $("#rooms-and-guests .children select").change();
    o();
    if ($("#PopularDestinations") && c) {
        var p = !1;
        $('#PopularDestinations input[name="predefinedDestination"]').change(function (a) {
            var a = $(a.target).val().split("|"), b = a[a.length - 1];
            h.val(a[0] + "|" + a[1]);
            c.val(b);
            p = !0
        });
        c.keydown(function () {
            p && ($('#PopularDestinations input[name="predefinedDestination"]').removeAttr("checked"), p = !1)
        })
    }
    var k, q = !1;
    c.bind("keypress", function () {
        q = !1
    });
    c.bind("categoryautocompleteselect", function () {
        q = !0
    });
    k = {wasAutocompleted: function () {
        return q
    }, hasValue: function () {
        return 0 < c.not(":hidden").length && c.val()
    }, value: function () {
        return c.val()
    }};
    c.change(function () {
        h.val("")
    });
    var t = function () {
        c.keyup(function (a) {
            13 === a.which && $("#SearchBox_Submit").click()
        })
    };
    t();
    var x = appConfig.landing.baseUrl +
        "destination", e = $("#SearchBox_DestinationAutoSuggest").contents().filter(function () {
        return 8 === this.nodeType
    });
    c.categoryAutocomplete({delay: 0, appendTo: "#SearchBox_DestinationAutoSuggest", source: function (a, b) {
        $.ajax({url: x, data: {propertyName: a.term}, dataType: "json", accepts: {json: "application/json;charset=UTF-8"}, success: function (a) {
            b($.map(a.items, function (a) {
                return{label: a.name, value: a.name, id: a.id.replace("|" + a.name, ""), category: a.categoryLocalized, categoryLabel: a.category}
            }))
        }, cache: !1})
    }, minLength: 2,
        select: function (a, b) {
            13 === a.which && (c.unbind("keyup"), setTimeout(t, 100));
            c.val(b.item.name);
            h.val(b.item.id)
        }, template: 0 < e.length ? e[0].nodeValue : null});
    var r = function (a) {
        a && "destinationName"in a && c.val(a.destinationName);
        a && "targetId"in a && h.val(a.targetId);
        j.submit()
    };
    d.dialog({autoOpen: !1, modal: !0, width: d.width()});
    $("#SearchBox_Submit").click(function (a) {
        a.preventDefault();
        if (y()) {
            if (k.hasValue() && !k.wasAutocompleted() && k.value() !== $("#search-box-initial-destination").val()) {
                var a = appConfig.landing.baseUrl +
                    "destination/locByDest?propertyName=" + encodeURIComponent(c.val()), b = function (a) {
                    a = a.split("|");
                    return a[0] + "|" + a[1]
                };
                d.bind("dialogclose", function () {
                    d.remove("ul")
                });
                d.find("button").click(function () {
                    var a = d.find(":radio:checked");
                    if (a.length) {
                        var b = a.val(), a = a.attr("id");
                        r({destinationName: b, targetId: a})
                    }
                    d.dialog("close")
                });
                $.ajax({url: a, dataType: "json"}).done(function (a) {
                    if (1 < a.items.length) {
                        var c = $("<ul/>"), e = d.find("ul");
                        e.hide();
                        for (var f = 0; f < a.items.length; f++) {
                            var g = a.items[f], h = b(g.id);
                            c.append($("<li/>").append($("<label/>",
                                {"for": h}).append($("<input/>", {type: "radio", name: "destination", "class": "radio", id: h, value: g.name})).append(g.name)))
                        }
                        e.replaceWith(c).show();
                        d.dialog("open")
                    } else 1 === a.items.length ? r({targetId: b(a.items[0].id)}) : (l.addClass("errorLabel"), i.addClass("errorField"), i.focus())
                })
            } else r();
            return!0
        }
        $("#SearchBox .finalError").show();
        return!1
    });
    var y = function () {
        $("#SearchBox .finalError").hide();
        var a = !0;
        l.removeClass("errorLabel");
        i.removeClass("errorField");
        0 < c.length && 0 >= c.val().length && (a = !1, l.addClass("errorLabel"),
            i.addClass("errorField"), i.focus());
        $.each([f, g], function () {
            $(this).removeClass("errorField");
            $(this).closest("label").removeClass("errorLabel");
            !$("#dateless").is(":checked") && !datepickerUtils.isInRange(this, datepickerUtils.datepickerOptions) && (a || $(this).focus(), a = !1, $(this).addClass("errorField"), $(this).closest("label").addClass("errorLabel"))
        });
        var b = !1;
        $("#rooms-and-guests > li").filter(function () {
            return!$(this).hasClass("add-row")
        }).each(function () {
            $(this).find(".children-ages select:enabled").each(function () {
                var c =
                    $(this);
                c.removeClass("errorField");
                c.val() || (a || c.focus(), a = !1, c.addClass("errorField"), b = !0)
            })
        });
        $(this).find(".children-ages legend").removeClass("errorLabel");
        b && $(this).find(".children-ages legend").addClass("errorLabel");
        return a
    };
    $("#SubNavChangeSearchLink").click(function (a) {
        a.preventDefault();
        a = $("#SubNavOverlay");
        a.is(":hidden") ? ($(this).find(".smallIcon").html("A"), a.slideDown()) : ($(this).find(".smallIcon").html("D"), a.slideUp())
    });
    $("#SubNavClose").click(function (a) {
        a.preventDefault();
        $("#SubNavChangeSearchLink .smallIcon").html("D");
        $("#SubNavOverlay").slideUp()
    });
    $("a[href*=#]").click(function () {
        1 < $(this).attr("href").length && $("html,body").animate({scrollTop: $($(this).attr("href")).offset().top - 10}, "slow");
        return!1
    });
    $.fn.extend({clickOutside: function (a, b) {
        var c = this;
        $("body").bind("click", function (d) {
            b && -1 < $.inArray(d.target, b) || $.contains(c[0], d.target) || a(d, c)
        });
        return this
    }});
    $(".taReviewCount").click(function () {
        var a = $(this).attr("href");
        window.open(a, "TAReviewCount", "width=600,height=600,scrollbars=yes");
        return!1
    })
});
