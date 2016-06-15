angular
    .module(angularAppName)
    .controller('DashboardController', function ($scope, $http, $timeout) {
        $http.get(SITE_RELATIVE_PATH + '/dashboard.json')
            .then(function (response) {
                console.log(response.data);
                $scope.projectInvitations = response.data.projectInvitations;
            });

        function extractElm(pool, elm) {
            var newPool = [];
            for (var i in pool) {
                if (pool[i].id != elm.id)
                    newPool.push(pool[i]);
            }
            return newPool;
        }

        $scope.approveProjectInvitation = function (invitation) {
            console.log(invitation);
            var data = {
                approveProjectInvitation: true,
                projectID: invitation.id
            };
            $http.post(SITE_RELATIVE_PATH + '/dashboard.json', data)
                .then(function (response) {
                    if (response.data.code == 'ok') {
                        $scope.projectInvitations = extractElm($scope.projectInvitations, invitation);
                    } else if (response.data.code == 'error') {

                    }
                });
        };

        $scope.declineProjectInvitation = function (invitation) {
            console.log(invitation);
            var data = {
                rejectProjectInvitation: true,
                projectID: invitation.id
            };
            $http.post(SITE_RELATIVE_PATH + '/dashboard.json', data)
                .then(function (response) {
                    console.log(response.data);
                    if (response.data.code == 'ok') {
                        $scope.projectInvitations = extractElm($scope.projectInvitations, invitation);
                    } else if (response.data.code == 'error') {

                    }
                });
        };
    });