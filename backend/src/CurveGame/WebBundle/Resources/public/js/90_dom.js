/**
 * Document-wide functions
 */
$(document).ready(function() {
    if (!ws.checkSupport()) {
        alert("Deze browser lijkt geen WebSockets te ondersteunen. Voor de beste beleving adviseren wij om Google Chrome te installeren");
    }

    slider.init();
    controls.init();

    $("#sendFormUsername").click(function(e) {
        e.preventDefault();
        $.isLoading(loaderOpts);
        var uname = $("#inputUsername").val();
        $("#inputUsername").val("");
        user.register(uname);
    });
});