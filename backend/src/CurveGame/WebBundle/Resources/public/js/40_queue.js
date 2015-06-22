/**
 * Holds all queue functionality.
 *
 * @type {{position: *}}
 */
var queue = {
    onTurn: undefined,
    heartbeat: function() {
        var thisObj = this;

        if (!user.hash) {
            if (debug)
                console.log("Error: queue.heartbeat(): No user hash known! Perhaps user object not yet populated? Exiting...");
            return;
        }

        if (debug)
            console.log("Initiating... queue.heartbeat(): --userHash: " + user.hash);

        $.ajax({
            type: 'GET',
            url: async.rootUrl + async.api.heartbeat + user.hash,
            contentType: "application/json; charset=utf-8",
            dataType: "json"
        }).done(function(data, textStatus, jqXHR) {
            if (debug)
                console.log("Response... queue.heartbeat(): --header: " + JSON.stringify(data));

            if (jqXHR.status == 204) {

                intervalQueueHbId = setTimeout(function() {
                    thisObj.heartbeat();
                }, refreshPollInterval);
            } else {
                clearTimeout(intervalQueueHbId);
                $.isLoading(loaderOpts);
                async.getHighscores();
                slider.next();
            }

        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (debug)
                console.log("Server reported an error when trying to GET the current queue position (queue.poll.ajax->error). Got header: " + jqXHR.status);

            if (jqXHR.status !== 400) {

                intervalQueueHbId = setTimeout(function() {

                    thisObj.heartbeat();
                }, refreshPollInterval);
            }

            if (jqXHR.status === 400)
                alert("The user does not exist (anymore)");
        });
    },
    poll: function() {
        var thisObj = this;

        if (!user.hash) {
            if (debug)
                console.log("Error: queue.poll(): No user ID known! Perhaps user object not yet populated? Exiting...");
            return;
        }

        var data = {
            hash: user.hash
        };

        if (debug)
            console.log("Initiating... queue.poll(): --userHash: " + user.hash);

        $.ajax({
            type: 'POST',
            url: async.rootUrl + async.api.poll,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify(data)
        }).done(function(data) {
            if (debug)
                console.log("Response... queue.poll(): --onTurn: " + data.onTurn);
            this.onTurn = data.onTurn;
            if (!this.onTurn) {
                intervalQueuePollId = setTimeout(function() {
                    thisObj.poll();
                }, refreshPollInterval);
            } else {
                controls.countdownReadyBtn();
                $("#readyToPlayContainer").fadeIn(500); // Auto deletes display:none CSS directive on finish.
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (debug)
                console.log("Server reported an error when trying to GET the current queue position (queue.poll.ajax->error). Got header: " + jqXHR.status);
            user.repeat();
        });
    },
    position: function() {
        var thisObj = this;

        if (!user.hash) {
            if (debug)
                console.log("Error: queue.position(): No user ID known! Perhaps user object not yet populated? Exiting...");
            return false;
        }

        var data = {
            hash: user.hash
        };

        if (debug)
            console.log("Initiating... queue.position(): --userHash: " + user.hash);

        $.ajax({
            type: 'POST',
            url: async.rootUrl + async.api.position,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            data: JSON.stringify(data)
        }).done(function(data) {
            if (debug)
                console.log("Response... queue.position(): --userHash: " + data.hash + " --position: " + data.position);

            if (data.position < 2) {
                $("#positionNum").html("You're up next!");
                thisObj.poll();
            } else {
                $("#positionNum").html("You are on position " + data.position + " in the queue.");
                intervalQueuePosId = setTimeout(function() {
                    thisObj.position();
                }, refreshPosInterval);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (debug)
                console.log("Server reported an error when trying to GET the current queue position (queue.position.ajax->error). Got header: " + jqXHR.status);
            user.repeat();
        });
    }
};