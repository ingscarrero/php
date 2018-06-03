/**
 * The main application.
 * 
 * @author juangalf-20150222
 */

  // Strict mode ON.
  'use strict';
  /* App Module */
  var tritonApp = angular.module('tritonApp', [
    'ngRoute',
    'phonecatAnimations',
    'tritonControllers',
    'phonecatFilters',
    'tritonServices', 'ui.bootstrap',
    'mgcrea.ngStrap.helpers.dateParser','mgcrea.ngStrap.tooltip', 'mgcrea.ngStrap.datepicker'
  ]);
  // Configuration. Set the different routes.
  tritonApp.config(['$routeProvider',
    function($routeProvider) {
      $routeProvider.
        when('/inventory', {
          templateUrl: 'partials/inventory-list.html',
          controller: 'InventoryListCtrl'
        }).
        when('/inventory/view/:materialId', {
          templateUrl: 'partials/inventory-detail.html',
          controller: 'InventoryDetailCtrl'
        }).
        when('/inventory/create', {
          templateUrl: 'partials/inventory-create.html',
          controller: 'InventoryCreateCtrl'
        }).
        when('/inventory/items/add', {
          templateUrl: 'partials/form.html',
          controller: 'InventoryItemsAddCtrl'
        }).
        when('/inventory/van/create', {
          templateUrl: 'partials/form.html',
          controller: 'InventoryVanCreateCtrl'
        }).
        when('/inventory/van/inventory', {
          templateUrl: 'partials/table.html',
          controller: 'InventoryVanCtrl'
        }).
        when('/inventory/van/view/:vanId', {
          templateUrl: 'partials/van-view.html',
          controller: 'InventoryVanDetailCtrl'
        }).
        when('/inventory/consignment/list', {
          templateUrl: 'partials/table.html',
          controller: 'InventoryConsignmentListCtrl'
        }).
        when('/inventory/consignment/view/:clientId', {
          templateUrl: 'partials/consignment-view.html',
          controller: 'InventoryConsignmentDetailCtrl'
        }).
        when('/inventory/returns/list', {
          templateUrl: 'partials/table.html',
          controller: 'InventoryReturnsListCtrl'
        }).
        when('/inventory/returns/new', {
          templateUrl: 'partials/form.html',
          controller: 'InventoryReturnsNewCtrl'
        }).
        when('/inventory/returns/view/:returnId', {
          templateUrl: 'partials/return-view.html',
          controller: 'InventoryReturnDetailCtrl'
        }).
        when('/salesorder/list', {
          templateUrl: 'partials/sales/salesorder-list.html',
          controller: 'SalesOrderListCtrl'
        }).
        when('/salesorder/save/:action', {
          templateUrl: 'partials/sales/salesorder-create.html',
          controller: 'SalesOrderCreateCtrl'
        }).
        when('/salesorder/edit/:soid', {
          templateUrl: 'partials/sales/salesorder-edit.html',
          controller: 'SalesOrderEditCtrl'
        }).
        when('/salesorder/view/:id', {
          templateUrl: 'partials/sales/salesorder-view.html',
          controller: 'SalesOrderViewCtrl'
        }).
        otherwise({
          redirectTo: '/inventory'
        });
  }]);