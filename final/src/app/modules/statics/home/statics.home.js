(function() {
  'use strict';

    // Pass the staticsHomeCtrl to the app
    angular
        .module('y')
        .controller('staticsHomeCtrl', staticsHomeCtrl);

    // Define the staticsHomeCtrl
    function staticsHomeCtrl() {

        // Inject with ng-annotate
        "ngInject";


        // Define staticsHome as this for ControllerAs and auto-$scope
        var staticsHome = this;
            staticsHome.title =    "Tree app";
    }
})();
