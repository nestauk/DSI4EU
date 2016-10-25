<?php
require __DIR__ . '/header.php';
/** @var $userID int */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */
/** @var $securityCode string */
?>
    <div class="content-block">
        <div class="w-row">
            <div class="w-col w-col-8">
                <img class="large-profile-img"
                     src="<?php echo \DSI\Entity\Image::PROFILE_PIC_URL . $loggedInUser->getProfilePicOrDefault() ?>">
                <h1 class="content-h1 profile-h1"><?php echo show_input($loggedInUser->getFullName()) ?></h1>
                <div class="profile-job-title">
                    <?php echo show_input($loggedInUser->getJobTitle()) ?>
                    <?php if ($loggedInUser->getJobTitle() AND $loggedInUser->getCompany()) echo ' at ' ?>
                    <?php echo show_input($loggedInUser->getCompany()) ?>
                </div>
                <p class="intro"><?php echo nl2br(show_input($loggedInUser->getBio())) ?></p>
                <h3>Location</h3>
                <p>
                    <?php echo show_input($loggedInUser->getCityName()) ?>
                    <?php if ($loggedInUser->getCityName() != '' AND $loggedInUser->getCountryName() != '') echo ', '; ?>
                    <?php echo show_input($loggedInUser->getCountryName()) ?>
                </p>
            </div>
        </div>
    </div>

    <div class="content-directory">
        <div class="container-wide">
            <div class="profile-info w-row">
                <div class="w-col w-col-2 w-col-stack">
                </div>
                <div class="w-col w-col-8 w-col-stack">
                    <div class="info-card" style="padding:10px">
                        <h2>Send message to all community admins</h2>
                        <?php if (isset($_SESSION['success'])) { ?>
                            <h4 style="color:green"><?php echo show_input($_SESSION['success']) ?></h4>
                            <?php unset($_SESSION['success']) ?>
                        <?php } ?>

                        <form method="post" action="">
                            <textarea name="message" style="width:100%;height:200px"></textarea>
                            <i>*Your name and profile link will be sent together with the email</i>

                            <br/><br/>

                            <input type="hidden" name="secureCode" value="<?php echo $securityCode ?>"/>

                            <input type="submit" class="tab-button-2 tab-button-next w-button" name="save"
                                   value="Send"/>

                            <br/><br/>
                            <br/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('select.select2').select2();
    </script>

<?php require __DIR__ . '/footer.php' ?>