<?php
require __DIR__ . '/header.php'
/** @var $loggedInUser \DSI\Entity\User */
/** @var $urlHandler \DSI\Service\URL */
?>
    <script type="text/javascript"
            src="<?php echo SITE_RELATIVE_PATH ?>/js/controllers/FundingController.js?<?php \DSI\Service\Sysctl::echoVersion() ?>"></script>

    <div ng-controller="FundingController"
         data-fundingjsonurl="<?php echo $urlHandler->fundingJson() ?>">

        <div class="container-wide content">
            <div class="w-row">
                <div class="w-col w-col-4 w-col-stack">
                    <div class="info-card">
                        <h1 class="card-h1">Funding</h1>
                        <p class="card-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius
                            enim in eros elementum tristique</p>
                        <p class="card-p question">Have you got funding to offer?</p>
                        <p class="card-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <div class="w-form">
                            <form id="email-form-2" name="email-form-2">
                                <h3 class="filter-h3">Search funding opportunities</h3>
                                <input class="funding-filter w-input" data-name="Search" id="Search-2" maxlength="256"
                                       name="Search" placeholder="Search by keyword or content" required="required"
                                       type="text">
                                <h3 class="filter-h3">Filter by:</h3>
                                <select class="funding-filter w-select" data-name="Country 4" id="country-4"
                                        name="country-4"
                                        ng-options="item as item.title for item in data.countries track by item.id"
                                        ng-model="inCountry">
                                </select>
                                <select class="funding-filter w-select" data-name="Country 6" id="country-6"
                                        name="country-6"
                                        ng-options="item as item.title for item in data.years track by item.id"
                                        ng-model="beforeYear">
                                </select>
                                <select class="funding-filter w-select" data-name="Country 5" id="country-5"
                                        name="country-5"
                                        ng-options="item as item.title for item in data.months track by item.id"
                                        ng-model="beforeMonth">
                                </select>
                                <select class="funding-filter w-select" data-name="funding source" id="funding-source-2"
                                        name="funding-source"
                                        ng-options="item as item.title for item in data.sources track by item.id"
                                        ng-model="fundingSource">
                                </select>
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
                <div class="w-col w-col-8 w-col-stack">
                    <div class="info-card">
                        <h2 class="funding-card-h2">{title.Call for proposals to develop an online tool enabling the
                            tracking of language versions of films}</h2>
                        <p class="funding-descr">{descr.Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare,
                            eros dolor interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo
                            cursus id rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.}</p>
                        <div class="funding-closing-date"><strong>Closing date:</strong> {27th September 2017}</div>
                        <a class="read-more w-button" href="#">Read more</a>
                        <div class="funding-country funding-new">New funding opportunity</div>
                        <div class="funding-country funding-source">Funding source</div>
                        <div class="funding-country">{country.England}</div>
                    </div>
                    <div class="info-card">
                        <h2 class="funding-card-h2">Call for proposals to develop an online tool enabling the tracking
                            of language versions of films</h2>
                        <p class="funding-descr">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                            varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id
                            rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
                        <div class="funding-closing-date"><strong>Closing date:</strong> {27th September 2017}</div>
                        <a class="read-more w-button" href="#">Read more</a>
                        <div class="funding-country">Spain</div>
                    </div>
                    <div class="info-card">
                        <h2 class="funding-card-h2">Call for proposals to develop an online tool enabling the tracking
                            of language versions of films</h2>
                        <p class="funding-descr">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse
                            varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor
                            interdum nulla, ut commodo diam libero vitae erat. Aenean faucibus nibh et justo cursus id
                            rutrum lorem imperdiet. Nunc ut sem vitae risus tristique posuere.</p>
                        <div class="funding-closing-date"><strong>Closing date:</strong> {27th September 2017}</div>
                        <a class="read-more w-button" href="#">Read more</a>
                        <div class="funding-country">Italy</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>