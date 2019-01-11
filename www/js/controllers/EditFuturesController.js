angular
	.module(angularAppName)
	.controller('EditFuturesController', function($scope, $http, Upload, $attrs) {
		$scope.modals = {
			resource: false
		};

		$scope.saveTexts = function() {
			$scope.loading = true;
			$scope.texts = {
				title: $('#title').val(),
				description: $('#description').val(),
				content: tinyMCE.get('content').getContent(),
			};

			$http.post(window.location.href, $scope.texts)
				.then(function(response) {
					swal({
						title: 'Success',
						text: 'The page details have been successfully saved',
						type: "success"
					});
				})
				.catch(function(error) {
					$scope.errors = error.data.object;
					swal({
						title: '',
						text: 'Please correct the errors',
						type: "error"
					});
				})
				.finally(function() {
					$scope.loading = false;
				});
		};
	});
