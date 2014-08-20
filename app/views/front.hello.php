<?php self::layout('layout'); ?>

<style>
    @import url(http://fonts.googleapis.com/css?family=Slabo+27px);

    body .background {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('<?= self::asset('img/lake_leman.jpeg') ?>') no-repeat;
        background-size: cover;
        opacity: 0.25;
    }

    .hello {
        margin-top: 300px;
        margin-left: 200px;
    }
    .hello h1 {
        font-weight: 700;
        font-size: 60px;
        font-family: 'Slabo 27px', serif;
    }
    .hello h1 strong {
        color: #00afff;
    }
    .hello p {
        width: 500px;
        font-size: 28px;
        line-height: 32px;;
        font-family: 'Slabo 27px', serif;
    }
</style>

<div class="background"></div>

<div class="hello">

    <h1>Hello <strong>Craft</strong> !</h1>

    <p>Your application skeleton is now deployed, take some rest.</p>

    <p>Next steps : build your database from your models, write your controllers and design your templates.</p>

    <p>Do not worry, Craft provides your some tools to make it easy.</p>

    <p>Keep it up !</p>

</div>