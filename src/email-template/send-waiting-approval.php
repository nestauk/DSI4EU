<?php
/** @var \DSI\Entity\ContentUpdate[] $contentUpdates */
/** @var \DSI\Service\URL $urlHandler */
if (!isset($urlHandler))
    $urlHandler = new \DSI\Service\URL();
?>
<table cellpadding="2" cellspacing="2">
    <tbody>
    <?php foreach ($contentUpdates AS $i => $contentUpdate) { ?>
        <tr style="background:<?= ($i % 2 ? '#fff' : '#eee') ?>">
            <td><?php echo $i + 1 ?>.</td>
            <td style="font-weight:bold">
                <?php if ($contentUpdate->getProjectID()) { ?>
                    <a href="<?= $urlHandler->fullUrl($urlHandler->project($contentUpdate->getProject())) ?>">
                        <?php _ehtml($contentUpdate->getProject()->getName()) ?>
                    </a>
                <?php } else { ?>
                    <a href="<?= $urlHandler->fullUrl($urlHandler->organisation($contentUpdate->getOrganisation())) ?>">
                        <?php _ehtml($contentUpdate->getOrganisation()->getName()) ?>
                    </a>
                <?php } ?>
            </td>
            <td>
                <?php if ($contentUpdate->getProjectID()) { ?>
                    project
                <?php } else { ?>
                    organisation
                <?php } ?>
            </td>
            <td>
                <?= $contentUpdate->getUpdated() ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<p style="margin-top:30px">
    <a href="<?= $urlHandler->fullUrl($urlHandler->waitingApproval()) ?>">See all waiting approvals</a>
</p>