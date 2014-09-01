<style>
#trace-panel {
    position: fixed;
    z-index: 8000;
    top: 0;
    bottom: 0;
    right: 0;
    width: 600px;
    background: #FFF;
    box-shadow: 0 0 4px rgba(0, 0, 0, 0.4);
}
#trace-panel .trace-panel-stack {
    margin-bottom: 20px;
}
#trace-panel .trace-panel-stack h2 {
    margin: 0;
    padding: 15px 20px;
    border-bottom: 1px solid #EEE;
}
#trace-panel .trace-panel-stack .trace-panel-stack-line {
    padding: 6px 20px;
    font-size: 12px;
    border-bottom: 1px solid #EEE;
}
#trace-panel .trace-panel-stack .trace-panel-stack-line aside {
    display: inline-block;
    float: right;
    color: #CCC;
}

#trace-panel .log-error {
    color: red;
}
</style>

<script>

</script>

<div id="trace-panel">

    <?php foreach($stacks as $name => $stack): ?>
    <div class="trace-panel-stack" data-name="<?= $name ?>">

        <h2><?= $name ?></h2>

        <?php foreach($stack->data() as $line): ?>
        <div class="trace-panel-stack-line">
            <?= $line->content ?>
            <aside><?= $line->aside ?></aside>
        </div>
        <?php endforeach; ?>

    </div>
    <?php endforeach; ?>

</div>