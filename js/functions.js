function setup_loader(){
    var overlay = $("#loader_overlay");
    var loader_container = $('#loader_container');
    var loader = $("#loader");
    var windowWidth = document.body.clientWidth/2;
    var windowHeight= document.body.clientHeight/2;
    var loader_container_left = windowWidth - (document.getElementById('loader_container').clientWidth/2);
    var loader_container_top = (windowHeight-50) - (document.getElementById('loader_container').clientWidth/2);
    loader_container.css('top', loader_container_top+"px");
    loader_container.css('left',loader_container_left+"px");

}
function start_loader(){
    var overlay = $("#loader_overlay");
    overlay.removeClass('hideLoader');
}
function loading_successful(){
    var overlay = $("#loader_overlay");
    overlay.addClass('hideLoader');
}
function loading_failed(){
    var overlay = $("#loader_overlay");
    overlay.addClass('hideLoader');
}
/*
function start_loader(){
    var overlay = $("#loader_overlay");
    var loader_container = $('#loader_container');
    var loader = $("#loader");
    var windowWidth = document.body.clientWidth/2;
    var windowHeight= document.body.clientHeight/2;
    overlay.addClass("overlay");
    loader_container.addClass("loader_container");
    loader.html("<img src='img/ajax-loader1.gif'>");
    var loader_container_left = windowWidth - (document.getElementById('loader_container').clientWidth/2);
    var loader_container_top = (windowHeight-50) - (document.getElementById('loader_container').clientWidth/2);
    loader_container.css('top', loader_container_top+"px");
    loader_container.css('left',loader_container_left+"px");
}
function loading_successful(){
    var overlay = $("#loader_overlay");
    var loader_container = $('#loader_container');
    var loader = $("#loader");

    overlay.removeClass('overlay');
    loader_container.removeClass('loader_container');
    loader.empty();
}
function loading_failed(){
    var overlay = $("#loader_overlay");
    var loader_container = $('#loader_container');
    var loader = $("#loader");
    overlay.removeClass('overlay');
    loader_container.removeClass('loader_container');
    loader.empty();
}*/
