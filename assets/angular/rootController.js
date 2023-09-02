/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Admin.directive('fileModel', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var model = $parse(attrs.fileModel);
                var modelSetter = model.assign;
                console.log(model);
                element.bind('change', function () {
                    scope.$apply(function () {
                        function imageIsLoaded(e) {

                            $(element[0]).parents(".thumbnail").find("img").attr('src', e.target.result);
                        }
                        var reader = new FileReader();
                        var fileobj = (element[0].files[0]);
                        let file_size = fileobj.size/1000000;
                        let filesizeFixedMB = file_size.toFixed(2)+" MB";
                        console.log(filesizeFixedMB);
                        reader.onload = imageIsLoaded;
                        reader.readAsDataURL(element[0].files[0]);
                        modelSetter(scope, element[0].files[0]);
                    });
                });
            }
        };
    }]);

Admin.controller('rootController', function ($scope, $http, $timeout, $interval) {
    var notify_url = rootBaseUrl + "localApi/notificationUpdate";
    $scope.rootData = {'notifications': []};
    $http.get(notify_url).then(function (rdata) {
        $scope.rootData.notifications = rdata.data;
    }, function () {})
})

