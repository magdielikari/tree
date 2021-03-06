(function() {

  'use strict';

    // Pass the navbarDirective to the app
    angular
        .module('y')
        .directive('navbarDirective', navbarDirective);


    // Define the navbarDirective
    function navbarDirective() {

        // Define directive
        var directive = {

                restrict: 'EA',
                templateUrl: 'app/shared/components/navbar-component/navbar-component.html',
                scope: {
                    navbarString: '@',                      // Isolated scope string
                    navbarAttribute: '=',                   // Isolated scope two-way data binding
                    navbarAction: '&'                       // Isolated scope action
                },
                link: linkFunc,
                controller: navbarDirectiveController,
                controllerAs: 'navbarDirective'
        };

        // Return directive
        return directive;

        // Define link function
        function linkFunc(scope, el, attr, ctrl) {

            // Do stuff...
        }
    }

    // Define directive controller
    function navbarDirectiveController() {

        // Do stuff...
    }

})();
