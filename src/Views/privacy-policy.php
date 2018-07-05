<?php
\Services\View::setPageTitle('Explore DSI');
require __DIR__ . '/header.php';

$totalProjects = (new \DSI\Repository\ProjectRepoInAPC())->countAll();
$totalOrganisations = (new \DSI\Repository\OrganisationRepoInAPC())->countAll();
?>

    <div class="privacy-policy-controller content-block">
        <div class="w-row">
            <div class="w-col w-col-8 w-col-stack">
                <h1 class="content-h1">Privacy Policy</h1>
                <p>
                    This website belongs to Nesta, and is being operated for the purpose of the DSI4EU project,
                    formally known as DSISCALE, which is funded under the European Union&rsquo;s Horizon 2020
                    Programme, grant number 780473, on behalf of a consortium of seven partner organisations: Nesta
                    (UK), Waag (Netherlands), betterplace lab (Germany), Fab Lab Barcelona (Spain), WeMake (Italy),
                    Barcelona Activa (Spain) and the ePaństwo Foundation (Poland). You can find <strong>full details
                        below.</strong> This privacy policy explains how Nesta uses personal information we collect
                    via this site. We are committed to protecting your privacy and we take all reasonable
                    precautions to safeguard personal information. This privacy notice does not cover the links
                    within this site linking to other websites. We encourage you to read the privacy statements on
                    the other websites you visit.
                </p>

                <h2 class="home-h2">1. What kind of information do we collect?</h2>
                <p>
                    <strong>Sign-up details:</strong> If you register for an individual profile on the site,
                    register or sign up for events, newsletters or publications via the site, we will ask you for
                    personal information such as your name, email address, occupation, location, a profile picture
                    and social media links. All of these fields except for your email address and name are optional.
                    If you sign up for the DSI4EU mailing list, we will ask you for your name, email address,
                    country (mandatory) and role and organisation (optional).
                </p>
                <p>
                    <strong>Project and organisation profiles: </strong>Once you have created an individual account
                    you will be able to submit information about your organisation and projects, as well as events
                    and funding/support opportunities. If in submitting this information you submit any personal
                    information, please ensure that you have the individual&rsquo;s consent to do so.
                </p>
                <p>
                    <strong>Application details:</strong> If you register to attend one of our events, we may ask
                    for more detailed information which we will use to process your application, including details
                    of your team if relevant, and you will be asked to agree to specific terms and conditions at the
                    time of making the application.
                </p>
                <p>
                    <strong>Feedback and surveys:</strong> If you choose to give feedback about the DSI4EU project,
                    we will ask you for your name and email address. We may occasionally ask users to complete
                    surveys (publicised for example through blogs or the DSI4EU mailing list). If you complete a
                    survey you will be asked to agree to specific terms and conditions at the time.
                </p>
                <p>
                    <strong>Research: </strong>If you agree to work with us in relation to any research we are
                    conducting we will ask you for your name and contact details, occupation and may ask for further
                    information about you as may be relevant to the particular research you are participating in.
                </p>
                <p>
                    <strong>Online activity:</strong> We record your activity and preferences when visiting the
                    sites through the use of cookies (see "Cookies", below).
                </p>
                <p>
                    <strong>Your posts and communications:</strong> If you post content or communicate via the site,
                    we may also ask for your name, occupation and the organisation you may work for. We also store
                    and monitor your content and communications.
                </p>

                <h2 class="home-h2">2. What do we do with information we collect and what is our legal basis for
                    this?</h2>
                <p><strong>Third party processors:</strong> As is indicated below, we may use third party platforms
                    and processors to deliver newsletters, event registrations, process payments, process surveys,
                    and process any request to update your data contact preferences. In using these third parties we
                    are pursuing our legitimate interest to use third party technology to achieve greater efficiency
                    within our organisation. To balance our interests against yours, we have taken steps to ensure
                    these third parties maintain appropriate technical and organisational measures to keep your
                    personal information secure.</p>
                <p><strong>Sign-up details: </strong>If you sign-up for us to send you newsletters, publications and
                    other information about Nesta, our partners and activities (see &ldquo;Marketing&rdquo; section
                    below), we will use your personal information to send these to you. Our legal basis for doing
                    this is your consent. You have the right to withdraw this consent at any time, as explained in
                    the &ldquo;Marketing&rdquo; section below. If you are given the opportunity to sign-up, register
                    for an event, or programme, or to create a personal profile via Facebook or Twitter, Facebook
                    and Twitter will make your email address registered to your Facebook or Twitter account
                    available to Nesta, and Nesta will use the email address to enable you to log in to the
                    particular platform. Our legitimate interest in doing this is the use of third party platforms
                    for business efficiency and ease of registration for users.</p>
                <p><strong>Project and organisation profiles: </strong>The information provided when creating an
                    account and profile will be used for the administration and management of your account, and to
                    allow you access to all the facilities and opportunities within the DSI4EU Network, including
                    enabling you to submit details of your organisation and any projects, and opportunities for
                    funding and support. Our legal basis for this is to fulfil the agreement with you for you to
                    participate in the DSI4EU Network. </p>
                <p><strong>Events:</strong> If you register to attend one of our events we will use your personal
                    information to process your application to attend the event, take payment, if any, for our
                    administration and management of the event and to send you any updates or information regarding
                    the event. Our legal basis for doing so is to fulfil the agreement with you for your attendance.
                </p>
                <p>We use CVent and Eventbrite as registration systems for many of our events and their servers are
                    based in the United States (U.S.) and may store and process your information outside the
                    European Economic Area (EEA). Both CVent and Eventbrite comply with the EU-US Privacy Shield
                    Framework. If you wish to know more please see<a
                            href="http://www.cvent.com/en/privacy-policy-global.shtml"> CVent&rsquo;s Privacy
                        Policy</a> and<a
                            href="https://www.eventbrite.com/support/articles/en_US/Troubleshooting/eventbrite-privacy-policy?lg=en_US">
                        Eventbrite&rsquo;s Privacy Policy</a>.</p>
                <p>If you buy a ticket for an event, or make a purchase via the website, we will use third party
                    payment processing organisations to take your payment. These organisations will be the data
                    controller of your information for the purpose of processing the transaction, and may involve
                    your information being processed outside of the EEA. Please read the terms and conditions before
                    completing your payment.</p>
                <p><strong>Application details:</strong> We will use your personal information to process your
                    applications you may make via the site. We undertake this processing because it is necessary to
                    fully assess your application, and if successful, to take steps to enter into an agreement with
                    you (this is our legal basis for the processing).</p>
                <p><strong>Feedback and surveys: </strong>If you agree to give us feedback or complete a survey, we
                    will use the information to improve our work and activities. We usually use SurveyGizmo to
                    process surveys, and they only process your information on our instructions. SurveyGizmo operate
                    in the US and comply with the EU-U.S. Privacy Shield Framework. For more information see<a
                            href="https://www.surveygizmo.com/privacy/"> SurveyGizmo&rsquo;s Privacy Policy</a>. If
                    you agree to participate in any survey that will form part of any research project, we will tell
                    you at the time you take part how your information will be used for the particular research
                    project or programme, and how long it will be kept for.</p>
                <p><strong>Research:</strong> If you agree to take part in any research we will use your information
                    for the purpose of that research project or programme. Full details of how your personal
                    information will be used will be given to you at the time you agree to participate; this could
                    include to inform the development of a new prize, project, programme or other initiative, being
                    incorporated into reports or other research outcomes, and may include being publicly displayed
                    on web pages relating to the particular research project or programme. </p>
                <p><strong>Online activity: </strong>The cookies we use tell us how you use the site and what pages
                    you have visited. In pursuing this activity, we use our legitimate interest of improving the
                    performance of the site, and to help us understand more about our customer&rsquo;s interests and
                    preferences, and to inform our marketing strategy. Some of these cookies are used for marketing
                    purposes and, if you agree, will be used to send you tailored advertisements. For more
                    information, see &ldquo;Cookies&rdquo; below.</p>
                <p><strong>Posts and Communications:</strong> If you post any comments on the website, any personal
                    information you agree to provide will be displayed publicly on the website along with your
                    comments.</p>
                <p><strong>Social media interaction:</strong> We use third party providers to check whether the
                    people whose contact email addresses we have collected have registered with those email accounts
                    on social media platforms, such as Facebook or Twitter. We currently use 89up Limited, who
                    provide us with statistical information about how many of our contacts are on Facebook. We do
                    not receive any information as to who is on Facebook. 89up Limited also provides us with the
                    Twitter handle for our contacts who are on Twitter. We use this information to follow our
                    contacts on Twitter. In pursuing these activities, we are pursuing our legitimate interests to
                    understand more about our contacts&rsquo; use of social media and this will then inform our
                    marketing strategy. We have an appropriate contract in place with to ensure the security and
                    protection of the data.</p>
                <p><strong>For all kinds of information collected: </strong>Please make sure that any personal
                    details you provide are accurate and up to date, and let us know about any changes. Please get
                    consent first before giving us anyone else&rsquo;s information.</p>
                <p>The nature of Nesta&rsquo;s work means we often work in partnership with other organisations,
                    however, we will not share your information with any other organisation unless we have your
                    permission first, unless we have a legitimate interest to do so (see section 5 below). </p>
                <p>Some challenges or prizes may include the opportunity to collaborate with other applicants, in
                    this situation you will be asked to agree to your information being shared in this way at the
                    time of application. </p>
                <p>We may also use your information to carry out analysis and research to improve our publications,
                    events and activities, customise our website and its content to your particular preferences,
                    notify you of any changes to our website or to our activities that may affect you, to prevent
                    and detect fraud and abuse, and to protect other users.</p>
                <p>However you choose to engage with or support Nesta, we may retain your information for our own
                    legitimate business interests for statistical analysis purposes, in order to review, develop and
                    improve our business activities. In this situation, we will only keep any personal information
                    if it is necessary to do so, and will always put in place appropriate safeguards, including
                    where possible anonymising or minimising the data retained.</p>
                <p>We use a third party to provide cloud based data security, storage and disaster recovery service
                    to backup data that we hold, we currently use Barracuda Networks lncorporated. Barracuda are a
                    company based in the U.S. and store your Data outside the EEA. Barracuda complies with the
                    EU-U.S. Privacy Shield Framework. For more information see<a
                            href="https://www.barracuda.com/company/legal/privacy"> Barracuda&rsquo;s Privacy
                        Policy</a>. </p>

                <h2 class="home-h2">3. How long will we keep your information for?</h2>
                <p><strong>General principle:</strong> We will only keep any personal information that you provide
                    to us for as long as is necessary to fulfil the purpose for which you gave us the information
                    and we will securely delete information when it is no longer needed for that purpose, as
                    explained in more detail below.</p>
                <p><strong>Events: </strong>If you attend one of our events, we will use your information for the
                    purpose of the event, and will only contact you for any other purpose if you have said we can.
                    We will retain your personal information collected for the purpose of the event for 3 years for
                    evaluation and business development purposes to help us understand our audience and reach, and
                    to improve future events.</p>
                <p>If you buy a ticket for one of our events, we will also keep your information in order to tell
                    you about future similar events. If you do not wish us to contact you about future events please
                    let us know by writing to us or emailing us at the at the contact details below. </p>
                <p><strong>Consent: </strong>We keep records of consent, and any withdrawal of consent, on our files
                    for as long as your personal information is being used in-line with that consent and for a
                    period of 6 years after the consent is withdrawn (unless otherwise requested by you).</p>
                <p><strong>Project and organisation profiles: </strong>Details for individuals, projects and
                    organisations provided when setting up an account and profiles will be publicly displayed on the
                    website for as long as you and your organisation and projects wish to remain part of the DSI4EU
                    network. Please note your email address will <strong>not</strong> be publicly displayed. You can
                    delete your individual profile at any time, as well as any organisation or project profile of
                    which you are the owner. You may also contact Nesta at the contact details below to ask for
                    withdrawal from the DSI4EU network.</p>
                <p><strong>Research:</strong> If you agree to take part in any research your personal information
                    will be kept for as long as it is of value to Nesta and the wider research community, and for as
                    long as may be specified by any external research funder, patent law, legislative and other
                    regulatory requirements. Research data shall be reviewed at least every 5 years to consider its
                    continued value to Nesta, and personal data anonymised or pseudonymised where possible, unless
                    to do so would affect the integrity of the research data and/or its outcomes, or its future
                    value.</p>
                <p>To the extent that personal data arising from any research is embodied within a research report
                    or other research outcome, it will be retained in perpetuity as part of the published
                    materials.</p>
                <p>Research that supports the development of a prize, project, programme, publication or other
                    research outcome, shall be kept for at least 5 years beyond publication or any other research
                    outcome has been completed. If the research is funded or the subject of any other contract, your
                    personal data may be kept for 6 years after the end of the contract or longer if the contract or
                    funding agreement specifies, which could be up to 12 years after the contract ends.</p>
                <p><strong>Posts and communications:</strong> any information that you post on the website shall
                    only be kept and displayed for such time as the subject matter to which is relates is publically
                    displayed. </p>
                <p><strong>Processing for statistical analysis purposes:</strong> This type of processing will only
                    be undertaken whilst we retain your personal information in line with the principles explained
                    above.</p>

                <h2 class="home-h2">4. Marketing</h2>
                <p>If you sign up to our mailing list we will use your details to keep you informed about latest
                    news, blogs, programme updates, research, publications, event details, jobs and funding
                    opportunities, and may request feedback, including our annual audience survey.</p>
                <p>We use third party providers, Mailchimp and Salesforce to deliver our e-newsletters. We gather
                    statistics around email opening and clicks using industry standard technologies to help us
                    monitor and improve our e-newsletter. Please note that Mailchimp and Salesforce use servers
                    hosted in the USA, but do comply with the EU-U.S. Privacy Shield. If you want to know more about
                    how your information will be stored and processed see
                    <a href="https://www.salesforce.com/company/privacy/full_privacy/"> Salesforce&rsquo;s Privacy
                        Policy</a> and
                    <a href="https://mailchimp.com/legal/privacy/"> Mailchimp&rsquo;s Privacy Policy</a>.
                    If we use any other providers we will let you know when you subscribe.
                </p>
                <p>If you no longer want to receive marketing communications from us, you can unsubscribe from our
                    mailing list at any time by please clicking the unsubscribe link, at the bottom of our emails or
                    by emailing info@nesta.org.uk detailing your name and email address. If you are given the
                    opportunity to update your contact preferences in any email from us, this will link to a
                    SurveyGizmo form. If this function is not made available, please email us at the above address
                    with your preference updates. </p>
                <p>We use third party remarketing services to tailor advertising to you based upon your browsing
                    history on this website, for more information see &ldquo;Cookies&rdquo; below.</p>

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

                <h2 class="home-h2">5. Who else has access to your information?</h2>
                <p>If we deem it necessary, we will share your information with the consortium partners Waag
                    (Stichting Waag Society, Netherlands), betterplace lag (gut.org, Germany), Fab Lab Barcelona
                    (Institut d&rsquo;arquitectura avan&ccedil;ada de Catalunya, Spain), WeMake (WeMake S.R.L.,
                    Italy), Barcelona Activa (Barcelona Activa SA SPM, Spain) and ePaństwo Foundation (Fundacja
                    ePaństwo, Poland). For more information about the consortium partners see our<a
                            href="https://digitalsocial.eu/partners"> Partners page</a>. We may share publicly
                    available information with the European Commission, the funder of the DSI4EU project, but we
                    will not share email addresses without your permission.</p>
                <p>We may share your information with other partners or companies who may help us operate the DSI4EU
                    Network. If you register to attend an event or indicate interest in any other activity detailed
                    on the site, we may share your details with the companies/organisations who help us to fund and
                    organise the events or other activities. Our legal basis for sharing with the consortium members
                    and other partners, or companies is to pursue our legitimate interest of being able to work
                    collaboratively with other organisations and individuals to operate the DSI4EU Network and any
                    associated events or activities. Some of these organisations may process your information in
                    countries outside the EEA, such as the United States, where data protection laws are not the
                    same as in the EEA. Rest assured that we will always ensure any transfer is subject to
                    appropriate security measures to safeguard your personal data.</p>
                <p>The details of the profile submitted when creating your account, will be displayed publicly along
                    with your organisations details for other users to see. The email details of the personal
                    profile will not be publicly displayed, and will only be used for the proper administration of
                    your account, and would not be used for any marketing purposes, unless you have also signed up
                    to our mailing list.</p>
                <p>Any details that you submit relating to projects, and funding opportunities will be publicly
                    displayed for other users to see. Please note that these opportunities will involve links to
                    websites belonging to other account holders and are not owned or managed by Nesta. Please ensure
                    that you read the terms and conditions and privacy policies of those sites you may be directed
                    to.</p>
                <p>We may share your information within the Nesta group of companies, for the purposes of managing
                    the DSI4EU project. Nesta currently provides all support and services for its subsidiary
                    companies, therefore, our legal basis for sharing your information is to pursue the legitimate
                    interests of shared resources and management reporting between the companies within the group.
                    Our group companies will not process your data outside the EEA unless we notify you otherwise.
                    Details of our group companies can be found<a
                            href="https://www.nesta.org.uk/nesta-group-companies"> here</a>.</p>
                <p>Comments, blogs and other information which you post on the site are displayed publicly and can
                    be viewed by other users. Please be careful when disclosing personal information which may
                    identify you or anyone else. We are not responsible for the protection or security of
                    information which you post in public areas.</p>
                <p>Inoveb the developer of this website, will have access to personal information that you provide,
                    including your name and where you work, if provided, solely for the purpose of the
                    administration and maintenance of the website. Nesta has an appropriate agreement in place with
                    Inoveb to ensure the security of your personal information.</p>
                <p>We may disclose your personal information to law enforcement agencies if required by law (in
                    which case our legal basis for doing this is for compliance with a legal obligation), or to
                    protect or defend ourselves or others against illegal or harmful activities (in which case, our
                    legal basis for doing this is the pursuit of these legitimate interests).</p>

                <h2 class="home-h2">6. Cookies</h2>
                <p>This site contains cookies. Cookies are small text files that are placed on your computer by
                    websites you visit. They are widely used to make websites work, or work more efficiently, as
                    well as to provide information to site owners. Most web browsers allow some control of most
                    cookies through browser settings.</p>
                <p>This site uses cookies that are strictly necessary to enable you to move around the site or to
                    provide certain basic features, such as logging into secure areas.</p>
                <p>The site also uses performance cookies which collect information about how you use the site, such
                    as how you are referred to it and how long you stay on certain pages. This information is
                    aggregated and therefore anonymous and is used to improve the performance of the site. If you
                    want, you can block cookies by changing the settings on your browser. Please note that some of
                    the features of the site may not work if you choose to block cookies. </p>
                <p>We also use third party tracking cookies for marketing. These third parties help us to send you
                    tailored advertisements about Nesta, that we think may be of interest to you, based on what
                    parts of Nesta&rsquo;s websites you have viewed. The advertisements will be shown to you on
                    other search results pages, or on sites within Google&rsquo;s or any other third-party display
                    network. We do not collect any identifiable information through the use these third-party
                    marketing services.</p>
                <p>If you do not wish to receive such advertisements, then you must deactivate these cookies from
                    your account. For more information and links to manage your setting see the <a
                            href="http://digitalsocial.eu/cookies-policy">Cookie Policy</a>.</p>

                <h2 class="home-h2">7. Security</h2>
                <p>
                    We take steps to protect your personal information and follow procedures designed to minimise
                    unauthorised access or disclosure of your information. If you have a password for an account on
                    this site, please keep this safe and do not share it with anyone else. You are responsible for
                    all activity on your account and must contact us immediately if you are aware of any
                    unauthorised use of your password or other security breach.
                </p>

                <h2 class="home-h2">8. Contacting us, exercising your rights and complaints</h2>
                <p>You are legally entitled to know what personal information we hold about you and how that
                    information is processed, which includes the right to:</p>
                <ul>
                    <li>know what information we hold about you</li>
                    <li>ask us to correct any mistakes in your information which we hold</li>
                    <li>ask us to delete your personal information</li>
                    <li>ask us to stop using your personal information or restrict how we can use it, for example if
                        you feel it is inaccurate or no longer needs to be used by Nesta
                    </li>
                    <li>to object to us using of your personal information</li>
                    <li>to object to any automated decision making that we may do using your personal information
                    </li>
                </ul>
                <p>If you wish to know what information we hold about you, or wish to exercise any of your other
                    rights as detailed above, or have any complaint about how we are using your personal
                    information, then please email us using info@nesta.org.uk or write to us at 58 Victoria
                    Embankment London EC4Y 0DS UK and provide enough information to identify yourself (e.g. name and
                    address or any registration details).</p>
                <p>If our information is incorrect or out of date, please provide us with information to update it.
                    If you want us to delete, restrict or stop using any information we hold about you, please
                    explain the reasons why you are asking this. If you are unhappy with how we are using your
                    information, again please explain to us the reasons and we will investigate the matter.</p>
                <p>You can also write to the same address if you have a complaint about this policy.</p>
                <p>If you are unhappy with how any data rights request or complaint has dealt with you have the
                    right to complain to the Information Commissioner at Wycliff House, Water Lane, Wilmslow,
                    Cheshire SK9 5AF or the following link. https://ico.org.uk/concerns or helpline: 0303 123
                    1113.</p>

                <h2 class="home-h2">9. Changes to the privacy policy</h2>
                <p>We may change this privacy policy from time to time. We will notify you of any changes that
                    relate to information we already hold about you, where practicable. You should check this policy
                    occasionally to ensure you are aware of the most recent version that will apply each time you
                    access this website.</p>
                <p><strong><em>Nesta</em></strong><em>, a company limited by guarantee registered in England and
                        Wales with company number 7706036 and charity number 1144091.</em></p>
                <p><em>Registered as a charity in Scotland number SC042833.</em></p>
                <p><em>Registered office: 58 Victoria Embankment, London, EC4Y 0DS</em></p>
                <p>Email: <a href="mailto:info@nesta.org.uk">info@nesta.org.uk</a></p>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>