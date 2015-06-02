/**
 * Document functions
 */
$(document).ready(function() {
    if (!ws.checkSupport()) {
        alert("Deze browser lijkt geen WebSockets te ondersteunen. Voor de beste beleving adviseren wij om Google Chrome te installeren");
    }

    slider.init();
    slider.next();
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
            controls.moveTo(controls.left);

        if (event.which == controls.keys.right)
            controls.moveTo(controls.right);
    });

    // Go straight.
    $(document).keyup(function(event) {

        if (event.which == controls.keys.left || event.which == controls.keys.right) {
            controls.moveTo(controls.straight);
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
    controls.moveTo(controls.left);
    controls.isDown = origIsDown;
});

// Go right when mouse or touch detected.
$("#rightControl").on("mousedown touchstart", function(e) {
    e.preventDefault();

    var origIsDown = controls.isDown;
    controls.isDown = false;
    controls.moveTo(controls.right);
    controls.isDown = origIsDown;
});

// When releasing mouse or touch, go straight.
$(".controls").on("mouseup touchend", function(e) {
    e.preventDefault();

    var origIsDown = controls.isDown;
    controls.isDown = false;
    controls.moveTo(controls.straight);
    controls.isDown = origIsDown;
});
/**
 * End mouse/touch controls
 */