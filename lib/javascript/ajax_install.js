/*global window, $, alert, console, action_data */

function executeStep(stepId, shopIndex) {
  'use strict';
  console.log("executeStep " + stepId + "for shopIndex " + shopIndex);

  if(shopIndex > installer_config["number_of_installations"]) {
    shopIndex = 1;
    stepId++;
  }
  if(stepId < action_data.length) {
    if(shopIndex==1) {
      $('#status').append('<h2>' + action_data[stepId].ui_string + '...</h2>');
      $('#status').append('<p>' + action_data[stepId].ui_hint + '</p>');
    }
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
        },
        timeout: 0, // disable timeout
    });

    request.done(function (msg) {
      // $('#status').append('<code>' + msg + '</code>');
        try {
            msg = JSON.parse(msg);
        } catch (e) {
        }
        $('#status').append('<p>' + msg.message.replace(/\n/, "<br>") + '</p>');

        if (
            msg.error
                || typeof msg.lastStepId === "undefined"
                || typeof msg.stepsTotal === "undefined"
        ) {
            $("#error").html("An error has occured: <br />" + msg.message);
            $("#error").show();
            $("#spinner").remove();
            throw new Error("EXIT: An error has occured: " + msg.message);
            return false;
        }

        // $('#status').append('<p>Done!</p>');

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
        throw new Error("EXIT: An error has occured: \n\t" + textStatus  + "\n\t" + errorThrown + "\n\t" + jqXHR.responseText + "\n\t" + jqXHR.status + "\n\t" + jqXHR.statusText);
        console.log("EXIT: An error has occured: \n\t" + textStatus  + "\n\t" + errorThrown + "\n\t" + jqXHR.responseText + "\n\t" + jqXHR.status + "\n\t" + jqXHR.statusText);
        return true;
    });

    // After request completes, move on to next step
    $.when(request).then(function() {
      console.log("Request completed");
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