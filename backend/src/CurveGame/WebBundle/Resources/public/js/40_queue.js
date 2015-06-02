/**
 * Holds all queue functionality.
 *
 * @type {{position: *}}
 */
var queue = {
    "onTurn": false,
    "position": function() {
        if (debug) console.log("Initiating... getQueuePosition: --userId: " + userId);

        $.ajax({
            type: 'GET',
            url: ajaxRootURL + 'queue/position/' + userId,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                if (debug)
                    console.log("Response... getQueuePosition: --userId: " + data.userId + " --position: " + data.position);
                positionCallback(data);
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (debug)
                    console.log("Server reported an error when trying to GET the current queue position (getQueuePosition.ajax->error). Got header: " + jqXHR.status);
            }
        });
    }
};