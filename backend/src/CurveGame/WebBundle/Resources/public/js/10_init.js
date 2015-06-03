/**
 * Initializing variables to use during this session.
 */
var debug = true;                       // Enable/Disable debug mode (console logging).
var refreshPosInterval = 5000;          // ms to next positioning refresh.
var refreshPollInterval = 2000;         // ms to next polling refresh.
var intervalQueuePollId = undefined;    // Holds the setInterval() ID.
var intervalQueuePosId = undefined;     // Holds the setInterval() ID.
var loaderOpts = {                      // Contains all $.isLoader() overlay params.
    'position': "overlay",                  // right | inside | overlay
    'text': "Loading, please wait...",      // Text to display next to the loader
    'class': "fa-refresh",                  // loader CSS class
    'tpl': '<span class="isloading-wrapper %wrapper%">%text%<i class="fa %class% fa-spin"></i></span>',
    'disableSource': true,                  // true | false
    'disableOthers': []                     // Other elements to disable when loader is active
};