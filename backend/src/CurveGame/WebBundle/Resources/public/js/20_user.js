/**
 * Holds user related tasks and properties.
 *
 * @type {{id: undefined, name: undefined, status: undefined, color: undefined, register: Function}}
 */
var user = {                            // Holds user related settings
    id: undefined,
    name: undefined,
    status: undefined,
    color: undefined,
    register: function(username) {
        if (debug)
            console.log("Initiating user registration... user.register(): --username: " + username);

        if (username.length < 4) {
            $.isLoading("hide");
            if (debug)
                console.log("Error in user.register(): Given username is too small (min. of 4 chars) required");
            alert("The username needs to be at least 4 characters long");
            return;
        }

        var data = {
            "username": username
        };

        $.ajax({
            type: 'POST',
            url: async.rootUrl + async.api.register,
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                if (debug)
                    console.log("Response... user.register: --data: " + JSON.stringify(data));

                user.id = data.userId;
                user.name = data.username;
                user.status = data.status;
                user.color = data.color;

                $.isLoading("hide");
                slider.next();

                intervalQueuePosId = setInterval(queue.position(), refreshInterval);
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (debug)
                    console.log("Server reported an error when trying to POST a command (ajaxSendMsg.ajax->error). Got header: " + jqXHR.status);
                $.isLoading("hide");
                alert("This username already exists! Please choose a different one");
            }
        });
    },
    repeat: function() { //If user wants to play again.

    }
};