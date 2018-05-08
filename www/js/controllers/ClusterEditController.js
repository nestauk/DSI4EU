angular
    .module(angularAppName)
    .controller('ClusterEditController', function ($scope, $http, Upload, $attrs) {

        var editUrl = $attrs.editurl;
        var editImageUrl = $attrs.editimageurl;

        $scope.modals = {
            image: false
        };

        $http.get(editUrl).then(function (response) {
            $scope.cluster = response.data.object;
        });

        $scope.openNewClusterImage = function ($event) {
            $scope.resetEditingImage();
            $scope.modals.image = true;
            $event.stopPropagation();
        };
        $scope.openEditClusterImage = function (clusterImage) {
            $scope.editingImage = clusterImage;
            $scope.modals.image = true;
        };

        $scope.saveClusterImage = function () {
            if ($scope.editingImage.loading)
                return;

            $scope.editingImage.loading = true;
            if (!$scope.editingImage.id)
                return $scope.addNewImage();
            else
                return $scope.saveExistingImage();
        };

        $scope.closeImageModal = function () {
            $scope.modals.image = false;
            $scope.resetEditingImage();
        };

        $scope.addNewImage = function () {
            $scope.editingImage.loading = true;
            $scope.editingImage.clusterLangID = $scope.cluster.id;
            $http.post(editImageUrl, $scope.editingImage)
                .then(function (response) {
                    $scope.modals.image = false;
                    swal({
                        title: 'Success',
                        text: 'The image has been successfully added',
                        type: "success"
                    });
                    $scope.cluster.images.push(response.data.object);
                })
                .catch(function (error) {
                    $scope.errors = error.data.object;
                })
                .finally(function () {
                    $scope.editingImage.loading = false;
                });
        };
        $scope.saveExistingImage = function () {
            $scope.editingImage.loading = true;
            $scope.editingImage.clusterLangID = $scope.cluster.id;
            $http.post(editImageUrl + $scope.editingImage.id, $scope.editingImage)
                .then(function (response) {
                    $scope.modals.image = false;
                    swal({
                        title: 'Success',
                        text: 'The image has been successfully saved',
                        type: "success"
                    });
                })
                .catch(function (error) {
                    $scope.errors = error.data.object;
                })
                .finally(function () {
                    $scope.editingImage.loading = false;
                });
        };
        $scope.deleteClusterImage = function (image) {
            if (confirm('Are you sure you want to remove this cluster image?')) {
                $http.delete(editImageUrl + image.id)
                    .then(function (response) {
                        $scope.modals.image = false;
                        swal({
                            title: 'Success',
                            text: 'The image has been successfully removed',
                            type: "success"
                        });

                        $scope.cluster.images = $scope.cluster.images.filter(function (_image) {
                            return _image !== image;
                        });
                    })
                    .catch(function (error) {
                        $scope.errors = error.data.object;
                    });
            }
        };

        $scope.save = function () {
            $scope.loading = true;
            $scope.cluster.description = tinyMCE.get('description').getContent();
            $scope.cluster.get_in_touch = tinyMCE.get('get_in_touch').getContent();

            $http.post(editUrl, $scope.cluster)
                .then(function (response) {
                    swal({
                        title: 'Success',
                        text: 'The cluster details have been successfully saved',
                        type: "success"
                    });
                })
                .catch(function (error) {
                    $scope.errors = error.data.object;
                })
                .finally(function () {
                    $scope.loading = false;
                });
        };

        $scope.uploadClusterImage = function (file, errFiles) {
            $scope.editingImage.loading = true;
            $scope.editingImage.f = file;
            $scope.editingImage.errFile = errFiles && errFiles[0];
            if (file) {
                file.upload = Upload.upload({
                    url: SITE_RELATIVE_PATH + '/temp-gallery.json',
                    data: {
                        file: file,
                        format: 'profilePic'
                    }
                });

                file.upload.then(function (response) {
                    $scope.editingImage.loading = false;
                    file.result = response.data;
                    if (response.data.code === 'ok') {
                        $scope.editingImage.path = $scope.editingImage.filename = response.data.imgPath;
                    } else if (response.data.code === 'error')
                        $scope.editingImage.errorMsg = response.data.errors;
                }, function (response) {
                    if (response.status > 0)
                        $scope.editingImage.errorMsg = response.status + ': ' + response.data;
                }, function (evt) {
                    file.progress = Math.min(100, parseInt(100.0 *
                        evt.loaded / evt.total));
                });
            }
        };

        $scope.resetEditingImage = function () {
            $scope.editingImage = {
                id: '',
                link: '',
                filename: '',
                path: '',
            };
        };
        $scope.resetEditingImage();
    });