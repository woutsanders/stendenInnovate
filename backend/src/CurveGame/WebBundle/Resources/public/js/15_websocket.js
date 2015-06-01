/**
 * Checks whether there is WebSocket support
 * @return bool True if WS is supported, else false.
 */
function wsCheckSupport() {

    return ((window.WebSocket || (typeof(WebSocket) == "object")) ? true : false);
}

/**
 * Creates a socket connection and connects to the server.
 */
function wsConnect() {

    ws = new WebSocket("ws://" + wsURL + ":" + wsPort + "/curvegame");

//    ws.onmessage(function(e) {
//        console.log(e);
//    });
}

/**
 * Sends the given message to the server.
 * @param userId
 * @param control
 */
function wsSendMsg(userId, control) {

    var data = {
        "userId": userId,
        "direction": control
    };

    ws.send(JSON.stringify(data));
}

/**
 * Closes the socket connection when game is over.
 */
function wsClose() {

    ws.close();
}