angular
    .module(angularAppName)
    .controller('FeedbackController', function ($scope, $http, $timeout) {
        $scope.feedback = {};
        $scope.sendFeedbackSubmit = function () {
            $scope.errors = {};
            $scope.loading = true;
            $scope.feedbackSent = false;
            $timeout(function () {
                var data = $scope.feedback;
                data.sendFeedback = true;
                $http.post(SITE_RELATIVE_PATH + '/feedback.json', data)
                    .then(function (result) {
                        $scope.loading = false;
                        if (result.data.code == 'ok') {
                            $scope.feedbackSent = true;
                            $scope.feedback = {};
                        } else if (result.data.code == 'error') {
                            $scope.errors = result.data.errors
                        }
                    });
            }, 500);
        };
    });