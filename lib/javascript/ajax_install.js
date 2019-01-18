/*global window, $, alert, console, action_data */
function extractFiles(stepId) {
    'use strict';
    var current_url, requests, ajax_url, done_url;

    current_url = window.location.href;
    ajax_url = current_url.replace(/\/execute$/, "/ajaxexecute");
    done_url = current_url.replace(/\/execute$/, "/done");

    if (typeof stepId === "undefined") {
        stepId = 0;
    }

    $('#status').append('<h2>' + action_data[stepId].ui_string + '...</h2>');
    $('#status').append('<p>' + action_data[stepId].ui_hint + '</p>');
    // $('#status').append('<p><code>data: number_of_installations: ' + installer_config["number_of_installations"] + '</code></p>');

    requests = [];
    for (var i = 0; i < installer_config["number_of_installations"]; i++) {
      requests[i] = $.ajax({
          method: "POST",
          url: ajax_url,
          data: {
              extract: true,
              stepId: stepId,
              stepShopIndex: i,
              installer_config: installer_config
          }
      });
      
      requests[i].done(function (msg) {
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
                  || typeof msg.lastStepId === "undefined"
                  || typeof msg.stepsTotal === "undefined"
          ) {
              $("#error").html("An error has occured: <br />" + msg.message);
              $('#error').removeClass('d-none');
              $("#error").show();
              $("#spinner").remove();
              return false;
          }

          $('#status').append('<p>Done!</p>');

          $("#progressContainer")
              .find("#installprogress")
              .width((msg.lastStepId / msg.stepsTotal * 100) + "%");

          $("#progressContainer")
              .find(".progressNumber")
              .css({left: Math.round((msg.lastStepId / msg.stepsTotal * 100)) + "%"})
              .html(Math.round((msg.lastStepId / msg.stepsTotal * 100)) + "% ("+msg.lastStepId+"/"+msg.stepsTotal+")");
      });
      requests[i].fail(function (jqXHR, textStatus, errorThrown) {
          $("#error").html("An error has occurred" + textStatus);
          $("#error").show();
          $("#spinner").remove();
      });
    }

    // After all requests complete, move on to next step
    $.when.apply($, requests).then(function(){
      // if no error occured continue...
      if ((stepId + 1) >= action_data.length) {
          // end
          // window.location.href = done_url;
          $("#spinner").remove();
          $('#status').append('<h2>Installation completed!</h2>');
      } else {
        extractFiles(stepId + 1);
      }
    })
}

$(function () {
    'use strict';
    extractFiles();
});