/**
 * Holds all queue functionality.
 *
 * @type {{position: *}}
 */
var queue = {
    onTurn: undefined,
    poll: function() {
        var thisObj = this;

        if (!user.id) {
            if (debug)
                console.log("Error: queue.poll(): No user ID known! Perhaps user object not yet populated? Exiting...");
            return;
        }

        if (debug)
            console.log("Initiating... queue.poll(): --userId: " + user.id);

        $.ajax({
            type: 'GET',
            url: async.rootUrl + async.api.poll + user.id,
            contentType: "application/json; charset=utf-8",
            dataType: "json"
        }).done(function(data) {
            if (debug)
                console.log("Response... queue.poll(): --onTurn: " + data.onTurn);
            this.onTurn = data.onTurn;
            if (this.onTurn) {
                intervalQueuePollId = setTimeout(function() {
                    thisObj.poll();
                }, refreshPollInterval);
            }
            return true;
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (debug)
                console.log("Server reported an error when trying to GET the current queue position (queue.poll.ajax->error). Got header: " + jqXHR.status);
            return false;
        });
    },
    position: function() {
        var thisObj = this;

        if (!user.id) {
            if (debug)
                console.log("Error: queue.position(): No user ID known! Perhaps user object not yet populated? Exiting...");
            return false;
        }

        if (debug) console.log("Initiating... queue.position(): --userId: " + user.id);

        intervalQueuePosId = setTimeout(function() {
            thisObj.position();
        }, refreshPosInterval);

        $.ajax({
            type: 'GET',
            url: async.rootUrl + async.api.position + user.id,
            contentType: "application/json; charset=utf-8",
            dataType: "json"
        }).done(function(data) {
            if (debug)
                console.log("Response... queue.position(): --userId: " + data.userId + " --position: " + data.position);

            if (data.position < 5) {
                $("#positionNum").html("You're up next!");
                intervalQueuePollId = setTimeout(function() {
                    thisObj.poll();
                }, refreshPollInterval);
                clearTimeout(intervalQueuePosId);
            } else {
                $("#positionNum").html("You are on position " + data.position + " in the queue.");
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (debug)
                console.log("Server reported an error when trying to GET the current queue position (queue.position.ajax->error). Got header: " + jqXHR.status);
            return false;
        });
    }
};