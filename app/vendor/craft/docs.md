# Craft

Craft est un ensemble d'outils et composants vous permettant de créer votre application web.
Simple, intuitif et non-intrusif, il laisse une grande liberté à votre créativité.


## Nouvelle application

Afin de charger Craft dans votre projet, il vous suffit, d'inclure `vendor/craft/bundle.php` dans votre `index.php`.
De cette manière, vous aurez accès aux différents paquages.

Pour démarrer une nouvelle application, vous devez instancier une class `craft\web\App` avec les routes que souhaitez mettre en place :

```php
<?php

require 'vendor/craft/bundle.php';

$app = new craft\web\App([
    '/' => 'my\logic\Front::hello',
]);

$app->plug();
```

Ces routes mettent en relation une url `/` avec l'action d'un contrôleur `my\logic\Front::hello`.

> Note : par défaut, Craft attribut le namespace `my` à votre projet.
> Le contrôleur `my\logic\Front` correspond à la class `logic/Front.php`.

La méthode plug permet de lancer la webapp et d'écouter l'url afin de router vers vos actions.


## Les évènements

Lors de l'éxecution d'une webapp, 2 évènements sont récupérants : la page/action est introuvable (404) ou l'utilisateur
n'est pas autorisé à executer cette page/action (403).

Voici comment intercepter ces évènements et re-router vers la page de votre choix :

```php
<?php

require 'vendor/craft/bundle.php';

$app = new craft\web\App([
    '/'         => 'my\logic\Front::hello',
    '/lost'     => 'my\logic\Error::lost',
    '/sorry'    => 'my\logic\Error::sorry',
    '/docs'     => 'my\logic\Front::docs',
]);

$app->on(404, function() use($app) {
    $app->plug('/lost');
});

$app->on(403, function() use($app) {
    $app->plug('/sorry');
});
```

Dans notre cas, la 404 sera re-router vers l'url `/lost` et la 403 vers `/sorry`.


## Session & Environnement

Craft propose plusieurs classes facilitant l'utilisation des données d'environnement get, post, serveur, cookie, env et auth.

// todo


## Base de données

// todo


## Box

// todo