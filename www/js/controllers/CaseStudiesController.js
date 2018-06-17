angular
    .module(angularAppName)
    .controller('CaseStudiesController', function ($scope, $http, $timeout, $attrs) {
        $('input[name="tagID"]').change(function () {
            var tags = [];
            $('input[name="tagID"]:checked').each(function () {
                tags.push(parseInt(this.value));
            });

            var caseStudies = $('.js-case-study');
            if (tags.length === 0) {
                caseStudies.show();
            } else {
                caseStudies.hide();
                for (var i = 0; i < tags.length; i++) {
                    caseStudies.each(function () {
                        if (($.inArray(tags[i], $(this).data('tags'))) !== -1)
                            $(this).show();
                    });
                }
            }
        });
    });