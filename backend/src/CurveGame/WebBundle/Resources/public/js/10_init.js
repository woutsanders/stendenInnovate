/**
 * Initializing variables to use during this session.
 */
var debug = true;                   // Enable/Disable debug mode (console logging)


/**
 * Holds all user related data.
 * Will initially be empty.
 *
 * @type {{id: null, name: null}}
 */
var user = {
    "id": null,
    "name": null
};


/**
 * Custom wrapper for ajax requests.
 *
 * @type {{send: *}}
 */
var async = {
    "rootUrl": $("#twigHack").data('api'),
    "send": function(msg, url) {
        if (debug)
            console.log("Initiating... ajaxSendMsg: --userId: " + userId + " --controlType: " + controlType);
        $.ajax({
            type: 'POST',
            url: this.rootUrl + url,
            data: JSON.stringify(msg),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
                if (debug)
                    console.log("Response... ajaxSendMsg: --message: " + data.message + " --controlType: " + data.moveTo);
            },
            error: function(jqXHR, textStatus, errorThrown){
                if (debug)
                    console.log("Server reported an error when trying to POST a command (ajaxSendMsg.ajax->error). Got header: " + jqXHR.status);
            }
        });
    }
};

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

/**
 * Custom wrapper for the websocket.
 *
 * @type {{url: (*|jQuery), port: number, socket: WebSocket, supported: boolean, checkSupport: Function, connect: Function, send: Function, close: Function}}
 */
var ws = {
    "url": $("#twigHack").data('host'),
    "port": 9989,
    "socket": WebSocket,
    "supported": false,
    "checkSupport": function() {
        return this.supported = ((window.WebSocket || (typeof(WebSocket) == "object")) ? true : false);
    },
    "connect": function() {
        this.socket = new WebSocket("ws://" + this.url + ":" + this.port + "/curvegame");
    },
    "send": function(msg) {
        this.socket.send(JSON.stringify(msg));
    },
    "close": function() {
        this.socket.close();
    }
};

/**
 * Holds all controller specific functionality
 *
 * @type {{left: number, straight: number, right: number, moveTo: Function, send: Function}}
 */
var controls = {
    "keys": {
        "left": 37,
        "right": 39
    },
    "left": 1,
    "straight": 0,
    "right": 2,
    "isDown": false,
    "isEnabled": false,
    "moveTo": function(movement) {
        if (this.isDown || !this.isEnabled) return;

        if (movement == "straight") {
            this.send(this.straight);
        }
        else if (movement == "left") {
            this.send(this.left);
        }
        else if (movement == "right") {
            this.send(this.right);
        } else {
            console.log("invalid control choice given!");
        }
    },
    "send": function(moveDigit) {

        var data = {
            "userId": user.id,
            "moveTo": moveDigit
        };

        //Use websockets if available...
        if (ws.supported) {
            ws.send(data);
        } else {
            async.send(data);
        }
    }
};