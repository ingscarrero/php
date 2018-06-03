'use strict';

/* Directives */
tritonApp.directive('dynamicHtml', ['$rootScope','$compile',
    function ($rootScope, $compile) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
		element.html(attrs.dynamicHtml);
		$compile(element.contents())(scope);

    }
  };
}]);
