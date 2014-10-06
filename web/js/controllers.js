var dockerUIApp = angular.module('dockerUIApp', []);

dockerUIApp.controller('ContainerListCtrl', function ($scope, $http) {

    $http.get(baseUrl + 'list').success(function(data) {
        $scope.containers = data;
    });

    $scope.startContainer = function() {
        alert('start');
    }

    $scope.stopContainer = function() {
        alert('stop');
    }
});