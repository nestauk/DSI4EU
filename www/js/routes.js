/**
 * Created by Alecs on 24/04/2016.
 */

angular.module('app')

    .config(function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise("/");
        $stateProvider
            .state('home', {
                url: "/",
                templateUrl: "views/home.html"
            })
            .state('about', {
                url: "/about",
                templateUrl: "views/about.html"
            })
            .state('contact', {
                url: "/contact",
                templateUrl: "views/contact.html",
                controller: 'ContactCtrl'
            })
            .state('users', {
                url: "/users",
                templateUrl: "views/users.html",
                controller: 'UsersCtrl'
            })
            .state('user', {
                url: "/user/:userID",
                templateUrl: "views/user.html",
                controller: 'UserCtrl'
            });
    });