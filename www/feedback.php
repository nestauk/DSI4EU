<?php require __DIR__ . '/header.php' ?>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/FeedbackController.js"></script>

    <div class="w-container feed-back-conteiner" ng-controller="FeedbackController">
        <div class="feed-back-collection">
            <h1 class="feedback-h1">DSI4EU Feed back form</h1>
            <h2 class="feedback-h2">Sign up process</h2>
            <p class="feed-back-info">Thank you for agreeing to help us develop the DSI4EU platform by completing the
                following tasks. Please could you forward any feedback you have on the following items:</p>
            <ul class="feedback-list">
                <li>Sign up using either email &amp; password or social media login</li>
                <li>Update your personal information</li>
                <li>Request a password reset</li>
            </ul>
            <div class="w-form feedback-form-wrapper">
                <form class="w-clearfix" ng-submit="sendFeedbackSubmit()">
                    <input type="text" placeholder="Your name" class="w-input feedback-form-input"
                           ng-model="feedback.name">
                    <div style="color:red" ng-show="errors.name" ng-bind="errors.name"></div>

                    <input type="email" placeholder="Email address" class="w-input feedback-form-input"
                           ng-model="feedback.email">
                    <div style="color:red" ng-show="errors.email" ng-bind="errors.email"></div>

                    <textarea placeholder="Your feedback" class="w-input feedback-form-input large"
                              ng-model="feedback.message"></textarea>
                    <div style="color:red" ng-show="errors.message" ng-bind="errors.message"></div>

                    <input type="submit" class="w-button feedback-form-submit"
                           ng-value="loading ? 'Sending...' : 'Send feedback'"
                           ng-disabled="loading">

                    <div ng-show="feedbackSent" style="color:green">
                        Thank you for your feedback!
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>