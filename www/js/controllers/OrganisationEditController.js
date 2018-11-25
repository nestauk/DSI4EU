angular
	.module(angularAppName)
	.controller('OrganisationEditController', function($scope, $http, $timeout, Upload, $attrs) {
		var organisationId = $attrs.organisationid;
		var organisationURL = $attrs.organisationurl;

		var editCountry = $('#edit-country');
		var editCountryRegion = $('#edit-countryRegion');

		var confirmationText = "The organisation details have been successfully saved. Please note new and updated organisation must be reviewed by an administrator before being made public.";

		$scope.logo = new DSI_Helpers.UploadImageHandler(Upload);
		$scope.headerImage = new DSI_Helpers.UploadImageHandler(Upload);


		$scope.organisation = {};
		$http.get(SITE_RELATIVE_PATH + '/org/edit/' + organisationId + '.json')
			.then(function(result) {
				$scope.organisation = result.data || {};
				$scope.logo.image = $scope.organisation.logo;
				$scope.headerImage.image = $scope.organisation.headerImage;
			});

		$scope.changeCurrentTab = function(tab) {
			if( $scope.currentTab === 1 ) {
				$scope.submitStep1({proceed: false});

				if( !$scope.organisation.name
					|| !$scope.organisation.organisationTypeId
				)
					return;
			}

			if( $scope.currentTab === 2 ) {
				$scope.submitStep2({proceed: false});

				$scope.organisation.countryID = editCountry.val();

				if( !$scope.organisation.countryID )
					return;
			}

			if( $scope.currentTab === 3 ) {
				$scope.submitStep3({proceed: false});

				if( !$scope.organisation.shortDescription )
					return;
			}

			$scope.currentTab = tab;
		};

		$scope.currentTab = 1;
		$scope.submitStep1 = function(params) {
			$scope.organisation.tags = $('#tagsSelect').val();
			$scope.organisation.networkTags = $('#networkTagsSelect').val();
			$scope.organisation.projects = $('#projectsSelect').val();
			$scope.saveDetails({
				postField: 1,
				onSuccess: function() {
					if( params ) {
						if( params.alert === true )
							return swal('Success!', confirmationText, 'success');
						if( params.proceed === false )
							return true;
					} else {
						return $scope.currentTab = 2;
					}
				}
			})
		};
		$scope.submitStep2 = function(params) {
			$scope.organisation.countryID = editCountry.val();
			$scope.organisation.region = editCountryRegion.val();
			$scope.saveDetails({
				postField: 2,
				onSuccess: function() {
					if( params ) {
						if( params.alert === true )
							return swal('Success!', confirmationText, 'success');
						if( params.proceed === false )
							return true;
					} else {
						return $scope.currentTab = 3;
					}
				}
			})
		};
		$scope.submitStep3 = function(params) {
			$scope.organisation.description = tinyMCE.get('description').getContent();
			$scope.saveDetails({
				postField: 3,
				onSuccess: function() {
					if( params ) {
						if( params.alert === true )
							return swal('Success!', confirmationText, 'success');
						if( params.proceed === false )
							return true;
					} else {
						return $scope.currentTab = 4;
					}
				}
			})
		};
		$scope.submitStep4 = function() {
			$scope.errors = {};
			if( !$scope.organisation.confirm ) {
				$scope.errors = {
					confirm: 'You have to agree with our terms and conditions'
				};
			} else {
				$scope.organisation.logo = $scope.logo.image;
				$scope.organisation.headerImage = $scope.headerImage.image;
				$scope.saveDetails({
					postField: 4,
					onSuccess: function() {
						if( params ) {
							if( params.alert === true )
								return swal('Success!', confirmationText, 'success');
							if( params.proceed === false )
								return true;
						} else {
							return window.location.href = organisationURL;
						}
					}
				})
			}
		};

		$scope.saveDetails = function(options) {
			$scope.loading = true;
			$scope.errors = {};

			var data = $scope.organisation;
			data['saveDetails'] = true;
			data['step'] = options.postField;

			console.log({data: data});
			$http.post(SITE_RELATIVE_PATH + '/org/edit/' + organisationId + '.json', data)
				.then(function(response) {
					console.log({response: response});
					$scope.loading = false;
					if( response.data.code === 'ok' )
						options.onSuccess();
					else if( response.data.code === 'error' )
						$scope.errors = response.data.errors;
				});
		};

		// country & region
		var currentCountry = '';
		var listCountries = function() {
			$http.get(SITE_RELATIVE_PATH + '/countries.json')
				.then(function(result) {
					editCountry.select2({data: result.data});
					editCountry.on("change", function() {
						if( currentCountry !== editCountry.val() ) {
							listCountryRegions(editCountry.val());
							currentCountry = editCountry.val();
						}
					});
					$scope.$watch('organisation.countryID', function(param) {
						editCountry.val($scope.organisation.countryID).trigger("change")
					});
				});
		};
		var listCountryRegions = function(countryID) {
			countryID = parseInt(countryID) || 0;
			if( countryID > 0 ) {
				$scope.regionsLoaded = false;
				$scope.regionsLoading = true;
				$http.get(SITE_RELATIVE_PATH + '/countryRegions/' + countryID + '.json')
					.then(function(result) {
						editCountryRegion
							.html("")
							.select2({data: result.data});
						$scope.regionsLoaded = true;
						$scope.regionsLoading = false;
						editCountryRegion.val($scope.organisation.region).trigger("change");
					});
			}
		};
		listCountries();
	});

(function() {
	tinymce.init({
		selector: '#description',
		statusbar: false,
		height: 500,
		plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
		autoresize_bottom_margin: 5,
		autoresize_max_height: 500,
		menubar: false,
		toolbar1: 'styleselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media | preview',
		image_advtab: true,
		paste_data_images: false,
		init_instance_callback: function(inst) {
			inst.execCommand('mceAutoResize');
		}
	});
}());