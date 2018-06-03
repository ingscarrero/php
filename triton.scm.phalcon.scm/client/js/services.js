/**
 * The services.
 * 
 * @author juangalf-20150222
 */
(function(){
  'use strict';
  var tritonServices = angular.module('tritonServices', ['ngResource']);
  /**
   * Configuration service.
   * 
   */
  tritonServices.factory('Configuration', ['$resource',
    function($resource){
      return $resource(
        // @todo::: Make this dynamic.
        '/triton/server/configuration/:module/:view.json' // The service URL.
        , {} // Parameters defaults.
        , {query: {method:'GET', params:{}, isArray:true}} // Actions.
      );
  }]);

  /**
   * Inventory service.
   * 
   */
  tritonServices.factory('Inventory', ['$resource',
    function($resource){
      return $resource(
        // @todo::: Make this dynamic.
        //'http://54.149.15.82/triton/server/materials/:materialId.json' // The service URL.
        '/triton/server/materials/:serviceId' // The service URL.
        , {} // Parameters defaults.
        , {query: {method:'GET', params:{serviceId:'list.json'}, isArray:false}} // Actions.
      );
  }]);
  
  /**
   * SalesOrder service.
   * 
   */
  tritonServices.factory('SalesOrder', ['$resource',
    function($resource){
      return $resource(
        // @todo::: Make this dynamic.
        '/triton/server/salesorder/list' // The service URL.
        , {} // Parameters defaults.
        , {query: {method:'GET', params:{}, isArray:false}} // Actions.
      );
  }]);
})();