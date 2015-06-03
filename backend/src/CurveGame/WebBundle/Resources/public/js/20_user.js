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
    register: function(username, repeat) {
        if (debug)
            console.log("Initiating user registration... user.register(): --username: " + username);

        if (username.length < 4) {
            $.isLoading("hide");
            if (debug)
                console.log("Error in user.register(): Given username is too small (min. of 4 chars) required");
            alert("The username needs to be at least 4 characters long");
            return;
        }

        if (repeat) {
            var data = {
                username: username,
                id: this.id,
                repeat: "y"
            };
        } else {
            var data = {
                "username": username
            };
        }

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

                intervalQueuePosId = setTimeout(queue.position(), refreshPosInterval);
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (debug)
                    console.log("Server reported an error when trying to POST a command (ajaxSendMsg.ajax->error). Got header: " + jqXHR.status);
                alert("This username already exists! Please choose a different one");
                $.isLoading("hide");
            }
        });
    },
    repeat: function() { //If user wants to play again, reset everything and respawn in queue...
        var thisObj = this;
        alert("You have been disconnected due to an error, time-out or loss of connection. You will respawn in the queue");
        slider.goto("#register", "slide-top");
        setTimeout(function() {
            $.isLoading(loaderOpts)
        }, 500);

        clearTimeout(intervalQueuePollId);
        clearTimeout(intervalQueuePosId);
        intervalQueuePollId = undefined;
        intervalQueuePosId = undefined;
        readySignalTimerTick = 12;

        setTimeout(function() {
            thisObj.register(thisObj.name, true);
        }, 800);
    }
};