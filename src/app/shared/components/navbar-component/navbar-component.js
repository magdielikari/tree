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
    function navbarDirectiveController(userService, $scope) {
        var self = this;
        self.title = "Tree";
        self.guest = userService.isGuest;
        self.role = userService.getRole();

        self.disable = true;

        userService.cookieLogin(function(){
            self.disable = false;
        });

        self.login = function(){
            userService.login(self.form, function(err, success){
                if(err){
                    console.error(err);
                }

                if(success){
                    self.guest = !success;
                    self.role = userService.getRole();
                }
            });
        };

        $scope.$on("user.login", function(ev, success, data){
           if(success){
                self.guest = !success;
                self.role = userService.getRole();
            }
        });
    }
})();
