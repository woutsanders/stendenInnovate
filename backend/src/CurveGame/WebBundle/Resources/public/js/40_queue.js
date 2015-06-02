/**
 * Holds all queue functionality.
 *
 * @type {{position: *}}
 */
var queue = {
    "isOnTurn": undefined,
    "onTurn": function() {

    },
    "position": function() {
        if (debug) console.log("Initiating... queue.position: --userId: " + user.id);

        $.ajax({
            type: 'GET',
            url: async.rootUrl + 'queue/position/' + user.id,
            contentType: "application/json; charset=utf-8",
            dataType: "json"
        }).done(function(data) {
            if (debug)
                console.log("Response... queue.position: --userId: " + data.userId + " --position: " + data.position);

            if (data.position == 1 ||
                data.position == 2 ||
                data.position == 3 ||
                data.position == 4)
            {
                $("#positionNum").html("You're up next!");
            } else {
                $("#positionNum").html(data.position);
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            if (debug)
                console.log("Server reported an error when trying to GET the current queue position (queue.position.ajax->error). Got header: " + jqXHR.status);
            return false;
        });
    }
};