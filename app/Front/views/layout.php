<!doctype html>
<html>
<head>
    <title>Your Awesome App</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1" />
    <?= self::css('css/layout', 'css/content'); ?>
    <?= self::js('js/jquery-2.1.0.min', 'js/main'); ?>
</head>
<body>

    <?= self::content(); ?>

</body>
</html>