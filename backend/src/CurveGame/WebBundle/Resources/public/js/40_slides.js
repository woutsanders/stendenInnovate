/**
 * Custom wrapper for the slider.
 *
 * @type {{init: Function, next: *}}
 */
var slider = {
    "init": function() {
        $('#screens')
            .removeClass('screen-hidden')
            .find('section')
            .screen({
                "transition": "slide-left"
            });

        console.log("I was run!");
    },
    "back": function() {
        if ($('#instructions').is(':visible') == false) {
            $.screen().back();
        }
    },
    "next": function() {
        $.screen().next();
    }
};