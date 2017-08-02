(function(angular) {
  'use strict';

var app = angular.module('XSCMS_DEBUG', ['ab-base64']);


 app.controller('Debug', function($scope,DoService,base64 ) {

    var params={type:"", what:"getdebug"} 
    var res=DoService.doGet(params,'admin');
    $scope.items = [];
   
    res.success(function(data, status, headers, config) {
        data.forEach(function(entry) {
            $scope.items.push(entry);
        });
    });

 });

})(window.angular);