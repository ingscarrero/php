/**
 * @file The autocomplete functionality.
 */
$(document).ready(function(){
  // @todo::: Make this configurable.
  var minChars = 1;
  // For all the fields marked with the class triton-autocomplete.
  $(".triton-autocomplete").each(function(){
    // Get the autocomplete parameter in order to query the data associated.
    var group = $( this ).data("group");
    var cache = {};
    $( this ).autocomplete({
      minLength: minChars,
      source: function( request, response ) {
          var term = request.term;
          // Check Cache.
          if ( term in cache ) {
            // Show it as a dropdown list.
            response( cache[ term ] );
            return;
          }
          // Query from the server.
          tritonServiceAjax(
            "autocomplete"
            ,"getAutocompleteData"
            , {group: group, word: term}
            , function (result){
                // debug.
                console.log("autocomplete:::" + result);
                // Decode results.
                var resultArray = $.parseJSON(result);
                // Store in cache
                cache[ term ] = resultArray;
                // Show it as a dropdown list.
                response(resultArray);
              }
          );
        }
    });    
    // Extra fields.
    $( this ).on( "autocompleteselect", function( event, ui ) {
      // Check if there is a local group to fill.
      var localGroup = $(this).data("localGroup");
      if ( localGroup ) {
        // Iterate on the returned object and try to match with local fields.
        $.each(ui.item, function(key, value){
          // Match with the dom and replace value.
          // $( "." + localGroup + "#" + key ).val(value);
          $( "." + localGroup + "." + key ).val(value);
        });
      }
      //@todo::: Special slabs case.
      if ( localGroup == "triton-autocomplete-product" ) {
        var type = $("#product_type").val();
        console.log(type);
        if ( type == 1 ) {
          $("#slab-details-add").css("display", "block").animate(
            {opacity: 1}
            , 500
            , function() {
              // Animation complete.
            }
          );
        }
        else {
          $("#slab-details-add").css("display", "none").css("opacity","0");
        }
      }
    } );
    // Ensure if the main field is empty all the others are also clean.
    $( this ).on( "change", function( event ) {
      if ( $( this ).val() == "" ) {
        // Check if there is a local group to fill.
        var localGroup = $(this).data("localGroup");
        if ( localGroup ) {
          // Clean.
          $( "." + localGroup ).val("");
        }
      }
    } );
  });
});
