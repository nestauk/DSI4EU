<div class="search-results-popover" ng-cloak
     ng-show="search.entry.length >= 3">
    <h3 class="search-results">Search results for: {{search.entry}}</h3>
    <div class="w-row">
        <div class="w-col w-col-3 search-result">
            <h4 class="results-h4">Case Studies</h4>
            <a ng-repeat="caseStudy in search.caseStudies"
               class="search-result-link" href="{{caseStudy.url}}" ng-bind="caseStudy.name"></a>
            <div ng-show="search.caseStudies.length == 0">No case studies found</div>
        </div>
        <div class="w-col w-col-3 search-result">
            <h4 class="results-h4">Blog posts</h4>
            <a ng-repeat="post in search.blogPosts"
               class="search-result-link" href="{{post.url}}" ng-bind="post.name"></a>
            <div ng-show="search.blogPosts.length == 0">No blog posts found</div>
        </div>
        <div class="w-col w-col-3 search-result">
            <h4 class="results-h4">Projects</h4>
            <a ng-repeat="project in search.projects"
               class="search-result-link" href="{{project.url}}" ng-bind="project.name"></a>
            <div ng-show="search.projects.length == 0">No projects found</div>
        </div>
        <div class="w-col w-col-3 search-result end">
            <h4 class="results-h4">Organisations</h4>
            <a ng-repeat="organisation in search.organisations"
               class="search-result-link" href="{{organisation.url}}" ng-bind="organisation.name"></a>
            <div ng-show="search.organisations.length == 0">No organisations found</div>
        </div>
    </div>
    <a class="view-all-search-results" href="<?php echo \DSI\Service\URL::search()?>{{search.entry}}">View all results</a>
</div>