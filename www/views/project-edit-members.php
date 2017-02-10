<?php
require __DIR__ . '/header.php';
/** @var $users \DSI\Entity\User[] */
/** @var $owner \DSI\Entity\User */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $project \DSI\Entity\Project */
/** @var $urlHandler \DSI\Service\URL */

if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();
?>
    <div ng-controller="ProjectEditMembersController">
        <div class="creator page-header">
            <div class="container-wide header">
                <h1 class="light page-h1">
                    Manage users for
                    <a href="<?php echo $urlHandler->project($project) ?>">
                        <?php echo show_input($project->getName()) ?>
                    </a>
                </h1>
            </div>
        </div>
        <div class="creator section-white">
            <div class="container-wide">
                <div class="add-story body-content">
                    <div class="w-tabs" data-easing="linear">
                        <div class="creator-tab-menu w-tab-menu">
                            <a class="step-tab tab-link-1 w-inline-block w-tab-link" data-w-tab="Tab 1">
                                <div>Manage existing users</div>
                            </a>
                            <a class="step-tab tab-link-2 w-inline-block w-tab-link" data-w-tab="Tab 2">
                                <div>Add Existing DSI4eu USer</div>
                            </a>
                            <a class="step-tab tab-link-3 w--current w-inline-block w-tab-link" data-w-tab="Tab 3">
                                <div>Invite by email</div>
                            </a>
                        </div>
                        <div class="w-tab-content">
                            <div class="step-window w-tab-pane" data-w-tab="Tab 1">
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4">
                                        <h2>Manage existing users</h2>
                                        <p>Here you can manage existing users.</p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8">
                                        <div class="w-form">
                                            <form id="email-form-3" name="email-form-3">
                                                <div class="w-row">
                                                    <div class="w-clearfix w-col w-col-6 w-col-stack"
                                                         ng-repeat="member in members">
                                                        <div class="involved-card manage">
                                                            <div class="w-row">
                                                                <div class="image-col w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                                                    <img class="involved-profile-img"
                                                                         src="http://uploads.webflow.com/img/image-placeholder.svg"
                                                                         width="50">
                                                                </div>
                                                                <div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                                                    <div class="card-name">{{member.name}}</div>
                                                                    <div class="card-position">{{member.jobTitle}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a class="remove-user" href="#">Remove user</a>
                                                        <div ng-show="member.isOwner">
                                                            Owner
                                                        </div>
                                                        <div ng-show="member.isAdmin">
                                                            <a class="remove-user" href="#">Admin</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step-window w-tab-pane" data-w-tab="Tab 2">
                                <div class="tabbed-nav-buttons w-clearfix"><a
                                            class="tab-button-3 tab-button-next w-button">Save
                                        and continue</a>
                                </div>
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4 w-col-stack">
                                        <h2>Add existing DSI4EU user</h2>
                                        <p>You can add existing users to your project.</p>
                                        <p>After being notified the user will need to accept your invitation before
                                            being
                                            added to your project.</p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                        <div class="w-form">
                                            <form id="email-form-3" name="email-form-3">
                                                <div class="w-row">
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <label for="email-6">Search for existing user by name or
                                                                email</label>
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Email 6" id="email-6" maxlength="256"
                                                                   name="email-6" placeholder="User name or email"
                                                                   required="required" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <div class="padding-left-50"></div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="w-form-done">
                                                <div>Thank you! Your submission has been received!</div>
                                            </div>
                                            <div class="w-form-fail">
                                                <div>Oops! Something went wrong while submitting the form</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step-window w--tab-active w-tab-pane" data-w-tab="Tab 3">
                                <div class="tabbed-nav-buttons w-clearfix"><a
                                            class="tab-button-4 tab-button-next w-button">Save
                                        and continue</a>
                                </div>
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4">
                                        <h2>Invite by email</h2>
                                        <p>You can invite new users to your project by email.</p>
                                        <p>After being invited the user will need to create a profile to complete the
                                            process.</p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8">
                                        <div class="w-form">
                                            <form id="email-form-3" name="email-form-3">
                                            <textarea class="creator-data-entry end w-input wide"
                                                      data-name="invite by email" id="invite-by-email" maxlength="5000"
                                                      name="invite-by-email"
                                                      placeholder="Add email to invite"></textarea>
                                            </form>
                                            <div class="w-form-done">
                                                <div>Thank you! Your submission has been received!</div>
                                            </div>
                                            <div class="w-form-fail">
                                                <div>Oops! Something went wrong while submitting the form</div>
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

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/ProjectEditMembersController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>