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

            if (event.which === controls.keys.left &&
                event.which === controls.keys.right) {
                return;
            }

            else if (event.which === controls.keys.left) {
                controls.moveTo(controls.left);
                controls.isDown = true;
                $("#leftControl").removeClass(user.color + "_unpushed_left" ).addClass(user.color + "_pushed_left");
            }

            else if (event.which === controls.keys.right) {
                controls.moveTo(controls.right);
                controls.isDown = true;
                $("#rightControl").removeClass(user.color + "_unpushed_right").addClass(user.color + "_pushed_right");
            }
        });
        $(document).keyup(function(event) {
            if (event.which === controls.keys.left &&
                event.which === controls.keys.right) {
                return;
            }

            else if (event.which === controls.keys.left || event.which === controls.keys.right) {
                controls.moveTo(controls.straight);
                controls.isDown = false;
                $("#leftControl").removeClass(user.color + "_pushed_left").addClass(user.color + "_unpushed_left");
                $("#rightControl").removeClass(user.color + "_pushed_right").addClass(user.color + "_unpushed_right");
            }
        });

        $("#leftControl").on("mousedown touchstart", function(e) { //Left
            e.preventDefault();
            controls.moveTo(controls.left);
            controls.isDown = true;
            $("#leftControl").removeClass(user.color + "_unpushed_left").addClass(user.color + "_pushed_left");

        });

        $("#rightControl").on("mousedown touchstart", function(e) { //Right
            e.preventDefault();
            controls.moveTo(controls.right);
            controls.isDown = true;
            $("#rightControl").removeClass(user.color + "_unpushed_right").addClass(user.color + "_pushed_right");
        });

        $(".controls").on("mouseup touchend", function(e) { //Straight
            e.preventDefault();
            controls.moveTo(controls.straight);
            controls.isDown = false;
            $("#leftControl").removeClass(user.color + "_pushed_left").addClass(user.color + "_unpushed_left");
            $("#rightControl").removeClass(user.color + "_pushed_right").addClass(user.color + "_unpushed_right");
        });
    },
    moveTo: function(movement) {
        if (this.isDown || !this.isEnabled)
            return;

        if (debug) {
            console.log("Initiating control command: --movement: " + movement);
        }

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
        if (!user.hash) {
            if (debug)
                console.log("Error: controls.send(): No user known! Perhaps user object not yet populated? Exiting...");
            return;
        }

        var data = {
            userHash: user.hash,
            moveTo: moveDigit
        };

        //Use websockets if available...
        if (ws.supported) {
            ws.sendUnityCtrlCmd(data);
        } else {
            async.sendUnityCtrlCmd(data);
        }
    },
    countdownReadyBtn: function() {
        var thisObj = this;

        readySignalTimerId = setTimeout(function() {
            thisObj.countdownReadyBtn();
            if (readySignalTimerTick < 1) {
                readySignalTimerTick = 12;
                user.repeat();
                clearTimeout(readySignalTimerId);
                readySignalTimerId = undefined;
            }
        }, 1000);

        $("#queue").find("#countdown").html("Press the ready button in " + (readySignalTimerTick - 2) + "s");
        $("#positionNum").hide();
        readySignalTimerTick--;
    }
};