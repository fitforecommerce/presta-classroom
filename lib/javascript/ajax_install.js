/*global window, $, alert, console, action_data */
function extractFiles(startId) {
    'use strict';
    var current_url, request, ajax_url, done_url;

    current_url = window.location.href;
    ajax_url = current_url.replace(/\/execute$/, "/ajaxexecute");
    done_url = current_url.replace(/\/execute$/, "/done");

    if (typeof startId === "undefined") {
        startId = 0;
    }

    $('#status').append('<h2>' + action_data[startId].ui_string + '...</h2>');
    $('#status').append('<p>' + action_data[startId].ui_hint + '</p>');
    // $('#status').append('<p><code>data: number_of_installations: ' + installer_config["number_of_installations"] + '</code></p>');

    request = $.ajax({
        method: "POST",
        url: ajax_url,
        data: {
            extract: true,
            startId: startId,
            installer_config: installer_config
        }
    });

    request.done(function (msg) {
        $('#status').append('<code>' + msg + '</code>');

        try {
            msg = JSON.parse(msg);
        } catch (e) {
            if (String(msg).match("<title>PrestaShop")) {
                msg = "Invalid server response";
            }
            msg = {
                message: msg
            };
        }

        if (
            msg.error
                || typeof msg.lastId === "undefined"
                || typeof msg.numFiles === "undefined"
        ) {
            $("#error").html("An error has occured: <br />" + msg.message);
            $('#error').removeClass('d-none');
            $("#error").show();
            $("#spinner").remove();
            return false;
        }

        $('#status').append('<p>Done!</p>');

        // if no error occured continue...
        if (msg.lastId >= msg.numFiles) {
            // end
            // window.location.href = done_url;
            $("#spinner").remove();
            $('#status').append('<h2>Installation completed!</h2>');
        }
        $("#progressContainer")
            .find("#installprogress")
            .width((msg.lastId / msg.numFiles * 100) + "%");

        $("#progressContainer")
            .find(".progressNumber")
            .css({left: Math.round((msg.lastId / msg.numFiles * 100)) + "%"})
            .html(Math.round((msg.lastId / msg.numFiles * 100)) + "%");

        extractFiles(msg.lastId);
    });

    request.fail(function (jqXHR, textStatus, errorThrown) {
        $("#error").html("An error has occurred" + textStatus);
        $("#error").show();
        $("#spinner").remove();
    });
}

$(function () {
    'use strict';
    extractFiles();
});