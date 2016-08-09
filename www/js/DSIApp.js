if (typeof angularDependencies !== 'undefined')
    angular.module(angularAppName, angularDependencies);
else
    angular.module(angularAppName, []);
