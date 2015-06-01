/**
 * Document functions
 */
$(document).ready(function() {
    if (!ws.checkSupport()) {
        alert("Deze browser lijkt geen WebSockets te ondersteunen. Voor de beste beleving adviseren wij om Google Chrome te installeren");
    }

    //Original code (separated): $('#screens section').screen({
    slider.init();
});
/**
 * End of document wide functions
 */

/**
 * Keyboard handling
 */
function handleKeys() {

    $(document).keydown(function(event) {
        controls.isDown = true;

        if (event.which == controls.keys.left)
            controls.send("left");

        if (event.which == controls.keys.right)
            controls.send("right");
    });

    // Go straight.
    $(document).keyup(function(event) {

        if (event.which == controls.keys.left || event.which == controls.keys.right) {
            controls.send("straight");
            controls.isDown = false;
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
    e.preventDefault();

    var origIsDown = controls.isDown;
    controls.isDown = false;
    controls.send("left");
    controls.isDown = origIsDown;
});

// Go right when mouse or touch detected.
$("#rightControl").on("mousedown touchstart", function(e) {
    e.preventDefault();

    var origIsDown = controls.isDown;
    controls.isDown = false;
    controls.send("right");
    controls.isDown = origIsDown;
});

// When releasing mouse or touch, go straight.
$(".controls").on("mouseup touchend", function(e) {
    e.preventDefault();

    var origIsDown = controls.isDown;
    controls.isDown = false;
    controls.send("straight");
    controls.isDown = origIsDown;
});
/**
 * End mouse/touch controls
 */