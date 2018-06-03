/**
 * Calls a service on the controller for this component via AJAX. This is the 
 * standard way to call AJAX services.
 *
 * @param componentName string The name of the component.
 * @param serviceName string The name of the service.
 * @param parameters array The parameters to send to the service.
 * @param callback string Name of the callback function to call when success.
 */
var tritonServiceAjax = function(componentName, serviceName, parameters, callback){
  $.ajax({
    type: "POST"
    , url: base_uri + componentName + "/ajax?service=" + serviceName
    , data: parameters
    , success: function (result){
      callback(result); 
    }
  });
}

/**
 * Sets (creates or updates) a cookie.
 *
 * @param cname string The name of the cookie.
 * @param cvalue string The value of the cookie.
 * @param exdays int string The amount of days you want the cookie to persists.
 */
function Triton_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

/**
 Returns the value of a cookie.
 *
 * @param cname string The name of the cookie.
 * @return string The value of the cookie. Empty string if the cookie doesn't 
 *  exists.
 */
function Triton_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}

/**
 * @todo::: Improve.
 * Form Handling.
 */
$(document).ready(function(){
  $("#submit-top").on({
    click: function(){
      var formId = $(this).data("mainForm");
      if ( formId ) {
        // Submit.
        $("#" + formId).submit();
      }
      else {
        // Check if there is a route.
        var route = $(this).data("route");
        document.location.href = route;
      }
    }
  });
  $("#cancel-top").on({
    click: function(){
      var exitTo = $(this).data("exit");
      // Submit.
      document.location.href = exitTo;
    }
  });
});
