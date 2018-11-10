angular
	.module(angularAppName)
	.controller('ProfileEditController', function($scope, $http, Upload, $attrs) {
		var profileUrl = $attrs.urlprofile;

		$scope.user = {};
		$http.get(window.location.pathname + '.json')
			.then(function(result) {
				$scope.user = result.data || {};
			});

		$scope.changeCurrentTab = function(tab) {
			if( $scope.currentTab === 'step1' )
				$scope.submitStep1({proceed: false});

			if( !$scope.user.firstName || !$scope.user.lastName || !$scope.user.email )
				return;

			$scope.currentTab = tab;
		};

		$scope.currentTab = 'step1';
		if( window.location.hash ) {
			var hash = window.location.hash.substring(1);
			if( hash === 'step3' )
				$scope.currentTab = 'step3';
		}

		$scope.submitStep1 = function(params) {
			$scope.saveUserDetails({
				postField: 'step1',
				onSuccess: function() {
					if( params ) {
						if( params.alert === true )
							return swal('Success!', 'The changes have been successfully saved.', 'success');
						if( params.proceed === false )
							return true;
					} else {
						return $scope.currentTab = 'step2';
					}
				}
			})
		};
		$scope.submitStep2 = function(params) {
			$scope.user.languages = $('#languagesSelect').val();
			$scope.user.skills = $('#skillsSelect').val();
			$scope.saveUserDetails({
				postField: 'step2',
				onSuccess: function() {
					if( params && params.proceed === false ) {
						swal('Success!', 'The changes have been successfully saved.', 'success');
					} else {
						$scope.currentTab = 'step3';
					}
				}
			})
		};

		$scope.submitStep3 = function(params) {
			$scope.user.projects = $('#projectsSelect').val();
			$scope.user.organisations = $('#organisationsSelect').val();
			$scope.saveUserDetails({
				postField: 'step3',
				onSuccess: function() {
					if( params && params.proceed === false ) {
						swal('Success!', 'The changes have been successfully saved.', 'success');
					} else {
						swal({
							confirmButtonText: "Go to my profile",
							customClass: 'swal-wide',
							html: true,
							text: 'Your profile has been successfully updated<br />' +
								'<button class="create" value="project">Create Project</button>' +
								'<button class="create" value="organisation">Create Organisation</button>' +
								'<br /><br />',
							title: 'Success',
							type: "success",
							width: 600,
						}, function(response) {
							if( response === true )
								window.location.href = profileUrl;
						});
						// $scope.currentTab = 'step4';
					}
				}
			})
		};

		$scope.saveUserDetails = function(options) {
			$scope.loading = true;
			$scope.errors = {};

			var data = $scope.user;
			data['saveDetails'] = true;
			data['step'] = options.postField;

			$http.post(window.location.pathname + '.json', data)
				.then(function(response) {
					$scope.loading = false;

					if( response.data.response === 'ok' )
						options.onSuccess();
					else if( response.data.response === 'error' )
						$scope.errors = response.data.errors;
				});
		};

		/*
		$scope.savePersonalDetails = function () {
			$scope.loading = true;
			$scope.errors = {};

			var data = $scope.user;
			data.saveDetails = true;

			$http.post(SITE_RELATIVE_PATH + '/my-profile.json', data)
				.then(function (response) {
					$scope.loading = false;

					if (response.data.response == 'ok')
						swal(response.data.message.title, response.data.message.text, "success");
					else if (response.data.response == 'error')
						$scope.errors = response.data.errors;
				});
		};
		*/

		$scope.profilePic = {};
		$scope.profilePic.upload = function(file, errFiles) {
			$scope.profilePic.loading = true;
			$scope.profilePic.f = file;
			$scope.profilePic.errFile = errFiles && errFiles[0];
			if( file ) {
				file.upload = Upload.upload({
					url: SITE_RELATIVE_PATH + '/temp-gallery.json',
					data: {
						file: file,
						format: 'profilePic'
					}
				});

				file.upload.then(function(response) {
					$scope.profilePic.loading = false;
					file.result = response.data;
					if( response.data.code === 'ok' )
						$scope.user.profilePic = response.data.imgPath;
					else if( response.data.code === 'error' )
						$scope.profilePic.errorMsg = response.data.errors;
				}, function(response) {
					if( response.status > 0 )
						$scope.profilePic.errorMsg = response.status + ': ' + response.data;
				}, function(evt) {
					file.progress = Math.min(100, parseInt(100.0 *
						evt.loaded / evt.total));
				});
			}
		};
	});

$(function() {
	$('body').on('click', '.swal-wide .create', function() {
		switch( $(this).val() ) {
			case 'project':
				setTimeout(function(){
					openCreateProject()
				}, 100);
				break;
			case 'organisation':
				setTimeout(function(){
					openCreateOrganisation()
				}, 100);
				break;
		}
	});
});