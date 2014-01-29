<div class="sub-form" data-name="<?= $form->name ?>">

    <?php foreach($form as $field): ?>
    <div class="line">
        <?= $field->html(); ?>
    </div>
    <?php endforeach; ?>

</div>

