angular
    .module(angularAppName)
    .controller('DashboardController', function ($scope, $http, $timeout) {
        $http.get(SITE_RELATIVE_PATH + '/dashboard.json')
            .then(function (response) {
                console.log(response.data);
                $scope.projectInvitations = response.data.projectInvitations;
            });

        $scope.approveProjectInvitation = function (invitation) {
            console.log(invitation);
            var data = {
                approveProjectInvitation: true,
                projectID: invitation.id
            };
            $http.post(SITE_RELATIVE_PATH + '/dashboard.json', data)
                .then(function (response) {
                    console.log(response.data);
                    if (response.data.code == 'ok') {
                        var newProjectInvitations = [];
                        for (i in $scope.projectInvitations) {
                            if ($scope.projectInvitations[i].id != invitation.id)
                                newProjectInvitations.push($scope.projectInvitations[i]);
                        }
                        $scope.projectInvitations = newProjectInvitations;
                    } else if (response.data.code == 'error') {

                    }
                });
        }
    });