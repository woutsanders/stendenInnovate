/**
 * Custom wrapper for ajax requests.
 *
 * @type {{send: *}}
 */
var async = {
    rootUrl: $("#twigHack").data('api'),
    result: undefined,
    api: {
        register: "user/register",
        unityCommand: "unity/command",
        poll: "queue/poll/",
        position: "queue/position/",
        confirm: "queue/confirmReady/"
    },
    sendUnityCtrlCmd: function(controlDigit) {
        if (debug)
            console.log("Initiating... async.sendUnityCtrlCmd(): --controlDigit: " + controlDigit);

        var data = {
            "userId": user.id,
            "moveTo": controlDigit
        };

        $.ajax({
            type: 'POST',
            url: this.rootUrl + this.api.unityCommand,
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                if (debug)
                    console.log("Response... async.sendUnityCtrlCmd(): --data: " + JSON.stringify(data));
                async.result = data;
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (debug)
                    console.log("Server reported an error when trying to POST a command (ajaxSendMsg.ajax->error). Got header: " + jqXHR.status);
            }
        });
    },
    sendReadySignal: function() {
        if (debug)
            console.log("Initiating... async.sendReadySignal()");

        $.ajax({
            method: "GET",
            url: this.rootUrl + this.api.confirm + user.id,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data) {
                if (debug)
                    console.log("Response... async.sendReadySignal(): --message: " + data.message);
                if (data.message === "success") {
                    controls.init();
                    controls.isEnabled = true;
                    $.isLoading("hide");
                    slider.next();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.status === 406) {
                    alert("You are kicked out of the queue, because you have pressed the ready button too late. You will restart in the queue again.");
                    user.repeat();
                }
            }
        });
    }
};