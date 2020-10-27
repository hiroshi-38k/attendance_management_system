<?php
$errors = get_error();
$messages = get_messages();
?>
<?php if (!empty($errors)){?>
<?php foreach($errors as $error){ ?>
    <p><span><?php print $error; ?></span></p>
<?php } ?>
<?php } ?>
<?php if (!empty($messages)){?>
<?php foreach($messages as $message){ ?>
    <p><span><?php print $message; ?></span></p>
<?php } ?>
<?php } ?>