/**
 * Custom wrapper for ajax requests.
 *
 * @type {{send: *}}
 */
var async = {
    "rootUrl": $("#twigHack").data('api'),
    "send": function(data, url) {
        if (debug)
            console.log("Initiating... async.send...");
        $.ajax({
            type: 'POST',
            url: this.rootUrl + url,
            data: JSON.stringify(msg),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                if (debug)
                    console.log("Response... async.send: --data: " + JSON.stringify(data));
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (debug)
                    console.log("Server reported an error when trying to POST a command (ajaxSendMsg.ajax->error). Got header: " + jqXHR.status);
            }
        });
    }
};