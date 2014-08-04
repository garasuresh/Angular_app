/**
 * Created with JetBrains PhpStorm.
 * User: suresh
 * Date: 22/07/14
 * Time: 08:53
 * To change this template use File | Settings | File Templates.
 */

var module = angular.module("customAPP",[]);
angular.element(document).ready(function(){
    angular.bootstrap(document.getElementById('custom-id'),['customAPP']);
});

module.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('%');
    $interpolateProvider.endSymbol('%');

});

module.controller('ItemController',function($scope,$http){

    // Pagination
    $scope.currentPage = 0;
    $scope.pageSize = 10;
    $scope.numberOfPages=function(){
        return Math.ceil((($scope.items)?$scope.items.length:0)/$scope.pageSize);
    }

    // Loading all items while loading page
    $http({method:'GET',url: '/api/items'}).success(function(data){
        $scope.items = data;
    });

    // Adding item
    $scope.addItem = function(){
        var formData = { name: $scope.itemName , price: $scope.price };
        $http({
            method: 'POST',
            url: '/api/post/item',
            data: formData }).
            success(function(data, status, headers, config) {
                angular.forEach(data, function(value){
                    $scope.items.push(value);
                    $scope.itemName = '';
                    $scope.price = '';
                });
            }).
            error(function(data, status, headers, config) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
            });
    }

    $scope.removeItem = function(id,index){
        $http({
            method: 'GET',
            url: '/api/item/remove/'+id }).
            success( function (response) {
                    if(response == 'success'){
                        var itemsArray = $scope.items;
                        itemsArray.splice(index,1);
                    }
            })
    }
});

module.filter('startFrom', function() {
    return function(input,start) {
        if(input != undefined){
            return input.slice(start);
        }
    }
});