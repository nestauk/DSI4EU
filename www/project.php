<?php
require __DIR__ . '/header.php';
/** @var $project \DSI\Entity\Project */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectController.js"></script>

    <div
        ng-controller="ProjectController"
        data-projectid="<?php echo $project->getId() ?>">

        <div class="w-section project-section">
            <div class="w-container body-container">
                <div class="project-detail">
                    <div class="project-header">
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/pin.png" class="card-pin">
                        <?php if ($isOwner) { ?>
                            <div class="card-city" style="text-shadow: 0 0 0 #000;">
                                <form ng-submit="saveCountryRegion()">
                                    <select id="Edit-country"
                                            data-placeholder="Select country"
                                            style="width:150px;background:transparent">
                                        <option></option>
                                    </select>
                                <span ng-hide="loadingCountryRegions">
                                    <select
                                        data-tags="true"
                                        id="Edit-countryRegion"
                                        data-placeholder="Type the city"
                                        style="width:150px;background:transparent">
                                    </select>
                                </span>
                                <span ng-show="loadingCountryRegions">
                                    Loading...
                                </span>
                                <span ng-hide="loadingCountryRegions">
                                    <input ng-hide="savingCountryRegion.loading || savingCountryRegion.saved"
                                           type="submit" value="Save" class="w-button add-skill-btn">
                                    <button ng-show="savingCountryRegion.loading && !savingCountryRegion.saved"
                                            type="button" class="w-button add-skill-btn">Saving...
                                    </button>
                                    <input ng-show="!savingCountryRegion.loading && savingCountryRegion.saved"
                                           type="submit" value="Saved" class="w-button add-skill-btn">
                                </span>
                                </form>
                            </div>
                        <?php } else { ?>
                            <div class="card-city">
                                <?php if ($project->getCountryRegion()) { ?>
                                    <?php echo $project->getCountryRegion()->getName() ?>,
                                    <?php echo $project->getCountry()->getName() ?>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <img src="<?php echo SITE_RELATIVE_PATH ?>/images/share-symbol.svg" class="share">

                        <?php if ($isOwner) { ?>
                            <input type="text"
                                   class="project-url"
                                   ng-model="project.url"
                                   ng-blur="updateBasic()"
                                   placeholder="Project Page"
                                   value="<?php echo $project->getUrl() ?>"
                                   style="background:transparent;color:white;width:500px;border:0"/>
                        <?php } else { ?>
                            <?php if ($project->getUrl()) { ?>
                                <a href="<?php echo $project->getUrl() ?>" target="_blank" class="project-url">
                                    <?php echo $project->getUrl() ?>
                                </a>
                            <?php } ?>
                        <?php } ?>

                        <div class="project-status"><span class="status-text">Project status:</span>
                            <?php if ($isOwner) { ?>
                                <select ng-model="project.status" ng-change="updateBasic()"
                                        style="background: transparent;border:0">
                                    <option value="live">Live</option>
                                    <option value="closed">Closed</option>
                                </select>
                            <?php } else { ?>
                                <strong class="status-indicator"><?php echo ucfirst($project->getStatus()) ?></strong>
                            <?php } ?>
                        </div>
                    </div>
                    <h1 class="project-h1">
                        <?php if ($isOwner) { ?>
                            <input type="text"
                                   ng-model="project.name"
                                   ng-blur="updateBasic()"
                                   value="<?php echo $project->getName() ?>"
                                   style="background:transparent;color:white;width:500px;border:0"/>
                        <?php } else { ?>
                            <h1 class="project-h1"><?php echo $project->getName() ?></h1>
                        <?php } ?>
                    </h1>
                </div>

                <?php /*
            <div id="googleMap">
                <style>
                    #map {
                        height: 600px;
                    }

                    .controls {
                        margin-top: 10px;
                        border: 1px solid transparent;
                        border-radius: 2px 0 0 2px;
                        box-sizing: border-box;
                        -moz-box-sizing: border-box;
                        height: 32px;
                        outline: none;
                        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                    }

                    #pac-input {
                        background-color: #fff;
                        font-family: Roboto;
                        font-size: 15px;
                        font-weight: 300;
                        margin-left: 12px;
                        padding: 0 11px 0 13px;
                        text-overflow: ellipsis;
                        width: 300px;
                    }

                    #pac-input:focus {
                        border-color: #4d90fe;
                    }

                    .pac-container {
                        font-family: Roboto;
                    }

                    #type-selector {
                        color: #fff;
                        background-color: #4d90fe;
                        padding: 5px 11px 0px 11px;
                    }

                    #type-selector label {
                        font-family: Roboto;
                        font-size: 13px;
                        font-weight: 300;
                    }

                    #target {
                        width: 345px;
                    }
                </style>
                <input id="pac-input" class="controls" type="text" placeholder="Search Box">
                <div id="map"></div>
                <script>
                    // This example adds a search box to a map, using the Google Place Autocomplete
                    // feature. People can enter geographical searches. The search box will return a
                    // pick list containing a mix of places and predicted search terms.

                    // This example requires the Places library. Include the libraries=places
                    // parameter when you first load the API. For example:
                    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

                    var selectedLocation = null;

                    function initAutocomplete() {
                        var map = new google.maps.Map(document.getElementById('map'), {
                            center: {lat: 53, lng: 15},
                            zoom: 4,
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });

                        // Create the search box and link it to the UI element.
                        var input = document.getElementById('pac-input');
                        var searchBox = new google.maps.places.SearchBox(input);
                        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                        // Bias the SearchBox results towards current map's viewport.
                        map.addListener('bounds_changed', function () {
                            searchBox.setBounds(map.getBounds());
                        });
                        map.addListener('click', function (e) {
                            placeMarkerAndPanTo(e.latLng, map);
                        });

                        var markers = [];
                        // Listen for the event fired when the user selects a prediction and retrieve
                        // more details for that place.
                        searchBox.addListener('places_changed', function () {
                            var places = searchBox.getPlaces();

                            if (places.length == 0) {
                                return;
                            }

                            // Clear out the old markers.
                            markers.forEach(function (marker) {
                                marker.setMap(null);
                            });
                            markers = [];

                            // For each place, get the icon, name and location.
                            var bounds = new google.maps.LatLngBounds();
                            places.forEach(function (place) {
                                var icon = {
                                    url: place.icon,
                                    size: new google.maps.Size(71, 71),
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(17, 34),
                                    scaledSize: new google.maps.Size(25, 25)
                                };

                                // Create a marker for each place.
                                markers.push(new google.maps.Marker({
                                    map: map,
                                    icon: icon,
                                    title: place.name,
                                    position: place.geometry.location
                                }));

                                if (place.geometry.viewport) {
                                    // Only geocodes have viewport.
                                    bounds.union(place.geometry.viewport);
                                } else {
                                    bounds.extend(place.geometry.location);
                                }
                            });
                            map.fitBounds(bounds);
                        });

                        function placeMarkerAndPanTo(latLng, map) {
                            for (var i = 0; i < markers.length; i++)
                                markers[i].setMap(null);
                            markers = [];

                            var marker = new google.maps.Marker({
                                position: latLng,
                                map: map
                            });
                            markers.push(marker);
                            selectedLocation = marker;
                        }
                    }

                </script>
                <script
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdjn0XlX4VebRi0Vm5BjfC7tv2T93Qi58&libraries=places&callback=initAutocomplete"
                    async defer></script>

                <div style="text-align:center;padding:10px;">
                    <input type="button" ng-click="saveGMapPosition()" value="Save Location" class="w-button add-skill-btn">
                </div>

                <div ng-show="possibleLocations.length > 0">
                    Please select the correct location:
                    <ul>
                        <li ng-repeat="location in possibleLocations">{{location.formatted_address}}</li>
                    </ul>
                </div>
                <div ng-show="possibleLocationsNotFound">
                    Address could not be found. Please insert your address manually.
                </div>
            </div>
                */ ?>

                <div class="w-row project-info">
                    <div class="w-col w-col-6">
                        <div class="info-card">
                            <h3 class="info-h card-h">About this project</h3>
                            <p class="project-summary">
                                <?php if ($isOwner) { ?>
                                    <textarea
                                        class="readjustTextarea"
                                        ng-model="project.description"
                                        ng-blur="updateBasic()"
                                        style="min-height:150px;border:0;width:100%">
                                        <?php echo show_input($project->getDescription()) ?>
                                    </textarea>
                                <?php } else { ?>
                                    <?php echo show_input($project->getDescription()) ?>
                                <?php } ?>
                            </p>
                            <h3 class="card-sub-h">Duration</h3>
                            <div class="duration-p">
                                <div ng-show="project.startDate && project.endDate">
                                    This project runs from
                                    <strong>{{getDateFrom(project.startDate)}}</strong> to
                                    <strong>{{getDateFrom(project.endDate)}}</strong>
                                </div>
                                <div ng-show="project.startDate && !project.endDate">
                                    This project runs from
                                    <strong>{{getDateFrom(project.startDate)}}</strong>
                                </div>
                                <div ng-show="!project.startDate && project.endDate">
                                    This project runs until
                                    <strong>{{getDateFrom(project.endDate)}}</strong>
                                </div>
                            </div>
                            <?php if ($isOwner) { ?>
                                <br/>
                                <div style="float:left;width:40%;margin-left:10%">
                                    Start Date
                                    <input type="text" placeholder="yyyy-mm-dd" ng-model="project.startDate"
                                           ng-blur="updateBasic()" style="width:130px" ng-pattern="datePattern"/>
                                </div>
                                <div style="float:left;width:40%">
                                    End Date
                                    <input type="text" placeholder="yyyy-mm-dd" ng-model="project.endDate"
                                           ng-blur="updateBasic()" style="width:130px" ng-pattern="datePattern"/>
                                </div>
                            <?php } ?>

                            <div style="clear:both"></div>
                        </div>
                        <div class="info-card">
                            <h3 class="info-h card-h">Organisations involved</h3>
                            <div class="w-row organisation-links">
                                <div class="w-col w-col-4 w-col-stack w-col-small-4">
                                    <a href="#" class="w-inline-block organisation-small"><img width="118"
                                                                                               src="<?php echo SITE_RELATIVE_PATH ?>/images/nesta-6a9b5fe999e8323b379ccc0d8e70290f.png"
                                                                                               class="org-img">
                                    </a>
                                </div>
                                <div class="w-col w-col-4 w-col-stack w-col-small-4">
                                    <a href="#" class="w-inline-block organisation-small"><img width="118"
                                                                                               src="<?php echo SITE_RELATIVE_PATH ?>/images/waag-f1d052f43133268eaf2e13090a0b4bf1.png"
                                                                                               class="org-img">
                                    </a>
                                </div>
                                <div class="w-col w-col-4 w-col-stack w-col-small-4">
                                    <a href="#" class="w-inline-block organisation-small"><img width="118"
                                                                                               src="<?php echo SITE_RELATIVE_PATH ?>/images/future-everything-2f261cf2d078264179fd82b21e5927b7.png"
                                                                                               class="org-img">
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="info-card">
                            <h3 class="info-h card-h">
                                <div style="float:left">
                                    Contributors
                                </div>
                                <?php if ($isOwner) { ?>
                                    <div class="add-item-block" ng-click="addingMember = !addingMember"
                                         style="float:right;margin-right:20px">
                                        <div class="add-item">+</div>
                                    </div>
                                <?php } ?>
                                <div style="clear:both"></div>
                            </h3>

                            <form ng-show="addingMember" ng-submit="addMember()">
                                <label>
                                    Select new member:
                                    <select data-tags="true"
                                            id="Add-member"
                                            style="width:150px">
                                        <option></option>
                                    </select>
                                    <input type="submit" value="Add" class="w-button add-skill-btn">
                                </label>
                            </form>

                            <div style="clear:both"></div>

                            <div class="project-owner">
                                <a href="<?php echo SITE_RELATIVE_PATH ?>/profile/<?php echo $project->getOwner()->getId() ?>"
                                   class="w-inline-block owner-link">
                                    <img width="50" height="50"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $project->getOwner()->getProfilePicOrDefault() ?>"
                                         class="project-creator-img">
                                    <div class="creator-name"><?php echo $project->getOwner()->getFirstName() ?></div>
                                    <div class="project-creator-text">
                                        <?php echo $project->getOwner()->getLastName() ?>
                                    </div>
                                </a>
                            </div>
                            <div class="w-row contributors">
                                <div class="w-col w-col-6 contributor-col" ng-repeat="member in project.members">
                                    <a href="<?php echo SITE_RELATIVE_PATH ?>/profile/{{member.id}}"
                                       class="w-inline-block contributor">
                                        <img width="40" height="40"
                                             ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{member.profilePic}}"
                                             class="contributor-small-img">
                                        <div class="contributor-name" ng-bind="member.firstName"></div>
                                        <div class="contributor-position" ng-bind="member.lastName"></div>
                                    </a>
                                    <?php if ($isOwner) { ?>
                                        <div class="delete" style="display:block" ng-click="removeMember(member)">-
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ($canUserRequestMembership) { ?>
                                <div class="join-project">
                                    <div ng-hide="requestToJoin.requestSent">
                                        <a href="#" ng-hide="requestToJoin.loading" class="w-button btn btn-join"
                                           ng-click="sendRequestToJoin()">Request to join</a>
                                        <button ng-show="requestToJoin.loading" class="w-button btn btn-join"
                                                style="background-color: #ec388e">
                                            Sending Request...
                                        </button>
                                    </div>
                                    <button ng-show="requestToJoin.requestSent" class="w-button btn btn-join">
                                        Request Sent
                                    </button>
                                </div>
                            <?php } ?>
                        </div>

                        <?php if ($isOwner) { ?>
                            <div class="info-card" style="min-height: 0;" ng-show="project.memberRequests.length > 0">
                                <h3 class="info-h card-h">
                                    Member Requests
                                </h3>

                                <div class="w-row contributors">
                                    <div class="w-col w-col-6 contributor-col"
                                         ng-repeat="member in project.memberRequests">
                                        <a href="<?php echo SITE_RELATIVE_PATH ?>/profile/{{member.id}}"
                                           class="w-inline-block contributor">
                                            <img width="40" height="40"
                                                 ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{member.profilePic}}"
                                                 class="contributor-small-img">
                                            <div class="contributor-name" ng-bind="member.firstName"></div>
                                            <div class="contributor-position" ng-bind="member.lastName"></div>
                                        </a>
                                        <div style="margin-left:30px">
                                            <a href="#" title="Approve Member Request" class="add-item"
                                               ng-click="approveRequestToJoin(member)"
                                               style="background-color: green">+</a>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a href="#" title="Reject Member Request" class="add-item"
                                               ng-click="rejectRequestToJoin(member)"
                                               style="background-color: red">-</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="info-card">
                            <h3 class="info-h card-h">This project is tagged under:</h3>
                            <div class="w-clearfix tags-block">
                                <div class="skill" ng-repeat="tag in project.tags">
                                    <?php if ($isOwner) { ?>
                                        <div class="delete" ng-click="removeTag(tag)">-</div>
                                    <?php } ?>
                                    <div ng-bind="tag"></div>
                                </div>
                                <?php if ($isOwner) { ?>
                                    <div class="add-item-block" ng-click="addingTag = !addingTag">
                                        <div class="add-item">+</div>
                                    </div>

                                    <div class="w-form" style="float:left"
                                         ng-show="addingTag">
                                        <form class="w-clearfix add-skill-section"
                                              ng-submit="addTag()">
                                            <select data-tags="true"
                                                    data-placeholder="Type your skill"
                                                    id="Add-tag"
                                                    class="w-input add-language"
                                                    style="width:200px;display:inline">
                                                <option></option>
                                            </select>
                                            <input type="submit" value="Add" class="w-button add-skill-btn">
                                        </form>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="info-card">
                            <h3 class="info-h card-h">Social impact</h3>
                            <div class="impact-block">
                                <h4 class="impact-h4">Areas of society impacted</h4>
                                <div class="w-clearfix tags-block impact">
                                    <div class="skill" ng-repeat="tag in project.impactTagsA">
                                        <?php if ($isOwner) { ?>
                                            <div class="delete" ng-click="removeImpactTagA(tag)">-</div>
                                        <?php } ?>
                                        <div ng-bind="tag"></div>
                                    </div>
                                    <?php if ($isOwner) { ?>
                                        <div class="add-item-block" ng-click="addingImpactTagA = !addingImpactTagA">
                                            <div class="add-item">+</div>
                                        </div>
                                        <div class="w-form" style="float:left"
                                             ng-show="addingImpactTagA">
                                            <form class="w-clearfix add-skill-section"
                                                  ng-submit="addImpactTagA()">
                                                <select data-tags="true"
                                                        data-placeholder="Type your skill"
                                                        id="Add-impact-tag-a"
                                                        class="w-input add-language"
                                                        style="width:200px;display:inline">
                                                    <option></option>
                                                </select>
                                                <input type="submit" value="Add" class="w-button add-skill-btn">
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="impact-block">
                                <h4 class="impact-h4">Technology focus</h4>
                                <div class="w-clearfix tags-block impact">
                                    <div class="skill" ng-repeat="tag in project.impactTagsB">
                                        <?php if ($isOwner) { ?>
                                            <div class="delete" ng-click="removeImpactTagB(tag)">-</div>
                                        <?php } ?>
                                        <div ng-bind="tag"></div>
                                    </div>
                                    <?php if ($isOwner) { ?>
                                        <div class="add-item-block" ng-click="addingImpactTagB = !addingImpactTagB">
                                            <div class="add-item">+</div>
                                        </div>
                                        <div class="w-form" style="float:left"
                                             ng-show="addingImpactTagB">
                                            <form class="w-clearfix add-skill-section"
                                                  ng-submit="addImpactTagB()">
                                                <select data-tags="true"
                                                        data-placeholder="Type your skill"
                                                        id="Add-impact-tag-b"
                                                        class="w-input add-language"
                                                        style="width:200px;display:inline">
                                                    <option></option>
                                                </select>
                                                <input type="submit" value="Add" class="w-button add-skill-btn">
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="impact-block last">
                                <h4 class="impact-h4">Technology method</h4>
                                <div class="w-clearfix tags-block impact">
                                    <div class="skill" ng-repeat="tag in project.impactTagsC">
                                        <?php if ($isOwner) { ?>
                                            <div class="delete" ng-click="removeImpactTagC(tag)">-</div>
                                        <?php } ?>
                                        <div ng-bind="tag"></div>
                                    </div>
                                    <?php if ($isOwner) { ?>
                                        <div class="add-item-block" ng-click="addingImpactTagC = !addingImpactTagC">
                                            <div class="add-item">+</div>
                                        </div>
                                        <div class="w-form" style="float:left"
                                             ng-show="addingImpactTagC">
                                            <form class="w-clearfix add-skill-section"
                                                  ng-submit="addImpactTagC()">
                                                <select data-tags="true"
                                                        data-placeholder="Type your skill"
                                                        id="Add-impact-tag-c"
                                                        class="w-input add-language"
                                                        style="width:200px;display:inline">
                                                    <option></option>
                                                </select>
                                                <input type="submit" value="Add" class="w-button add-skill-btn">
                                            </form>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-col w-col-6">
                        <div class="info-card">
                            <div data-ix="postblockclosed" class="add-post">
                                <div class="w-clearfix post-author new-post"><img width="40" height="40"
                                                                                  src="<?php echo SITE_RELATIVE_PATH ?>/images/XG1hqhM1_400x400.jpg"
                                                                                  class="post-author-img post">
                                    <div class="w-form add-post-form-wrapper">
                                        <form id="email-form" name="email-form" data-name="Email Form"
                                              class="w-clearfix">
                                    <textarea id="new-post" placeholder="What's been happening?" name="new-post"
                                              data-name="new post" data-ix="addpost"
                                              class="w-input add-post-form"></textarea>
                                        </form>
                                        <div class="w-form-done">
                                            <p>Thank you! Your submission has been received!</p>
                                        </div>
                                        <div class="w-form-fail">
                                            <p>Oops! Something went wrong while submitting the form</p>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="w-button btn-new-post btn">Add post</a>
                            </div>
                            <div class="w-clearfix current-status">
                                <h3 class="status-h3">Latest post</h3>
                                <div class="post-author latest"><img width="40" height="40"
                                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/XG1hqhM1_400x400.jpg"
                                                                     class="post-author-img">
                                    <div class="post-author-detail latest">DanielMPettifer</div>
                                    <div class="posted-on latest">Posted today at 11:12 gmt</div>
                                </div>
                                <div class="news-content">Our new app has just been published on the apple app store:
                                    <br><a class="status-link"
                                           href="https://geo.itunes.apple.com/us/app/netflix/id363590051?mt=8">https://geo.itunes.apple.com/us/app/netflix/id363590051?mt=8</a>
                                    <br> android version to be released very soon! Follow us to stay up to date!
                                </div>
                            </div>
                            <h3 class="info-h card-h">Previous posts</h3>
                            <div class="w-clearfix project-post">
                                <div class="post-author"><img width="40" height="40"
                                                              src="<?php echo SITE_RELATIVE_PATH ?>/images/XG1hqhM1_400x400.jpg"
                                                              class="post-author-img">
                                    <div class="post-author-detail">DanielMPettifer</div>
                                    <div class="posted-on">Posted yesterday at 08:22 gmt</div>
                                </div>
                                <div class="project-post-detail"><img
                                        src="<?php echo SITE_RELATIVE_PATH ?>/images/wearable-circuit-board-tattoo-644x424.jpg"
                                        class="project-post-image">
                                    <div class="w-richtext project-post-rich-text">
                                        <h4>First human test of implants</h4>
                                        <p>A rich text element can be used with static or dynamic content. For static
                                            content, just drop it into any page and begin editing. For dynamic content,
                                            add
                                            a rich text field to any collection and then connect a rich text element to
                                            that
                                            field in the settings panel. Voila!</p>
                                    </div>
                                </div>
                            </div>
                            <div class="w-clearfix project-post">
                                <div class="post-author"><img width="40" height="40"
                                                              src="<?php echo SITE_RELATIVE_PATH ?>/images/XG1hqhM1_400x400.jpg"
                                                              class="post-author-img">
                                    <div class="post-author-detail">DanielMPettifer</div>
                                    <div class="posted-on">Posted yesterday at 08:22 gmt</div>
                                </div>
                                <div class="project-post-detail">
                                    <div style="padding-top: 56.17021276595745%;" class="w-embed w-video">
                                        <iframe class="embedly-embed"
                                                src="https://cdn.embedly.com/widgets/media.html?src=https%3A%2F%2Fwww.youtube.com%2Fembed%2FWs6AAhTw7RA%3Ffeature%3Doembed&amp;url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DWs6AAhTw7RA%26feature%3Dyoutu.be&amp;image=https%3A%2F%2Fi.ytimg.com%2Fvi%2FWs6AAhTw7RA%2Fhqdefault.jpg&amp;key=c4e54deccf4d4ec997a64902e9a30300&amp;type=text%2Fhtml&amp;schema=youtube"
                                                scrolling="no" frameborder="0" allowfullscreen=""></iframe>
                                    </div>
                                    <div class="w-richtext project-post-rich-text">
                                        <h4>Quantum levitation now included in our base model</h4>
                                        <p>A rich text element can be used with static or dynamic content. For static
                                            content, just drop it into any page and begin editing. For dynamic content,
                                            add
                                            a rich text field to any collection and then connect a rich text element to
                                            that
                                            field in the settings panel. Voila!</p>
                                        <h4>How to customize formatting for each rich text</h4>
                                        <p>Headings, paragraphs, blockquotes, figures, images, and figure captions can
                                            all
                                            be styled after a class is added to the rich text element using the "When
                                            inside
                                            of" nested selector system.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php require __DIR__ . '/footer.php' ?>