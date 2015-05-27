/**
 * Sends control data in AJAX request
 */
function sendAjaxCommand(userId, command) {

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
 * End of control data functions
 */