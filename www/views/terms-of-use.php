<?php
require __DIR__ . '/header.php';

$totalProjects = (new \DSI\Repository\ProjectRepoInAPC())->countAll();
$totalOrganisations = (new \DSI\Repository\OrganisationRepoInAPC())->countAll();
?>

    <div class="w-section page-header">
        <div class="w-clearfix container-wide header">
            <h1 class="page-h1 light">Terms of use</h1>
        </div>
    </div>

    <div class="w-section section-grey">
        <div class="container-wide">
            <div class="w-row">
                <div class="w-col w-col-12 intro-col">
                    <p>
                        This website is managed by Nesta (“Nesta”) on behalf of the European Commission and our
                        partners: Stichting Waag Society, gut.org, WeMake S.R.L., Institut d’arquitectura avançada de
                        Catalunya, Barcelona Activa SA SPM and Fundacja ePaństwo. Use of the site is subject to these
                        Terms. Our Privacy Policy
                        explains how we use any personal information which we collect via the site. If you don’t agree
                        with these Terms or our Privacy Policy, please don’t use this site.
                    </p>
                    <p>
                        1. The information, materials and logos on this site belong to the European Commission, Nesta,
                        our partners or licensors. Unless otherwise indicated, materials and reports are licensed for
                        use under a Creative Commons Attribution Non-Commercial Share-A-like licence (CC-BY- NC-SA),
                        full details at https://creativecommons.org/licenses/by-nc-sa/4.0/. We make no representation or
                        warranty about the content, including in relation to non-infringement of intellectual property
                        rights.
                    </p>
                    <p>
                        2. You must not use this site: to post, share or transmit material which is unlawful or
                        infringes anyone else’s rights for any commercial purpose or in any way which is abusive,
                        defamatory or obscene or which harasses or distresses anyone or restricts or inhibits their use
                        and enjoyment of the site or otherwise damages our reputation to transmit software viruses or
                        code designed to interrupt, destroy or compromise the integrity of computer software, hardware
                        or telecommunications equipment
                    </p>
                    <p>
                        3. You are responsible for any comments, text, video, audio, images or other content which you
                        post on the site and must obtain any relevant permissions or consents to post this content. You
                        grant us a perpetual, worldwide, non-exclusive, royalty-free and fully transferable right to
                        copy, download, distribute, transmit and adapt your content and waive your moral rights in it.
                    </p>
                    <p>
                        4. We are not responsible for content posted on the site by users and third parties. The views
                        expressed are not necessarily the views of the European Commission, Nesta or our partners. You
                        can report any inappropriate content to us at contact@digitalsocial.eu . Although we don’t
                        monitor all content posted on the site, we reserve the right to do so and to edit or remove
                        content at our absolute discretion and without notice.
                    </p>
                    <p>
                        5. The information on this site is subject to change from time to time and we can’t guarantee
                        that it will always be accurate and up-to-date. We are not responsible for the content of
                        external sites which you link to from this site.
                    </p>
                    <p>
                        6. We don’t guarantee that the site will always be available or free from errors or viruses. You
                        are responsible for installing virus-checking software to protect your hardware. As far as
                        legally possible, we and our partners exclude all liability for any loss or damage suffered as a
                        result of using or accessing our site, whether direct or indirect, and however arising,
                        including any loss of data or damage caused by downloading content from our site.
                    </p>
                    <p>
                        7. These Terms may be varied from time to time. If you use the site after any change has been
                        made, you will be deemed to have accepted the change. Additional terms may apply to certain
                        areas of this site, such as applications for grant funding or payment for events.
                    </p>
                    <p>
                        8. These Terms are governed by English law and the courts of England and Wales will have
                        jurisdiction over any dispute arising from them.
                    </p>
                    <p>
                        Nesta, a company limited by guarantee registered in England and Wales with company number
                        7706036 and charity number 1144091. Registered as a charity in Scotland number SC042833.
                    </p>
                    <p>
                        Registered office: 58 Victoria Embankment, London EC4Y 0DS.
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php require __DIR__ . '/footer.php' ?>