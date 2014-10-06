var dockerUIApp = angular.module('dockerUIApp', []);

dockerUIApp.controller('ContainerListCtrl', function ($scope, $http) {

    $http.get(baseUrl + 'list').success(function(data) {
        $scope.containers = data;
    });

    $scope.startContainer = function(containerId) {
        console.log('start: ' + containerId);

        var data = $.param({
            containerId: containerId
        });

        $http.post(baseUrl + 'start', data).success(function(data, success) {
            console.log(data);
        });
    }

    $scope.stopContainer = function(containerId) {
        console.log('stop: ' + containerId);

        var data = $.param({
            containerId: containerId
        });

        $http.post(baseUrl + 'stop', data).success(function(data, success) {
            console.log(data);
        });
    }
});