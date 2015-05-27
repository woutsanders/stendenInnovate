/**
 * Sends the control digit, received from the DOM.
 *
 * @param userId
 * @param controlType
 */
function ajaxSendMsg(userId, controlType) {

    if (debug) console.log("Initiating... ajaxSendMsg: --userId: " + userId + " --controlType: " + controlType);

    var data = {
        "userId": userId,
        "moveTo": controlType
    };

    // Send the data.
    return $.ajax({
        type: 'POST',
        url: rootURL + 'unity/command',
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data){

            if (debug) console.log("Response... ajaxSendMsg: --message: " + data.message + " --controlType: " + data.moveTo);
            return true;
        },
        error: function(jqXHR, textStatus, errorThrown){
            if (debug) console.log("Server reported an error when trying to POST a command (ajaxSendMsg.ajax->error). Got header: " + jqXHR.status);
        }
    });
}

/**
 * Retrieves the current position in the queue.
 */
function getQueuePosition() {

    return $.ajax({
        type: 'GET',
        url: rootURL + 'queue/position/' + userId,
        dataType: "json",
        success: function(data){

            data = {
                "userId": data.userId,
                "position": data.position
            };

            return data;
        },
        error: function(jqXHR, textStatus, errorThrown){
            if (debug) console.log("Server reported an error when trying to GET the current queue position (getQueuePosition.ajax->error). Got header: " + jqXHR.status);
        }
    });
}