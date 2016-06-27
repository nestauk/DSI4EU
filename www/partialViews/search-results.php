<div class="search-results-popover" ng-cloak
     ng-show="search.entry.length >= 3">
    <h3 class="search-results">Search results for: {{search.entry}}</h3>
    <div class="w-row">
        <div class="w-col w-col-4 search-result">
            <h4 class="results-h4">Stories</h4>
            <a ng-repeat="story in search.stories"
               class="search-result-link" href="{{story.url}}" ng-bind="story.name"></a>
            <div ng-show="search.stories.length == 0">No stories found</div>
        </div>
        <div class="w-col w-col-4 search-result">
            <h4 class="results-h4">Projects</h4>
            <a ng-repeat="project in search.projects"
               class="search-result-link" href="{{project.url}}" ng-bind="project.name"></a>
            <div ng-show="search.projects.length == 0">No projects found</div>
        </div>
        <div class="w-col w-col-4 search-result end">
            <h4 class="results-h4">Organisations</h4>
            <a ng-repeat="organisation in search.organisations"
               class="search-result-link" href="{{organisation.url}}" ng-bind="organisation.name"></a>
            <div ng-show="search.organisations.length == 0">No organisations found</div>
        </div>
    </div>
    <a class="view-all-search-results" href="<?php echo \DSI\Service\URL::search()?>{{search.entry}}">View all results</a>
</div>