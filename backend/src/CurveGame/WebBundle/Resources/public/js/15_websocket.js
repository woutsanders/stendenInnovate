/**
 * Custom wrapper for the websocket.
 */
var ws = {
    url: $("#twigHack").data('host'),
    port: 9989,
    socket: undefined,
    supported: undefined,
    checkSupport: function() {
        return (!this.supported ? ((window.WebSocket || (typeof(WebSocket) == "object")) ? true : false) : this.supported);
    },
    connect: function() {
        if (debug)
            console.log("Attempting WS connect()... ws.connect()");
        this.socket = new WebSocket("ws://" + this.url + ":" + this.port + "/curvegame");
        if (this.socket) {
            if (debug)
                console.log("Connected with server in ws.connect()");
        } else {
            if (debug)
                console.log("Error: WebSocket could not connect in ws.connect()");
        }
    },
    sendUnityCtrlCmd: function(msg) {
        if (this.socket != null) {
            var data = JSON.stringify(msg);
            if (debug)
                console.log("Initiating... ws.sendUnityCtrlCmd(msg): --msg: " + data);
            if(this.socket.send(JSON.stringify(data))) {
                if (debug)
                    console.log("WebSocket: Data sent in ws.sendUnityCtrlCmd(): --msg: " + data);
            } else {
                if (debug)
                    console.log("Error: WebSocket could not sent data to server in ws.sendUnityCtrlCmd()");
            }
        }
    },
    close: function() {
        if (this.socket != null)
            if (this.socket.close()) {
                if (debug)
                    console.log("WebSocket: Closed connection explicitly in ws.close()");
            } else {
                if (debug)
                    console.log("Error: WebSocket could not close connection in ws.close()");
            }
    }
};