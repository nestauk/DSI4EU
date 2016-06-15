<?php
require __DIR__ . '/header.php';
/** @var $project \DSI\Entity\Project */
/** @var $userHasInvitation bool */
/** @var $canUserRequestMembership bool */
/** @var $isOwner bool */
/** @var $loggedInUser \DSI\Entity\User */
?>
    <script src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectController.js"></script>

    <div
        ng-controller="ProjectController"
        data-projectid="<?php echo $project->getId() ?>">

        <div class="w-section project-section">
            <div class="container-wide">
                <div class="w-row project-info">
                    <div id="textScroll" class="w-col w-col-6 w-col-stack">
                        <div id="text">
                            <div class="project-detail">
                                <div class="project-header">
                                    <h1 class="project-h1">
                                        <?php if ($isOwner) { ?>
                                            <input type="text" value="<?php echo $project->getName() ?>"
                                                   ng-model="project.name" ng-blur="updateBasic()"
                                                   style="background:transparent;color:white;width:500px;border:0"/>
                                        <?php } else { ?>
                                            <?php echo $project->getName() ?>
                                        <?php } ?>
                                    </h1>
                                    <img src="<?php echo SITE_RELATIVE_PATH ?>/images/pin.png" class="card-pin">

                                    <?php if ($isOwner) { ?>
                                        <div class="card-city" style="text-shadow: 0 0 0 #000;">
                                            <div ng-hide="changeRegion" ng-click="changeRegion = true"
                                                 style="cursor:pointer">
                                                <?php if ($project->getCountryRegion()) { ?>
                                                    <?php echo $project->getCountryRegion()->getName() ?>,
                                                    <?php echo $project->getCountry()->getName() ?>
                                                <?php } ?>
                                            </div>

                                            <form ng-submit="saveCountryRegion()" ng-show="changeRegion" ng-cloak>
                                                <select id="Edit-country" data-placeholder="Select country"
                                                        style="width:150px;background:transparent">
                                                    <option></option>
                                                </select>
                                                <span ng-show="regionsLoaded">
                                                    <select
                                                        data-tags="true" id="Edit-countryRegion"
                                                        data-placeholder="Type the city"
                                                        style="width:150px;background:transparent">
                                                    </select>
                                                </span>
                                                <span ng-show="regionsLoading">
                                                    Loading...
                                                </span>
                                                <span ng-show="regionsLoaded">
                                                    <input
                                                        ng-hide="savingCountryRegion.loading || savingCountryRegion.saved"
                                                        type="submit" value="Save" class="w-button add-skill-btn">
                                                    <button
                                                        ng-show="savingCountryRegion.loading && !savingCountryRegion.saved"
                                                        type="button" class="w-button add-skill-btn">Saving...
                                                    </button>

                                                    <input
                                                        ng-show="!savingCountryRegion.loading && savingCountryRegion.saved"
                                                        type="submit" value="Saved" class="w-button add-skill-btn">

                                                    <button class="w-button add-skill-btn"
                                                            ng-show="!savingCountryRegion.loading"
                                                            ng-click="changeRegion = false">
                                                        Close
                                                    </button>
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

                                    <?php if ($isOwner) { ?>
                                        <input type="text" class="project-url"
                                               ng-model="project.url" ng-blur="updateBasic()"
                                               placeholder="Project Page" value="<?php echo $project->getUrl() ?>"
                                               style="background:transparent;color:white;width:500px;border:0"/>
                                    <?php } else { ?>
                                        <?php if ($project->getUrl()) { ?>
                                            <a href="<?php echo $project->getUrl() ?>"
                                               target="_blank" class="project-url">
                                                <?php echo $project->getUrl() ?>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>

                                    <div class="project-status" ng-hide="changeRegion">
                                        <span class="status-text">Project status:</span>
                                        <?php if ($isOwner) { ?>
                                            <select ng-model="project.status" ng-change="updateBasic()"
                                                    style="background: transparent;border:0">
                                                <option value="live">Live</option>
                                                <option value="closed">Closed</option>
                                            </select>
                                        <?php } else { ?>
                                            <strong
                                                class="status-indicator"><?php echo ucfirst($project->getStatus()) ?></strong>
                                        <?php } ?>
                                    </div>

                                    <img class="edit-white"
                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-compose-outline-white.png"
                                         width="25">
                                </div>
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

                            <div class="info-card">
                                <h3 class="info-h card-h">
                                    <div style="float:left">
                                        Organisations involved
                                    </div>
                                    <?php if ($isOwner) { ?>
                                        <div class="add-item-block"
                                             ng-click="addingOrganisation = !addingOrganisation"
                                             style="float:right;margin-right:20px">
                                            <div class="add-item">+</div>
                                        </div>
                                    <?php } ?>
                                    <div style="clear:both"></div>
                                </h3>

                                <form ng-show="addingOrganisation" ng-submit="addOrganisation()" ng-cloak>
                                    <label>
                                        Select organisation:
                                        <select data-placeholder="Select organisation"
                                                id="Add-organisation"
                                                style="width:150px">
                                            <option></option>
                                        </select>
                                        <input type="submit" value="Add" class="w-button add-skill-btn">
                                    </label>
                                </form>

                                <div class="list-items" ng-cloak>
                                    <div class="w-inline-block partner-link">
                                        <div class="w-clearfix list-item"
                                             ng-repeat="organisation in project.organisationProjects">
                                            <div class="partner-title">
                                                <a ng-href="{{organisation.url}}"
                                                   ng-bind="organisation.name"></a>
                                            </div>
                                            <div class="no-of-projects">
                                                <span ng-bind="organisation.projectsCount"></span>
                                                Project<span
                                                    ng-bind="organisation.projectsCount == 1 ? '' : 's'"></span>
                                            </div>
                                        </div>
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

                                <div ng-show="addingMember" style="margin-left:20px" ng-cloak>
                                    <form id="add-member-form" name="add-member-form"
                                          data-name="Add Member Form"
                                          class="w-clearfix">
                                        Select new member:
                                        <select data-tags="true"
                                                data-placeholder=""
                                                id="Add-member" name="Add-member"
                                                class="w-input"
                                                multiple
                                                style="width:200px">
                                            <option></option>
                                        </select>
                                    </form>

                                    <div style="color:red;padding:12px 0 10px 100px;">
                                        <div style="color:orange">
                                            <div ng-show="addProjectMember.loading">Loading...</div>
                                        </div>
                                        <div style="color:green">
                                            <div ng-show="addProjectMember.success"
                                                 ng-bind="addProjectMember.success"></div>
                                        </div>
                                        <div
                                            ng-show="addProjectMember.errors.email"
                                            ng-bind="addProjectMember.errors.email"></div>
                                        <div
                                            ng-show="addProjectMember.errors.member"
                                            ng-bind="addProjectMember.errors.member"></div>
                                    </div>
                                </div>

                                <div style="clear:both"></div>

                                <div class="project-owner">
                                    <a href="<?php echo SITE_RELATIVE_PATH ?>/profile/<?php echo $project->getOwner()->getId() ?>"
                                       class="w-inline-block owner-link">
                                        <img width="50" height="50"
                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $project->getOwner()->getProfilePicOrDefault() ?>"
                                             class="project-creator-img">
                                        <div
                                            class="creator-name"><?php echo $project->getOwner()->getFirstName() ?></div>
                                        <div class="project-creator-text">
                                            <?php echo $project->getOwner()->getLastName() ?>
                                        </div>
                                    </a>
                                </div>
                                <div class="w-row contributors" ng-cloak>
                                    <div class="w-col w-col-6 contributor-col"
                                         ng-repeat="member in project.members">
                                        <div class="contributor">
                                            <div class="star-holder">
                                                <?php if ($isOwner) { ?>
                                                    <img ng-show="member.isAdmin"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-orange.png"
                                                         ng-click="member.isAdmin = !member.isAdmin; updateAdminStatus(member)"
                                                         class="star-full" style="opacity:1">
                                                    <img ng-show="!member.isAdmin"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-outline-orange.png"
                                                         ng-click="member.isAdmin = !member.isAdmin; updateAdminStatus(member)"
                                                         class="star-empty">
                                                    <input type="checkbox"
                                                           ng-checked="member.isAdmin"
                                                           ng-model="member.isAdmin"
                                                           ng-change="updateAdminStatus(member)"
                                                           style="display:none"
                                                    />
                                                <?php } else { ?>
                                                    <img ng-show="member.isAdmin"
                                                         src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-star-orange.png"
                                                         class="star-full" style="opacity:1">
                                                <?php } ?>
                                            </div>
                                            <a href="<?php echo SITE_RELATIVE_PATH ?>/profile/{{member.id}}"
                                               class="w-inline-block">
                                                <img width="40" height="40" class="contributor-small-img"
                                                     ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{member.profilePic}}">
                                                <div class="contributor-name" ng-bind="member.firstName"></div>
                                                <div class="contributor-position"
                                                     ng-bind="member.lastName"></div>
                                            </a>
                                            <?php if ($isOwner) { ?>
                                                <div class="delete" ng-click="removeMember(member)">-</div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($canUserRequestMembership) { ?>
                                    <div class="join-project" ng-cloak>
                                        <div ng-hide="requestToJoin.requestSent">
                                            <a href="#" ng-hide="requestToJoin.loading"
                                               class="w-button btn btn-join"
                                               ng-click="sendRequestToJoin()">Request to join</a>
                                            <button ng-show="requestToJoin.loading" ng-cloak
                                                    class="w-button btn btn-join"
                                                    style="background-color: #ec388e">
                                                Sending Request...
                                            </button>
                                        </div>
                                        <button ng-show="requestToJoin.requestSent"
                                                class="w-button btn btn-join">
                                            Request Sent
                                        </button>
                                    </div>
                                <?php } ?>
                                <?php if ($userHasInvitation) { ?>
                                    <div class="join-project" ng-hide="invitationActioned" ng-cloak>
                                        You have been invited to be part of this project
                                        <button class="w-button" ng-click="approveInvitationToJoin()">
                                            Accept
                                        </button>
                                        <button class="w-button" ng-click="rejectInvitationToJoin()">
                                            Decline
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>

                            <?php if ($isOwner) { ?>
                                <div class="info-card" style="min-height: 0;" ng-cloak
                                     ng-show="project.memberRequests.length > 0">
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
                                                <div class="contributor-position"
                                                     ng-bind="member.lastName"></div>
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
                                            <div class="add-item-block"
                                                 ng-click="addingImpactTagA = !addingImpactTagA">
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
                                                    <input type="submit" value="Add"
                                                           class="w-button add-skill-btn">
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
                                            <div class="add-item-block"
                                                 ng-click="addingImpactTagB = !addingImpactTagB">
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
                                                    <input type="submit" value="Add"
                                                           class="w-button add-skill-btn">
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
                                            <div class="add-item-block"
                                                 ng-click="addingImpactTagC = !addingImpactTagC">
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
                                                    <input type="submit" value="Add"
                                                           class="w-button add-skill-btn">
                                                </form>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="postsScroll" class="w-col w-col-6 w-col-stack">
                        <div class="info-card">
                            <h3 class="info-h card-h">About this project</h3>
                            <p class="project-summary">
                                <?php if ($isOwner) { ?>
                                    <textarea
                                        class="readjustTextarea"
                                        ng-model="project.description"
                                        ng-blur="updateBasic()"
                                        placeholder="Type a description"
                                        style="min-height:150px;border:0;width:100%">
                                        <?php echo show_input($project->getDescription()) ?>
                                    </textarea>
                                <?php } else { ?>
                                    <?php echo show_input($project->getDescription()) ?>
                                <?php } ?>
                            </p>
                            <h3 class="card-sub-h">Duration</h3>
                            <div class="duration-p" ng-cloak>
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
                                <div ng-cloak>
                                    <div style="float:left;width:40%;margin-left:10%">
                                        Start Date
                                        <input type="text" placeholder="yyyy-mm-dd" ng-model="project.startDate"
                                               ng-blur="updateBasic()" style="width:130px"
                                               ng-pattern="datePattern"/>
                                    </div>
                                    <div style="float:left;width:40%">
                                        End Date
                                        <input type="text" placeholder="yyyy-mm-dd" ng-model="project.endDate"
                                               ng-blur="updateBasic()" style="width:130px"
                                               ng-pattern="datePattern"/>
                                    </div>
                                </div>
                            <?php } ?>

                            <div style="clear:both"></div>
                        </div>

                        <div id="posts">
                            <div class="info-card">
                                <?php if ($loggedInUser AND $isOwner) { ?>
                                    <div class="add-post">
                                        <div class="w-clearfix post-author new-post">
                                            <img
                                                src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>"
                                                width="40" height="40" class="post-author-img post">
                                            <div class="profile-label">Do you have something to share?</div>
                                            <a href="#" data-ix="new-post-show" class="create-new-post">Add new
                                                post <span
                                                    class="add-post-plus">+</span></a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div ng-controller="ProjectPostController" ng-repeat="post in project.posts" ng-cloak>
                                    <div class="w-clearfix" ng-class="{'current-status' : $index == 0}">

                                        <h3 ng-show="$index == 0" class="status-h3">Latest post</h3>
                                        <h3 ng-show="$index == 1" class="info-h card-h">Previous posts</h3>

                                        <div class="post-author" ng-class="{'latest' : $index == 0}">
                                            <img width="40" height="40" class="post-author-img"
                                                 ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{post.user.profilePic}}">
                                            <div class="post-author-detail" ng-class="{'latest' : $index == 0}"
                                                 ng-bind="post.user.name"></div>
                                            <div class="posted-on" ng-class="{'latest' : $index == 0}"
                                                 ng-bind="post.time"></div>
                                        </div>
                                        <div class="news-content"
                                             ng-bind-html="renderHtml(post.text)"></div>
                                    </div>
                                    <div class="w-clearfix comment-count" ng-cloak>
                                        <a href="#" class="w-inline-block w-clearfix comment-toggle">
                                            <img width="256" class="comment-bubble"
                                                 src="<?php echo SITE_RELATIVE_PATH ?>/images/ios7-chatbubble.png">
                                            <div class="comment-indicator" ng-click="loadComments()">
                                                <span ng-show="post.commentsCount == 0">There are no comments, be the first to say something</span>
                                                <span ng-show="post.commentsCount == 1">There is {{post.commentsCount}} comment</span>
                                                <span ng-show="post.commentsCount > 1">There are {{post.commentsCount}} comments</span>
                                                <span ng-show="loadingComments">| Loading comments...</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="post-comments" ng-show="showComments" ng-cloak="">
                                        <div class="comment">
                                            <?php if ($loggedInUser) { ?>
                                                <div class="w-row">
                                                    <div class="w-col w-col-1 w-clearfix">
                                                        <img class="commentor-img"
                                                             src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>">
                                                    </div>
                                                    <div class="w-col w-col-11">
                                                        <div class="post-comment">
                                                            <div class="w-form">
                                                                <form ng-submit="submitComment()">
                                                                    <input type="text"
                                                                           placeholder="Write your comment"
                                                                           class="w-input add-comment"
                                                                           ng-model="post.newComment">
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div ng-controller="ProjectPostCommentController"
                                                 ng-repeat="comment in post.comments">
                                                <div class="w-row comment-original">
                                                    <div class="w-col w-col-4 w-clearfix">
                                                        <img class="commentor-img"
                                                             ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{comment.user.profilePic}}">
                                                        <div class="commentor-name">{{comment.user.name}}</div>
                                                        <br/>
                                                        <a href="#" ng-click="replyToComment = !replyToComment"
                                                           class="reply">Reply</a>
                                                    </div>
                                                    <div class="w-col w-col-8">
                                                        <div class="post-comment comment-original-post">
                                                            {{comment.comment}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="w-row reply-cols"
                                                         ng-repeat="reply in comment.replies">
                                                        <div class="w-col w-col-3 w-clearfix reply-col-1">
                                                            <img class="commentor-img commentor-reply-img"
                                                                 ng-src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/{{reply.user.profilePic}}">
                                                            <div class="commentor-name commentor-reply">
                                                                {{reply.user.name}}
                                                            </div>
                                                        </div>
                                                        <div class="w-col w-col-9">
                                                            <div class="post-comment reply-comment">
                                                                {{reply.comment}}
                                                            </div>
                                                            <a href="#"
                                                               ng-click="$parent.replyToComment = !$parent.replyToComment"
                                                               class="reply">Reply</a>
                                                        </div>
                                                    </div>
                                                    <?php if ($loggedInUser) { ?>
                                                        <div class="w-row reply-input" ng-show="replyToComment">
                                                            <div class="w-col w-col-1 w-clearfix">
                                                                <img class="commentor-img commentor-reply-img"
                                                                     src="<?php echo SITE_RELATIVE_PATH ?>/images/users/profile/<?php echo $loggedInUser->getProfilePicOrDefault() ?>">
                                                            </div>
                                                            <div class="w-col w-col-11">
                                                                <div class="w-form">
                                                                    <form ng-submit="submitComment()">
                                                                        <input type="text"
                                                                               placeholder="Add your reply"
                                                                               class="w-input add-comment"
                                                                               ng-model="comment.newReply">
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="new-post-bg bg-blur">
            <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
            <script>
                tinymce.init({
                    selector: '#newPost',
                    height: 500,
                    plugins: "autoresize autolink lists link preview paste textcolor colorpicker image imagetools media",
                    autoresize_bottom_margin: 0,
                    autoresize_max_height: 500,
                    menubar: false,
                    toolbar1: 'styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | preview',
                    image_advtab: true,
                    paste_data_images: false
                });
            </script>

            <div class="add-post-modal">
                <form ng-submit="addPost()">
                    <textarea id="newPost" style="height:100%">Please type your update here...</textarea>
                    <a href="#" data-ix="hide-new-post" class="modal-save cancel">Cancel</a>
                    <input type="submit" class="modal-save" value="Publish post"
                           style="border-width:0;line-height:20px;"/>
                </form>
            </div>
        </div>
    </div>
<?php require __DIR__ . '/footer.php' ?>