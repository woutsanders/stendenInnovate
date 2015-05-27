/**
 * Document functions
 */
$(document).ready(function() {
    if ((typeof(wsCheckSupport) == "function") && !wsCheckSupport()) {
        //$("body").html("<h1>WebSocket error!</h1><p>Deze browser lijkt geen Web Sockets te ondersteunen. Download de laatste versie van Google Chrome om dit spel te spelen.</p>");
        //return;
        alert("Deze browser lijkt geen WebSockets te ondersteunen. Voor de beste beleving adviseren wij Google Chrome te installeren");
    } else {
        wsSupport = true;
    }

    handleKeys();
});
/**
 * End of document wide functions
 */

/**
 * Keyboard handling
 */
function handleKeys() {

    $(document).keydown(function(event) {

        if (keyDown == true) {
            return;
        }

        keyDown = true;

        // Go left
        if (event.which == keyLeft) {
            sendMsg(userId, leftControl);
        }

        // Go right
        if (event.which == keyRight) {
            sendMsg(userId, rightControl);
        }
    });

    // Go straight.
    $(document).keyup(function(event) {

        // Cancel movement and return to neutral status...
        if (event.which == keyLeft || event.which == keyRight) {
            sendMsg(userId, straightControl);
            keyDown = false;
        }
    });
}
/**
 * End of keyboard handling
 */

/**
 * Mouse & Touch handling
 */
    // Go left when mouse or touch detected.
$("#leftControl").on("mousedown touchstart", function(e) {
    ajaxSendMsg(userId, leftControl);
});

// Go right when mouse or touch detected.
$("#rightControl").on("mousedown touchstart", function(e) {
    ajaxSendMsg(userId, rightControl);
});

// When releasing mouse or touch, go straight.
$(".controls").on("mouseup touchend", function(e) {
    ajaxSendMsg(userId, straightControl);
});
/**
 * End mouse/touch controls
 */