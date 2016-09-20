<?php
/** @var \DSI\Entity\PasswordRecovery $passwordRecovery */
?>
Welcome to <a href="https://<?php echo SITE_DOMAIN ?>/">DigitalSocial.eu</a>,
<br/><br/>
Please <a href="https://<?php echo SITE_DOMAIN ?>/reset-password">click here</a>
to reset your password.<br/>
Your security code is: <b><?php echo $passwordRecovery->getCode() ?></b>
<br/><br/>
After completing your registration you will be able to add a project or organisation,
join existing projects and organisations or view funding opportunities and events.