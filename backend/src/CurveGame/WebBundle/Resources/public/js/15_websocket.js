/**
 * Custom wrapper for the websocket.
 */
var ws = {
    url: $("#twigHack").data('host'),
    port: 9989,
    socket: undefined,
    supported: undefined,
    checkSupport: function() {
        if (!ws.supported) {
            if (window.WebSocket || (typeof(WebSocket) == "object")) {
                ws.supported = true;
                return true;
            } else {
                ws.supported = false;
                return false;
            }
        }
    },
    connect: function() {
        if (!this.supported)
            return;
        if (debug)
            console.log("Attempting WS connect()... ws.connect()");

        ws.socket = new WebSocket("ws://" + this.url + ":" + this.port + "/curvegame");
        ws.onmessage = function(data) {
            if (debug)
                console.log("ws.onmessage(): Received: --data: " + data);
        };

        if (ws.socket) {
            if (debug)
                console.log("Connected with server in ws.connect()");
        } else {
            if (debug)
                console.log("Error: WebSocket could not connect in ws.connect()");
        }
    },
    sendUnityCtrlCmd: function(msg) {
        if (ws.socket != undefined) {
            var data = JSON.stringify(msg);
            if (debug)
                console.log("WebSocket: Data sent in ws.sendUnityCtrlCmd(): --msg: " + data);
            ws.socket.send(data);
        }
    },
    close: function() {
        if (this.socket != undefined) {
            this.socket.close();
            if (debug)
                console.log("WebSocket: Closed connection explicitly in ws.close()");
        }
    }
};