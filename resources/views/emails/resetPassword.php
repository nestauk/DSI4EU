<?php
/** @var \DSI\Entity\PasswordRecovery $passwordRecovery */
?>
Welcome to <a href="https://<?php echo SITE_DOMAIN ?>/">DigitalSocial.eu</a>,
<br/><br/>
Please <a href="https://<?php echo SITE_DOMAIN ?>/reset-password">click here</a>
to reset your password.<br/>
Your security code is: <b><?php echo $passwordRecovery->getCode() ?></b>
<br/><br/>
For security reasons, this code expires in 24 hours. You can generate a new code after 24 hours.
<br /><br />
After completing your registration you will be able to add a project or organisation,
join existing projects and organisations or view funding opportunities and events.