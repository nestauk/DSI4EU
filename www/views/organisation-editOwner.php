<?php
require __DIR__ . '/header.php';
/** @var $users \DSI\Entity\User[] */
/** @var $owner \DSI\Entity\User */
/** @var $loggedInUser \DSI\Entity\User */
/** @var $organisation \DSI\Entity\Organisation */

if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();
?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.0/jquery-ui.js"></script>

    <div class="creator page-header">
        <div class="container-wide header">
            <h1 class="light page-h1">Edit Organisation Owner</h1>
        </div>
    </div>
    <div class="creator section-white" ng-controller="OrganisationEditOwnerController">
        <div class="container-wide">
            <div class="add-story body-content">
                <div class="w-tabs" data-easing="linear">
                    <div class="w-tab-content">
                        <div class="step-window w-tab-pane w--tab-active">
                            <form id="email-form-3" name="email-form-3" ng-submit="save()">
                                <div class="w-row">
                                    <div class="creator-col w-col w-col-4">
                                        <h2>Organisation Owner: </h2>
                                    </div>
                                    <div class="creator-col creator-col-right w-col w-col-8">
                                        <div class="w-form">
                                            <div class="w-row">
                                                <div class="w-col w-col-6 w-col-stack">
                                                    <div class="padding-right-50">
                                                        <select id="newOwner" name="" class="select2" style="width:300px"
                                                                data-placeholder="Select new owner">
                                                            <option></option>
                                                            <?php foreach ($users AS $user) { ?>
                                                                <option value="<?php echo $user->getId() ?>"
                                                                    <?php if ($owner->getId() == $user->getId()) echo "selected" ?>>
                                                                    <?php echo show_input($user->getFullName()) ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="w-col w-col-6 w-col-stack">
                                                    <div class="padding-left-50">
                                                        <button type="submit"
                                                                class="tab-button-2 tab-button-next w-button"
                                                                ng-bind="loading ? 'Loading...' : 'Save'"
                                                                ng-disabled="loading">Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/OrganisationEditOwnerController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <script>
        $(function () {
            $('select.select2').select2();
        });
    </script>

<?php require __DIR__ . '/footer.php' ?>