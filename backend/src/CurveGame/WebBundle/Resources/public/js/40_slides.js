/**
 * Custom wrapper for the slider.
 *
 * @type {{init: Function, next: *}}
 */
var slider = {
    "container": $('#screens'),
    "slides": $('#screens section'),
    "init": function() {
        // The DOM is ready now, we can unhide the slides.
        this.container.removeClass('hidden');

        // Setup
        this.slides.screen({
            // Default transition
            'transition': 'slide-left'
        });

        // Global listeners.
        this.slides.screen('listen');
    },
    "back": function() {
        if ($('#instructions').is(':visible') == false) {
            $.screen.back('slide-right');
        }
    },
    "next": function() {
        $.screen.next();
    },
    "goto": function(element, effect) {

        $(element).screen('select',effect);
    }
};