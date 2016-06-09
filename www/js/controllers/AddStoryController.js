var app = angular.module('DSIApp');

app.controller('AddStoryController', function ($scope, $http, $timeout, Upload) {
    // featuredImage
    (function(){
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
                    console.log(response.data);
                    file.result = response.data;
                    if (response.data.code == 'ok')
                        $scope.featuredImage = response.data.imgPath;
                    else if (response.data.code == 'error')
                        $scope.featuredImageUpload.errorMsg = response.data.errors;

                    $scope.featuredImageUpload = {};
                    console.log($scope.featuredImage);
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
    (function(){
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
                    console.log(response.data);
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
    (function(){
        $scope.addStory = function(){
            var data = {
                add: true,
                title: $scope.title,
                datePublished: $scope.datePublished,
                featuredImage: $scope.featuredImage,
                mainImage: $scope.mainImage,
                content: $('#newStory').val(),
                isPublished: $scope.isPublished
            };
            console.log(data);
        }
    }())
});