namespace my\logic;

class <?= $controller ?>
{

    <?php foreach($methods as $method => $args): ?>

    /**
    * <?= ucfirst($method) ?>
    * @render views/<?= strtolower($controller) ?>.<?= strtolower($method) ?>
    <?php foreach($args as $arg): ?>
    * @param <?= $arg ?>
    <?php endforeach; ?>
    * @return mixed
    */
    public function <?= $method ?>(<?= implode(', ', $args) ?>)
    {
        // now code, you lazy !
    }

    <?php endforeach; ?>

}