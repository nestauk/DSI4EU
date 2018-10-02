angular
    .module(angularAppName)
    .controller('UploadResourcesController', function ($scope, $http, Upload) {
        $scope.response = [];
        $scope.loading = false;
        $scope.file = null;
        $scope.errFiles = null;
        $scope.hasErrors = function () {
            return $scope.response.map(function (resp) {
                return resp.errors.length > 0;
            }).filter(function (resp) {
                return resp;
            }).length > 0;
        };
        $scope.uploader = {};
        $scope.upload = function (file, errFiles) {
            $scope.file = file;
            $scope.errFiles = errFiles;
            $scope.uploadFile(file, errFiles, {
                file: file,
                url: '/upload/resources'
            }, function (response) {
                $scope.response = response.data.object;
                $scope.loading = false;
            })
        };
        $scope.uploadAndSave = function () {
            $scope.uploadFile($scope.file, $scope.errFiles, {
                file: $scope.file,
                url: '/upload/resources/save'
            }, function (response) {
                swal('Success!', 'The resources have been successfully uploaded.', 'success');
                $scope.loading = false;
            })
        };

        $scope.uploadFile = function (file, errFiles, data, onSuccess) {
            $scope.response = [];
            $scope.loading = true;
            $scope.errorMsg = {};
            $scope.uploader.f = file;
            $scope.uploader.errFile = errFiles && errFiles[0];
            if (file) {
                file.upload = Upload.upload(data);
                file.upload.then(function (response) {
                    console.log({success: response.data.object});
                    onSuccess(response);
                }, function (response) {
                    console.log({error: response.data});
                    $scope.loading = false;
                }, function (evt) {
                    file.progress = Math.min(100, parseInt(100.0 *
                        evt.loaded / evt.total));
                });
            } else {
                $scope.loading = false;
            }
        }
    });