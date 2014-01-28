<form action="<?= $form->action ?>" method="post">

    <?php foreach($form as $field): ?>
    <div class="line">
        <?= $field->html(); ?>
    </div>
    <?php endforeach; ?>

    <div class="line">
        <button type="submit">Submit</button>
    </div>

</form>