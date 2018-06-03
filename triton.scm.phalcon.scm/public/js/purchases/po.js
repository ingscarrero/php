/**
 *
 */
var Po_addProductToPo = function(){
  // Validate required fields.
  var poId = $("#poid").val();
  if ( !poId ) {
    console.log("No poID to insert.");
    return;
  }
  var productId = $("#product_id").val();
  if ( !productId ) {
    console.log("No product ID to insert.");
    return;
  }
  else {
    var product = $("#product").val();
    var sku = $("#sku").val();
  }
  var so = $("#so").val();
  if ( !so ) {
    console.log("No SO to insert.");
    return;
  }
  var quantity = $("#quantity").val();
  if ( !quantity ) {
    console.log("No quantity to insert.");
    return;
  }
  var unitPrice = $("#unit_price").val();
  if ( !unitPrice ) {
    console.log("No unitPrice to insert.");
    return;
  }
  var extended = $("#extended").val();
  if ( !extended ) {
    console.log("No extended to insert.");
    return;
  }
  // Validate if this is a SLAB Product.
  var productType = $("#product_type").val();
  var slabsData = {};
  if ( productType == 1 ) {
    slabsData = Po_getSlabsData();
  }
  // Non-required fields.
  var description = $("#description").val();
  var note = $("#note").val();
  var pack_quant = $("#pack_quant").val();
  var pack_unit = $("#pack_unit").val();
  var each_pack_unit = $("#each_pack_unit").val();
  var units = $("#units").val();
  // Send message to insert in DB.
  tritonServiceAjax(
    "po"
    ,"addProduct"
    , {
        poid: poId
        , product: productId
        , so: so
        , quantity: quantity
        , unit_price: unitPrice
        , extended: extended
        , description: description
        , note: note
        , pack_quant: pack_quant
        , pack_unit: pack_unit
        , each_pack_unit: each_pack_unit
        , units: units
        , slabs_data: slabsData
      }
    , function (result){
        // Success => Add the product to the POs view.
        if (result) {
          // Clone the prototype.
          var newRow = $(".list-table-row-prototype").clone().removeClass("list-table-row-prototype").addClass("list-table-row");
          // Add the content.
          $(newRow).find(".pop-list-field-so .list-field-content").text(so);
          $(newRow).find(".pop-list-field-prod-sku .list-field-content").text(product + " (" + sku + ")");
          $(newRow).find(".pop-list-field-desc .list-field-content").text(description);
          $(newRow).find(".pop-list-field-qty .list-field-content").text(quantity);
          $(newRow).find(".pop-list-field-uom .list-field-content").text(units);
          $(newRow).find(".pop-list-field-price .list-field-content").text(unitPrice);
          $(newRow).find(".pop-list-field-extended .list-field-content").text(extended);
          $(newRow).find(".pop-list-field-slabs .list-field-content").text(slabsData.count);
          $(newRow).find(".pop-list-field-slabs .slabs_data").val(JSON.stringify(slabsData));
          $(newRow).find(".pid").val(productId);
          // @todo
          $(newRow).find(".ppid").val(0);
          $(newRow).find(".type").val(productType);
          if ( productType == 1 ) {
            $(newRow).find(".check-fulfill-partial").remove();
          }
          else{
            $(newRow).find(".fulfill-slab-prod").remove();
          }
          // Add the new row.
          $(".po-prods-list-table .list-table-body").prepend(newRow);
          // Show the new total.
          $(".totals-container .subtotal .value").text("$" + result);
          $(".totals-container .total .value").text("$" + result);
        }
        console.log(result);
      }
  );
}

/**
 *
 */
var Po_removeProductFromPo = function(button){
  var ppid = $(button).data("ppid")
  console.log("remove:::" + ppid);
  // Send message to delete in DB.
  tritonServiceAjax(
    "po"
    ,"deleteProduct"
    , {ppid: ppid}
    , function (result){
        // Success => Remove the product from the list and show new total.
        if (result) {
          // Remove the row.
          $(button).parent().parent().parent().remove();
          // Show the new total.
          $(".totals-container .subtotal .value").text("$" + result);
          $(".totals-container .total .value").text("$" + result);
        }
        console.log(result);
      }
  );
}

/**
 *
 */
var Po_addInvoiceToPo = function(){
  // Validate required fields.
  var poId = $("#poid").val();
  if ( !poId ) {
    console.log("No poID to insert.");
    return;
  }
  var transaction_name = $("#transaction_name").val();
  if ( !transaction_name ) {
    console.log("No transaction to insert.");
    return;
  }
  var number = $("#number").val();
  if ( !number ) {
    console.log("No number to insert.");
    return;
  }
  var container_number = $("#container_number").val();
  if ( !container_number ) {
    console.log("No container_number to insert.");
    return;
  }
  var eta_date = $("#eta_date").val();
  if ( !eta_date ) {
    console.log("No eta_date to insert.");
    return;
  }
  var total = $("#total").val();
  if ( !total ) {
    console.log("No total to insert.");
    return;
  }
  // Non-required fields.
  var received_date = $("#received_date").val();
  // Send message to insert in DB.
  tritonServiceAjax(
    "po"
    ,"addInvoice"
    , {
        poid: poId
        , transaction_name: transaction_name
        , number: number
        , container_number: container_number
        , eta_date: eta_date
        , total: total
        , received_date: received_date
      }
    , function (result){
        // Success => Add the product to the POs view.
        if (result) {
          // Clone the prototype.
          var newRow = $(".list-table-poi-row-prototype").clone().removeClass("list-table-poi-row-prototype").addClass("list-table-row");
          // Add the content.
          $(newRow).find(".poi-list-field-date .list-field-content").text(result);
          $(newRow).find(".poi-list-field-transaction .list-field-content").text(transaction_name);
          $(newRow).find(".poi-list-field-number .list-field-content").text(number);
          $(newRow).find(".poi-list-field-total .list-field-content").text(total);
          $(newRow).find(".poi-list-field-eta .list-field-content").text(eta_date);
          $(newRow).find(".poi-list-field-received .list-field-content").text(received_date);
          $(newRow).find(".poi-list-field-container .list-field-content").text(container_number);
          // Add the new row.
          $(".po-invoices-list-table .list-table-body").append(newRow);
        }
        console.log(result);
      }
  );
}

/**
 *
 */
var Po_approvePo = function(button){
  // Validate required fields.
  var poId = $("#poid").val();
  if ( !poId ) {
    console.log("No poID to approve.");
    return;
  }
  // Send message to insert in DB.
  tritonServiceAjax(
    "po"
    ,"approvePo"
    , {poid: poId}
    , function (result){
        // Success => Add the product to the POs view.
        if (result) {
          // Show as approved: remove button and add an approved tag.
          $(button).replaceWith("<div class='button-tag-green'>Approved</div>");
          
        }
        console.log(result);
      }
  );
}

/**
 *
 */
var Po_sendPo = function(){
  console.log("PO sent.");
}

/**
 *
 */
var Po_bundlesDetail = function(){
  var bundlePrototype = "";
  var lastBundle;
  var slabPrototype;
  var lastSlab;
  var slabsContainer;
  // Get the amount of bundles.
  var bundles = $("#quantity").val();
  // Check if the bundles were created previously.
  var curBundles = $("#slab-more-details-add .slab-bundle-details").length;
  if ( curBundles != bundles ) {
    // Create bundles.
    // Get the amount of slabs per bundle and dimensions.
    var slabs = $("#slabs_per_bundle").val();
    var width = $("#width").val();
    var height = $("#height").val();
    var thickness = $("#thickness").val();
    var codebar = $("#codebar").val();
    var codebarCounter = codebar
    // Validate.
    if ( bundles && slabs  && width && height && thickness && codebar ) {
      // Reset the container.
      $("#slab-more-details-add").html("");
      // Expand the bundles
      for ( var i=0; i<bundles; i++ ) {
        console.log(i);
        bundlePrototype = $("#slab-bundle-details-prototype").html();
        // Set default values
        $("#slab-more-details-add").append(bundlePrototype);
        // Fill fields with defaults.
        lastBundle = $("#slab-more-details-add").find(".slab-bundle-details:last");
        $(lastBundle).find(".title").text("Bundle " + (i+1));
        $(lastBundle).find(".bundle_slabs_per_bundle").val(slabs);
        $(lastBundle).find(".bundle_width").val(width);
        $(lastBundle).find(".bundle_height").val(height);
        $(lastBundle).find(".bundle_thickness").val(thickness);
        // Expand the slabs.
        slabsContainer = $(lastBundle).children(".slabs-list");
        for ( var j=0; j<slabs; j++ ) {
          slabPrototype = $("#slab-row-prototype").html();
          $(slabsContainer).append(slabPrototype);
          // Fill fields with defaults.
          lastSlab = $(slabsContainer).children().last();
          $(lastSlab).find(".slab-label").text("Slab " + (j+1) + " (" + codebarCounter + ")");
          $(lastSlab).find(".slab_width").val(width);
          $(lastSlab).find(".slab_height").val(height);
          $(lastSlab).find(".slab_thickness").val(thickness);
          codebarCounter++;
        }
        // Bind handler to buttons.
        $(lastBundle).find(".slabs-detail-button").on({
          click: function(){ Po_slabsDetail($(this).parent().parent()); }
        });
      }
      // Show.
      $("#slab-more-details-add").removeClass("hidden");
    }
  }
  // Bundles were created previously.
  else{
    if ($("#slab-more-details-add").hasClass("hidden")) {
      // Show.
      $("#slab-more-details-add").removeClass("hidden");
    }
    else {
      // Hide.
      $("#slab-more-details-add").addClass("hidden");
    }
  }
}

/**
 * 
 */
var Po_slabsDetail = function(bundle){
  var slabPrototype;
  var lastSlab;
  var slabsContainer = $(bundle).children(".slabs-list");
  // Get the amount of slabs per bundle.
  var slabs = $(bundle).find(".bundle_slabs_per_bundle").val();
  // Check if the bundles were created previously.
  var curSlabs = $(slabsContainer).children().length;
  // Create new slabs?
  if ( slabs != curSlabs ) {
    // Get the amount of slabs per bundle and dimensions.
    var width = $(bundle).find(".bundle_width").val();
    var height = $(bundle).find(".bundle_height").val();
    var thickness = $(bundle).find(".bundle_thickness").val();
    // Validate.
    if ( slabs  && width && height && thickness ) {
      // Reset the container.
      $(slabsContainer).html("");
      // Expand the slabs.
      for ( var j=0; j<slabs; j++ ) {
        slabPrototype = $("#slab-row-prototype").html();
        $(slabsContainer).append(slabPrototype);
        // Fill fields with defaults.
        lastSlab = $(slabsContainer).children().last();
        $(lastSlab).find(".slab-label").text("Slab " + (j+1));
        $(lastSlab).find(".slab_width").val(width);
        $(lastSlab).find(".slab_height").val(height);
        $(lastSlab).find(".slab_thickness").val(thickness);
      }
      // Show.
      $(slabsContainer).removeClass("hidden");
    }
  }
  else{
    // Show or hide?
    if ($(slabsContainer).hasClass("hidden")) {
      $(slabsContainer).removeClass("hidden");
    }
    else{
      $(slabsContainer).addClass("hidden");
    }
  }
}

/**
 * @todo
 */
var Po_getSlabsData = function(){
  var returnData = [];
  var bundleTemp = {};
  var slabTemp = {};
  var slabsTotal = 0;
  // Iterate the bundles.
  $("#slab-more-details-add .slab-bundle-details").each(function(){
    bundleTemp = {};
    bundleTemp.slabsAmount = $(this).find(".bundle_slabs_per_bundle").val();
    bundleTemp.width = $(this).find(".bundle_width").val();
    bundleTemp.height = $(this).find(".bundle_height").val();
    bundleTemp.thickness = $(this).find(".bundle_thickness").val();
    // Iterate the slabs.
    bundleTemp.slabs = [];
    $(this).children(".slabs-list").children().each(function(){
      slabTemp = {};
      slabTemp.width = $(this).find(".slab_width").val();
      slabTemp.height = $(this).find(".slab_height").val();
      slabTemp.thickness = $(this).find(".slab_thickness").val();
      slabsTotal++;
      bundleTemp.slabs.push(slabTemp);
    });
    returnData.push(bundleTemp);
  });
  // R.
  return {count:slabsTotal, bundles:returnData};
}

/**
 * Button to display slab detail in the product list.
 */
var Po_listBundleDetail = function(button){
  console.log("oo");
  var detailContainer = $(button).parent().parent().parent().parent().children(".list-table-row-detail");
  console.log(detailContainer);
  if ( $(detailContainer).hasClass("hidden") ) {
    $(detailContainer).removeClass("hidden");
  }
  else{
    $(detailContainer).addClass("hidden");
  }
}

/**
 * Fulfillment checks.
 */
var Po_fulfillCheckChange = function(check){
  // Get the container.
  var productRow = $(check).parent().parent().parent().parent();
  // Fulfill total.
  if ( $(check).hasClass("check-fulfill-total") ) {
    // Unchecking => deselect all slabs.
    if ( !$(check).is(":checked") ) {
      // If its a slab product => deselect all.
      $(productRow).find(".check-fulfill-single-slab").prop('checked', false);
    }
    // Checking.
    else {
      // If its a slab product => select all.
      $(productRow).find(".check-fulfill-single-slab").prop('checked', true);
      // If is not a slab => deselect partial.
      $(productRow).find(".check-fulfill-partial").prop('checked', false);
    }
  }
  // Fulfill partial.
  else if ( $(check).hasClass("check-fulfill-partial") ) {
    // Unchecking => do nothing.
    if ( !$(check).is(":checked") ) {
      return;
    }
    // Checking
    else {
      // The user checked the partial => deselect total.
      productRow = $(check).parent().parent().find(".check-fulfill-total").prop('checked', false);
    }
  }
  // Fulfill slab.
  else if ( $(check).hasClass("check-fulfill-single-slab") ) {
    // Unchecking => be sure to de-check the total.
    if ( !$(check).is(":checked") ) {
      $(productRow).find(".check-fulfill-total").prop('checked', false);
    }
    // User checking => If all the brothers are checked check total.
    else {
      var flag = $(productRow).find(".check-fulfill-single-slab:not(:checked)").length;
      if ( flag === 0 ) {
        $(productRow).find(".check-fulfill-total").prop('checked', true);
      }
    }
  }
}

/**
 * Fulfillment general.
 * @todo: only saves totals
 */
var Po_fulfillGeneral = function(){
  var fulfilledProducts = [];
  var prodTemp = {};
  var checked;
  var fulfilledRows = [];
  // Check all products.
  $(".po-prods-list-table .list-table-row").each(function(){
    // Find if its checked. @todo::: check for partials.
    checked = $(this).find(".check-fulfill:checked");
    if ( checked.length > 0 ) {
      // Add this product to the object to send.
      prodTemp = {};
      prodTemp.pid = $(this).find(".pid").val();
      prodTemp.ppid = $(this).find(".ppid").val();
      prodTemp.type = $(this).find(".type").val();
      prodTemp.slabs_data = $(this).find(".slabs_data").val();
      prodTemp.amount = $(this).find(".fulfill_amount_total").val();
      fulfilledProducts.push(prodTemp);
      fulfilledRows.push(this);
    }
  });
  console.log(fulfilledProducts);
  // Save the inventory in DB.
  tritonServiceAjax(
    "po"
    ,"fulfillProducts"
    , {
        products: fulfilledProducts
      }
    , function (result){
        // Success => Add the product to the POs view.
        if (result) {
          for ( var i=0, size=fulfilledRows.length; i<size; i++ ) {
            // Change the color.
            $(fulfilledRows[i]).css("background-color", "#c6f8c9");
          }
        }
        console.log(result);
      }
  );
}

/**
 * Document ready event.
 *
 */
$(document).ready(function(){
  /**
   * Button for adding products to the PO.
   */
  $("#add-product-button").on({
    click: function(){ Po_addProductToPo(); }
  });
  /**
   * Remove products from the PO.
   */
  $(".po-prods-list-table .pop-list-field-pre .fa-times").on({
    click: function(){ Po_removeProductFromPo(this); }
  });
  /**
   * Button for adding invoices to the PO.
   */
  $("#add-invoice-button").on({
    click: function(){ Po_addInvoiceToPo(); }
  });
  /**
   * Button for approving a PO (edit form).
   */
  $("#approve-po-button").on({
    click: function(){ Po_approvePo(this); }
  });
  /**
   * Button for approving a PO (edit form).
   */
  $("#send-po-button").on({
    click: function(){ Po_sendPo(); }
  });
  /**
   * Button for bundle detail.
   */
  $("#bundles-detail-button").on({
    click: function(){ Po_bundlesDetail(); }
  });
  /**
   * Button to display slab detail in the product list.
   */
  $(".fulfill-slab-prod").on({
    click: function(){ Po_listBundleDetail(this); }
  });
  /**
   * Fulfillment checks.
   */
  $(".check-fulfill").on({
    change: function(){ Po_fulfillCheckChange(this); }
  });
  /**
   * Fulfill button.
   */
  $("#fulfill-general-button").on({
    click: function(){ Po_fulfillGeneral(); }
  });
});
