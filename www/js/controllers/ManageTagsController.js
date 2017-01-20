angular
    .module(angularAppName)
    .controller('ManageTagsController', function ($scope, $http, $interval, $attrs) {
        var manageTagsJsonUrl = $attrs.managetagsjsonurl;

        $scope.letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');

        $scope.filter = {
            tagID: $attrs.searchtag,
            netwTagID: $attrs.searchnetwtag
        };
        $scope.searchName = $attrs.searchname;
        $scope.startLetter = '';
        $scope.tags = [];

        $http.get(manageTagsJsonUrl)
            .then(function (result) {
                $scope.loaded = true;
                $scope.data = result.data;
                console.log($scope.data);
            });

        $scope.setStartLetter = function (letter) {
            $scope.startLetter = letter;
        };

        $scope.startsWithLetter = function (item) {
            if ($scope.startLetter == '')
                return true;

            var letterMatch = new RegExp($scope.startLetter, 'i');
            return letterMatch.test(item.name.substring(0, 1));
        };

        $scope.organisationInCountry = function () {
            return function (item) {
                if ($scope.filter.countryID == '0')
                    return true;

                return $scope.filter.countryID == item.countryID;
            }
        };

        $scope.organisationHasTag = function () {
            return function (item) {
                if ($scope.filter.tagID == '0')
                    return true;

                return $.inArray(parseInt($scope.filter.tagID), item.tags) !== -1;
            }
        };

        $scope.projectHasNetwTag = function () {
            return function (item) {
                if ($scope.filter.netwTagID == '0')
                    return true;

                return $.inArray(parseInt($scope.filter.netwTagID), item.netwTags) !== -1;
            }
        };

        function deleteTag(tag, postField, filterTags) {
            swal({
                title: "Delete tag",
                text: "Are you sure you want to delete this tag?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                post = {};
                post[postField] = true;
                post.tagID = tag.id;

                $http
                    .post(window.location.href, post)
                    .then(function (response) {
                        if (response.data.code == 'ok') {
                            swal({
                                title: "Deleted",
                                text: "The tag has been deleted",
                                type: "success"
                            });

                            $scope.data[filterTags] = $scope.data[filterTags].filter(function (elm) {
                                return elm.id != tag.id;
                            })
                        } else {
                            alert('unexpected error');
                            console.log(response.data)
                        }
                    });
            });
        }

        $scope.deleteNetworkTag = function (tag) {
            deleteTag(tag, 'deleteNetworkTag', 'networkTags');
        };

        $scope.deleteOrganisationTag = function (tag) {
            deleteTag(tag, 'deleteOrganisationTag', 'organisationTags');
        };

        $scope.deleteProjectTag = function (tag) {
            deleteTag(tag, 'deleteProjectTag', 'projectTags');
        };

        $scope.deleteProjectImpactTag = function (tag) {
            deleteTag(tag, 'deleteProjectImpactTag', 'projectImpactTags');
        };
    });