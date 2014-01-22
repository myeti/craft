namespace my\models;

use craft\orm\Model;

class <?= $model ?>
{

    use Model;

    <?php foreach($fields as $field => $type): ?>
    /** @var <?= $type ?> */
    public $<?= $field ?>;

    <?php endforeach; ?>

}