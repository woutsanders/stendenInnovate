/**
 * Custom wrapper for the websocket.
 *
 * @type {{url: (*|jQuery), port: number, socket: WebSocket, supported: boolean, checkSupport: Function, connect: Function, send: Function, close: Function}}
 */
var ws = {
    url: $("#twigHack").data('host'),
    port: 9989,
    socket: undefined,
    supported: false,
    checkSupport: function() {
        return this.supported = ((window.WebSocket || (typeof(WebSocket) == "object")) ? true : false);
    },
    connect: function() {
        this.socket = new WebSocket("ws://" + this.url + ":" + this.port + "/curvegame");
    },
    sendUnityCtrlCmd: function(msg) {
        if (this.socket != null)
            this.socket.send(JSON.stringify(msg));
    },
    close: function() {
        if (this.socket != null)
            this.socket.close();
    }
};