<?php require __DIR__ . '/header.php' ?>

    <div class="w-container feed-back-conteiner" ng-controller="FeedbackController">
        <div class="feed-back-collection">
            <h1 class="feedback-h1"><?php _ehtml('DSI4EU Feedback form')?></h1>
            <br/><br/>
            <p class="feed-back-info">Digitalsocial.eu is a work in progress. We are keen to gather feedback from our
                users. Please let us
                know about anything you find on the site that doesn't work as you expect and any ideas you have on how
                the site could be improved.</p>
            <div class="w-form feedback-form-wrapper">
                <form class="w-clearfix" ng-submit="sendFeedbackSubmit()">
                    <input type="text" placeholder="<?php _ehtml('Your name')?>" class="w-input feedback-form-input"
                           ng-model="feedback.name">
                    <div style="color:red" ng-show="errors.name" ng-bind="errors.name"></div>

                    <input type="email" placeholder="<?php _ehtml('Email address')?>" class="w-input feedback-form-input"
                           ng-model="feedback.email">
                    <div style="color:red" ng-show="errors.email" ng-bind="errors.email"></div>

                    <textarea placeholder="<?php _ehtml('Your feedback')?>" class="w-input feedback-form-input large"
                              ng-model="feedback.message"></textarea>
                    <div style="color:red" ng-show="errors.message" ng-bind="errors.message"></div>

                    <input type="submit" class="w-button feedback-form-submit"
                           ng-value="loading ? '<?php _ehtml('Loading')?>...' : '<?php _ehtml('Send feedback')?>'"
                           ng-disabled="loading">

                    <div ng-show="feedbackSent" style="color:green">
                        <?php _ehtml('Thank you for your feedback')?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/FeedbackController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

<?php require __DIR__ . '/footer.php' ?>