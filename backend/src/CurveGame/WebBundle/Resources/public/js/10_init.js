/**
 * Initializing variables to use during this session.
 */
var debug = true;                   // Enable/Disable debug mode (console logging).
var userId = 2;                         // Contains the UserID
var leftControl = 1;                // Left control init
var straightControl = 0;            // Straight control init
var rightControl = 2;               // Right control init
var keyLeft = 37;                   // Keypad ID
var keyRight = 39;                  // Keypad ID
var keyDown = false;                // KeyDown toggle
var ajaxRootURL =
    $("#twigHack").data('api');     // Gets the relative API path from root
var wsSupport = false;              // WebSocket support toggle
var wsURL = "woutsanders.com";      // WebSocket host URL
var wsPort = 9899;                  // WebSocket port
var ws;                             // WebSocket container