<?php self::layout('views/layout') ?>

<h1>Entity #<?= $entity->id ?></h1>

<a href="<?= url('/entity', $entity->id, 'edit') ?>">edit</a>
<a href="<?= url('/entity', $entity->id, 'delete') ?>" data-confirm="Are you sure to delete this entity ?">delete</a>

<div class="entity">

    <?php foreach($entity as $field => $value): ?>
    <div class="line">
        <strong><?= $field ?></strong>
        <p><?= $value ?></p>
    </div>
    <?php endforeach; ?>

</div>