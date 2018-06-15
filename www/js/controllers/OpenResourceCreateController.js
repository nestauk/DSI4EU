angular
    .module(angularAppName)
    .controller('OpenResourceCreateController', function ($scope, $http, Upload) {
        // image
        (function () {
            $scope.featuredImageUpload = {};
            $scope.uploadFeaturedImage = function (file, errFiles) {
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

        $scope.resource = {};

        // addResource
        (function () {
            $scope.addResource = function () {
                $scope.errors = {};
                $scope.loading = true;
                var data = {
                    title: $scope.resource.title,
                    description: $scope.resource.description,
                    link_text: $scope.resource.link_text,
                    link_url: $scope.resource.link_url,
                    clusters: $scope.resource.clusters,
                    types: $scope.resource.types,
                    author_id: $scope.resource.author_id,
                    image: $scope.featuredImage,
                };
                $http.post('/api/open-resource/', data)
                    .then(function (response) {
                        $scope.loading = false;
                        swal({
                            title: 'Success',
                            text: 'The resource has been successfully created',
                            type: "success"
                        }, function () {
                            window.location.href = response.data.object.url;
                        });
                    }, function (response) {
                        $scope.loading = false;
                        $scope.errors = response.data.object;
                    });
            }
        }())
    });