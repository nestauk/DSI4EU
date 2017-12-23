<?php
$pageTitle = 'Explore DSI';
require __DIR__ . '/header.php';

$totalProjects = (new \DSI\Repository\ProjectRepoInAPC())->countAll();
$totalOrganisations = (new \DSI\Repository\OrganisationRepoInAPC())->countAll();
?>

    <div class="w-section page-header">
        <div class="w-clearfix container-wide header">
            <h1 class="page-h1 light">Privacy Policy</h1>
        </div>
    </div>

    <div class="w-section section-grey">
        <div class="container-wide">
            <div class="w-row">
                <div class="w-col w-col-12 intro-col">
                    <p>
                        This website is managed by Nesta (“Nesta”) on behalf of the European Commission and our
                        partners, Waag Society and SUPSI. This privacy policy explains how the European Commission,
                        Nesta and our partners use personal information which we collect via this site and what cookies
                        we use.
                    </p>

                    <h2 class="home-h2">1. What kind of information do we collect?</h2>
                    <p>
                        When you register or sign up on the site, we will ask you for personal information such as your
                        name, email address, location and other personal details. If you register organisations and/or
                        projects we will link those to your personal profile and display your name as a collaborator on
                        that organisation/project profile. We may also ask you for feedback about digitalsocial.eu or to
                        complete surveys. We may record your activity and preferences when visiting the sites (see
                        "Cookies", below). If you post content or communicate via the site, we may also store and
                        monitor your content and communications.
                    </p>

                    <h2 class="home-h2">2. What do we do with information we collect?</h2>
                    <p>
                        We will use your personal information to operate the site, to send you newsletters, publications
                        and other information about digitalsocial.eu, our partners and activities. We may also use your
                        information to carry out analysis and research to improve our publications, events and
                        activities, to prevent and detect fraud and abuse, and to protect other users.
                        Please make sure that any personal details you provide are accurate and up to date, and let us
                        know about any changes. Please get consent first before giving us anyone else’s information.
                    </p>

                    <h2 class="home-h2">3. How to unsubscribe</h2>
                    <p>
                        If you no longer want to receive communications from us, please click the unsubscribe link on
                        any email from us or, where available, change the preferences on your account. If you want to
                        remove
                        your personal information from digitalsocial.eu please email contact@digitalsocial.eu.
                    </p>

                    <h2 class="home-h2">4. Who else has access to your information?</h2>
                    <p>
                        We may share your information with our partners and with companies who help us to operate this
                        site and to organise events and other activities. Some of these organisations may process your
                        information in countries outside the European Economic Area (EEA), such as the United States,
                        where data protection laws are not the same as in the EEA. If you don’t want us to transfer your
                        information in this way, please don’t access and use our websites.
                    </p>

                    <p>
                        Comments, blogs and other information which you post on the site will be displayed publicly and
                        to other users. Please be careful when disclosing personal information which may identify you or
                        anyone else. We are not responsible for the protection or security of information which you post
                        in public areas.
                    </p>

                    <p>
                        We may disclose your personal information if required by law, or to protect or defend ourselves
                        or others against illegal or harmful activities, or as part of a reorganisation or
                        restructuring.
                    </p>

                    <h2 class="home-h2">5. Cookies</h2>
                    <p>
                        This site contains cookies. Cookies are small text files that are placed on your computer by
                        websites you visit. They are widely used to make websites work, or work more efficiently, as
                        well as
                        to provide information to site owners. Most web browsers allow some control of most cookies
                        through
                        browser settings. To find out more about cookies, including how to see what cookies have been
                        set
                        and how to manage and delete them, visit www.aboutcookies.org or www.allaboutcookies.org.
                    </p>

                    <p>
                        This site uses cookies that are strictly necessary to enable you to move around the site or to
                        provide certain basic features, such as logging into secure areas.
                        The site also uses performance cookies which collect information about how you use the site,
                        such as
                        how you are referred to it and how long you stay on certain pages. This information is
                        aggregated
                        and therefore anonymous and is only used to improve the performance of the site. Some of these
                        performance cookies are Google Analytics web cookies. To opt out of being tracked by Google
                        Analytics across all websites visit http://tools.google.com/dlpage/gaoptout.
                    </p>

                    <h2 class="home-h2">6. Security</h2>
                    <p>
                        We take steps to protect your personal information and follow procedures designed to minimise
                        unauthorised access or disclosure of your information. However, we can’t guarantee to eliminate
                        all
                        risk of misuse. If you have a password for an account on this site, please keep this safe and
                        don’t
                        share it with anyone else. You are responsible for all activity on your account and must contact
                        us
                        immediately if you are aware of any unauthorised use of your password or other security breach.
                    </p>

                    <h2 class="home-h2">7. Contacting us</h2>
                    <p>
                        You are legally entitled to know what personal information we hold about you and how that
                        information is processed. If you would like to know what information we hold about you, please
                        write
                        to the General Counsel & Company Secretary at Nesta, 1 Plough Place, London EC4A 1DE, UK. We
                        will
                        ask you for proof of identity and may charge a £10 fee.
                    </p>

                    <p>
                        Nesta, a company limited by guarantee registered in England and Wales with company number 7706036
                        and charity number 1144091.
                    </p>

                    <p>
                        Registered as a charity in Scotland number SC042833.
                        Registered office: 1 Plough Place, London EC4A 1DE
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>