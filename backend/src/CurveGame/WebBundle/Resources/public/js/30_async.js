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
        poll: "queue/poll",
        position: "queue/position",
        confirm: "queue/confirm/ready",
        deleteProfile: "user/delete/profile",
        heartbeat: "queue/heartbeat/"
    },
    getHighscores: function() {
        if (debug)
            console.log("Initiating... async.getHighscores()");

        $.ajax({
            type: 'GET',
            url: async.rootUrl + async.api.heartbeat + user.hash,
            contentType: "application/json; charset=utf-8",
            dataType: "json"
        }).done(function(data, textStatus, jqXHR) {
            if (debug)
                console.log("Response... async.getHighscores(): " + JSON.stringify(data));

            $("#scores").html("Your personal highscore of this game is <strong>" + data.score + "</strong> points! Well done!");

        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (debug)
                console.log("Server reported an error when trying to GET the current queue position (queue.poll.ajax->error). Got header: " + jqXHR.status);

            intervalQueueHbId = setTimeout(function() {
                thisObj.heartbeat();
            }, refreshPollInterval);
        });
    },
    sendUnityCtrlCmd: function(data) {
        if (debug)
            console.log("Initiating... async.sendUnityCtrlCmd(): --controlDigit: " + JSON.stringify(data));

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
                    console.log("Server reported an error when trying to POST a command (sendUnityCtrlCmd.ajax->error). Got header: " + jqXHR.status);
                user.repeat();
            }
        });
    },
    sendReadySignal: function() {
        if (debug)
            console.log("Initiating... async.sendReadySignal()");

        var data = {
            hash: user.hash
        };

        $.ajax({
            method: "POST",
            url: this.rootUrl + this.api.confirm,
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data) {
                if (debug)
                    console.log("Response... async.sendReadySignal(): --message: " + data.message);
                if (data.message === "success") {
                    user.color = data.color;
                    controls.init();
                    controls.isEnabled = true;
                    ws.connect();
                    $.isLoading("hide");
                    $("#leftControl").addClass(user.color + "_unpushed_left");
                    $("#rightControl").addClass(user.color + "_unpushed_right");
                    slider.next();
                    queue.heartbeat();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (debug)
                    console.log("Server reported an error when trying to POST a command (sendReadySignal.ajax->error). Got header: " + jqXHR.status);
                user.repeat();
            }
        });
    }
};