/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

  
  
    /**
   * The sales order list controller.
   * 
   */
  tritonControllers.controller('SalesOrderListCtrl', 
    [
      '$scope'
      , '$routeParams'
      , '$http'
      , 'Configuration', 'SalesOrder'
      , function($scope, $routeParams, $http, Configuration, SalesOrder) {
          // Navigation parameters.
          $scope.navigation = {
            title: "Sales Order"
            , subtitle: ""
            , actionMain: {value: "New", action: function(){window.location.href='#/salesorder/save/new';}}
            , actionSecond: {value: "List All", action: function(){
                $scope.salesOrderNumberFilter = '';
                $scope.dateFilter = '';
                $scope.billingCustomerFilter ='';
                $scope.typeFilter = '';
                $scope.salesPersonFilter = '';
                $scope.loadListPage(1);
                }}
          }
 
          // Set the default amount of items per page.
          // $scope.itemsPerPage = '5';
          // Define the fields in the table view.
           $scope.tableView = Configuration.query({module:"sales", view:"salesorder-list"}, function(result){});
          
          $scope.loadListPage = function(pPage){
            // Current page state.
            $scope.currentPage = pPage;
            // Query.
            $http({
              method: 'GET'
              // @todo:: Make this configurable.
              ,url: '/triton/server/salesorder/list/'
              ,params: {
                page: pPage
                , sales_order_number: $scope.salesOrderNumberFilter
                , date: $scope.dateFilter
                , billing_customer: $scope.billingCustomerFilter
                , ship_to: $scope.typeFilter
                , primary_sales_person :$scope.salesPersonFilter
              }
            })
            .success(function(data, status, headers, config) {
              console.log(data);
              $scope.salesOrders = data.list;
              $scope.pager = data.pager;
              // Load the lists.
              if ( !$scope.listsLoaded ) {
                $scope.loadLists();
              }
            })
            .error(function(data, status, headers, config) {
              console.log(data);
              console.log(status);
              console.log(headers);
              console.log(config);
            });
          }
          /**
           * Loads the types list.
           * 
           */ 
          $scope.loadLists = function(){
            // @todo:: Check: they are always loading!
            console.log("loadLists");

            
            // Mark the lists as loaded.
            $scope.listsLoaded = true;
          }
          
          $scope.deleteSalesOrder = function(item){
                $http({
                    method : 'DELETE',
                    url : '/triton/server/salesorder/delete/' + item.soid
                })
                .success(function(data, status, headers, config) {
                    $scope.loadListPage(1);
                });
            }
          
          /**
           * init.
           */
          $scope.currentPage = 1;
          // Load the first page.
          $scope.loadListPage($scope.currentPage);
          // Set the default order.
          $scope.orderProp = 'age';
          
          $http({
            method : 'GET',
            url : '/triton/server/company/list'
          })
          .success(function(data, status, headers, config) {
              $scope.companies = data;
              $( "input[name='billing_customer_filter']" ).autocomplete({
                    minLength: 0,
                    source: data,
                    select: function( event, ui ) {
                        $scope.billingCustomerFilter= ui.item.value;
                        $( "input[name='billing_customer_filter']" ).val(ui.item.label);
                        $( "input[name='hidden_billing_customer_filter']" ).val(ui.item.value);
                        $scope.loadListPage(1);
                      return false;
                    }
                 });
          });

          $http({
            method : 'GET',
            url : '/triton/server/user/list'
          })
          .success(function(data, status, headers, config) {
             $scope.users = data;
          });
        }
    ]
  );
  
  /**
   * The sales order view contoller.
   * 
   */
  tritonControllers.controller('SalesOrderViewCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , '$routeParams'
        , function($scope, Configuration, $http, $routeParams) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Create Sales Order"
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){$scope.create();}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/salesorder/list';}}
            }
            $scope.newSalesOrder = {};
            $scope.newSalesOrderD = {};
            // General info from the return.
            $http({
              method : 'GET',
              url : '/triton/server/salesorder/' + $routeParams.id
            })
            .success(function(data, status, headers, config) {
              console.log(data);
              $scope.newSalesOrder = data;
              
            });
        }
    ]);
  
   /**
   * The sales order create/edit contoller.
   * 
   */
  tritonControllers.controller('SalesOrderCreateCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , '$routeParams'
        , function($scope, Configuration, $http, $routeParams) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Create Sales Order"
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){$scope.create();}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/salesorder/list';}}
            }
            $scope.newSalesOrder = {};
            $scope.newSalesOrderD = {};
            $scope.dropdowndata = {};
            $scope.shownonpickup = "No";
            $http({
                method : 'GET',
                url : '/triton/server/branch/list'
              })
              .success(function(data, status, headers, config) {
                $scope.dropdowndata.locations = data;
                
              });
              
              $http({
                method : 'GET',
                url : '/triton/server/company/list'
              })
              .success(function(data, status, headers, config) {
                $scope.dropdowndata.companies = data;
                 $( "input[name='billing_customer']" ).autocomplete({
                    minLength: 0,
                    source: data,
                    select: function( event, ui ) {
                        $scope.newSalesOrder.billing_customer= ui.item.value;
                        $( "input[name='billing_customer']" ).val(ui.item.label);
                        $( "input[name='hidden_billing_customer']" ).val(ui.item.value);
                        $scope.updateBillInfo(ui.item.value);
                      return false;
                    }
                 });
                 $( "input[name='ship_to_company']" ).autocomplete({
                    minLength: 0,
                    source: data,
                    select: function( event, ui ) {
                        $scope.newSalesOrder.ship_to_company= ui.item.value;
                        $( "input[name='ship_to_company']" ).val(ui.item.label);
                        $( "input[name='hidden_ship_to_company']" ).val(ui.item.value);
                        $scope.updateShipToInfo(ui.item.value);
                      return false;
                    }
                 });
                 $( "input[name='referred_by']" ).autocomplete({
                    minLength: 0,
                    source: data,
                    select: function( event, ui ) {
                        $scope.newSalesOrder.referred_by= ui.item.value;
                        $( "input[name='referred_by']" ).val(ui.item.label);
                        $( "input[name='hidden_referred_by']" ).val(ui.item.value);
                      return false;
                    }
                 });
              });
              
              $http({
                method : 'GET',
                url : '/triton/server/user/list'
              })
              .success(function(data, status, headers, config) {
                 $scope.dropdowndata.users = data;
              });
              
              $http({
                method : 'GET',
                url : '/triton/server/taxonomy/byvocabulary/12'
              })
              .success(function(data, status, headers, config) {
                $scope.dropdowndata.paymentterm_taxonomy_terms = data;
              });
              $http({
                method : 'GET',
                url : '/triton/server/taxonomy/byvocabulary/7'
              })
              .success(function(data, status, headers, config){
                $scope.dropdowndata.pricelevel_taxonomy_terms = data;
              });
              $http({
                method : 'GET',
                url : '/triton/server/taxonomy/byvocabulary/13'
              })
              .success(function(data, status, headers, config) {
                $scope.dropdowndata.salestax_taxonomy_terms = data;
              });
            $scope.create_url = '/triton/server/salesorder/new';
            if($routeParams.action != "new"){
                $http({
                    method : 'GET',
                    url : '/triton/server/salesorder/' + $routeParams.action
                })
                .success(function(data, status, headers, config) {
                    $scope.newSalesOrder = data;
                    $scope.newSalesOrderD.billing_customer = data.billing_customer_n;
                    
                    $scope.newSalesOrderD.ship_to_company = data.ship_to_company_n;
                    $scope.newSalesOrderD.referred_by = data.referred_by_n;
                    $scope.newSalesOrder.payment_terms_n = data.payment_terms;
                    $scope.newSalesOrder.price_level_n = data.price_level;
                    $scope.newSalesOrder.primary_sales_person_n = data.primary_sales_person;
                    $scope.newSalesOrder.sales_tax_n = data.sales_tax;
                    $scope.newSalesOrder.location_n = data.location;
                    if($scope.newSalesOrder.ship_to != "Pick Up"){
                        $scope.shownonpickup = "Yes";
                    }else{
                        $scope.shownonpickup = "No";
                    }
                });
                $scope.create_url = '/triton/server/salesorder/update';
            }else{
                $http({
                method : 'GET',
                url : '/triton/server/salesordernumber/create'
              })
              .success(function(data, status, headers, config) {
                $scope.newSalesOrder.sales_order_number = data.result;
              });
              $scope.newSalesOrder.ship_to = "Pick Up";
              $scope.shownonpickup = "No";
            }
            $scope.listenShipTo = function(){
                if( $scope.newSalesOrder.ship_to != "Pick Up"){
                    $scope.shownonpickup = "Yes"
                }else{
                    $scope.shownonpickup = "No";
                }
            }
            $scope.updateCascadeData = function(item, model, name){
                console.log(name +":"+ item +":"+model);
            };
            
            
            $scope.open = function($event,name) {
                $event.preventDefault();
                $event.stopPropagation();
                if(name == 'date'){
                    $scope.openeddate = true;
                }else{
                    $scope.openedpickup_date = true;
                }
              
              };

              $scope.dateOptions = {
                formatYear: 'yy',
                startingDay: 1
              };

              $scope.formats = ['dd-MM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
              $scope.format = $scope.formats[0];
            // Define the fields in the form.
            $scope.formView = Configuration.query({module:"sales", view:"create-salesorder"}, function(result){});
            
            $scope.requirederros = [];
            
            $scope.create = function() {
                $scope.requirederros = [];
                $( ".has-error" ).removeClass("has-error");
                console.log($scope.newSalesOrder);
             
              $scope.formhasError = false;
              if($scope.newSalesOrder.sales_order_number == undefined || $scope.newSalesOrder.sales_order_number.length == 0){
                  $( "input[name='sales_order_number']" ).addClass('has-error');
                  $scope.requirederros.push("Sales Order Number is required.")
                  $scope.formhasError = true;
              }
              if($scope.newSalesOrder.date == undefined || $scope.newSalesOrder.date.length == 0){
                  $( "input[name='date']" ).addClass('has-error');
                  $scope.requirederros.push("Sales Order Date is required.")
                  $scope.formhasError = true;
              }
              if($scope.newSalesOrder.location == undefined || $scope.newSalesOrder.location.length == 0){
                  $( "select[name='location']" ).addClass('has-error');
                  $scope.requirederros.push("Location is required.")
                  $scope.formhasError = true;
              }
              if($scope.newSalesOrder.billing_customer == undefined || $scope.newSalesOrder.billing_customer.length == 0){
                  $( "input[name='billing_customer']" ).addClass('has-error');
                  $scope.requirederros.push("Billing Customer is required.")
                  $scope.formhasError = true;
              }
              if($scope.newSalesOrder.payment_terms == undefined || $scope.newSalesOrder.payment_terms.length == 0){
                  $( "select[name='payment_terms']" ).addClass('has-error');
                  $scope.requirederros.push("Payment Term is required.")
                  $scope.formhasError = true;
              }
              if($scope.newSalesOrder.primary_sales_person == undefined || $scope.newSalesOrder.primary_sales_person.length == 0){
                  $( "select[name='primary_sales_person']" ).addClass('has-error');
                  $scope.requirederros.push("Primary sales person is required.")
                  $scope.formhasError = true;
              }
              if($scope.newSalesOrder.pickup_date == undefined || $scope.salesOrderForm.pickup_date.length == 0){
                  $( "input[name='pickup_date']" ).addClass('has-error');
                  $scope.requirederros.push("Pickup Date is required.")
                  $scope.formhasError = true;
              }
              if($scope.newSalesOrder.sales_tax == undefined || $scope.newSalesOrder.sales_tax.length == 0){
                  $( "select[name='sales_tax']" ).addClass('has-error');
                  $scope.requirederros.push("Sales tax is required.")
                  $scope.formhasError = true;
              }
              if($scope.newSalesOrder.ship_to != "Pick Up" && ($scope.newSalesOrder.ship_to_company == undefined ||  $scope.newSalesOrder.ship_to_company.length == 0)){
                  $( "input[name='ship_to_company']" ).addClass('has-error');
                  $scope.requirederros.push("Ship To informatin is required.")
                  $scope.formhasError = true;
              }
              if ($scope.formhasError) { 
                  $('#errorMessageModal').modal('show')
                    return; 
               }
              console.log(JSON.stringify($scope.newSalesOrder));
              // Save
              $http({
                method : 'POST',
                url : $scope.create_url,
                data : $scope.newSalesOrder
              })
              .success(function(data, status, headers, config) {
                console.log(data);
                //window.location.href = '#/salesorder/list/';
                window.location.href = '#/salesorder/edit/'+data.soid;
              })
              .error(function(data, status, headers, config) {
                console.log(data);
                console.log(status);
                console.log(headers);
                console.log(config);
              });
            };
            $scope.reset = function() {
              window.location.href = '/triton/client/#/salesorder';
            };   
            $scope.autocompleteFieldChange = function( fieldName, autocompleteGroup ){
              var term = $scope.newSalesOrder[fieldName];
              if ( term.length > 2 ) {
                console.log($scope.newSalesOrder[fieldName] + "->" + autocompleteGroup);
              }
            }
            
            $scope.updateShipToInfo=function(company_id){
                $http({
                            method : 'GET',
                            url : '/triton/server/company/'+company_id
                        })
                        .success(function(data, status, headers, config) {
                          $scope.newSalesOrder.ship_to_attention = data.attention;
                          $scope.newSalesOrder.ship_to_address = data.address;
                          $scope.newSalesOrder.ship_to_unit_number = data.address2;
                          $scope.newSalesOrder.ship_to_city = data.city;
                          $scope.newSalesOrder.ship_to_state = data.state;
                          $scope.newSalesOrder.ship_to_zip = data.zip_code;
                          $scope.newSalesOrder.ship_to_country = data.country;
                          $scope.newSalesOrder.ship_to_phone = data.phones;
                          $scope.newSalesOrder.ship_to_fax = data.faxes;
                          $scope.newSalesOrder.ship_to_mobile = data.mobile;
                          $scope.newSalesOrder.ship_to_email = data.email;
                        });
            }
            $scope.updateBillInfo=function(company_id){
                $http({
                            method : 'GET',
                            url : '/triton/server/company/'+company_id
                        })
                        .success(function(data, status, headers, config) {
                          $scope.newSalesOrder.billing_attention = data.attention;
                          $scope.newSalesOrder.billing_address = data.address;
                          $scope.newSalesOrder.billing_unit_number = data.address2;
                          $scope.newSalesOrder.billing_city = data.city;
                          $scope.newSalesOrder.billing_state = data.state;
                          $scope.newSalesOrder.billing_zip = data.zip_code;
                          $scope.newSalesOrder.billing_country = data.country;
                          $scope.newSalesOrder.billing_phone = data.phones;
                          $scope.newSalesOrder.billing_fax = data.faxes;
                          $scope.newSalesOrder.billing_mobile = data.mobile;
                          $scope.newSalesOrder.billing_email = data.email;
                        });
            }
          }
      ]
  );
  //end of sales order create controller
  
   /**
   * The sales order prdouct add controller
   * 
   */
  tritonControllers.controller('SalesOrderEditCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , '$routeParams'
        , function($scope, Configuration, $http, $routeParams) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Edit Sales Order"
              , subtitle: ""
              , actionMain: {value: "New Sales Order", action: function(){window.location.href='#/salesorder/new';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/salesorder/list';}}
            }
            $scope.newProduct = {};
            $scope.newProductD = {};
            $scope.dropdowndata = {};
            $scope.salesOrder = {};
            $scope.searchProduct = {"showResult": "No"};
            $scope.soid = $routeParams.soid;
            $scope.salesorderStatus = "";
            $scope.editSalesOrder = function(){
                window.location.href = '#/salesorder/save/'+$routeParams.soid;
            }
            $http({
                method : 'GET',
                url : '/triton/server/salesorder/'+$scope.soid
            })
            .success(function(data, status, headers, config) {
              $scope.salesOrder = data;
              $scope.salesorderStatus = data.status;
            });
            $scope.updatestatus = function(){
                $http({
                    method : 'POST',
                    url : '/triton/server/salesorder/status/update',
                    data : {soid: $scope.soid, "status" : $scope.salesorderStatus}
                })
                .success(function(data, status, headers, config) {
                  
                });  
            }
            $scope.deleteSoProduct = function(soid){
                $http({
                method : 'DELETE',
                url : '/triton/server/salesorderproduct/'+soid
              })
              .success(function(data, status, headers, config) {
                $http({
                method : 'GET',
                url : '/triton/server/salesorder/product/list',
                params: {'page': 'All', 'soid':$scope.soid}
                })
                .success(function(data, status, headers, config) {
                  $scope.products = data;
                });
              });
            };
            $scope.cancelAndNewSearch = function(){
                $scope.newProduct = {};
                $scope.searchProduct.showResult =  "No";
                
            }
            $scope.editSoProduct = function(item){
                $scope.saveprducttext = "SAVE STOCK PRODUCT";
                $http({
                method : 'GET',
                url : '/triton/server/product/'+item.product,
                })
                .success(function(product, status, headers, config) {
                    var available = "";
                    if(product.type=="1"){
                        available = product.amount + " Slabs / ";
                    }else{
                       available = product.amount + " Lines / ";
                    }
                    if(product.size== null || product.size == 'null'){
                        available += product.weight + " "+product.unitname;
                    }else{
                        available += product.size + " "+product.unitname;
                    }
                    $scope.newProduct.available = available;
                   $scope.newProduct.searchunits =item.units;
                });
                 $scope.newProduct.name = item.name;
                 $scope.newProduct.sku = item.sku;
                
               
                //$scope.newProduct.units =item.units;
                $scope.newProduct.description = item.description;
                $scope.newProduct.notes=item.notes;
                $scope.newProduct.notes=item.notes;
                $scope.newProduct.unit_price=item.unit_price;
                $scope.newProduct.extended=item.extended;
                $scope.newProduct.quantity=item.quantity;
                $scope.newProduct.spid=item.spid;
                $scope.newProduct.is_taxable = item.is_taxable=='Yes'?true:false;
                $scope.searchProduct.showResult =  "Selected";
                console.log($scope.newProduct);
            }
            $scope.createSoProduct = function(){
                $( ".has-error" ).removeClass("has-error");
                console.log($scope.newSalesOrder);
              $scope.formhasError = false;
              if($scope.newProduct.quantity == undefined || $scope.newProduct.quantity == "" || $scope.newProduct.quantity < 0){
                  $( "input[name='quantity']" ).addClass('has-error');
                  $scope.formhasError = true;
              }
              if($scope.newProduct.unit_price == undefined || $scope.newProduct.unit_price.length == 0){
                  $( "input[name='unit_price']" ).addClass('has-error');
                  $scope.formhasError = true;
              }
              if($scope.newProduct.searchunits == undefined || $scope.newProduct.searchunits.length == 0){
                  $( "select[name='searchunits']" ).addClass('has-error');
                  $scope.formhasError = true;
              }else{
                  $scope.newProduct.units = $scope.newProduct.searchunits;
              }
              
              if($scope.newProduct.extended == undefined || $scope.newProduct.extended.length == 0){
                  $( "input[name='extended']" ).addClass('has-error');
                  $scope.formhasError = true;
              }
              ///salesorder/update for update
              var saveurl = '/triton/server/salesorder/product/new';
              if($scope.newProduct.spid != undefined && $scope.newProduct.spid>0){
                  saveurl = '/triton/server/salesorder/product/update';
              }
              if ($scope.formhasError) { return; }
                $http({
                method : 'POST',
                url : saveurl,
                data : $scope.newProduct
              })
              .success(function(data, status, headers, config) {
                $scope.newProduct = {};
                $scope.searchProduct.showResult =  "No";
                $http({
                method : 'GET',
                url : '/triton/server/salesorder/product/list',
                params: {'page': 'All', 'soid':$scope.soid}
                })
                .success(function(data, status, headers, config) {
                  $scope.products = data;
                });
              });
            };
            $http({
                method : 'GET',
                url : '/triton/server/units/list',
                params: {}
              })
              .success(function(data, status, headers, config) {
                $scope.dropdowndata.units = data;
              });
             $http({
                method : 'GET',
                url : '/triton/server/salesorder/product/list',
                params: {'page': 'All', 'soid':$routeParams.soid}
              })
              .success(function(data, status, headers, config) {
                $scope.products = data;
              });
           
             $scope.loadListPage = function(pPage){
            // Current page state.
            $scope.currentPage = pPage;
            if($scope.searchProduct.productName.length == 0 && $scope.searchProduct.sku.length ==0)
                return;
            // Query.
            $http({
              method: 'GET'
              ,url: '/triton/server/product/search/list'
              ,params: {
                page: pPage
                , 'name': $scope.searchProduct.productName
                , 'sku': $scope.searchProduct.sku
              }
            })
            .success(function(data, status, headers, config) {
              console.log(data);
              $scope.searchResultList = data.list;
              $scope.pager = data.pager;
              // Load the lists.
              if ( !$scope.listsLoaded ) {
                $scope.loadLists();
              }
            })
            .error(function(data, status, headers, config) {
              console.log(data);
            });
          }
          $scope.loadLists = function(){
            // @todo:: Check: they are always loading!
            console.log("loadLists");
            $scope.listsLoaded = true;
          }
                   
          // Set the default order.
          $scope.orderProp = 'name'; 
             $scope.searchProduct = function(){
                 $scope.searchProduct.showResult = "Yes";
                 $scope.currentPage = 1;
                 $scope.loadListPage($scope.currentPage);
             };
             $scope.updateSelecteProductInfo = function(product){
               console.log(product);
               $scope.searchProduct.showResult = "Selected";
               $scope.newProduct=product;
               $scope.newProduct.product = product.pid;
               $scope.newProduct.soid = $scope.soid;
               $scope.saveprducttext = "ADD STOCK PRODUCT";
               var available = "";
               if(product.type=="1"){
                   available = product.amount + " Slabs / ";
               }else{
                  available = product.amount + " Lines / ";
               }
               if(product.size== null || product.size == 'null'){
                   available += product.weight + " "+product.unitname;
               }else{
                   available += product.size + " "+product.unitname;
               }
               $scope.newProduct.unit_price = product.price_1;
               $scope.newProduct.available = available;
             };
         }]);

