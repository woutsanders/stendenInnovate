/**
 * Document-wide functions
 */
$(document).ready(function() {
    resizeWarning();

    if (!ws.checkSupport()) {
        alert("Deze browser lijkt geen WebSockets te ondersteunen. Voor de beste beleving adviseren wij om Google Chrome te installeren");
    }

    slider.init();
    controls.init();

    $("input").on('click touchend', function(e) {
        e.preventDefault();
        $(this).focus();
    });

    $("#sendFormUsername").on('click touchend', function(e) {
        e.preventDefault();
        $.isLoading(loaderOpts);
        var uname = $("#inputUsername").val();
        $("#inputUsername").val("");
        user.register(uname, false);
    });

    $("#readyToPlayBtn").on('click touchend', function(e) {
        e.preventDefault();
        $.isLoading(loaderOpts);
        clearTimeout(readySignalTimerId);
        controls.isEnabled = true;
        ws.connect();
        async.sendReadySignal();
    });
});

$(window).resize(function() {
    resizeWarning();
});

$(window).unload(function() {
    alert("Your game profile will be deleted from your device");

    $.ajax({});
});

function resizeWarning() {
    if(window.innerHeight > window.innerWidth){
        alert("Please rotate your phone to landscape view (widescreen) and press OK");
    }
    $.screen.adjust();
}