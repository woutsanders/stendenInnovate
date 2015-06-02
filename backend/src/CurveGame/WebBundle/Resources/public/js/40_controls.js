/**
 * Holds all controller specific functionality
 *
 * @type {{left: number, straight: number, right: number, moveTo: Function, send: Function}}
 */
var controls = {
    "keys": {
        "left": 37,
        "right": 39
    },
    "left": 1,
    "straight": 0,
    "right": 2,
    "isDown": false,
    "isEnabled": false,
    "moveTo": function(movement) {
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
            console.log("invalid control choice given!");
        }
    },
    "send": function(moveDigit) {

        var data = {
            "userId": user.id,
            "moveTo": moveDigit
        };

        //Use websockets if available...
        if (ws.supported) {
            ws.send(data);
        } else {
            async.send(data);
        }
    }
};