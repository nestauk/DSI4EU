angular
    .module(angularAppName)
    .controller('OpenResourceEditController', function ($scope, $http, $attrs, Upload) {
        // image
        (function () {
            $scope.featuredImageUpload = {};
            $scope.featuredImageUpdated = false;
            $scope.uploadFeaturedImage = function (file, errFiles) {
                $scope.featuredImageUpdated = true;
                $scope.featuredImageUpload.f = file;
                $scope.featuredImageUpload.errFile = errFiles && errFiles[0];
                if (file) {
                    file.upload = Upload.upload({
                        url: SITE_RELATIVE_PATH + '/temp-gallery.json',
                        data: {file: file}
                    });

                    file.upload.then(function (response) {
                        file.result = response.data;
                        if (response.data.code == 'ok')
                            $scope.featuredImage = response.data.imgPath;
                        else if (response.data.code == 'error')
                            $scope.featuredImageUpload.errorMsg = response.data.errors;

                        $scope.featuredImageUpload = {};
                    }, function (response) {
                        if (response.status > 0)
                            $scope.featuredImageUpload.errorMsg = response.status + ': ' + response.data;
                    }, function (evt) {
                        file.progress = Math.min(100, parseInt(100.0 *
                            evt.loaded / evt.total));
                    });
                }
            };
        }());

        // getResource
        (function () {
            $http.get($attrs.loadurl)
                .then(function (response) {
                    $scope.resource = response.data.object;
                    $scope.featuredImage = $scope.resource.image;
                }, function (error) {
                    console.log({error});
                })
        }());

        // addResource
        (function () {
            $scope.saveResource = function () {
                $scope.loading = true;
                var data = {
                    title: $scope.resource.title,
                    description: $scope.resource.description,
                    link_text: $scope.resource.link_text,
                    link_url: $scope.resource.link_url,
                };
                if ($scope.featuredImageUpdated)
                    data.image = $scope.featuredImage;

                $http.post('/api/open-resource/' + $scope.resource.id, data)
                    .then(function (response) {
                        $scope.loading = false;
                        swal({
                            title: 'Success',
                            text: 'The resource has been successfully saved',
                            type: "success"
                        }, function () {
                            // window.location.href = response.data.object.url;
                        });
                    }, function () {
                        $scope.loading = false;
                        $scope.errors = response.data.errors;
                    });
            }
        }())
    });