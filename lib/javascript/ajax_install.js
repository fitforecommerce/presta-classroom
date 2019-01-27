/*global window, $, alert, console, action_data */

function executeStep(stepId, shopIndex) {
  'use strict';
  console.log("shopIndex " + shopIndex);
  console.log("number_of_installations " + installer_config["number_of_installations"]);

  if(shopIndex > installer_config["number_of_installations"]) {
    shopIndex = 1;
    stepId++;
  }
  if(shopIndex==1) {
    $('#status').append('<h2>' + action_data[stepId].ui_string + '...</h2>');
    $('#status').append('<p>' + action_data[stepId].ui_hint + '</p>');
  }
  if(stepId < action_data.length) {
    console.log("run step for shop " + shopIndex);
    runStepForShop(stepId, shopIndex);
  } else {
    $("#spinner").remove();
    $('#status').append('<h2>Installation completed!</h2>');
  }
  return true;
}

function runStepForShop(stepId, shopIndex) {
    'use strict';
    var request, ajax_url, current_url, current_total_step, total_steps;

    current_url = window.location.href;
    ajax_url = current_url.replace(/\/execute$/, "/ajaxexecute");

    console.log("ajax_url: "+ ajax_url);

    request = $.ajax({
        method: "POST",
        url: ajax_url,
        data: {
            extract: true,
            stepId: stepId,
            stepShopIndex: shopIndex,
            installer_config: installer_config
        }
    });

    request.done(function (msg) {
        $('#status').append('<p><code>' + msg + '</code></p>');

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

        current_total_step = ((msg.lastStepId - 1) * installer_config["number_of_installations"]) + shopIndex;
        total_steps = msg.stepsTotal * installer_config["number_of_installations"];

        $("#progressContainer")
            .find("#installprogress")
            .width((current_total_step / total_steps * 100) + "%");

        $("#progressContainer")
            .find(".progressNumber")
            .css({left: Math.round((current_total_step / total_steps * 100)) + "%"})
            .html(Math.round((current_total_step / total_steps * 100)) + "% ("+current_total_step+"/"+total_steps+")");
    }); // end done

    request.fail(function (jqXHR, textStatus, errorThrown) {
        $("#error").html("An error has occurred" + textStatus);
        $("#error").show();
        $("#spinner").remove();
    });
    // After all requests complete, move on to next step
    $.when(request).then(function() {
      executeStep(stepId, (shopIndex+1));
    });
}

$(function () {
    'use strict';
    if(!window.executeStepCalled) {
      console.log("call executeStep");
      executeStep(0,1);
      window.executeStepCalled = true;
    }
});