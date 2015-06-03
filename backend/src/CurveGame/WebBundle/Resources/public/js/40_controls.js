/**
 * Holds all controller specific functionality
 *
 * @type {{left: number, straight: number, right: number, moveTo: Function, send: Function}}
 */
var controls = {
    keys: {
        left: 37,
        right: 39
    },
    left: 1,
    straight: 0,
    right: 2,
    isDown: false,
    isEnabled: false,
    init: function() {
        $(document).keydown(function(event) {
            controls.isDown = true;

            if (event.which == controls.keys.left)
                if (debug)
                    console.log("Initiating: keydownEvent, left.");
                controls.moveTo(controls.left);

            if (event.which == controls.keys.right)
                if (debug)
                    console.log("Initiating: keydownEvent, right.");
                controls.moveTo(controls.right);
        });
        $(document).keyup(function(event) {
            if (event.which == controls.keys.left || event.which == controls.keys.right) {
                if (debug)
                    console.log("Initiating keyUpEvent, straight");
                controls.moveTo(controls.straight);
                controls.isDown = false;
            }
        });

        $("#leftControl").on("mousedown touchstart", function(e) { //Left
            e.preventDefault();
            if (debug)
                console.log("Initiating: mousedown/touchstart, left.");
            controls.moveTo(controls.left);
            controls.isDown = true;
        });

        $("#rightControl").on("mousedown touchstart", function(e) { //Right
            e.preventDefault();
            if (debug)
                console.log("Initiating: mousedown/touchstart, right");
            controls.moveTo(controls.right);
            controls.isDown = true;
        });

        $(".controls").on("mouseup touchend", function(e) { //Straight
            e.preventDefault();
            controls.moveTo(controls.straight);
            controls.isDown = false;
        });
    },
    moveTo: function(movement) {
        if (this.isDown || !this.isEnabled) return;

        if (movement == this.straight) {
            this.send(this.straight);
        }
        else if (movement == this.left) {
            this.send(this.left);
        }
        else if (movement == this.right) {
            this.send(this.right);
        } else {
            if (debug)
                console.log("Error: controls.moveTo(): Invalid control choice given!");
        }
    },
    send: function(moveDigit) {
        if (!user.id) {
            if (debug)
                console.log("Error: controls.send(): No user ID known! Perhaps user object not yet populated? Exiting...");
            return;
        }

        var data = {
            "userId": user.id,
            "moveTo": moveDigit
        };

        //Use websockets if available...
        if (ws.supported) {
            ws.sendUnityCtrlCmd(data);
        } else {
            async.sendUnityCtrlCmd(data);
        }
    }
};