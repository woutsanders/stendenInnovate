/**
 * Checks whether there is WebSocket support
 * @return bool True if WS is supported, else false.
 */
function wsCheckSupport() {

    return typeof(WebSocket) == "function";
}

/**
 * Creates a socket connection and connects to the server.
 */
function wsConnect() {

    ws = new WebSocket("ws://" + wsURL + ":"+ wsPort +"/ws");
}

/**
 * Sends the given message to the server.
 * @param playerId
 * @param control
 */
function wsSendMsg(playerId, control) {

    data =
    ws.send(msg);
}

/**
 * Closes the socket connection when game is over.
 */
function wsClose() {

    ws.close();
}

ws.onmessage(function(e) {
    //Do further handling...
});