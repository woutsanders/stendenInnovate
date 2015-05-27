/**
 * Sends the control digit, received from the DOM.
 *
 * @param userId
 * @param command
 */
function sendAjaxControlCmd(userId, command) {

    var data = {
        "userId": userId,
        "moveTo": command
    };

    // Send the data.
    $.ajax({
        type: 'POST',
        url: rootURL + 'unity/command',
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data){
            return true;
        },
        error: function(jqXHR, textStatus, errorThrown){
            var html =  "<p>control fail<br/>";
            html += "Got header: " + jqXHR.status;
            alert(html);
        }
    });
}

/**
 *
 */
function send() {

    $.ajax({
        type: 'GET',
        url: rootURL + 'queue/position/' + userId,
        dataType: "json",
        success: function(data){
            var html =  "<p>UserID: " + data.userId +
                "<br/>Position: " + data.position +
                "</p>";

            $("#test").html(html);
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('The server made a boo boo (HTTP errno): ' + jqXHR.status);
        }
    });
}