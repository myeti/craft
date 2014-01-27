<form action="<?= $action ?>" method="post">

    <?php foreach($fields as $field): ?>
    <div class="line">
        <?= $field->html(); ?>
    </div>
    <?php endforeach; ?>

</form>