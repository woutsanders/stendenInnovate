/**
 * Document-wide functions
 */
$(document).ready(function() {

    resizeWarning();

    if (!ws.checkSupport()) {
        alert("Deze browser lijkt geen WebSockets te ondersteunen. Voor de beste beleving adviseren wij om Google Chrome te installeren op een Android device. U wordt doorgestuurd");
        window.location.assign("http://bit.ly/1Cnxtpa")
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
        async.sendReadySignal();
    });

    $("#userRepeat").on('click touchend', function(e) {
        e.preventDefault();
        user.repeat(true);
    });
});

window.unload = function(e) {
    //alert("Your game profile will be deleted from your device");
    user.deleteProfile();
};

window.onbeforeunload = function(e) {
    //alert("Your game profile will be deleted from your device");
    user.deleteProfile();
};

function resizeWarning() {
    if(window.innerHeight > window.innerWidth){
        alert("Please rotate your phone to landscape view (widescreen) and press OK");
    }
    $.screen.adjust();
}