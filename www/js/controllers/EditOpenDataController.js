angular
    .module(angularAppName)
    .controller('EditOpenDataController', function ($scope, $http, Upload, $attrs) {
        $scope.modals = {
            resource: false
        };

        $scope.openNewClusterImage = function ($event) {
            $scope.resetEditingImage();
            $scope.modals.image = true;
            $event.stopPropagation();
        };
        $scope.openEditClusterImage = function (clusterImage) {
            $scope.editingImage = clusterImage;
            $scope.modals.image = true;
        };

        $scope.closeResourceModal = function () {
            $scope.modals.image = false;
            $scope.resetEditingImage();
        };
        $scope.saveResources = function () {
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
        $scope.deleteResource = function (image) {
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

        $scope.saveTexts = function () {
            $scope.loading = true;
            $scope.texts = {
                main: tinyMCE.get('main-text').getContent(),
                sub: tinyMCE.get('sub-text').getContent(),
            };

            $http.post(window.location.href, $scope.texts)
                .then(function (response) {
                    swal({
                        title: 'Success',
                        text: 'The page details have been successfully saved',
                        type: "success"
                    });
                })
                .catch(function (error) {
                    $scope.errors = error.data.object;
                    swal({
                        title: '',
                        text: 'Please correct the errors',
                        type: "error"
                    });
                })
                .finally(function () {
                    $scope.loading = false;
                });
        };
    });