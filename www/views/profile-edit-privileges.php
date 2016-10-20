<?php
require __DIR__ . '/header.php';
/** @var $userID int */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $user \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */
/** @var $securityCode string */
?>
    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <img class="large-profile-img"
                     src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $user->getProfilePicOrDefault() ?>">
                <h1 class="content-h1 profile-h1"><?php echo show_input($user->getFullName()) ?></h1>
                <div class="profile-job-title">
                    <?php echo show_input($user->getJobTitle()) ?>
                    <?php if ($user->getJobTitle() AND $user->getCompany()) echo ' at ' ?>
                    <?php echo show_input($user->getCompany()) ?>
                </div>
                <p class="intro"><?php echo nl2br(show_input($user->getBio())) ?></p>
                <h3>Location</h3>
                <p>
                    <?php echo show_input($user->getCityName()) ?>
                    <?php if ($user->getCityName() != '' AND $user->getCountryName() != '') echo ', '; ?>
                    <?php echo show_input($user->getCountryName()) ?>
                </p>
            </div>
            <div class="sidebar w-col w-col-4">
                <h1 class="content-h1 side-bar-space-h1">Actions</h1>
                <a class="sidebar-link" href="<?php echo $urlHandler->editUserProfile($user) ?>">
                    <span class="green">-&nbsp;</span>Edit profile</a>
                <a class="sidebar-link" href="<?php echo $urlHandler->editUserPrivileges($user) ?>">
                    <span class="green">-&nbsp;</span>Edit privileges</a>
                <?php /* <a class="sidebar-link" href="<?php echo $urlHandler->logout() ?>">
                     <span class="green">- Sign out</span></a> */ ?>
            </div>
        </div>
    </div>

    <div class="content-directory">
        <div class="container-wide">
            <div class="profile-info w-row">
                <div class="w-col w-col-4 w-col-stack">
                </div>
                <div class="w-col w-col-4 w-col-stack">
                    <div class="info-card" style="padding:10px">
                        <h2>Change user privileges</h2>
                        <form method="post" action="">
                            <select name="userRole" class="select2" style="width:100%">
                                <option value="user">
                                    Ordinary User
                                </option>
                                <option <?php if ($user->isEditorialAdmin()) echo 'selected' ?> value="editorial-admin">
                                    Editorial Admin
                                </option>
                                <option <?php if ($user->isCommunityAdmin()) echo 'selected' ?> value="community-admin">
                                    Community Admin
                                </option>
                                <option <?php if ($user->isSysAdmin()) echo 'selected' ?> value="sys-admin">
                                    System Admin
                                </option>
                            </select>
                            <br/><br/>

                            <input type="hidden" name="secureCode" value="<?php echo $securityCode ?>"/>

                            <input type="submit" class="tab-button-2 tab-button-next w-button" name="save"
                                   value="Save"/>

                            <br/><br/>
                            <br/>
                        </form>
                    </div>
                </div>
                <div class="w-col w-col-4 w-col-stack">
                </div>
            </div>
        </div>
    </div>

    <script>
        $('select.select2').select2();
    </script>

<?php require __DIR__ . '/footer.php' ?>