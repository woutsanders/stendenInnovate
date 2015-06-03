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
        confirm: "/queue/confirmReady"
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
    }
};