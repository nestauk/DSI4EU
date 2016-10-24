angular
    .module(angularAppName)
    .controller('AddStoryController', function ($scope, $http, Upload) {
        // featuredImage
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

        // mainImage
        (function () {
            $scope.mainImageUpload = {};
            $scope.uploadMainImage = function (file, errFiles) {
                $scope.mainImageUpload.f = file;
                $scope.mainImageUpload.errFile = errFiles && errFiles[0];
                if (file) {
                    file.upload = Upload.upload({
                        url: SITE_RELATIVE_PATH + '/temp-gallery.json',
                        data: {file: file}
                    });

                    file.upload.then(function (response) {
                        file.result = response.data;
                        if (response.data.code == 'ok')
                            $scope.mainImage = response.data.imgPath;
                        else if (response.data.code == 'error')
                            $scope.mainImageUpload.errorMsg = response.data.errors;

                        $scope.mainImageUpload = {};
                    }, function (response) {
                        if (response.status > 0)
                            $scope.mainImageUpload.errorMsg = response.status + ': ' + response.data;
                    }, function (evt) {
                        file.progress = Math.min(100, parseInt(100.0 *
                            evt.loaded / evt.total));
                    });
                }
            };
        }());

        // addStory
        (function () {
            $scope.addStory = function () {
                $scope.loading = true;
                var data = {
                    add: true,
                    title: $scope.title,
                    cardShortDescription: $scope.cardShortDescription,
                    datePublished: $scope.datePublished,
                    featuredImage: $scope.featuredImage,
                    mainImage: $scope.mainImage,
                    content: tinyMCE.get('newStory').getContent(),
                    isPublished: $scope.isPublished
                };
                $http.post(SITE_RELATIVE_PATH + '/story/add', data)
                    .then(function (response) {
                        $scope.loading = false;
                        if (response.data.code == 'ok') {
                            swal({
                                title: 'Success',
                                text: 'The story has been successfully created',
                                type: "success"
                            }, function () {
                                window.location.href = response.data.url;
                            });
                        } else if (response.data.code == 'error') {
                            $scope.errors = response.data.errors;
                            console.log(response.data.errors);
                        } else {
                            alert('unexpected error');
                            console.log(response.data);
                        }
                    });
            }
        }())
    });