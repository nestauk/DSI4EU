angular
    .module(angularAppName)
    .controller('ProjectEditController', function ($scope, $http, $timeout, Upload, $attrs) {
        var projectID = $attrs.projectid;
        $http.get(SITE_RELATIVE_PATH + '/project/edit/' + projectID + '.json')
            .then(function (result) {
                console.log(result.data);
                $scope.project = result.data || {};
            });

        $scope.save = function () {
            $scope.loading = true;
            $scope.errors = {};

            var data = $scope.project;
            data.saveDetails = true;

            console.log(data);

            $http.post(SITE_RELATIVE_PATH + '/project/edit/' + projectID + '.json', data)
                .then(function (response) {
                    $scope.loading = false;
                    console.log(response.data);
                    
                    if (response.data.result == 'ok')
                        swal(response.data.message.title, response.data.message.text, "success");
                    else if (response.data.result == 'error')
                        $scope.errors = response.data.errors;
                });
        };

        $scope.profilePic = {};
        $scope.profilePic.upload = function (file, errFiles) {
            $scope.profilePic.loading = true;
            $scope.profilePic.f = file;
            $scope.profilePic.errFile = errFiles && errFiles[0];
            if (file) {
                file.upload = Upload.upload({
                    url: SITE_RELATIVE_PATH + '/temp-gallery.json',
                    data: {
                        file: file,
                        format: 'profilePic'
                    }
                });

                file.upload.then(function (response) {
                    $scope.profilePic.loading = false;
                    console.log(response.data);
                    file.result = response.data;
                    if (response.data.code == 'ok')
                        $scope.user.profilePic = response.data.imgPath;
                    else if (response.data.code == 'error')
                        $scope.profilePic.errorMsg = response.data.errors;
                }, function (response) {
                    if (response.status > 0)
                        $scope.profilePic.errorMsg = response.status + ': ' + response.data;
                }, function (evt) {
                    file.progress = Math.min(100, parseInt(100.0 *
                        evt.loaded / evt.total));
                });
            }
        };
    });