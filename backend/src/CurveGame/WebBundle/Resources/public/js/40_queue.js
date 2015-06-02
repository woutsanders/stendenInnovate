/**
 * Holds all queue functionality.
 *
 * @type {{position: *}}
 */
var queue = {
    "onTurn": false,
    "position": function() {
        var returnValue = null;

        if (debug) console.log("Initiating... queue.position: --userId: " + userId);

        $.ajax({
            type: 'GET',
            url: ajaxRootURL + 'queue/position/' + userId,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                if (debug)
                    console.log("Response... queue.position: --userId: " + data.userId + " --position: " + data.position);

                returnValue = {
                    "userId": user.id,
                    "position": data.position
                };
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (debug)
                    console.log("Server reported an error when trying to GET the current queue position (queue.position.ajax->error). Got header: " + jqXHR.status);
            }
        });

        if (returnValue) return returnValue;
    }
};