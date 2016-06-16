angular
    .module(angularAppName)
    .controller('PersonalDetailsController', function ($scope, $http, $timeout, Upload) {
        $http.get(SITE_RELATIVE_PATH + '/my-profile.json')
            .then(function (result) {
                $scope.user = result.data || {};
            });

        $scope.savePersonalDetails = function () {
            $scope.loading = true;
            $scope.errors = {};

            var data = $scope.user;
            data.saveDetails = true;

            console.log(data);

            $http.post(SITE_RELATIVE_PATH + '/my-profile.json', data)
                .then(function (response) {
                    $scope.loading = false;
                    console.log(response.data);

                    if (response.data.response == 'ok')
                        swal(response.data.message.title, response.data.message.text, "success");
                    else if (response.data.response == 'error')
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