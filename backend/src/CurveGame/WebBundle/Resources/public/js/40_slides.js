/**
 * Custom wrapper for the slider.
 *
 * @type {{init: Function, next: *}}
 */
var slider = {
    "container": $('#screens'),
    "slides": $('#screens section'),
    "init": function(transition) {
        if (!transition)
            transition = "slide-left";

        // The DOM is ready now, we can unhide the slides.
        this.container.removeClass('hidden');

        // Setup
        this.slides.screen({
            // Default transition
            'transition': transition
        });

        this.adjustDelayed();
    },
    "back": function(effect) {
        if (!effect)
            effect = "slide-right";
        if (!$('#instructions').is(':visible')) {
            $.screen.back(effect);
            this.adjustDelayed();
        }
    },
    "next": function(effect) {
        if (!effect)
            effect = "slide-left";
        $.screen.next(effect);
        this.adjustDelayed();
    },
    "goto": function(element, effect) {
        if (!element)
            return false;
        if (!effect)
            effect = "slide-left";
        $(element).screen('select', effect);
        this.adjustDelayed();
    },
    "adjustDelayed": function() {
        setTimeout(function() {
            $.screen.adjust();
        }, 500);
    }
};