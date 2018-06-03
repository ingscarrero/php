/**
 * Triggered when the user clicks on the menu's top arrow.
 * 
 */
var Menu_clickMenuHeader = function(){
  // Add some nice transitions.
  $("#main-menu-container").css("-webkit-transition", "width 0.5s");
  $("#main-menu-container").css("transition", "width 0.5s");
  $("#master-container").css("-webkit-transition", "margin-left 0.5s");
  $("#master-container").css("transition", "margin-left 0.5s");
  // collapse / expand.
  if ( $("#main-menu-container").hasClass("menu-collapsed") ) {
    $("#main-menu-container").removeClass("menu-collapsed");
    $("#master-container").removeClass("menu-collapsed");
    // Keep state.
    Triton_setCookie("tritonMenuMode", "expanded", 30);
  }
  else {
    $("#main-menu-container").addClass("menu-collapsed");
    $("#master-container").addClass("menu-collapsed");
    // Keep state.
    Triton_setCookie("tritonMenuMode", "collapsed", 30);
  }
}

$(document).ready(function(){
  // Top arrow events.
  $("#main-menu-header").on({
    click: function(){Menu_clickMenuHeader();}
  });
  // Menu mode.
  var menuMode = Triton_getCookie("tritonMenuMode");
  if ( menuMode == "collapsed" ) {
    $("#main-menu-container").addClass("menu-collapsed");
    $("#master-container").addClass("menu-collapsed");
  }
  // Popup menu.
  $(".main-menu-item").on({
    mouseenter: function(){ $(this).find(".float-menu-cont").show(); }
  });
  $(".main-menu-item, .float-menu-cont").on({
    mouseleave: function(){ $(this).find(".float-menu-cont").hide(); }
  });
});
