<!doctype html>
<html>
<head>
    <title>Your App</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1" />
    <?= self::css('public/css/layout', 'public/css/content'); ?>
    <?= self::js('public/js/jquery-2.1.0.min', 'public/js/main'); ?>
</head>
<body>

    <?= self::content(); // display here the template's content ?>

</body>
</html>