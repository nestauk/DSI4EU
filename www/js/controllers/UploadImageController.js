var app = angular.module('DSIApp');

app.controller('UploadImageController', function ($scope, $http, Upload, $timeout) {
    $http.get(SITE_RELATIVE_PATH + '/my-profile.json')
        .then(function (result) {
            $scope.user = result.data || {};
        });

    $scope.uploadFiles = function(file, errFiles) {
        $scope.f = file;
        $scope.errFile = errFiles && errFiles[0];
        if (file) {
            file.upload = Upload.upload({
                url: SITE_RELATIVE_PATH + '/uploadProfilePicture',
                data: {file: file}
            });

            file.upload.then(function (response) {
                $timeout(function () {
                    file.result = response.data;
                    if(response.data.result == 'ok')
                        $scope.user.profilePic = response.data.imgPath;
                    else if(response.data.result == 'error'){
                        $scope.errorMsg = response.data.errors;
                    }
                });
            }, function (response) {
                if (response.status > 0)
                    $scope.errorMsg = response.status + ': ' + response.data;
            }, function (evt) {
                file.progress = Math.min(100, parseInt(100.0 *
                    evt.loaded / evt.total));
            });
        }
    };
});