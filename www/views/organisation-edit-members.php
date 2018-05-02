<?php
require __DIR__ . '/header.php';
/** @var $users \DSI\Entity\User[] */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $organisation \DSI\Entity\Organisation */
/** @var $urlHandler Services\URL */
/** @var $isAdmin bool */
/** @var $isOwner bool */

if (!isset($urlHandler))
    $urlHandler = new Services\URL();
?>
    <div ng-controller="OrganisationEditMembersController">
        <div class="creator page-header">
            <div class="container-wide header">
                <h1 class="light page-h1">
                    <?php echo sprintf(
                        __('Manage members for %s'),
                        '<a href="' . $urlHandler->organisation($organisation) . '">' .
                        show_input($organisation->getName()) .
                        '</a>'
                    ) ?>
                </h1>
            </div>
        </div>
        <div class="creator section-white">
            <div class="container-wide">
                <div class="add-story body-content">
                    <div class="w-tabs" data-easing="linear">
                        <div class="creator-tab-menu w-tab-menu">
                            <a class="step-tab tab-link-1 w--current w-inline-block w-tab-link" data-w-tab="Tab 1">
                                <div><?php _ehtml('Manage existing members') ?></div>
                            </a>
                            <a class="step-tab tab-link-2 w-inline-block w-tab-link" data-w-tab="Tab 2">
                                <div><?php _ehtml('Add existing DSI4eu user') ?></div>
                            </a>
                            <?php /* <a class="step-tab tab-link-3 w-inline-block w-tab-link" data-w-tab="Tab 3">
                                <div><?php _ehtml('Invite by email') ?></div>
                            </a> */ ?>
                        </div>
                        <div class="w-tab-content">
                            <div class="step-window w--tab-active w-tab-pane" data-w-tab="Tab 1">
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4">
                                        <h2><?php _ehtml('Manage existing members') ?></h2>
                                        <p><?php _ehtml('Here you can manage existing members.') ?></p>
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
                                                                    <img src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL ?>{{member.profilePic}}"
                                                                         class="involved-profile-img" width="50">
                                                                </div>
                                                                <div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                                                    <div class="card-name">{{member.name}}</div>
                                                                    <div class="card-position">{{member.jobTitle}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div ng-show="member.isOwner">
                                                            Is Owner
                                                        </div>
                                                        <div ng-hide="member.isOwner">
                                                            <a ng-hide="member.isOwner" class="remove-user" href="#"
                                                               ng-click="removeMember(member)">Remove member</a>
                                                            <div ng-show="member.isAdmin">
                                                                Is Admin
                                                                <?php if ($isOwner) { ?>
                                                                    <a class="remove-user" href="#"
                                                                       ng-click="removeAdmin(member)">
                                                                        Remove admin privileges
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                            <?php if ($isOwner) { ?>
                                                                <div ng-hide="member.isAdmin">
                                                                    <a class="remove-user" style="color:green" href="#"
                                                                       ng-click="makeAdmin(member)">
                                                                        Give admin privileges
                                                                    </a>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step-window w-tab-pane" data-w-tab="Tab 2">
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4 w-col-stack">
                                        <h2><?php _ehtml('Add existing DSI4EU user') ?></h2>
                                        <p><?php _ehtml('You can add existing users to your organisation.') ?></p>
                                        <p>
                                            <?php _ehtml('After being notified the user will [...]') ?>
                                        </p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8 w-col-stack">
                                        <div class="w-form">
                                            <form id="email-form-3" name="email-form-3"
                                                  ng-submit="searchExistingUser.submit()">
                                                <div class="w-row">
                                                    <label for="email-6">
                                                        <?php _ehtml('Search for existing user by name or email') ?>
                                                    </label>

                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Email 6" id="email-6" maxlength="256"
                                                                   placeholder="<?php _ehtml('User name or email') ?>"
                                                                   required="required" type="text" name="email-6"
                                                                   ng-model="searchExistingUser.input">
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <button class="tab-button-4 tab-button-next w-button"
                                                                type="submit" style="float:none">
                                                            <?php _ehtml('Search') ?>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="w-row">
                                                    <div ng-show="searchExistingUser.loading">
                                                        <?php _ehtml('Loading') ?>...
                                                    </div>
                                                    <div class="w-clearfix w-col w-col-6 w-col-stack"
                                                         ng-repeat="member in searchExistingUser.users">
                                                        <div class="involved-card manage">
                                                            <div class="w-row">
                                                                <div class="image-col w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                                                    <img src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL ?>{{member.profilePic}}"
                                                                         class="involved-profile-img" width="50">
                                                                </div>
                                                                <div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                                                    <div class="card-name">{{member.name}}</div>
                                                                    <div class="card-position">{{member.jobTitle}}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a class="remove-user add-user" style="color:green" href="#"
                                                           ng-click="searchExistingUser.addUser(member)">
                                                            <?php _ehtml('Add user') ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="w-form">
                                            <div class="w-row">
                                                <label for="email-6">
                                                    <?php _ehtml('Invited Members') ?>
                                                </label>

                                                <div class="w-clearfix w-col w-col-6 w-col-stack"
                                                     ng-repeat="member in invitedMembers">
                                                    <div class="involved-card manage">
                                                        <div class="w-row">
                                                            <div class="image-col w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                                                <img src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL ?>{{member.profilePic}}"
                                                                     class="involved-profile-img" width="50">
                                                            </div>
                                                            <div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                                                <div class="card-name">{{member.name}}</div>
                                                                <div class="card-position">{{member.jobTitle}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <a class="remove-user add-user" href="#"
                                                       ng-click="cancelInvitationForUser(member)">
                                                        <?php _ehtml('Cancel Invitation') ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <?php /*<div class="step-window w-tab-pane" data-w-tab="Tab 3">
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4">
                                        <h2><?php _ehtml('Invite by email') ?></h2>
                                        <p><?php _ehtml('You can invite new users to your organisation by email.') ?></p>
                                        <p><?php _ehtml('After being invited the user will [...]') ?></p>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8">
                                        <div class="w-form">
                                            <form id="email-form-3" name="email-form-3"
                                                  ng-submit="inviteByEmail.submit()">
                                                <div class="w-row">
                                                    <label for="email-6">
                                                        <?php _ehtml('Add email to invite') ?>
                                                    </label>

                                                    <div class="w-col w-col-6">
                                                        <div class="padding-right-50">
                                                            <input class="creator-data-entry end w-input"
                                                                   data-name="Email 6" id="email-6" maxlength="256"
                                                                   name="email-6"
                                                                   placeholder="<?php _ehtml('Email address') ?>"
                                                                   required="required" type="text"
                                                                   ng-model="inviteByEmail.email">
                                                        </div>
                                                    </div>
                                                    <div class="w-col w-col-6">
                                                        <button class="tab-button-4 tab-button-next w-button"
                                                                type="submit" style="float:none">
                                                            <?php _ehtml('Invite') ?>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="w-form">
                                                    <div class="w-row">
                                                        <label for="email-6">
                                                            <?php _ehtml('Invited Emails') ?>
                                                        </label>

                                                        <div class="w-clearfix w-col w-col-6 w-col-stack"
                                                             ng-repeat="member in invitedEmails">
                                                            <div class="involved-card manage">
                                                                <div class="w-row">
                                                                    <div class="image-col w-col w-col-3 w-col-small-3 w-col-tiny-3">
                                                                        <img src="//uploads.webflow.com/img/image-placeholder.svg"
                                                                             class="involved-profile-img" width="50">
                                                                    </div>
                                                                    <div class="w-clearfix w-col w-col-9 w-col-small-9 w-col-tiny-9">
                                                                        <div class="card-name">{{member.email}}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <a class="remove-user add-user" href="#"
                                                               ng-click="cancelInvitationForEmail(member)">
                                                                <?php _ehtml('Cancel Invitation') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var translate = new Translate();
        <?php foreach([
            'You are about to invite this user to join the organisation',
            'Continue',
            'The user has been invited to join the organisation.',
            'You are about to invite this person to join the organisation',
            'An invitation to join the organisation has been sent by email.',
            "You are about to cancel this user's invitation to join the organisation",
            'Success!',
            'The user has been removed from the organisation.',
            'The user now has admin privileges.',
            'Admin privileges have been removed from the user.',
            'The invitation to join the organisation has been cancelled.',

        ] AS $translate) { ?>
        translate.set('<?php echo show_input($translate)?>', '<?php _ehtml($translate)?>');
        <?php } ?>
    </script>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationEditMembersController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>