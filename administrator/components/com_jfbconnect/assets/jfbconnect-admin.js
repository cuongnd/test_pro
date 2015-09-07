/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
if (typeof jfbcJQuery == "undefined")
    jfbcJQuery = jQuery;

var jfbcAdmin = {
    request: {
        send: function (start)
        {
            var statusDiv = jfbcJQuery('#sendStatus');
            if (start)
            {
                statusDiv.html("<h2>Sending in progress...</h2>This may take a few minutes. Please do not navigate away from this page until complete!");
            }
            jfbcAdmin.ajax('option=com_jfbconnect&controller=request&task=send', jfbcAdmin.request.updateStatus);
        },

        updateStatus: function (req)
        {
            var status = jfbcJQuery.parseJSON(req);
            var statusDiv = jfbcJQuery('#sendStatus');
            statusDiv.html("<h2>Sending in progress...</h2>" +
                "<p>This may take a few minutes. Please do not navigate away from this page until complete!<br/><br/>" +
                "<strong>Total Sent</strong>: " + status.sendCount + "<br/><i>(" + status.sendSuccess + " Successful / " +
                status.sendFail + " Fail)</i></p>");

            if (status.inProgress)
                jfbcAdmin.request.send(false);
            else
                statusDiv.html("<h2>Send Complete!</h2>" +
                    "<p><strong>Total Sent</strong>: " + status.sendCount + "<br/><i>(" + status.sendSuccess + " Successful / " +
                    status.sendFail + " Fail)</i></p>" +
                    "<h4><a href=\"index.php?option=com_jfbconnect&controller=notification&task=display&requestid=" + status.requestId + "\">Go to the Notifications Area for this Request</a></h4>");
        }
    },

    ajax: function (url, callback)
    {
        jfbcJQuery.ajax({url: 'index.php',
                data: url}
        ).done(callback);
    },

    autotune: {
        enablePlugin: function (name, status)
        {
            form = document.getElementById('adminForm');
            form.pluginName.value = name;
            form.pluginStatus.value = status;
            form.task.value = 'publishPlugin';
            form.submit();
        }
    },

    scsocialwidget: {
        fetchWidgets: function (name)
        {
            jfbcAdmin.ajax('option=com_jfbconnect&controller=ajax&task=scsocialprovider&provider=' + name + '&id=' + sc_modid, jfbcAdmin.scsocialwidget.showWidgets);
        },

        showWidgets: function (ret)
        {
            jfbcJQuery('#widget_settings').html("");
            jfbcJQuery('#widget_list').html(ret);
            jfbcJQuery("jform[params][widget_type]").val("widget");
        },

        fetchSettings: function (name)
        {
            if (name == 'widget')
                jfbcJQuery('#widget_settings').html("");
            else
            {
                var provider = jfbcJQuery("#jform_params_provider_type").val();
                jfbcAdmin.ajax('option=com_jfbconnect&controller=ajax&task=scsocialwidget&provider=' + provider + '&name=' + name + '&id=' + sc_modid, jfbcAdmin.scsocialwidget.showSettings);
            }
        },

        showSettings: function (ret)
        {
            jfbcJQuery('#widget_settings').html(ret);
        }
    },

    channels: {
        outbound: {
            fetchChannels: function (name)
            {
                jfbcJQuery('#channel-attribs').html("Please select a Provider and Channel type above.");
                if (name != '--')
                    jfbcAdmin.ajax('option=com_jfbconnect&controller=ajax&task=channelGetOutboundChannels&provider=' + name, jfbcAdmin.channels.outbound.showChannels);
            },
            showChannels: function (ret)
            {
                jfbcJQuery('#channel-attribs').html("Please select a Provider and Channel type above.");
                jfbcJQuery('#jform_type').parent().html(ret);
            },
            fetchChannelSettings: function (name)
            {
                var provider = jfbcJQuery("#jform_provider").val();
                jfbcAdmin.ajax('option=com_jfbconnect&controller=ajax&task=channelGetOutboundChannelSettings&provider=' + provider + '&channel=' + name, jfbcAdmin.channels.outbound.showSettings);
            },
            showSettings: function (ret)
            {
                jfbcJQuery('#channel-attribs').html("Please hit save to load settings for the channel selected.");
//                jfbcJQuery('#channel-attribs').html(ret);
            }
        }
    }
}