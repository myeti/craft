<?php self::layout('layout') ?>

<h1>:class list</h1>

<a href="<?= url('/entity/new') ?>">+ new entity</a>

<ul class="entity-list">
    <?php foreach($list as $entity): ?>
    <li>
        <a href="<?= url('/entity/', $entity->id) ?>">
            <?= $entity->id ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>