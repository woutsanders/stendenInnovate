/**
 * Initializing variables to use during this session.
 */
var debug = true;                   // Enable/Disable debug mode (console logging)
var loaderOpts = {
    'position': "overlay",              // right | inside | overlay
    'text': "Loading, please wait...",  // Text to display next to the loader
    'class': "fa-refresh",              // loader CSS class
    'tpl': '<span class="isloading-wrapper %wrapper%">%text%<i class="fa %class% fa-spin"></i></span>',
    'disableSource': true,              // true | false
    'disableOthers': []
};

var user = {                        // Holds user related settings
    id: undefined,
    name: undefined,
    status: undefined,
    color: undefined,
    register: function(username) {
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
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (debug)
                    console.log("Server reported an error when trying to POST a command (ajaxSendMsg.ajax->error). Got header: " + jqXHR.status);
                alert("An error occurred. Please reload the browser to try again");
            }
        });
    }
};