/**
 * @file 
 * Handles general User Interface functionality.
 */
$(document).ready(function(){
  // All the datepickers.
  $(".triton-datepicker").each(function(){
    // @todo::: Maybe some specific packed configurations.
    $(this).datepicker({
      numberOfMonths: 2
      , showButtonPanel: true
      , dateFormat: "yy-mm-dd"
    });
  });
});
