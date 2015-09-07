jQuery.jchat = jchatAlias;
function jchatAlias($) {
    var g = jchat_livesite + "index.php?option=com_jchat&format=raw";
    var t = {};
    var s = {};
    var T = "";
    var r = 0;
    var o = 0;
    var J = true;
    var U;
    var X = 2000;
    var K = 2000;
    var F = 10000;
    var emptyResponse = 20;
    var restartInterval = 1;
    var h = 1;
    var R = 0;
    var n = 0;
    var l = 1;
    var vars = null;
    var chatTitle = "";
    var forceBuddylistRefresh = 0;
    var audio = 1;
    var sound = false;
    var avatarUploadEnabled = true;
    var avatarDisabled = false;
    var tabLength = 14;
    var headLength = 30;
    var chatLength = 20;
    var buddyLength = 20;
    var firstCall = true;
    var popupPositions = new Object();
    var wallStartHeight = 0;
    var isContentEditable = null;
    var longname = null;
    var shortname = null;
    var my_username;
    var my_avatar;
    var fromPlaceholder;
    var writeElement = '<div contentEditable="true" class="jchat_textarea"></div>';
    var writeElementWall = '<div contentEditable="true" class="jchat_textarea jchat_textarea_wall"></div>';
    var valFunction = "html";
    var skypeEnabled = true;
    var groupChat = true;
    var chatboxesOpenMode = false;
    var isGuest = true;
    var allowGuestAvatarupload = true;
    var allowGuestFileupload = true;
    var allowGuestSkypeBridge = true;
    $("<div/>").attr("id", "jchat_base").appendTo($("body"));
    function injectFloatingMsg(text, userDetails) {
        if (userDetails) {
            text = sprintf(text, userDetails)
        }
        $("div#jchat_msg").remove();
        $("<div/>").attr("id", "jchat_msg").prependTo("body").css("height", "10px").append('<div id="jchat_msgtext">' + text + "</div>").css("margin-top", 0).animate({height: "50px", "margin-top": "-25px"}, 500, "linear").delay(2500).fadeOut(500, function () {
            $(this).remove()
        })
    }

    function wallTooltip(element, Z) {
        if ($("#jchat_refresh_tooltip").length > 0) {
            $("#jchat_refresh_tooltip .jchat_tooltip_content").html(Z)
        } else {
            $("body").append('<div id="jchat_refresh_tooltip"><div class="jchat_tooltip_content">' + Z + "</div></div>")
        }
        var ab = $(element).offset();
        var Y = $(element).width();
        var mixed = $("#jchat_refresh_tooltip").width();
        $("#jchat_refresh_tooltip").css("left", (ab.left + Y) - mixed + 12).css("display", "block");
        $("#jchat_refresh_tooltip").css("top", (ab.top - parseInt($("#jchat_refresh_tooltip").height()) - 5) - $(window).scrollTop() + "px")
    }

    function triggerGenericTooltip(selector, element, text) {
        if (!text) {
            text = $(element).data("text")
        }
        if ($(selector).length > 0) {
            $(selector + " .jchat_tooltip_content").html(text)
        } else {
            $("body").append('<div id="' + selector.replace("#", "") + '" class="jchat_generic_tooltip"><div class="jchat_tooltip_content">' + text + "</div></div>")
        }
        var ab = $(element).offset();
        var Y = $(element).width();
        var mixed = $(selector).width();
        $(selector).css("left", (ab.left + Y) - mixed + 12).css("display", "block");
        $(selector).css("top", (ab.top - parseInt($(selector).height()) - 6) - $(window).scrollTop() + "px")
    }

    function optionsTooltip(element, Z) {
        if ($("#jchat_tooltip").length > 0) {
            $("#jchat_tooltip .jchat_tooltip_content").html(Z)
        } else {
            $("body").append('<div id="jchat_tooltip"><div class="jchat_tooltip_content">' + Z + "</div></div>")
        }
        var ab = $(element).offset();
        var Y = $(element).width();
        var mixed = $("#jchat_tooltip").width();
        $("#jchat_tooltip").css("bottom", 29).css("left", (ab.left + Y) - mixed + 12).css("display", "block");
        if (avatarUploadEnabled !== true || (isGuest && !allowGuestAvatarupload)) {
            $("#jchat_avatar").remove()
        }
        if (!skypeEnabled || (isGuest && !allowGuestSkypeBridge)) {
            $("div.jchat_skype").next().remove().end().remove()
        }
    }

    function avatarTooltip(element, Z) {
        $(element).parent().append('<div id="jchat_avatartooltip"><div class="jchat_tooltip_content"></div></div>');
        $("#jchat_avatartooltip .jchat_tooltip_content").html(Z);
        var ab = $(element).offset();
        var Y = $(element).width();
        var windowScroll = getPageScroll();
        var mixed = $("#jchat_avatartooltip").width();
        $("#jchat_avatartooltip").css("top", ab.top - parseInt(windowScroll[1])).css("left", (ab.left + Y) - mixed - 35).css("display", "block").css("z-index", 10002)
    }

    function wallTooltipUsers(element) {
        var concatenatedUsers = jchat_groupchat_nousers;
        var usersOfGroupChat = $("div[id^=jchat_userlist] span.jchat_contact[data-contact=1]");
        if (usersOfGroupChat.length) {
            concatenatedUsers = my_username + "<br/>";
            for (var i = 0; i < usersOfGroupChat.length; i++) {
                concatenatedUsers += $(usersOfGroupChat[i]).attr("data-name") + "<br/>"
            }
        }
        if ($("#jchat_users_informations_tooltip").length > 0) {
            $("#jchat_users_informations_tooltip .jchat_tooltip_content").html(concatenatedUsers)
        } else {
            $("body").append('<div id="jchat_users_informations_tooltip"><div class="jchat_tooltip_content">' + concatenatedUsers + "</div></div>")
        }
        var ab = $(element).offset();
        var Y = $(element).width();
        var mixed = $("#jchat_users_informations_tooltip").width();
        $("#jchat_users_informations_tooltip").css("left", (ab.left + Y) - mixed + 12).css("display", "block");
        $("#jchat_users_informations_tooltip").css("top", (ab.top - parseInt($("#jchat_users_informations_tooltip").height()) - 5) - $(window).scrollTop() + "px")
    }

    function avatarUploadTooltip(element) {
        $(element).before('<div class="jchat_avatar_upload_tooltip"></div>');
        $(".jchat_avatar_upload_tooltip").append('<div class="jchat_tooltip_header jchat_tooltip_avatar_upload_header">' + jchat_manage_avatars + "</div>");
        $(".jchat_avatar_upload_tooltip").click(function () {
            $(element).trigger("click")
        });
        $(".jchat_avatar_upload_tooltip").append("<img/>").children("img").attr("src", jchat_livesite + "components/com_jchat/images/loading.gif").css({position: "absolute", margin: "25% 38%", width: "64px"});
        $(".jchat_avatar_upload_tooltip").append('<iframe id="avatarUpload_iframe" scrolling="no" src="' + g + '&controller=avatar&task=showForms"></iframe>');
        $("#avatarUpload_iframe").load(function () {
            setTimeout(function () {
                $(".jchat_avatar_upload_tooltip img").remove()
            }, 1)
        });
        var ab = $(element).offset();
        var Y = $(element).width();
        var windowScroll = getPageScroll();
        var mixed = ($(".jchat_avatar_upload_tooltip").width() - $(element).width()) / 2;
        $(".jchat_avatar_upload_tooltip").css("top", ab.top - parseInt(windowScroll[1]) - 150).css("left", ab.left - mixed + 14).css("display", "block").css("z-index", 10002)
    }

    function emoticonsTooltip(element, context, wall) {
        var dataWall = "";
        var dataUserID = "";
        if (wall) {
            dataWall = ' data-wall="1" '
        } else {
            dataUserID = ' data-userid="' + T + '" '
        }
        $(element).before("<div" + dataUserID + dataWall + ' class="jchat_emoticonstooltip"></div>');
        var emoticonsIcons = new Array();
        var indexCounter = 0;
        $.each(jQuery.jchat_emoticons, function (index, value) {
            if ((index > 0) && (index % 10) == 0) {
                indexCounter++
            }
            if (emoticonsIcons[indexCounter] === undefined) {
                emoticonsIcons[indexCounter] = ""
            }
            emoticonsIcons[indexCounter] += '<img title="' + value.title + '" src="' + value.path + '"/>'
        });
        $(".jchat_tooltip_header", context).click(function () {
            $(element).trigger("click")
        });
        $.each(emoticonsIcons, function (k, elements) {
            $(".jchat_emoticonstooltip", context).append('<div class="jchat_tooltip_content">' + elements + "</div>")
        });
        var ab = $(element).offset();
        var Y = $(element).width();
        var windowScroll = getPageScroll();
        $(".jchat_emoticonstooltip", context).css("top", ab.top - parseInt(windowScroll[1]) - 132).css("left", ab.left - 206).css("display", "block").css("z-index", 10002);
        $(".jchat_emoticonstooltip img", context).click(function (event) {
            var range, selection;
            var targetContentEditable = $(this).parent().parent().prev().children("div");
            var nativeElement = $(targetContentEditable).get(0);
            if ($.support.leadingWhitespace && isContentEditable) {
                var clonedEmoticon = $(this).clone();
                $(targetContentEditable).append(clonedEmoticon)
            } else {
                if (isContentEditable) {
                    var fromTitle = " " + $(this).attr("title");
                    $(targetContentEditable).append(fromTitle)
                } else {
                    var fromTitle = " " + $(this).attr("title");
                    targetContentEditable = $(this).parent().parent().prev().children("textarea");
                    $(targetContentEditable).val($(targetContentEditable).val() + fromTitle)
                }
            }
            if (document.createRange) {
                range = document.createRange();
                range.selectNodeContents(nativeElement);
                range.collapse(false);
                selection = window.getSelection();
                selection.removeAllRanges();
                if (!jchatHasTouch()) {
                    selection.addRange(range)
                }
            } else {
                if (document.selection) {
                    range = document.body.createTextRange();
                    range.moveToElementText(nativeElement);
                    range.collapse(false);
                    if (!jchatHasTouch()) {
                        range.select()
                    }
                }
            }
            if (!jchatHasTouch()) {
                targetContentEditable.focus()
            }
        });
        if (!jchatHasTouch()) {
            $("div.jchat_textarea").focus()
        }
    }

    function uploadFileTooltip(element, context, to) {
        $(element).before('<div data-userid="' + T + '" class="jchat_fileuploadtooltip"></div>');
        $(".jchat_fileuploadtooltip", context).append("<img/>").children("img").attr("src", jchat_livesite + "components/com_jchat/images/loading.gif").css({position: "absolute", margin: "10px 46%", width: "32px"});
        $(".jchat_fileuploadtooltip", context).append('<iframe id="fileupload_iframe"  scrolling="no" src="' + g + "&controller=attachments&task=showForms&to=" + to + '"></iframe>');
        $("#fileupload_iframe", context).load(function () {
            setTimeout(function () {
                $(".jchat_fileuploadtooltip img", context).remove()
            }, 1)
        });
        var ab = $(element).offset();
        var Y = $(element).width();
        var windowScroll = getPageScroll();
        var mixed = $(".jchat_fileuploadtooltip", context).width();
        $(".jchat_fileuploadtooltip", context).css("top", ab.top - parseInt(windowScroll[1]) - 59).css("left", ab.left - mixed + 18).css("display", "block").css("z-index", 10002);
        if (!jchatHasTouch()) {
            $("div.jchat_textarea").focus()
        }
    }

    function sendMessage(Z, Y, mixed) {
        if (Z.keyCode == 13 && Z.shiftKey == 0) {
            var message = eval("$(Y)." + valFunction + "()");
            message = message.replace(/^\s+|\s+$/g, "");
            message = html_entity_decode(message, "ENT_QUOTE");
            var linksArray = message.match(/([^">]http|[^">]https|^http):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi);
            if (linksArray !== null && linksArray.length > 0) {
                $.each(linksArray, function (index, link) {
                    message = message.replace(link, '<a target="_blank" href="' + link + '">' + link + "</a>")
                })
            }
            message = $.jchatEmoticons(message);
            message = strip_tags(message, "<img>,<br>,<a>");
            eval("$(Y)." + valFunction + '("")');
            $(Y).css("height", "21px");
            $("#jchat_user_" + mixed + "_popup .jchat_tabcontenttext").css("height", "200px");
            $(Y).css("overflow-y", "hidden");
            if (!jchatHasTouch()) {
                $(Y).focus()
            } else {
                $(Y).blur();
                $("body").focus()
            }
            if (message != "") {
                $.post(g, {to: mixed, message: message, controller: "sender"}, function (ab) {
                    if (ab) {
                        n = ab;
                        $("#jchat_userlist_" + mixed).trigger("addmessage", [message, "1", "1", ab]);
                        $("#jchat_user_" + mixed + "_popup .jchat_tabcontenttext").scrollTop($("#jchat_user_" + mixed + "_popup .jchat_tabcontenttext")[0].scrollHeight)
                    }
                    h = 1;
                    if (K > X) {
                        K = X;
                        clearTimeout(U);
                        U = setTimeout(function () {
                            ajaxReceive()
                        }, X)
                    }
                })
            }
            return false
        }
    }

    function sendWallMessage(Z, Y) {
        if (Z.keyCode == 13 && Z.shiftKey == 0) {
            var message = eval("$(Y)." + valFunction + "()");
            message = message.replace(/^\s+|\s+$/g, "");
            message = html_entity_decode(message, "ENT_QUOTE");
            var linksArray = message.match(/([^">]http|[^">]https|^http):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi);
            if (linksArray !== null && linksArray.length > 0) {
                $.each(linksArray, function (index, link) {
                    message = message.replace(link, '<a target="_blank" href="' + link + '">' + link + "</a>")
                })
            }
            message = $.jchatEmoticons(message);
            var testomessaggio = strip_tags(message, "<img>,<br>,<a>");
            testomessaggio = testomessaggio.replace(/&/gi, "&amp;");
            eval("$(Y)." + valFunction + '("")');
            $(Y).css("height", "21px");
            $(Y).css("overflow-y", "hidden");
            if (!jchatHasTouch()) {
                $(Y).focus()
            } else {
                $(Y).blur();
                $("body").focus()
            }
            tabSlider();
            if (message != "") {
                $.post(g, {to: "wall", message: message, controller: "sender"}, function (idmessaggio) {
                    if (idmessaggio) {
                        if (my_avatar !== undefined && my_avatar !== null && my_avatar !== "" && !my_avatar.match(/default_my.png/) && avatarDisabled !== true) {
                            fromPlaceholder = '<img alt="' + jchat_you + my_username + '" width="32px" height="32px" src="' + my_avatar + '" />'
                        } else {
                            fromPlaceholder = "<strong>" + my_username + "</strong>:"
                        }
                        if ($("#jchat_message_" + idmessaggio).length > 0) {
                        } else {
                            $("#jchat_wall_popup div.jchat_tabcontenttext").append('<div class="jchat_chatboxmessage" id="jchat_message_' + idmessaggio + '"><span class="jchat_chatboxmessagefrom">' + fromPlaceholder + '&nbsp;</span><span class="jchat_chatboxmessagecontent">' + testomessaggio + "</span></div>");
                            $("span.jchat_chatboxmessagefrom img", "#jchat_wall_popup").mouseover(function (event) {
                                avatarTooltip(this, $(this).attr("alt"))
                            }).mouseout(function (event) {
                                $("#jchat_avatartooltip").remove()
                            })
                        }
                        $("#jchat_wall_popup div.jchat_tabcontenttext").scrollTop($("#jchat_wall_popup div.jchat_tabcontenttext")[0].scrollHeight)
                    }
                    h = 1;
                    if (K > X) {
                        K = X;
                        clearTimeout(U);
                        U = setTimeout(function () {
                            ajaxReceive()
                        }, X)
                    }
                })
            }
            return false
        }
    }

    function deleteConversation(conversationFromId, isWall) {
        $.post(g, {from: conversationFromId, controller: "sender"}, function (ab) {
            if (!isWall) {
                $("div.jchat_chatboxmessage", "#jchat_user_" + conversationFromId + "_popup").remove()
            } else {
                $("div.jchat_chatboxmessage", "#jchat_wall_popup").remove()
            }
        })
    }

    function popup(ab, mixed, ac) {
        var Z = mixed.clientHeight;
        var Y = 94;
        if (Y > Z) {
            Z = Math.max(mixed.scrollHeight, Z);
            if (Y) {
                Z = Math.min(Y, Z)
            }
            if (Z > mixed.clientHeight) {
                $(mixed).css("height", Z + 4 + "px");
                $("#jchat_user_" + ac + "_popup .jchat_tabcontenttext").css("height", 221 - (Z + 4) + "px")
            }
        } else {
            $(mixed).css("overflow-y", "auto")
        }
        $("#jchat_user_" + ac + "_popup .jchat_tabcontenttext").scrollTop($("#jchat_user_" + ac + "_popup .jchat_tabcontenttext")[0].scrollHeight)
    }

    function popupWall(ab, mixed, wallStartHeight) {
        var Z = mixed.clientHeight;
        var Y = 94;
        if (Y > Z) {
            Z = Math.max(mixed.scrollHeight, Z);
            if (Y) {
                Z = Math.min(Y, Z)
            }
            if (Z > mixed.clientHeight) {
                $(mixed).css("height", Z + 4 + "px");
                $("#jchat_wall_popup div.jchat_tabcontenttext").height(wallStartHeight - ($(".jchat_textarea_wall").height() - 21))
            }
        } else {
            $(mixed).css("overflow-y", "auto")
        }
        $("#jchat_wall_popup div.jchat_tabcontenttext").scrollTop($("#jchat_wall_popup div.jchat_tabcontenttext")[0].scrollHeight)
    }

    function statusClassOp() {
        $("#jchat_optionsbutton_popup .offline").css("text-decoration", "none");
        $("#jchat_optionsbutton_popup .available").css("text-decoration", "none");
        $("#jchat_userstab_icon").removeClass("jchat_user_available2");
        $("#jchat_userstab_icon").removeClass("jchat_user_offline")
    }

    function postStatus(Y) {
        $.post(g, {status: Y, controller: "sender"}, function (Z) {
        })
    }

    function postSkypeId(Y) {
        $.post(g, {skypeid: Y, controller: "sender"}, function (Z) {
        })
    }

    function usersTabSt(Y) {
        o = 1;
        statusClassOp();
        $("#jchat_userstab_icon").addClass("jchat_user_offline");
        if (Y != 1) {
            postStatus("offline")
        }
        $("#jchat_wall_popup").removeClass("jchat_tabopen");
        $("#jchat_userstab_popup").removeClass("jchat_tabopen");
        $("#jchat_userstab").removeClass("jchat_tabclick").removeClass("jchat_userstabclick");
        restartUpdateSession("buddylist", "0")
    }

    function refreshActiveChatBoxes() {
        var Y = "";
        $("span[id^=jchat_user_]").each(function () {
            var mixed = $(this).data("id");
            var Z = 0;
            if ($("#jchat_user_" + mixed + " .jchat_tabalert").length > 0) {
                Z = parseInt($("#jchat_user_" + mixed + " .jchat_tabalert").html())
            }
            Y += mixed + "|" + Z + ","
        });
        Y = Y.slice(0, -1);
        restartUpdateSession("activeChatboxes", Y)
    }

    function createPrivateMessagesPopup(ae, ab, Y, ad, statusAwayInfo, completeObject) {
        if ($("#jchat_user_" + ae).length > 0) {
            if (!$("#jchat_user_" + ae).hasClass("jchat_tabclick")) {
                $(".jchat_tabalert").css("display", "none");
                var mixed = 800;
                if (e("initialize") == 1 || e("updatesession") == 1) {
                    mixed = 0
                }
                $("#jchat_user_" + ae).trigger("fetchMessages")
            }
            return
        }
        tabSlider();
        if (ab.length > tabLength) {
            shortname = ab.substr(0, tabLength) + "..."
        } else {
            shortname = ab
        }
        if (ab.length > headLength) {
            longname = ab.substr(0, headLength) + "..."
        } else {
            longname = ab
        }
        var splacement = parseInt($("div[id^=jchat_userlist_]").index($("#jchat_userlist_" + ae)));
        var popupPosition = {left: parseInt(50 + splacement) + "%", top: parseInt(50 + (splacement * 3)) + splacement + "%"};
        var marginPosition = {left: "-115px", top: "-167px"};
        if (typeof popupPositions[ae] === "object") {
            popupPosition.left = popupPositions[ae].x + "px";
            popupPosition.top = popupPositions[ae].y + "px";
            marginPosition.left = 0;
            marginPosition.top = 0
        }
        $("<div/>").attr("id", "jchat_user_" + ae + "_popup").addClass("jchat_tabpopup jchat_tabopen").html('<div class="jchat_tabcontent messagelist"><div class="jchat_tabcontenttext private"></div><div class="jchat_tabcontentinput">' + writeElement + '</div><div class="jchat_trigger_emoticon"></div><div class="jchat_trigger_fileupload"></div><div class="jchat_trigger_export"></div><div class="jchat_trigger_delete"></div><div class="jchat_trigger_refresh"></div></div>').appendTo($("body"));
        if (isGuest && !allowGuestFileupload) {
            $(".jchat_trigger_fileupload").remove()
        }
        $("#jchat_user_" + ae + "_popup").css({left: popupPosition.left, top: popupPosition.top, "margin-left": marginPosition.left, "margin-top": marginPosition.top});
        $("<div/>").attr("id", "jchat_user_" + ae + "_popup");
        $("#jchat_user_" + ae + "_popup .jchat_textarea").focusin(function () {
            T = ae
        });
        $("#jchat_user_" + ae + "_popup .jchat_textarea").keydown(function (af) {
            return sendMessage(af, this, ae)
        });
        $("#jchat_user_" + ae + "_popup .jchat_textarea").keyup(function (af) {
            return popup(af, this, ae)
        });
        $(".jchat_trigger_emoticon", "#jchat_user_" + ae + "_popup").toggle(function (event) {
            if (!!$(".jchat_fileuploadtooltip", "#jchat_user_" + ae + "_popup").length) {
                $(".jchat_trigger_fileupload", "#jchat_user_" + ae + "_popup").trigger("click")
            }
            emoticonsTooltip(this, "#jchat_user_" + ae + "_popup");
            $(this).addClass("toggle_on")
        }, function (event) {
            $(".jchat_emoticonstooltip", "#jchat_user_" + ae + "_popup").remove();
            $(this).removeClass("toggle_on")
        });
        $(".jchat_trigger_fileupload", "#jchat_user_" + ae + "_popup").toggle(function (event) {
            if (!!$(".jchat_emoticonstooltip", "#jchat_user_" + ae + "_popup").length) {
                $(".jchat_trigger_emoticon", "#jchat_user_" + ae + "_popup").click()
            }
            uploadFileTooltip(this, "#jchat_user_" + ae + "_popup", ae);
            $(this).addClass("toggle_on")
        }, function (event) {
            $(".jchat_fileuploadtooltip", "#jchat_user_" + ae + "_popup").remove();
            $(this).removeClass("toggle_on")
        });
        $(".jchat_trigger_export", "#jchat_user_" + ae + "_popup").append("<a/>");
        $(".jchat_trigger_export a", "#jchat_user_" + ae + "_popup").attr("href", g + "&controller=export&task=exportFile&chatid=" + ae);
        $(".jchat_trigger_delete", "#jchat_user_" + ae + "_popup").bind("click", function () {
            deleteConversation(ae)
        });
        $("div.jchat_trigger_refresh", "#jchat_user_" + ae + "_popup").click(function (event) {
            var currentBuddylistName = $("span.jchat_userscontentname", "#jchat_userlist_" + ae).text();
            $("div.jchat_tab_shortname", "#jchat_user_" + ae + "_popup").text(currentBuddylistName);
            return fetchMessages(ae)
        });
        $("div.jchat_trigger_refresh", "#jchat_user_" + ae + "_popup").mousedown(function () {
            $(this).addClass("jchat_refresh_mousedown")
        }).mouseup(function () {
            $(this).removeClass("jchat_refresh_mousedown")
        });
        $("#jchat_user_" + ae + "_popup .jchat_tabtitle").append('<div class="jchat_closebox"></div><br clear="all"/>');
        $("#jchat_user_" + ae + "_popup .jchat_tabtitle .jchat_closebox").mouseenter(function () {
            $(this).addClass("jchat_chatboxmouseoverclose");
            $("#jchat_user_" + ae + "_popup .jchat_tabtitle").removeClass("jchat_chatboxtabtitlemouseover")
        });
        $("#jchat_user_" + ae + "_popup .jchat_tabtitle .jchat_closebox").mouseleave(function () {
            $(this).removeClass("jchat_chatboxmouseoverclose");
            $("#jchat_user_" + ae + "_popup .jchat_tabtitle").addClass("jchat_chatboxtabtitlemouseover")
        });
        $("#jchat_user_" + ae + "_popup .jchat_tabtitle .jchat_closebox").click(function () {
            $("#jchat_user_" + ae + "_popup").remove();
            $("#jchat_user_" + ae).remove();
            if (T == ae) {
                T = "";
                restartUpdateSession("openChatboxId", "")
            }
            tabSlider();
            refreshActiveChatBoxes()
        });
        $("#jchat_user_" + ae + "_popup .jchat_tabtitle").click(function () {
            $("#jchat_user_" + ae).trigger("fetchMessages")
        });
        $("#jchat_user_" + ae + "_popup .jchat_tabtitle").mouseenter(function () {
            $(this).addClass("jchat_chatboxtabtitlemouseover")
        });
        $("#jchat_user_" + ae + "_popup .jchat_tabtitle").mouseleave(function () {
            $(this).removeClass("jchat_chatboxtabtitlemouseover")
        });
        shortname = completeObject.profilelink !== null ? '<a href="' + completeObject.profilelink + '" target="_blank">' + shortname + "</a>" : shortname;
        $("<span/>").attr("id", "jchat_user_" + ae).attr("data-id", ae).addClass("jchat_tab jchat_tabclick jchat_usertabclick").html('<div class="jchat_closebox_bottom_status jchat_' + Y + '">' + statusAwayInfo + '</div><div class="jchat_tab_shortname">' + shortname + "</div>").prependTo($("#jchat_user_" + ae + "_popup"));
        $("#jchat_user_" + ae).append('<div class="jchat_closebox_bottom"></div>');
        $("#jchat_user_" + ae + " .jchat_closebox_bottom").mouseenter(function () {
            $(this).addClass("jchat_closebox_bottomhover")
        });
        $("#jchat_user_" + ae + " .jchat_closebox_bottom").mouseleave(function () {
            $(this).removeClass("jchat_closebox_bottomhover")
        });
        $("#jchat_user_" + ae + " .jchat_closebox_bottom").click(function () {
            $("#jchat_user_" + ae + "_popup").remove();
            $("#jchat_user_" + ae).remove();
            if (T == ae) {
                T = "";
                restartUpdateSession("openChatboxId", "")
            }
            tabSlider();
            refreshActiveChatBoxes()
        });
        $("#jchat_user_" + ae).mouseenter(function () {
            $(this).addClass("jchat_tabmouseover");
            $("#jchat_user_" + ae + " div").addClass("jchat_tabmouseovertext")
        });
        $("#jchat_user_" + ae).mouseleave(function () {
            $(this).removeClass("jchat_tabmouseover");
            $("#jchat_user_" + ae + " div").removeClass("jchat_tabmouseovertext")
        });
        $("#jchat_user_" + ae).bind("fetchMessages", function () {
            if ($("#jchat_user_" + ae + " .jchat_tabalert").length > 0) {
                $("#jchat_user_" + ae + " .jchat_tabalert").remove();
                refreshActiveChatBoxes()
            }
            if ($("div.jchat_avatar_upload_tooltip").length > 0) {
                $("#jchat_avatar").trigger("click")
            }
            if ($("div.jchat_emoticonstooltip", "#jchat_wall_popup").length > 0) {
                $("div.jchat_trigger_emoticon", "#jchat_wall_popup").trigger("click")
            }
            $(this).addClass("jchat_tabclick").addClass("jchat_usertabclick");
            restartUpdateSession("openChatboxId", ae);
            T = ae;
            fetchMessages(ae);
            $("#jchat_user_" + ae + "_popup .jchat_tabcontenttext").scrollTop($("#jchat_user_" + ae + "_popup .jchat_tabcontenttext")[0].scrollHeight);
            if (!firstCall && !jchatHasTouch()) {
                $("#jchat_user_" + ae + "_popup .jchat_textarea").focus()
            }
        });
        $("#jchat_user_" + ae).trigger("fetchMessages");
        refreshActiveChatBoxes();
        $("#jchat_user_" + ae + "_popup").draggable({handle: "#jchat_user_" + ae, drag: function (event, ui) {
            tabSlider()
        }, start: function (event, ui) {
            $(this).addClass("dragging")
        }, stop: function (event, ui) {
            popupPositions[ae] = {x: ui.helper.offset().left, y: ui.helper.offset().top - $(window).scrollTop()};
            $.jStorage.set("popupPositions", popupPositions);
            $(this).removeClass("dragging")
        }});
        if ($.browser.msie && $.browser.version <= 8) {
        } else {
            jchatInitTouchEvents($("#jchat_user_" + ae).get(0))
        }
        if (jchatHasTouch()) {
            $("#jchat_user_" + ae).css("background-position", "185px -850px")
        }
    }

    function fetchMessages(Y) {
        $.ajax({async: false, url: g, data: {chatbox: Y, controller: "stream"}, type: "post", cache: false, dataType: "json", success: function (ab) {
            if (ab) {

                $("#jchat_user_" + Y + "_popup .jchat_tabcontenttext").html("");
                var Z = "";
                $.each(ab, function (ac, ad) {
                    if (ac == "messages") {
                        $.each(ad, function (af, ae) {
                            if (ae.id > n) {
                                n = ae.id
                            }
                            if (ae.self == 1) {
                                if (my_avatar !== undefined && my_avatar !== null && my_avatar !== "" && avatarDisabled !== true) {
                                    fromPlaceholder = '<img alt="' + jchat_you + my_username + '" width="32px" height="32px" src="' + my_avatar + '" />'
                                } else {
                                    fromPlaceholder = "<strong>" + my_username + "</strong>:"
                                }
                            } else {
                                var userName = $("#jchat_userlist_" + Y).triggerHandler("getname");
                                var userAvatar = $("#jchat_userlist_" + Y).triggerHandler("getavatar");
                                if (userAvatar === undefined || userAvatar === null || userAvatar === "") {
                                    userAvatar = ae.avatar
                                }
                                if (userName === undefined || userName === null || userName === "") {
                                    if (ae.fromuser.length > chatLength) {
                                        userName = ae.fromuser.substr(0, chatLength) + "..."
                                    } else {
                                        userName = ae.fromuser
                                    }
                                }
                                if (userAvatar !== undefined && userAvatar !== null && userAvatar !== "" && avatarDisabled !== true) {
                                    fromPlaceholder = '<img alt="' + userName + '" width="32px" height="32px" src="' + userAvatar + '" />'
                                } else {
                                    fromPlaceholder = "<strong>" + userName + "</strong>:"
                                }
                            }
                            if (typeof ae.profilelink !== "undefined" && ae.profilelink !== "" && ae.profilelink !== null) {
                                fromPlaceholder = '<a href="' + ae.profilelink + '" target="_blank">' + fromPlaceholder + "</a>"
                            }
                            if (ae.type == "file") {
                                ae.message = trasformMsgFile(ae.message, ae.id, ae.self, ae.from, ae.status, false)
                            } else {
                                ae.message = strip_tags(ae.message, "<img>,<br>,<a>");
                                ae.message = ae.message.replace(/&/gi, "&amp;")
                            }
                            Z += ('<div class="jchat_chatboxmessage" id="jchat_message_' + ae.id + '"><span class="jchat_chatboxmessagefrom">' + fromPlaceholder + '&nbsp;</span><span class="jchat_chatboxmessagecontent">' + ae.message + "</span></div>")
                        })
                    }
                });

                $("#jchat_user_" + Y + "_popup .jchat_tabcontenttext").append(Z);
                $("#jchat_user_" + Y + "_popup .jchat_tabcontenttext").scrollTop($("#jchat_user_" + Y + "_popup .jchat_tabcontenttext")[0].scrollHeight)
            }
            $("span.jchat_chatboxmessagefrom img").mouseover(function (event) {
                avatarTooltip(this, $(this).attr("alt"))
            }).mouseout(function (event) {
                $("#jchat_avatartooltip").remove()
            })
        }})
    }

    function fetchWallMessages() {
        $.ajax({async: false, url: g, data: {wall: true, controller: "stream"}, type: "post", cache: false, dataType: "json", success: function (ab) {
            if (ab) {
                $("#jchat_wall_popup div.jchat_tabcontenttext").html("");
                var Z = "";
                $.each(ab, function (ac, ad) {
                    if (ac == "wallmessages") {
                        $.each(ad, function (af, ae) {
                            if (ae.self == 1) {
                                if (my_avatar !== undefined && my_avatar !== null && my_avatar !== "" && !my_avatar.match(/default_my.png/) && avatarDisabled !== true) {
                                    fromPlaceholder = '<img alt="' + jchat_you + my_username + '" width="32px" height="32px" src="' + my_avatar + '" />'
                                } else {
                                    fromPlaceholder = "<strong>" + my_username + "</strong>:"
                                }
                            } else {
                                var userName = $("#jchat_userlist_" + ae.fromuserid).triggerHandler("getname");
                                var userAvatar = $("#jchat_userlist_" + ae.fromuserid).triggerHandler("getavatar");
                                if (userAvatar === undefined || userAvatar === null || userAvatar === "") {
                                    userAvatar = ae.avatar
                                }
                                if (userName === undefined || userName === null || userName === "") {
                                    if (ae.fromuser.length > chatLength) {
                                        userName = ae.fromuser.substr(0, chatLength) + "..."
                                    } else {
                                        userName = ae.fromuser
                                    }
                                }
                                if (userAvatar !== undefined && userAvatar !== null && userAvatar !== "" && !userAvatar.match(/default_other.png/) && avatarDisabled !== true) {
                                    fromPlaceholder = '<img alt="' + userName + '" width="32px" height="32px" src="' + userAvatar + '" />'
                                } else {
                                    fromPlaceholder = "<strong>" + userName + "</strong>:"
                                }
                            }
                            if (typeof ae.profilelink !== "undefined" && ae.profilelink !== "" && ae.profilelink !== null) {
                                fromPlaceholder = '<a href="' + ae.profilelink + '" target="_blank">' + fromPlaceholder + "</a>"
                            }
                            if (ae.type == "file") {
                                ae.message = trasformMsgFile(ae.message, ae.id, ae.self, ae.from, ae.status, false)
                            } else {
                                ae.message = strip_tags(ae.message, "<img>,<br>,<a>");
                                ae.message = ae.message.replace(/&/gi, "&amp;")
                            }
                            Z += ('<div class="jchat_chatboxmessage" id="jchat_message_' + ae.id + '"><span class="jchat_chatboxmessagefrom">' + fromPlaceholder + '&nbsp;</span><span class="jchat_chatboxmessagecontent">' + ae.message + "</span></div>")
                        })
                    }
                });
                $("#jchat_wall_popup div.jchat_tabcontenttext").append(Z);
                $("#jchat_wall_popup div.jchat_tabcontenttext").scrollTop($("#jchat_wall_popup div.jchat_tabcontenttext")[0].scrollHeight)
            }
            $("span.jchat_chatboxmessagefrom img", "#jchat_wall_popup").mouseover(function (event) {
                avatarTooltip(this, $(this).attr("alt"))
            }).mouseout(function (event) {
                $("#jchat_avatartooltip").remove()
            })
        }})
    }

    function listaUtenti(ab, Z, status, mixed, completeObject, contactExit) {
        if (mixed == "") {
            mixed = jchat_defaultstatus
        }
        var statusAwayInfo = "";
        if (status.indexOf("|") > 0) {
            var statusInfoArray = status.split("|");
            status = statusInfoArray[0];
            var statusAwayTime = statusInfoArray[1];
            statusAwayInfo = '<span class="jchat_timeinfo">' + statusAwayTime + "</span>"
        } else {
            if (status === "available" && !completeObject.lastmessagetime) {
                status = "neveractive"
            }
        }
        var exists = !!($("#jchat_userlist_" + ab).length);
        var wasOwned = $("#jchat_userlist_" + ab + " span.jchat_contact").attr("data-owned");
        var wasContact = $("#jchat_userlist_" + ab + " span.jchat_contact").attr("data-contact");
        var isInitialize = e("initialize") == "0" ? false : true;
        if ($("#jchat_userlist_" + ab).length > 0) {
            $("#jchat_user_" + ab + " .jchat_closebox_bottom_status").removeClass("jchat_available");
            $("#jchat_user_" + ab + " .jchat_closebox_bottom_status").removeClass("jchat_away");
            $("#jchat_user_" + ab + " .jchat_closebox_bottom_status").removeClass("jchat_offline");
            $("#jchat_user_" + ab + " .jchat_closebox_bottom_status").addClass("jchat_" + status);
            $("#jchat_user_" + ab + " .jchat_closebox_bottom_status").html(statusAwayInfo);
            $("#jchat_userlist_" + ab).remove()
        }
        if (Z.length > buddyLength) {
            longname = Z.substr(0, buddyLength) + "..."
        } else {
            longname = Z
        }
        var userAvatar = "";
        if (typeof completeObject.avatar !== "undefined" && completeObject.avatar !== "") {
            if (completeObject.avatar !== null && avatarDisabled !== true) {
                userAvatar = '<img width="32px" height="32px"  src="' + completeObject.avatar + '" />'
            }
        }
        $("<div/>").attr("id", "jchat_userlist_" + ab).addClass("jchat_userlist").html('<span class="jchat_userscontentname">' + userAvatar + longname + '</span><span class="jchat_contact"></span>' + statusAwayInfo + '<span class="jchat_userscontentdot jchat_' + status + '"></span>').data("id", ab).appendTo($("#jchat_userstab_popup .jchat_tabcontent .jchat_userscontent .jchat_userslist_" + status));
        $("#jchat_userlist_" + ab).mouseover(function () {
            $(this).addClass("jchat_userlist_hover")
        });
        $("#jchat_userlist_" + ab).mouseout(function () {
            $(this).removeClass("jchat_userlist_hover")
        });
        $("#jchat_userlist_" + ab).click(function () {
            createPrivateMessagesPopup(ab, Z, status, mixed, statusAwayInfo, completeObject)
        });
        $("#jchat_userlist_" + ab).dblclick(function () {
            createPrivateMessagesPopup(ab, Z, status, mixed, statusAwayInfo, completeObject)
        });
        $("span.jchat_contact", "#jchat_userlist_" + ab).attr("data-userid", ab);
        if (completeObject.iscontact && completeObject.isowner) {
            $("span.jchat_contact", "#jchat_userlist_" + ab).attr("data-contact", 1).attr("data-name", completeObject.name);
            $("span.jchat_contact", "#jchat_userlist_" + ab).addClass("active").attr("data-text", jchat_remove);
            if (!wasContact && !isInitialize && exists) {
                injectFloatingMsg(jchat_groupchat_request_accepted_owner, completeObject.name)
            }
        } else {
            if (completeObject.iscontact || completeObject.isowner) {
                $("span.jchat_contact", "#jchat_userlist_" + ab).attr("data-owned", 1);
                $("span.jchat_contact", "#jchat_userlist_" + ab).addClass("owned").attr("data-text", jchat_pending);
                if (!wasOwned && !isInitialize && exists) {
                    injectFloatingMsg(jchat_groupchat_request_received, completeObject.name)
                }
            } else {
                $("span.jchat_contact", "#jchat_userlist_" + ab).attr("data-contact", 0);
                $("span.jchat_contact", "#jchat_userlist_" + ab).addClass("noactive").attr("data-text", jchat_invite)
            }
        }
        $("#jchat_userlist_" + ab).delegate("span.jchat_contact", "click", function (event) {
            event.stopPropagation();
            var userid = $(this).attr("data-userid");
            var activeContact = $(this).attr("data-contact");
            var activeOwned = $(this).attr("data-owned");
            var isClient = (completeObject.iscontact && !completeObject.isowner) ? true : false;
            var isOwner = (completeObject.isowner && !completeObject.iscontact) ? true : false;
            if (isClient) {
                $(this).removeClass("owned").addClass("noactive").attr("data-contact", 0).attr("data-owned", 0);
                updateRubricaStatus("deleteContact", userid);
                injectFloatingMsg(jchat_groupchat_request_removed, completeObject.name)
            } else {
                if (isOwner) {
                    if (activeContact) {
                        $(this).removeClass("owned").addClass("noactive").attr("data-contact", 0).attr("data-owned", 0);
                        updateRubricaStatus("deleteContact", userid);
                        injectFloatingMsg(jchat_groupchat_request_removed, completeObject.name)
                    } else {
                        if (activeOwned) {
                            $(this).removeClass("noactive").addClass("active").attr("data-owned", 0).attr("data-contact", 1).attr("data-name", completeObject.name);
                            updateRubricaStatus("storeContact", userid);
                            injectFloatingMsg(jchat_groupchat_request_accepted)
                        }
                    }
                } else {
                    if (activeContact != 1 && activeOwned != 1) {
                        $(this).removeClass("noactive").addClass("owned").attr("data-owned", 1);
                        updateRubricaStatus("storeContact", userid);
                        injectFloatingMsg(jchat_groupchat_request_sent, completeObject.name)
                    } else {
                        $(this).removeClass("active").addClass("noactive").attr("data-contact", 0).attr("data-owned", 0);
                        updateRubricaStatus("deleteContact", userid);
                        injectFloatingMsg(jchat_groupchat_request_removed, completeObject.name)
                    }
                }
            }
        });
        $("#jchat_userlist_" + ab).bind("addmessage", function (event, testomessaggio, af, ae, idmessaggio, messageObject, lastMessageReached) {
            createPrivateMessagesPopup(ab, Z, status, mixed, statusAwayInfo, messageObject);

            if (typeof messageObject === "undefined" && T == ab && af == 1) {
                if (af == 1) {
                    if (my_avatar !== undefined && my_avatar !== null && my_avatar !== "" && avatarDisabled !== true) {
                        fromPlaceholder = '<img alt="' + jchat_you + my_username + '" width="32px" height="32px" src="' + my_avatar + '" />'
                    } else {
                        fromPlaceholder = "<strong>" + my_username + "</strong>:"
                    }
                }
                testomessaggio = strip_tags(testomessaggio, "<img>,<br>,<a>");
                testomessaggio = testomessaggio.replace(/&/gi, "&amp;");
                if ($("#jchat_message_" + idmessaggio).length > 0) {
                } else {
                    $("#jchat_user_" + ab + "_popup .jchat_tabcontenttext").append('<div class="jchat_chatboxmessage" id="jchat_message_' + idmessaggio + '"><span class="jchat_chatboxmessagefrom">' + fromPlaceholder + '&nbsp;</span><span class="jchat_chatboxmessagecontent">' + testomessaggio + "</span></div>");
                    $("span.jchat_chatboxmessagefrom img").mouseover(function (event) {
                        avatarTooltip(this, $(this).attr("alt"))
                    }).mouseout(function (event) {
                        $("#jchat_avatartooltip").remove()
                    })
                }
            }
            if (T != ab && ae == 0) {
                tabAlert(ab, 1, 1, messageObject, lastMessageReached)
            } else {
                if (audio == 1 && af == 0 && ae == 0 && messageObject.type != "file") {
                    if ($(msgNotify).data("events") !== undefined) {
                        $(msgNotify).trigger("onMessage")
                    } else {
                        msgNotify.playMessageAlert()
                    }
                } else {
                    if (audio == 1 && af == 0 && ae == 0 && messageObject.type == "file") {
                        if ($(msgNotify).data("events") !== undefined) {
                            $(msgNotify).trigger("onFileSent")
                        } else {
                            msgNotify.playSentFile()
                        }
                    }
                }
            }
            if (ae == 0) {
                var target = $("#jchat_user_" + messageObject.from + "_popup .jchat_textarea");
                if (!$(target).is(":focus") && !$(target).data("isblinking")) {
                    $(target).blink();
                    $(target).data("isblinking", true)
                }
            }
        });
        $("#jchat_userlist_" + ab).bind("getname", function (ac) {
            if (Z.length > chatLength) {
                return Z.substr(0, chatLength) + "..."
            } else {
                return Z
            }
        });
        $("#jchat_userlist_" + ab).bind("getavatar", function (ac) {
            return completeObject.avatar
        });
        if (completeObject.skypeid) {
            injectSkypeControls(completeObject)
        }
    }

    function tabAlert(mixed, Y, Z, messageObject, lastMessageReached) {
        if (audio == 1 && messageObject.type != "file") {
            if ($(msgNotify).data("events") !== undefined) {
                $(msgNotify).trigger("onMessage")
            } else {
                msgNotify.playMessageAlert()
            }
        } else {
            if (audio == 1 && messageObject.type == "file") {
                if ($(msgNotify).data("events") !== undefined) {
                    $(msgNotify).trigger("onFileSent")
                } else {
                    msgNotify.playSentFile()
                }
            }
        }
    }

    function initializeDom() {
        $("<span/>").attr("id", "jchat_userstab").addClass("jchat_tab").html('<span id="jchat_userstab_icon"></span><span id="jchat_userstab_text" style="float:left">' + chatTitle + "</span>").appendTo($("#jchat_base"));
        $("<span/>").attr("id", "jchat_closesidebarbutton").addClass("jchat_tab").addClass("jchat_exitimages").appendTo($("#jchat_userstab"));
        $("<span/>").attr("id", "jchat_optionsbutton").addClass("jchat_tab").addClass("jchat_optionsimages").appendTo($("#jchat_userstab"));
        $("<div/>").attr("id", "jchat_userstab_popup").addClass("jchat_tabpopup").css("display", "none").html('<div class="jchat_userstabtitle">' + jchat_privatechat + '<input id="jchat_users_search" type="text" value="' + jchat_search + '"/></div><div class="jchat_tabcontent userslist"><div class="jchat_userscontent"><div class="jchat_userslist_available"></div><div class="jchat_userslist_neveractive"></div><div class="jchat_userslist_away"></div></div>').appendTo($("body"));
        $("#jchat_userstab").mouseover(function () {
            $(this).addClass("jchat_tabmouseover")
        });
        $("#jchat_userstab").mouseout(function () {
            $(this).removeClass("jchat_tabmouseover")
        });
        $(document).on("click", "#jchat_userstab", function (event) {
            if (o == 1) {
                o = 0;
                $("#jchat_userstab_text").html(chatTitle);
                ajaxReceive();
                $("#jchat_optionsbutton_popup .available").click()
            }
            s.options = 0;
            if ($(this).hasClass("jchat_tabclick")) {
                restartUpdateSession("buddylist", "0")
            } else {
                restartUpdateSession("buddylist", "1")
            }
            $("#jchat_userstab_popup").css("right", "0").css("bottom", "0");
            $(this).toggleClass("jchat_tabclick").toggleClass("jchat_userstabclick");
            $("#jchat_userstab_popup").toggleClass("jchat_tabopen");
            $("#jchat_wall_popup").toggleClass("jchat_tabopen")
        });
        var defaultGenericTooltips = ["jchat_trigger_fileupload", "jchat_trigger_export", "jchat_trigger_delete", "jchat_trigger_refresh", "jchat_trigger_skypesave", "jchat_trigger_skypedelete"];
        $.each(defaultGenericTooltips, function (k, elem) {
            $(document).on("mouseover", "." + elem,function (event) {
                triggerGenericTooltip("#" + elem + "_tooltip", this, eval(elem))
            }).on("mouseout", "." + elem, function (event) {
                $("#" + elem + "_tooltip").css("display", "none")
            })
        });
        $(document).on("mouseover", ".jchat_contact",function (event) {
            triggerGenericTooltip("#jchat_contact_tooltip", this)
        }).on("mouseout", ".jchat_contact", function (event) {
            $("#jchat_contact_tooltip").css("display", "none")
        });
        $("#jchat_contacts_toggle").click(function (event) {
            if ($(this).attr("data-checked") == 1) {
                $(this).css("background-image", "url(" + jchat_livesite + "components/com_jchat/images/default/icon_gray.png)");
                $(this).attr("data-checked", 0);
                updateRubricaStatus("refreshSessionVars", 0)
            } else {
                $(this).css("background-image", "url(" + jchat_livesite + "components/com_jchat/images/default/icon_green.png)");
                $(this).attr("data-checked", 1);
                updateRubricaStatus("refreshSessionVars", 1)
            }
        });
        $("#jchat_users_search").focusin(function () {
            $(this).val("")
        }).focusout(function () {
            $(this).val(jchat_search);
            forceBuddylistRefresh = 0
        }).keyup(function () {
            restartUpdateSession("buddylist", "1");
            forceBuddylistRefresh = 1
        });
        $("head").append('<style>.jchat_tab_shortname a:hover::before{ content:"' + jchat_userprofile_link + '" }</style>')
    }

    function initializeOptionsDiv() {
        $("<div/>").attr("id", "jchat_optionsbutton_popup").addClass("jchat_tabpopup").css("display", "none").html('<div class="jchat_userstabtitle">' + jchat_optionsbutton + '</div><div class="jchat_tabcontent"><span style="float:left" class="jchat_user_available"></span><span class="jchat_optionsstatus available">' + jchat_available + '</span><br clear="all"/><span class="jchat_optionsstatus2 jchat_user_offline"></span><span class="jchat_optionsstatus offline">' + jchat_statooffline + '</span><br clear="all"/><div id="jchat_avatar" class="jchat_avatar">Avatar</div><div class="jchat_sounds">' + jchat_audio_onoff + '</div><br clear="all"/><div class="jchat_skype"><span class="jchat_options_skype">Insert Skype ID</span></div><div><input type="text" id="skype_id" value=""/><div id="jchat_trigger_skypesave" class="jchat_trigger_skypesave"></div><div id="jchat_trigger_skypedelete" class="jchat_trigger_skypedelete"></div></div>').appendTo($("body"));
        $("#jchat_optionsbutton_popup .available").click(function (Y) {
            statusClassOp();
            $("#jchat_userstab_icon").addClass("jchat_user_available2");
            $(this).css("text-decoration", "underline");
            postStatus("available")
        });
        $("#jchat_optionsbutton_popup .offline").click(function (Y) {
            usersTabSt();
            $(this).css("text-decoration", "underline")
        });
        $("#jchat_trigger_skypesave").click(function () {
            var skypeIdValue = $(this).prev().val();
            postSkypeId(skypeIdValue);
            if (skypeIdValue) {
                injectFloatingMsg(jchat_skypeidsaved, skypeIdValue)
            } else {
                injectFloatingMsg(jchat_skypeid_deleted, null)
            }
        });
        $("#jchat_trigger_skypedelete").click(function () {
            $(this).prevAll("input").val(null);
            postSkypeId(null);
            injectFloatingMsg(jchat_skypeid_deleted, null)
        });
        $(document).on("mouseover", "#jchat_optionsbutton, #jchat_closesidebarbutton", function () {
            optionsTooltip(this, eval($(this).attr("id")))
        });
        $(document).on("mouseout", "#jchat_optionsbutton, #jchat_closesidebarbutton", function () {
            $(this).removeClass("jchat_tabmouseover");
            $("#jchat_tooltip").css("display", "none")
        });
        $(document).on("click", "#jchat_optionsbutton", function (event) {
            if (r == 0) {
                if (o == 1) {
                    o = 0;
                    $("#jchat_userstab_text").html(chatTitle);
                    ajaxReceive();
                    $("#jchat_optionsbutton_popup .available").click()
                }
                $("#jchat_optionsbutton_popup").toggleClass("jchat_tabopen")
            }
            event.stopPropagation();
            return false
        });
        $("#jchat_optionsbutton_popup .jchat_userstabtitle").click(function () {
            $("#jchat_optionsbutton_popup").toggleClass("jchat_tabopen")
        });
        $("#jchat_optionsbutton_popup .jchat_userstabtitle").mouseenter(function () {
            $(this).addClass("jchat_chatboxtabtitlemouseover2")
        });
        $("#jchat_optionsbutton_popup .jchat_userstabtitle").mouseleave(function () {
            $(this).removeClass("jchat_chatboxtabtitlemouseover2")
        });
        $("#jchat_avatar").toggle(function (event) {
            avatarUploadTooltip(this)
        }, function (event) {
            $("#jchat_avatar").prev().remove()
        });
        jQuery("div.jchat_sounds").bind("click", function (event) {
            audio = audio == 1 ? 0 : 1;
            restartUpdateSession("audio", audio);
            $(this).toggleClass("noaudio")
        })
    }

    function initializeWall() {
        $("<div/>").attr("id", "jchat_wall_popup").addClass("jchat_tabpopup jchat_walltab_popup").css("display", "none").html('<div class="jchat_userstabtitle">' + jchat_wall_msgs + '</div><div class="jchat_tabcontent messagelist"><div class="jchat_tabcontenttext jchat_tabcontenttext_wall"></div><div class="jchat_tabcontentinput">' + writeElementWall + '</div><div class="jchat_trigger_emoticon"></div><div class="jchat_trigger_refresh"></div><div class="jchat_trigger_users_informations"></div><div class="jchat_trigger_export"></div><div class="jchat_trigger_delete"></div></div>').appendTo($("body"));
        $(".jchat_trigger_emoticon", "#jchat_wall_popup").toggle(function (event) {
            emoticonsTooltip(this, "#jchat_wall_popup", true);
            $(this).addClass("toggle_on")
        }, function (event) {
            $(".jchat_emoticonstooltip", "#jchat_wall_popup").remove();
            $(this).removeClass("toggle_on")
        });
        $("#jchat_wall_popup div.jchat_trigger_refresh").click(function (event) {
            return fetchWallMessages()
        });
        $("#jchat_wall_popup div.jchat_trigger_users_informations").mouseover(function () {
            wallTooltipUsers("div.jchat_trigger_users_informations");
            $(this).addClass("jchat_tabmouseover")
        });
        $("#jchat_wall_popup div.jchat_trigger_users_informations").mouseout(function () {
            $(this).removeClass("jchat_tabmouseover");
            $("#jchat_users_informations_tooltip").css("display", "none")
        });
        $("#jchat_wall_popup div.jchat_trigger_refresh").mousedown(function () {
            $(this).addClass("jchat_refresh_mousedown")
        }).mouseup(function () {
            $(this).removeClass("jchat_refresh_mousedown")
        });
        $("#jchat_wall_popup div.jchat_trigger_export").append("<a/>");
        $("#jchat_wall_popup div.jchat_trigger_export a").attr("href", g + "&controller=export&task=exportFile&chatid=wall");
        $("#jchat_wall_popup div.jchat_trigger_delete").bind("click", function () {
            deleteConversation("wall", true)
        });
        $("#jchat_wall_popup .jchat_textarea").keydown(function (event) {
            return sendWallMessage(event, this)
        });
        $("#jchat_wall_popup .jchat_textarea").keyup(function (af) {
            return popupWall(af, this, wallStartHeight)
        });
        $("#jchat_wall_popup .jchat_userstabtitle").mouseenter(function () {
            $(this).addClass("jchat_chatboxtabtitlemouseover2")
        });
        $("#jchat_wall_popup .jchat_userstabtitle").mouseleave(function () {
            $(this).removeClass("jchat_chatboxtabtitlemouseover2")
        })
    }

    function tabSlider() {
        $("#jchat_base").css("width", "100%");
        $("#jchat_userstab_popup").css("right", 0).css("bottom", "0");
        var parentDivHeight = $("div.jchat_tabcontenttext_wall").parents("#jchat_wall_popup").show().height();
        $("div.jchat_tabcontenttext_wall").parents("#jchat_wall_popup").hide();
        $("div.jchat_tabcontenttext_wall").height(parentDivHeight - 100);
        var windowSize = $(window).width();
        wallStartHeight = parseInt($("#jchat_wall_popup div.jchat_tabcontenttext").height());
        $(".jchat_fileuploadtooltip").each(function (index, elem) {
            var leftKonstant = 0;
            var subKonstantLarge = 0;
            var uploadContext = $(elem).parents("div[id^=jchat_user]");
            if (!!parseInt($(uploadContext).css("margin-left"))) {
                leftKonstant = 463;
                subKonstantLarge = 45
            } else {
                leftKonstant = 348;
                subKonstantLarge = 211
            }
            $(elem, uploadContext).css("left", $(uploadContext).position().left - leftKonstant).css("top", $(uploadContext).position().top + subKonstantLarge)
        });
        $(".jchat_emoticonstooltip").each(function (index, elem) {
            var leftKonstant = 0;
            var subKonstantSmall = 0;
            var leftKonstant = 0;
            var subKonstantLarge = 0;
            var uploadContext = $(elem).parents("div[id^=jchat_user]");
            if (!!parseInt($(uploadContext).css("margin-left"))) {
                leftKonstant = 310;
                subKonstantSmall = -28
            } else {
                leftKonstant = 196;
                subKonstantSmall = 138
            }
            var emoticonsContext = $(elem).parents("div[id^=jchat_user]");
            if ($(elem).data("wall")) {
                var triggerPosition = $(elem).next();
                var triggerTopPosition = $(triggerPosition).position().top - $(elem).height() + 1;
                emoticonsContext = $(elem).parents("div[id^=jchat_wall_popup]");
                $(elem).css("left", $(emoticonsContext).position().left - $(elem).width() + 32).css("top", triggerTopPosition)
            } else {
                $(elem, emoticonsContext).css("left", $(emoticonsContext).position().left - leftKonstant).css("top", $(emoticonsContext).position().top + subKonstantSmall)
            }
        });
        $(".jchat_avatar_upload_tooltip").each(function (index, elem) {
            var ab = $("#jchat_avatar").offset();
            var windowScroll = getPageScroll();
            $(elem).css("left", ab.left - 45).css("top", ab.top - parseInt(windowScroll[1]) - 150)
        })
    }

    function b(Y, Z) {
        t[Y] = Z
    }

    function e(Y) {
        if (t[Y]) {
            return t[Y]
        } else {
            return""
        }
    }

    function restartUpdateSession(Y, Z) {
        s[Y] = Z;
        if (e("initialize") != 1 && e("updatesession") != 1) {
            R = 1;
            clearTimeout(U);
            U = setTimeout(function () {
                ajaxReceive()
            }, restartInterval)
        }
    }

    function getSessionProperty(Y, Z) {
        if (s[Y]) {
            return s[Y]
        } else {
            return""
        }
    }

    function ajaxReceive() {
        for (vars in s) {
            t["sessionvars[" + vars + "]"] = s[vars]
        }
        t.controller = "stream";
        t.searchfilter = $("#jchat_users_search").val();
        t.force_refresh = forceBuddylistRefresh;
        if (R == 1) {
            b("updatesession", "1");
            R = 0
        }
        t.last_received_msg_id = n;
        var Y = "";
        var wallMsgs = "";
        $.ajax({url: g, data: t, type: "post", cache: false, dataType: "json", success: function (mixed) {
            if (mixed) {
                var Z = 0;
                $.each(mixed, function (ab, ac) {
                    if (ab == "paramslist") {
                        X = ac.chatrefresh * 1000;
                        K = ac.chatrefresh * 1000;
                        avatarUploadEnabled = !!parseInt(ac.avatarupload);
                        avatarDisabled = !!parseInt(ac.avatardisable);
                        chatboxesOpenMode = !!parseInt(ac.chatboxes_open_mode);
                        skypeEnabled = !!parseInt(ac.skypebridge);
                        groupChat = !!parseInt(ac.groupchat);
                        chatTitle = ac.chat_title;
                        if (!!parseInt(ac.offline_message_switcher)) {
                            jchat_nousers = '<div class="jchat_nousers_placeholder">' + ac.offline_message + "</div>"
                        }
                        isGuest = !!parseInt(ac.isguest);
                        allowGuestAvatarupload = !!parseInt(ac.allow_guest_avatarupload);
                        allowGuestFileupload = !!parseInt(ac.allow_guest_fileupload);
                        allowGuestSkypeBridge = !!parseInt(ac.allow_guest_skypebridge)
                    }
                    if (ab == "buddylist") {
                        if (!ac) {
                            $(".jchat_nousers, .jchat_userlist").remove();
                            $(".jchat_userscontent").append('<div class="jchat_nousers">' + jchat_nousers + "</div>");
                            $("#jchat_userstab_text").html(chatTitle + "(0)")
                        } else {
                            $(".jchat_nousers").remove();
                            var numUsers = 0;
                            $.each(ac, function (ae, ad) {
                                listaUtenti(ad.id, ad.name, ad.status, ad.message, ad, false);
                                if (ad.status !== "offline") {
                                    numUsers++
                                }
                            });
                            $("#jchat_userstab_text").html(chatTitle + "(" + numUsers + ")");
                            var presentUsers = $("div.jchat_userlist");
                            $.each(presentUsers, function (k, HTMLelem) {
                                var thisUserId = $(HTMLelem).data("id");
                                if ($.inArray(thisUserId, mixed.buddylist_ids) == -1) {
                                    $(HTMLelem).remove()
                                }
                            })
                        }
                    }
                    if (ab == "my_username" && ab != "" && ab != "undefined") {
                        if (ac.length > chatLength) {
                            my_username = ac.substr(0, chatLength) + "..."
                        } else {
                            my_username = ac
                        }
                    }
                    if (ab == "my_avatar" && ab != "" && ab !== undefined && ab !== null) {
                        my_avatar = ac
                    }
                    if (ab == "loggedout") {
                        $("#jchat_optionsbutton").addClass("jchat_optionsimages_exclamation");
                        $("#jchat_userstab").hide();
                        $("#jchat_optionsbutton_popup").hide();
                        $("#jchat_wall_popup").hide();
                        $("#jchat_userstab_popup").hide();
                        $(".jchat_tabopen").css("cssText", "display: none !important;");
                        if (T != "") {
                            $("#jchat_user_" + T + "_popup").hide();
                            T = ""
                        }
                        r = 1
                    }
                    if (ab == "userstatus") {
                        $.each(ac, function (ad, ae) {
                            if (ad == "message") {
                                $("#jchat_optionsbutton_popup .jchat_statustextarea").val(ae)
                            }
                            if (ad == "status") {
                                if (ae == "offline") {
                                    usersTabSt(1)
                                } else {
                                    statusClassOp();
                                    $("#jchat_userstab_icon").addClass("jchat_user_" + ae + "2");
                                    $("#jchat_optionsbutton_popup ." + ae).css("text-decoration", "underline")
                                }
                            }
                            if (ad == "skype_id") {
                                $("#skype_id").val(ae)
                            }
                        })
                    }
                    if (ab == "initialize") {
                        firstCall = true;
                        $.each(ac, function (ad, af) {
                            if (ad == "buddylist") {
                                if (af == 1) {
                                    $("#jchat_userstab").click()
                                }
                            }
                            if (ad == "options") {
                                if (af == 1) {
                                    $("#jchat_optionsbutton").click()
                                }
                            }
                            if (ad == "activeChatboxes") {
                                if (!!parseInt(ac.buddylist) || chatboxesOpenMode) {
                                    var ag = af.split(/,/);
                                    for (var i = 0; i < ag.length; i++) {
                                        var ae = ag[i].split(/\|/);
                                        $("#jchat_userlist_" + ae[0]).dblclick();
                                        if (parseInt(ae[1]) > 0) {
                                            tabAlert(ae[0], ae[1], 0, {})
                                        }
                                    }
                                }
                            }
                            if (ad == "audio") {
                                audio = af;
                                if (audio == 0) {
                                    $("div.jchat_sounds").addClass("noaudio")
                                }
                            }
                        });
                        b("initialize", "0")
                    }
                    if (ab == "wallmessages") {
                        $.each(ac, function (af, ad) {
                            ++Z;
                            if (ad.self == 1) {
                                if (my_avatar !== undefined && my_avatar !== null && my_avatar !== "" && !my_avatar.match(/default_my.png/) && avatarDisabled !== true) {
                                    fromPlaceholder = '<img alt="' + jchat_you + my_username + '" width="32px" height="32px" src="' + my_avatar + '" />'
                                } else {
                                    fromPlaceholder = "<strong>" + my_username + "</strong>:"
                                }
                            } else {
                                var userName = $("#jchat_userlist_" + ad.fromuserid).triggerHandler("getname");
                                var userAvatar = $("#jchat_userlist_" + ad.fromuserid).triggerHandler("getavatar");
                                if (userAvatar === undefined || userAvatar === null || userAvatar === "") {
                                    userAvatar = ad.avatar
                                }
                                if (userName === undefined || userName === null || userName === "") {
                                    if (ad.fromuser.length > chatLength) {
                                        userName = ad.fromuser.substr(0, chatLength) + "..."
                                    } else {
                                        userName = ad.fromuser
                                    }
                                }
                                if (userAvatar !== undefined && userAvatar !== null && userAvatar !== "" && !userAvatar.match(/default_other.png/) && avatarDisabled !== true) {
                                    fromPlaceholder = '<img alt="' + userName + '" width="32px" height="32px" src="' + userAvatar + '" />'
                                } else {
                                    fromPlaceholder = "<strong>" + userName + "</strong>:"
                                }
                                if (typeof ad.profilelink !== "undefined" && ad.profilelink !== "" && ad.profilelink !== null) {
                                    fromPlaceholder = '<a href="' + ad.profilelink + '" target="_blank">' + fromPlaceholder + "</a>"
                                }
                            }
                            ad.message = strip_tags(ad.message, "<img>,<br>,<a>");
                            ad.message = ad.message.replace(/&/gi, "&amp;");
                            if ($("#jchat_message_" + ad.id).length > 0) {
                            } else {
                                wallMsgs += ('<div class="jchat_chatboxmessage" id="jchat_message_' + ad.id + '"><span class="jchat_chatboxmessagefrom">' + fromPlaceholder + '&nbsp;</span><span class="jchat_chatboxmessagecontent">' + ad.message + "</span></div>");
                                if (audio == 1 && firstCall === false) {
                                    if ($(msgNotify).data("events") !== undefined) {
                                        $(msgNotify).trigger("onWallMessage")
                                    } else {
                                        msgNotify.playWallMessageAlert()
                                    }
                                }
                            }
                        })
                    }

                    if (ab == "messages") {
                        $.each(ac, function (af, ad) {
                            n = ad.id;
                            if ($("#jchat_user_" + (ad.from) + "_popup").length > 0) {
                                if (ad.self == 1) {
                                    if (my_avatar !== undefined && my_avatar !== null && my_avatar !== "" && avatarDisabled !== true) {
                                        fromPlaceholder = '<img alt="' + jchat_you + my_username + '" width="32px" height="32px" src="' + my_avatar + '" />'
                                    } else {
                                        fromPlaceholder = "<strong>" + my_username + "</strong>:"
                                    }
                                } else {
                                    var userName = $("#jchat_userlist_" + ad.from).triggerHandler("getname");
                                    var userAvatar = $("#jchat_userlist_" + ad.from).triggerHandler("getavatar");
                                    if (userAvatar === undefined || userAvatar === null || userAvatar === "") {
                                        userAvatar = ad.avatar
                                    }
                                    if (userName === undefined || userName === null || userName === "") {
                                        userName = ad.fromuser
                                    }
                                    if (userAvatar !== undefined && userAvatar !== null && userAvatar !== "" && avatarDisabled !== true) {
                                        fromPlaceholder = '<img alt="' + userName + '" width="32px" height="32px" src="' + userAvatar + '" />'
                                    } else {
                                        fromPlaceholder = "<strong>" + userName + "</strong>:"
                                    }
                                    if (typeof ad.profilelink !== "undefined" && ad.profilelink !== "" && ad.profilelink !== null) {
                                        fromPlaceholder = '<a href="' + ad.profilelink + '" target="_blank">' + fromPlaceholder + "</a>"
                                    }
                                }
                                if (ad.type == "file") {
                                    if (firstCall) {
                                        var sound = false
                                    }
                                    ad.message = trasformMsgFile(ad.message, ad.id, ad.self, ad.from, ad.status, sound)
                                } else {
                                    ad.message = strip_tags(ad.message, "<img>,<br>,<a>");
                                    ad.message = ad.message.replace(/&/gi, "&amp;")
                                }
                                if ($("#jchat_message_" + ad.id).length > 0) {
                                } else {
                                    Y = ('<div class="jchat_chatboxmessage" id="jchat_message_' + ad.id + '"><span class="jchat_chatboxmessagefrom">' + fromPlaceholder + '&nbsp;</span><span class="jchat_chatboxmessagecontent">' + ad.message + "</span></div>");
                                    if (audio == 1 && firstCall === false && ad.type != "file") {
                                        if ($(msgNotify).data("events") !== undefined) {
                                            $(msgNotify).trigger("onMessage")
                                        } else {
                                            msgNotify.playMessageAlert()
                                        }
                                    }
                                    if (!ad.old) {
                                        var target = $("#jchat_user_" + ad.from + "_popup .jchat_textarea");
                                        if (!$(target).is(":focus") && !$(target).data("isblinking")) {
                                            $(target).blink();
                                            $(target).data("isblinking", true)
                                        }
                                    }
                                }
                                $("#jchat_user_" + ad.from + "_popup .jchat_tabcontenttext").append(Y);
                                $("#jchat_user_" + ad.from + "_popup .jchat_tabcontenttext").scrollTop($("#jchat_user_" + ad.from + "_popup .jchat_tabcontenttext")[0].scrollHeight);
                                $("span.jchat_chatboxmessagefrom img").mouseover(function (event) {
                                    avatarTooltip(this, $(this).attr("alt"))
                                }).mouseout(function (event) {
                                    $("#jchat_avatartooltip").remove()
                                })
                            } else {
                                var lastMessageReached = !!(af == (ac.length - 1));
                                $("#jchat_userlist_" + ad.from).trigger("addmessage", [ad.message, ad.self, ad.old, ad.id, ad, lastMessageReached])
                            }
                        });
                        h = 1;
                        K = X
                    }
                    if (ab == "downloads") {
                        $.each(ac, function (k, elem) {
                            refreshMsgFileStatus(elem[0], elem[1])
                        })
                    }
                });
                if (wallMsgs) {
                    $("#jchat_wall_popup div.jchat_tabcontenttext").append(wallMsgs);
                    $("#jchat_wall_popup div.jchat_tabcontenttext").scrollTop($("#jchat_wall_popup div.jchat_tabcontenttext")[0].scrollHeight);
                    $("span.jchat_chatboxmessagefrom img", "#jchat_wall_popup").mouseover(function (event) {
                        avatarTooltip(this, $(this).attr("alt"))
                    }).mouseout(function (event) {
                        $("#jchat_avatartooltip").remove()
                    })
                }
            }
            b("initialize", "0");
            b("updatesession", "0");
            if (r != 1 && o != 1) {
                h++;
                if (h > emptyResponse) {
                    K *= 2;
                    h = 1
                }
                if (K > F) {
                    K = F
                }
                clearTimeout(U);
                U = setTimeout(function () {
                    ajaxReceive()
                }, K)
            }
            if (firstCall && groupChat) {
                tabSlider();
                $("#jchat_wall_popup div.jchat_tabcontenttext").scrollTop($("#jchat_wall_popup div.jchat_tabcontenttext")[0].scrollHeight)
            }
            firstCall = false;
            sound = true;
            if (groupChat === false) {
                $("#jchat_wall_popup").remove();
                $("span.jchat_contact").hide()
            }
        }})
    }

    function trasformMsgFile(msgText, idMessage, self, from, status, sounds) {
        var attachmentStringImg = '<img class="clessidra" src="' + jchat_livesite + 'components/com_jchat/images/attachment.png"/>';
        if (sounds !== false) {
            if ($(msgNotify).data("events") !== undefined) {
                $(msgNotify).trigger("onFileSent")
            } else {
                msgNotify.playSentFile()
            }
        }
        if (self == 1) {
            msgText = attachmentStringImg + jchat_sent_file + '<div class="filename">' + msgText + "</div>";
            if (status == 0) {
                msgText += '<img class="clessidra" src="' + jchat_livesite + 'components/com_jchat/images/default/loading.gif"/>' + jchat_sent_file_waiting
            } else {
                msgText += jchat_sent_file_downloaded
            }
        } else {
            var href = "index.php?option=com_jchat&amp;controller=attachments&amp;task=doDownload&amp;format=raw&amp;idMessage=" + idMessage + "&amp;from=" + from;
            var link = '<a class="msgfile" href="' + href + '">' + jchat_sent_file_download + "</a>";
            msgText = attachmentStringImg + jchat_received_file + '<div class="filename">' + msgText + "</div>" + link
        }
        return msgText
    }

    function refreshMsgFileStatus(idPopupUser, idMessage) {
        $("#jchat_message_" + idMessage + " span.jchat_chatboxmessagecontent span.filestatus", "#jchat_user_" + idPopupUser + "_popup").prev().remove();
        $("#jchat_message_" + idMessage + " span.jchat_chatboxmessagecontent span.filestatus", "#jchat_user_" + idPopupUser + "_popup").html(jchat_sent_file_downloaded_realtime);
        if ($(msgNotify).data("events") !== undefined) {
            $(msgNotify).trigger("onFileDownloaded")
        } else {
            msgNotify.playCompleteFile()
        }
    }

    function injectSkypeControls(userObject) {
        if (!userObject.skypeid || !skypeEnabled) {
            return false
        }
        var appendDomElems = function () {
            if (skypeInstalled) {
                var domElems = '<div class="jchat_skypecall"><a data-text="' + jchat_startskypecall + '" class="skypeicon" href="skype:' + userObject.skypeid + '?call"></a></div>'
            } else {
                var domElems = '<div class="jchat_skypecall disabled"><a class="skypeicon" data-text="' + jchat_startskypedownload + '" href="http://www.skype.com/intl/en/get-skype/" target="_blank"></a></div>'
            }
            $("#jchat_userlist_" + userObject.id).append(domElems);
            $("#jchat_userlist_" + userObject.id + " div.jchat_skypecall a").on("click",function (event) {
                event.stopPropagation()
            }).on("mouseover",function (event) {
                triggerGenericTooltip("#jchat_skypecall", this)
            }).on("mouseout", function (event) {
                $("#jchat_skypecall").css("display", "none").remove()
            })
        };
        var skypeInstalled = true;
        appendDomElems();
        $("div.jchat_skypecall a").click(function (event) {
            event.preventDefault();
            var startcallTimeout = 200;
            if (jchatHasTouch()) {
                startcallTimeout = 1500
            }
            setTimeout(function () {
                $(event.target).trigger("mouseout");
                window.location = $(event.target).attr("href")
            }, startcallTimeout)
        });
        return skypeInstalled
    }

    function updateRubricaStatus(op, value) {
        var postVars = {controller: "groupchat", task: op, id: value};
        $.ajax({url: g, data: postVars, type: "post", cache: false, dataType: "json", success: function (response) {
            if (response.buddylist !== undefined) {
                var presentUsers = $("div.jchat_userlist");
                $.each(presentUsers, function (k, HTMLelem) {
                    var thisUserId = $(HTMLelem).data("id");
                    if ($.inArray(thisUserId, response.buddylist_ids) == -1) {
                        $(HTMLelem).remove()
                    }
                });
                $("div.jchat_nousers").remove();
                if (response.buddylist.length > 0) {
                    var numUsers = 0;
                    $.each(response.buddylist, function (k, elem) {
                        listaUtenti(elem.id, elem.name, elem.status, elem.message, elem, true);
                        if (elem.status !== "offline") {
                            numUsers++
                        }
                    });
                    $("#jchat_userstab_text").html(chatTitle + ":" + numUsers)
                } else {
                    $(".jchat_userscontent").append('<div class="jchat_nousers">' + jchat_nousers + "</div>");
                    $("#jchat_userstab_text").html(chatTitle + ":0")
                }
            }
        }})
    }

    (function startApp() {
        initializeOptionsDiv();
        isContentEditable = supportContentEditable();
        if (!isContentEditable) {
            writeElement = '<textarea class="jchat_textarea" ></textarea>';
            writeElementWall = '<textarea class="jchat_textarea jchat_textarea_wall"></textarea>';
            valFunction = "val"
        }
        initializeWall();
        initializeDom();
        if ($.browser.msie && $.browser.version == 7) {
            $("<link/>").attr({href: jchat_livesite + "components/com_jchat/css/fix/ie7fix.css", rel: "stylesheet", type: "text/css"}).appendTo("head")
        }
        $(window).bind("resize", tabSlider);
        b("buddylist", "1");
        b("initialize", "1");
        -b("updatesession", "0");
        $([window, document]).blur(function () {
            J = false
        }).focus(function () {
            if (J == false) {
                l = 1
            }
            J = true
        });
        if ($.jStorage.get("popupPositions", false)) {
            popupPositions = $.jStorage.get("popupPositions")
        }
        ajaxReceive()
    })()
}
jQuery(function (a) {
    a.jchat(a);
    msgNotify = new jchatSounds("alert.mp3", "bonk.mp3", "sent_file.mp3", "downloaded_file.mp3");
    msgNotify.registerEvents()
});