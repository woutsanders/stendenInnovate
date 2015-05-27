/**
 * Document functions
 */
$(document).ready(function() {
    if (!wsCheckSupport()) {

        $("body").html("<h1>WebSocket error!</h1><p>Deze browser lijkt geen Web Sockets te ondersteunen. Download de laatste versie Google Chrome om dit spel te spelen.</p>");
        return;
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
            sendAjaxCommand(userId,leftControl);
        }

        // Go right
        if (event.which == keyRight) {
            sendAjaxCommand(userId, rightControl);
        }
    });

    // Go straight.
    $(document).keyup(function(event) {

        if (event.which == 37 || event.which == 39) {
            sendAjaxCommand(userId,straightControl);
            keyDown = false;
        }
    });
}