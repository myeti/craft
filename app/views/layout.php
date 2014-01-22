<?php use craft\view\html; ?>
<!doctype html>
<html>
<head>
    <title>Your App</title>
    <?= html::meta(); ?>
    <?= html::css('public/css/layout'); ?>
    <?= html::css('public/css/content'); ?>
</head>
<body>

    <?= self::content(); ?>

</body>
</html>