<?php

/** @var \DSI\Entity\User $owner */
/** @var \DSI\Entity\Organisation $organisation */
/** @var \Services\URL $urlHandler */
?>
<p>Hi <?= $owner->getFirstName() ?>,</p>
<p></p>
<p>Welcome to the Digital Social Innovation (DSI) platform.
    Thanks for joining our growing network of people using digital technologies to tackle social challenges.</p>
<p>We wanted to let you know that we’ve approved the organisation you added to our website and it is now
    public on
    <a href="<?= $urlHandler->fullUrl($urlHandler->home()) ?>">digitalsocial.eu</a>.
    You can access it here:
    <a href="<?= $urlHandler->fullUrl($urlHandler->organisation($organisation)) ?>"><?= $urlHandler->fullUrl($urlHandler->organisation($organisation)) ?></a>.
</p>
<p>We hope you enjoy this opportunity to explore our platform further. Discover more about DSI in Europe through our
    <a href="<?= $urlHandler->fullUrl($urlHandler->caseStudies()) ?>">case studies</a> and
    <a href="<?= $urlHandler->fullUrl($urlHandler->blogPosts()) ?>">blogs</a>, browse
    <a href="<?= $urlHandler->fullUrl($urlHandler->funding()) ?>">funding and support opportunities</a>, and find
    <a href="<?= $urlHandler->fullUrl($urlHandler->events()) ?>">events</a>
    across the continent!</p>
<p></p>
<p>If you want to keep up to date with our work, why not follow us on
    <a href="https://twitter.com/dsi4eu"><b>Twitter</b></a> and sign up to our
    <a href="http://bit.ly/DSINews"><b>mailing list</b></a>?</p>
<p></p>
<p>Best wishes,</p>
<p></p>
<p>The DSI4EU team</p>