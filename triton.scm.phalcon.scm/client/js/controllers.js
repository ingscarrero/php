/**
 * The controllers.
 * 
 * @author juangalf-20150222
 */
  /* Controllers */
  var tritonControllers = angular.module('tritonControllers', []);
  
  /**
   * The Global Integration controller. Controls the interaction between general
   * components of the application, eg. the menu - the header - the content.
   *
   */
  tritonControllers.controller('IntegrationCtrl', 
    [
      '$scope'
      , function($scope){
          // Define the navigation header template partial.
          $scope.navHeader = {name: "nav-header.html", url: "partials/nav-header.html"};
          // Initial state of the menu.
          $scope.menuBClasses = "";
          /**
           * Handles the menu collapse / expand.
           *
           */
          $scope.clickMenuHeader = function(){
            // Add some nice transitions. In here to avoid animation on init.
            $("#main-menu-container").css("-webkit-transition", "width 0.5s");
            $("#main-menu-container").css("transition", "width 0.5s");
            $("#master-container").css("-webkit-transition", "margin-left 0.5s");
            $("#master-container").css("transition", "margin-left 0.5s");
            // collapse / expand.
            if ( $scope.menuBClasses == "menu-collapsed" ) {
              $scope.menuBClasses = "";
            }
            else {
              $scope.menuBClasses = "menu-collapsed";
            }            
          }
          /**
           * Util function used in the pagination. Returns an array of the size
           * of a number passed as parameter.
           * 
           * @param num (int) The length of the new array.
           */
          $scope.getFixedIterator = function(num) {
            return new Array(num);   
          }
        }
    ]
  );
  
  /**
   * The Maim Menu controller. 
   *
   */
  tritonControllers.controller('MenuCtrl', 
    [
      '$scope'
      , function($scope){
          // Define the template partial.
          $scope.mainMenu = {name: "main-menu.html", url: "partials/main-menu.html"};
        }
    ]
  );
  
  /**
   * The Header controller.
   *
   */
  tritonControllers.controller('HeaderCtrl', 
    [
      '$scope'
      , function($scope){
          // Define the template partial.
          $scope.header = {name: "header.html", url: "partials/header.html"};
        }
    ]
  );
  
  /**
   * The inventory list controller.
   * 
   */
  tritonControllers.controller('InventoryListCtrl', 
    [
      '$scope'
      , '$routeParams'
      , '$http'
      , 'Configuration'
      , function($scope, $routeParams, $http, Configuration) {
          // Navigation parameters.
          $scope.navigation = {
            title: "Product Catalog"
            , subtitle: ""
            , actionMain: {value: "New", action: function(){window.location.href='#/inventory/create';}}
            , actionSecond: {value: "List All", action: function(){window.location.href='#/inventory/list';}}
          }
          // The letters array.
          $scope.lettersArray = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
          // Set the default amount of items per page.
          // $scope.itemsPerPage = '5';
          // Define the fields in the table view.
          $scope.tableView = Configuration.query({module:"materials", view:"general-list"}, function(result){});
          /**
           * Loads another page from the material list.
           * 
           * @param pPage integer The number of the page to load.
           */ 
          $scope.loadListPage = function(pPage){
            // Current page state.
            $scope.currentPage = pPage;
            // Query.
            $http({
              method: 'GET'
              // @todo:: Make this configurable.
              ,url: '/triton/server/materials/list/'
              ,params: {
                page: pPage
                , q: $scope.query
                , letter: $scope.curLetter
                , type: $scope.typeFilter
                , category: $scope.categoryFilter
                , group: $scope.groupFilter
                , thickness: $scope.thicknessFilter
                , kind: $scope.kindFilter
              }
            })
            .success(function(data, status, headers, config) {
              console.log(data);
              $scope.materials = data.list;
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
            // Special case: kind list.
            $scope.kindList = [{value:"Stock", label:"Stock"},{value:"Non Stock", label:"Non Stock"}];
            // General case: other lists.
            var lists = [
              "type"
              , "category"
              , "group"
              , "thickness"
            ];
            // Construct the lists.
            for ( var i=0, size=lists.length; i<size; i++ ) {
              // Query.
              $http({
                method: 'GET'
                // @todo:: Make this configurable.
                ,url: '/triton/server/taxonomy/vocabulary/' + lists[i]
                // ,parameter: {page: pPage, q:$routeParams.q}
              })
              .success(function(data, status, headers, config) {
                switch(data.data[0].vocabulary_name) {
                  case "type": $scope.typeList = data.data; break;
                  case "category": $scope.categoryList = data.data; break;
                  case "group": $scope.groupList = data.data; break;
                  case "thickness": $scope.thicknessList = data.data; break;
                }
              })
              .error(function(data, status, headers, config) {
                console.log(data);
                console.log(status);
                console.log(headers);
                console.log(config);
              });
            }
            // Mark the lists as loaded.
            $scope.listsLoaded = true;
          }
          $scope.loadLetterPage = function(letter){
            $scope.resetFilters();
            $scope.curLetter = letter;
            $scope.loadListPage(1);
          }
          $scope.resetFilters = function(){
            $scope.query = "";
            $scope.curLetter = "";
            $scope.typeFilter = "";
            $scope.categoryFilter = "";
            $scope.groupFilter = "";
            $scope.thicknessFilter = "";
            $scope.kindFilter = "";
          }
          /**
           * init.
           */
          $scope.currentPage = 1;
          // Load the first page.
          $scope.loadListPage($scope.currentPage);
          // Set the default order.
          $scope.orderProp = 'age';
        }
    ]
  );
  /**
   * The inventory detail contoller.
   * 
   */
  tritonControllers.controller('InventoryDetailCtrl'
    , [
        '$scope'
        , '$routeParams'
        , 'Inventory'
        , '$http'
        , 'Configuration'
        , function($scope, $routeParams, Inventory, $http, Configuration) {
            $scope.text = JSON.stringify($routeParams);
            /*
            $scope.material = Inventory.get({serviceId: $routeParams.materialId + ".json"}, function(material) {
              $scope.mainImageUrl = material.images[0];
            });
            */
            $scope.setImage = function(imageUrl) {
              $scope.mainImageUrl = imageUrl;
            }
            // Get view configuration.
            $scope.view = Configuration.query({module:"materials", view:"view-material"}, function(result){});
            // Query.
            $http({
              method : 'GET',
              // @todo:: Make this configurable.
              url : '/triton/server/materials/view/' + $routeParams.materialId
            })
            .success(function(data, status, headers, config) {
              console.log(data);
              if ( data.status == "FOUND" ) {
                // Navigation parameters.
                $scope.navigation = {
                  title: "Product Detail " + data.data.name
                  , subtitle: ""
                  , actionMain: {value: "List", action: function(){window.location.href='#/inventory/list';}}
                  , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory/list';}}
                };
                $scope.material = data.data;
              }
            })
            .error(function(data, status, headers, config) {
              console.log(data);
              console.log(status);
              console.log(headers);
              console.log(config);
            });
        }
      ]
  );
    
  /**
   * The inventory create contoller.
   * 
   */
  tritonControllers.controller('InventoryCreateCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , function($scope, Configuration, $http) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Create Product"
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){$scope.create();}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory/list';}}
            }
            $scope.newMaterial = {};
            // Define the fields in the form.
            $scope.formView = Configuration.query({module:"materials", view:"create-material"}, function(result){});
            $scope.create = function() {
              console.log(JSON.stringify($scope.newMaterial));
              // Save
              $http({
                method : 'POST',
                // @todo:: Make this configurable.
                //url : 'http://54.149.15.82/triton/server/materials/index.php',
                url : '/triton/server/materials/new',
                data : $scope.newMaterial
              })
              .success(function(data, status, headers, config) {
                console.log(data);
                window.location.href = '#/inventory/view/' + data.pid;
              })
              .error(function(data, status, headers, config) {
                console.log(data);
                console.log(status);
                console.log(headers);
                console.log(config);
              });
            };
            $scope.reset = function() {
              window.location.href = '/triton/client/#/inventory';
            };   
            $scope.autocompleteFieldChange = function( fieldName, autocompleteGroup ){
              var term = $scope.newMaterial[fieldName];
              if ( term.length > 2 ) {
                console.log($scope.newMaterial[fieldName] + "->" + autocompleteGroup);
              }
            }
          }
      ]
  );
    
  /**
   * The inventory items create contoller.
   * 
   */
  tritonControllers.controller('InventoryItemsAddCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , function($scope, Configuration, $http) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Inventory Manual Submitting"
              , subtitle: ""
              , actionMain: {value: "Add", action: function(){window.location.href='#/inventory';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
            // Form attributes.
            $scope.formClasses = "form-add-inventory";
            //$scope.newMaterial = {};
            // Define the fields in the form.
            $scope.formView = Configuration.query({module:"materials", view:"inventory-add"}, function(result){});
          }
      ]
  );
    
  /**
   * Vans creation contoller.
   * 
   */
  tritonControllers.controller('InventoryVanCreateCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , function($scope, Configuration, $http) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Vans Creation"
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){window.location.href='#/inventory';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
            // Form attributes.
            $scope.formClasses = "form-van-create";
            // Define the fields in the form.
            $scope.formView = Configuration.query({module:"materials", view:"van-create"}, function(result){});
          }
      ]
  );
    
  /**
   * Vans creation contoller.
   * 
   */
  tritonControllers.controller('InventoryVanCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , function($scope, Configuration, $http) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Vans Inventory"
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){window.location.href='#/inventory';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
            // Define the fields in the table view.
            $scope.tableView = Configuration.query({module:"materials", view:"van-list"}, function(result){});
            $scope.rowUrl = "#/inventory/van/view/";
            /**
             * Loads another page from the material list.
             * 
             * @param pPage integer The number of the page to load.
             */ 
            $scope.loadListPage = function(pPage){
              // Current page state.
              $scope.currentPage = pPage;
              //$scope.dataList = [];
              // Query.
              $http({
                method: 'GET'
                // @todo:: Make this configurable.
                ,url: '/triton/server/materials/van/list'
                ,params: {
                  page: pPage
                }
              })
              .success(function(data, status, headers, config) {
                console.log(data);
                $scope.dataList = data.list;
                $scope.pager = data.pager;
              })
              .error(function(data, status, headers, config) {
                console.log(data);
                console.log(status);
                console.log(headers);
                console.log(config);
              });
            }
            /**
             * init.
             */
            $scope.currentPage = 1;
            // Load the first page.
            $scope.loadListPage($scope.currentPage);
          }
      ]
  );
    
  /**
   * Vans view / edit contoller.
   * 
   */
  tritonControllers.controller('InventoryVanDetailCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , '$routeParams'
        , function($scope, Configuration, $http, $routeParams) {
            // The current van.
            $scope.van = {}
            $scope.newProduct = {};
            $scope.autocompleteCache = {};
            // Navigation parameters.
            $scope.navigation = {
              title: "Van " + $scope.van.name
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){window.location.href='#/inventory';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
            // Form attributes.
            $scope.formClasses = "form-van-edit";
            // General info from the van.
            $http({
              method : 'GET',
              // @todo:: Make this configurable.
              url : '/triton/server/materials/van/view/' + $routeParams.vanId
            })
            .success(function(data, status, headers, config) {
              console.log(data);
              if ( data.status == "FOUND" ) {
                $scope.van = data.data;
                $scope.navigation.title = $scope.van.name;
              }
            })
            .error(function(data, status, headers, config) {
              console.log(data);
              console.log(status);
              console.log(headers);
              console.log(config);
            });
            $scope.addProductToVan = function(){
              var amount = $("#productAmount").val();
              // Validate.
              if ( amount != null && amount && $scope.newProduct.name != null ) {
                // Other parameters.
                $scope.newProduct.name = $("#productName").val();
                $scope.newProduct.iid = $("#productIid").val();
                $scope.newProduct.sku = $("#productSku").val();
                $scope.newProduct.description = $("#productDescription").val();
                console.log($scope.newProduct);
                // Save
                $http({
                  method : 'POST',
                  // @todo:: Make this configurable.
                  url : '/triton/server/materials/van/addProduct/' + $routeParams.vanId,
                  data : $scope.newProduct
                })
                .success(function(data, status, headers, config) {
                  console.log(data);
                  // Add the row to the table.
                  $("").clone()
                  
                  // Clone the prototype.
                  var newRow = $(".row-vanprod-prototype").clone().removeClass("row-vanprod-prototype").removeClass("hidden");
                  // Add the content.
                  $(newRow).find(".sku a").text(data.sku);
                  $(newRow).find(".name a").text(data.name);
                  $(newRow).find(".description a").text(data.description);
                  $(newRow).find(".price a").text();
                  $(newRow).find(".amount a").text(data.amount);
                  // Add the new row.
                  $(".table-van-products tbody").append(newRow);                  
                })
                .error(function(data, status, headers, config) {
                  console.log(data);
                  console.log(status);
                  console.log(headers);
                  console.log(config);
                });
              }
            };
            $scope.autocompleteFieldChange = function(field, group){
              $(".autocomplete-menu").remove();
              var term = $scope.newProduct[field];
              var list = [];
              if ( term != null && term.length > 2 ) {
                // Look in the cache.
                if ( $scope.autocompleteCache[term] != null ) {
                  list = $scope.autocompleteCache[term];
                }
                // Go to server.
                else {
                  $http({
                    method: 'GET'
                    // @todo:: Make this configurable.
                    ,url: '/triton/server/materials/list/'
                    ,params: {
                      page: 1
                      , q: term
                      , inventory: true
                    }
                  })
                  .success(function(data, status, headers, config) {
                    console.log(data);
                    if ( data.list != null  ) {
                      list = data.list;
                      var html = "<ul class='autocomplete-menu'>";
                      for ( var i=0, size=list.length; i<size; i++ ) {
                        html += "<li class='autocomplete-item' data-sku='" + list[i].sku 
                          + "' data-description='" + list[i].description  + "' data-amount='" 
                          + list[i].amount + "' data-units='" + list[i].units  
                          + "' data-iid='" + list[i].iid + "'>" 
                          + list[i].name 
                          + "<li/>";
                      }
                      html += "</ul>";
                      $("#productName").after(html);
                      // Handler.
                      $(".autocomplete-item").on({
                        click: function(){
                          $("#productName").val($(this).text());
                          $("#productSku").val($(this).data("sku"));
                          $("#productDescription").val($(this).data("description"));
                          $("#productCurrent").val($(this).data("amount"));
                          $("#productUnits").val($(this).data("units"));
                          $("#productIid").val($(this).data("iid"));

                          $(".autocomplete-menu").remove();
                        }
                      });
                      // Store results in cache.
                      $scope.autocompleteCache[term] = list;
                      // Show the autocomplete list.
                      console.log(term + "->" + group);
                      console.log(list);
                    }
                  })
                  .error(function(data, status, headers, config) {
                    console.log(data);
                    console.log(status);
                    console.log(headers);
                    console.log(config);
                  });
                }
              }
            };
          }
      ]
  );
    
  /**
   * Returns list controller.
   * 
   */
  tritonControllers.controller('InventoryReturnsListCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , '$routeParams'
        , function($scope, Configuration, $http, $routeParams) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Inventory Returns"
              , subtitle: ""
              , actionMain: {value: "New", action: function(){window.location.href='#/inventory/returns/new';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
            // Define the fields in the table view.
            $scope.tableView = Configuration.query({module:"materials", view:"returns-list"}, function(result){});
            $scope.rowUrl = "#/inventory/returns/view/";
            /**
             * Loads another page from the material list.
             * 
             * @param pPage integer The number of the page to load.
             */ 
            $scope.loadListPage = function(pPage){
              // Current page state.
              $scope.currentPage = pPage;
              //$scope.dataList = [];
              // Query.
              $http({
                method: 'GET'
                // @todo:: Make this configurable.
                ,url: '/triton/server/materials/returns/list'
                ,params: {
                  page: pPage
                }
              })
              .success(function(data, status, headers, config) {
                console.log(data);
                $scope.dataList = data.list;
                $scope.pager = data.pager;
              })
              .error(function(data, status, headers, config) {
                console.log(data);
                console.log(status);
                console.log(headers);
                console.log(config);
              });
            }
            /**
             * init.
             */
            $scope.currentPage = 1;
            // Load the first page.
            $scope.loadListPage($scope.currentPage);
          }
      ]
  );
    
  /**
   * Returns create controller.
   * 
   */
  tritonControllers.controller('InventoryReturnsNewCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , '$routeParams'
        , function($scope, Configuration, $http, $routeParams) {
            // Navigation parameters.
            $scope.navigation = {
              title: "New Inventory Return"
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){window.location.href='#/inventory/returns/new';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
                 // Form attributes.
            $scope.formClasses = "form-return-create";
            // Define the fields in the form.
            $scope.formView = Configuration.query({module:"materials", view:"return-create"}, function(result){});
          }
      ]
  );

  /**
   * Returns view / edit contoller.
   * 
   */
  tritonControllers.controller('InventoryReturnDetailCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , '$routeParams'
        , function($scope, Configuration, $http, $routeParams) {
            // The current return.
            $scope.inventoryReturn = {}
            // Navigation parameters.
            $scope.navigation = {
              title: "Return"
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){window.location.href='#/inventory';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
            // General info from the return.
            $http({
              method : 'GET',
              // @todo:: Make this configurable.
              url : '/triton/server/materials/returns/view/' + $routeParams.returnId
            })
            .success(function(data, status, headers, config) {
              console.log(data);
              if ( data.status == "FOUND" ) {
                $scope.inventoryReturn = data.data;
                $scope.navigation.title = $scope.inventoryReturn.name;
                $scope.navigation.subtitle = $scope.inventoryReturn.date;
              }
            })
            .error(function(data, status, headers, config) {
              console.log(data);
              console.log(status);
              console.log(headers);
              console.log(config);
            });
            $scope.addProductToReturn = function(){
              console.log("oi2");
            }
          }
      ]
  );

    
  /**
   * Consignments list contoller.
   * 
   */
  tritonControllers.controller('InventoryConsignmentListCtrl'
    , [
        '$scope'
        , 'Configuration'
        , '$http'
        , function($scope, Configuration, $http) {
            // Navigation parameters.
            $scope.navigation = {
              title: "Consigments Inventory"
              , subtitle: ""
              , actionMain: {value: "New", action: function(){window.location.href='#/inventory';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
            // Define the fields in the table view.
            $scope.tableView = Configuration.query({module:"materials", view:"consignments-list"}, function(result){});
            $scope.rowUrl = "#/inventory/consignment/view/";
            /**
             * Loads another page from the material list.
             * 
             * @param pPage integer The number of the page to load.
             */ 
            $scope.loadListPage = function(pPage){
              // Current page state.
              $scope.currentPage = pPage;
              //$scope.dataList = [];
              // Query.
              $http({
                method: 'GET'
                // @todo:: Make this configurable.
                ,url: '/triton/server/materials/consignments/list'
                ,params: {
                  page: pPage
                }
              })
              .success(function(data, status, headers, config) {
                console.log(data);
                $scope.dataList = data.list;
                $scope.pager = data.pager;
              })
              .error(function(data, status, headers, config) {
                console.log(data);
                console.log(status);
                console.log(headers);
                console.log(config);
              });
            }
            /**
             * init.
             */
            $scope.currentPage = 1;
            // Load the first page.
            $scope.loadListPage($scope.currentPage);
          }
      ]
  );
    
  /**
   * Vans view / edit contoller.
   * 
   */
  tritonControllers.controller('InventoryConsignmentDetailCtrl'
    , [
        '$scope'
        , '$http'
        , '$routeParams'
        , function($scope, $http, $routeParams) {
            // The current company.
            $scope.company = {}
            // Navigation parameters.
            $scope.navigation = {
              title: "Consignment Location"
              , subtitle: ""
              , actionMain: {value: "Save", action: function(){window.location.href='#/inventory';}}
              , actionSecond: {value: "Cancel", action: function(){window.location.href='#/inventory';}}
            }
            // Form attributes.
            $scope.formClasses = "form-consignment-edit";
            // General info from the van.
            $http({
              method : 'GET',
              // @todo:: Make this configurable.
              url : '/triton/server/materials/consignments/view/' + $routeParams.clientId
            })
            .success(function(data, status, headers, config) {
              console.log(data);
              if ( data.status == "FOUND" ) {
                $scope.company = data.data;
                $scope.navigation.title = $scope.company.company_name;
              }
            })
            .error(function(data, status, headers, config) {
              console.log(data);
              console.log(status);
              console.log(headers);
              console.log(config);
            });
            $scope.addProductToConsignment = function(){
              console.log("oi");
            }
          }
      ]
  );

