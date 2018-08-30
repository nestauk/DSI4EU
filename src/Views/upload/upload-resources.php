<?php

use Models\Resource;

$angularModules['fileUpload'] = true;

require __DIR__ . '/../header.php';
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \Services\URL */
?>
    <div ng-controller="UploadResourcesController">
        <div class="content-block">
            <div class="w-row">
                <div class="w-col w-col-8">
                    <h1 class="content-h1">Upload Research and Resources</h1>
                </div>
            </div>
        </div>
        <div class="content-directory">
            <div class="container-wide step-window">
                <div class="w-form">
                    <div class="w-row">
                        <div class="form-col-left w-col w-col-12" ng-cloak>
                            <a target="_blank" href="/assets/files/resources-upload.csv">
                                Download resources upload template</a>.<br>
                            <i>* Please note that cluster names and resource types must be separated by semicolon. <br>
                                * Do not change the CSV headers
                            </i>
                            <br><br>

                            <a class="w-button dsi-button story-image-upload" href="#"
                               style="width:300px"
                               ngf-select="upload($file, $invalidFiles)"
                               ng-bind="loading ? 'Loading...' : 'Upload resources CSV'">
                            </a>

                            <div class="errors" ng-show="hasErrors()" style="font-weight: bold;margin:10px;">
                                Please fix all the errors and re-upload the file.
                            </div>

                            <br>

                            <div class="striped" ng-show="response.length > 0">
                                <div ng-repeat="item in response">
                                    <div><b>Title:</b> {{item.title}}</div>
                                    <div><b>Description:</b> {{item.description}}</div>
                                    <div><b>Link Url:</b> {{item.link_url}}</div>
                                    <div><b>Link text:</b> {{item.link_text}}</div>
                                    <div><b>Author:</b> {{item.author}}</div>
                                    <div><b>Clusters:</b> {{item.clusters}}</div>
                                    <div><b>Types:</b> {{item.types}}</div>
                                    <div ng-show="item.errors.length > 0" class="errors">
                                        <b>Errors:</b>
                                        <ul class="errors">
                                            <li class="errors" ng-repeat="error in item.errors">{{error}}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <div class="errors" ng-show="hasErrors()" style="font-weight: bold;margin:10px;">
                                Please fix all the errors and re-upload the file.
                            </div>

                            <button type="button" class="tab-button-2 w-button"
                                    ng-click="uploadAndSave()"
                                    ng-disabled="response.length === 0 || hasErrors()">
                                Save uploaded resources
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript"
            src="/js/controllers/UploadResourcesController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <style>
        .w-button[disabled] {
            background: #a5d1f8;
        }

        .striped {
            margin-bottom: 20px;
        }

        .striped > div {
            background-color: #fff;
            padding: 20px;
            border-top: 1px solid #ddd;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        .striped > div:nth-child(odd) {
            background-color: #fafafa;
        }

        .errors {
            color: red;
        }
    </style>

<?php require __DIR__ . '/../footer.php' ?>