angular
  .module(angularAppName)
  .controller('WaitingApprovalController', function ($scope, $http, $attrs) {
    var Helpers = {
      getFirstNonEmptyValue: function (values) {
        for (var i in values) {
          if (values[i] != '')
            return values[i];
        }
        return null;
      },
      getItemIndexById: function (pool, id) {
        for (var i in pool) {
          if (pool[i].id == id)
            return i;
        }
        return -1;
      },
      swalWarning: function (data) {
        data.options.type = "warning";
        data.options.showCancelButton = true;
        data.optionscloseOnConfirm = false;
        data.options.showLoaderOnConfirm = true;

        swal(data.options, function () {
          $http
            .post(window.location.href, {
              getSecureCode: true
            })
            .then(function (response) {
              if (response.data.code === 'ok') {
                receivedCode(response.data.secureCode)
              } else {
                alert('unexpected error');
                console.log(response.data)
              }
            });

          function receivedCode(secureCode) {
            data.post.secureCode = secureCode;
            $http
              .post(window.location.href, data.post)
              .then(function (response) {
                if (response.data.code === 'ok') {
                  success()
                } else {
                  alert('unexpected error');
                  console.log(response.data)
                }
              })
          }

          function success() {
            data.success.type = "success";
            swal(data.success, data.successCallback);
          }
        });
      }
    };

    var listJsonUrl = $attrs.listjsonurl;

    $http.get(listJsonUrl)
      .then(function (result) {
        if (result.data.code === 'ok') {
          $scope.items = result.data.items;
        }
      });

    $scope.approveItem = function (item) {
      Helpers.swalWarning({
        options: {
          title: item.projectID ? 'Approve project' : 'Approve organisation',
          text: item.projectID ? "Are you sure you want to approve this project?" :
            "Are you sure you want to approve this organisation?"
        },
        post: {
          approveItem: true,
          id: item.id
        },
        success: {
          title: item.projectID ? "Project approved" : "Organisation approved",
          text: item.projectID ? "The project has been approved" : "The organisation has been approved"
        },
        successCallback: function () {
          $scope.items = $scope.items.filter(function (_item) {
            return _item.id !== item.id;
          });
          $scope.$digest();
        }
      });
    };

    $scope.rejectItem = function (item) {
      Helpers.swalWarning({
        options: {
          title: item.projectID ? 'Delete project' : 'Delete organisation',
          text: item.projectID ? "Are you sure you want to delete this project?" :
            "Are you sure you want to delete this organisation?"
        },
        post: {
          rejectItem: true,
          id: item.id
        },
        success: {
          title: item.projectID ? "Project deleted" : "Organisation deleted",
          text: item.projectID ? "The project has been deleted" : "The organisation has been deleted"
        },
        successCallback: function () {
          $scope.items = $scope.items.filter(function (_item) {
            return _item.id !== item.id;
          });
          $scope.$digest();
        }
      });
    };

    $scope.selectAll = function () {
      $scope.items = $scope.items.map(function (item) {
        item.checked = true;
        return item;
      });
    };

    $scope.deselectAll = function () {
      $scope.items = $scope.items.map(function (item) {
        item.checked = false;
        return item;
      });
    };
  });