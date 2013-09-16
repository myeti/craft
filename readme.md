# Craft Framework

Simple, précis, efficace.


## Le routing

La définition des règles de routage se fait de la manière suivante : une url pour une action.

```php
$router = new craft\Router([
    '/' =>      'my\logic\Front::index',
    '/about' => 'my\logic\Front::about'
]);

$app = new craft\App($router);
$app->process();
```

### Les paramètres

Ils existent 2 type de paramètres, les arguments `:` et les variables d'environnement `+` :

```php
$router = new craft\Router([
    '/+lang/about' =>       'my\logic\Front::about',
    '/+lang/page/:id' =>    'my\logic\Front::page',
]);
```

Dans notre cas, `+lang` deviendra une variable d'environnement globale à toute l'application, récupérable grâce à la fonction `$lang = env('lang');`.
L'argument `:id` sera, lui, transmis à l'action.

Par exemple : `www.yourapp.com/fr/page/5`, determinera `fr` comme variable d'environnement, et l'id `5` sera passé à l'action.

### Types d'action

Le routeur accepte différents types d'actions :
- le couple Class/Method sous forme de chaine : `my\logic\Front::index`
- le couple Class/Method sous forme de tableau : `['my\logic\Front', 'index']`
- le nom d'une fonction : `my_front_index`
- une fonction anonyme : `function(){ echo 'Hello :)'; }`

### 404

Si aucune règle ne match l'url, un évenement 404 est déclenché.


## Les actions

Afin de laisser une plus grande liberté aux développeurs, les contrôleurs n'étendent d'aucune classes de `craft` :

```php
namespace my\logic;

class Front
{

    /**
     * @view views/front.index.php
     */
    public function index()
    {

    }

    /**
     * @view views/front.about.php
     */
    public function about()
    {
        return ['author' => 'Babor Lelefan'];
    }

}
```

### Méta-données

Craft intègre un système de méta-données basé sur les commentaires PHP.
Le framework va nottament définir la vue à afficher ou les permissions d'utilisateur à vérifier en fonction des paramètres `@`.

Affichage d'une vue : `@view dir/to/template.php`.

Restriction d'utilisateur : `@auth 5`. Ici 5 correspond au rang du l'utilisateur, si le rang n'est pas assez élevé, un évenement 403 est déclenché.

### Les valeurs de retour

Les données retournées par les actions sous forme de tableau seront transmis à la vue (si elle est définie) pour l'affichage.
Dans notre cas, `['author' => 'Babor Lelefan']` sera accessible par la variable `$author` dans la vue `views/front.about.php`.


## Les vues

Craft propose un système de templating simple, sans syntaxe particulière :

```php
# views/front.about.php
<?php self::layout('views/layout.php') ?>
<h1>Webapp handcrafted by <?= $author ?></h1>
```

La variable `$author` correspond à la donnée retournée par l'action `my\logic\Front::about`

### Le layout

Afin de subvenir au besoin d'imbriquation des templates, il est possible de définir le layout d'une vue grâce à la méthode `self::layout()`.
Du côté du layout, il faut spécifier la méthode `self::content()` à l'endroit où l'en souhaite afficher le template :

```php
# views/layout.php
<!doctype html>
<html>
<head>
    <title>My crafted app !</title>
    <?= self::meta(); ?>
    <?= self::css('web/css/layout'); ?>
    <?= self::js('web/js/jquery'); ?>
</head>
<body>

<?= self::content(); ?>

</body>
</html>
```

### Helpers

3 helpers sont disponibles dans l'exemples ci-dessus : le `css` et `js` qui permet d'affichir une balise css ou js et le `meta` qui permet de gerer le viewport et l'encodage.


## Le contexte

Les données globales type POST, SERVER, adresse ip, device, etc... sont accesible grâce au `mog()` (Kupo !) :

```php
ip = mog()->ip;
$referer = mog()->server['HTTP_REFERER'];
$ajax = mog()->async;
```

Voir la classe pour plus de détails.

### La session

L'accès et le stockage de donnée en session s'effectuent via la fonction `session()` :

```php
session('foo', 'bar');
$foo = session('foo');
```

### Les messages flash

L'utilisation des messages flash se fait de la même manière qu'avec la fonction `session()`, attention cependant, la récupération d'un message le consomme !

```php
flash('success', 'Yeah !');
$message = flash('success');    # retourne "Yeah !"
$another = flash('success');    # retourne null
```

### Les redirections

Il est possible de rediriger vers une url spécifique grâce à : `go('somewhere/please')` (renvoi vers `www.yourapp.com/somwhere/please`).

### L'authentification

L'objet `Auth` permet de gérer les permissions d'utilisateur :

```php
# login
craft\Auth::login(5, $user);

# logout
craft\Auth::logout();

# get data
$isLOgged = craft\Auth::logged();
$rank = craft\Auth::rank();
$user = craft\Auth::user();
```


## Les événements

Durant le processus d'une application Craft, plusieurs évenements sont déclenchés à certaines étapes clés afin de permettre au développeur de modifier le comportement du framework.

```php
$app = new App($router);

$app->on('call', function($self, $build, $data){

    if(isset($build->metadata['json'])){
        die(json_encode($data));
    }

});

$app->process();
```

L'exemple ci-dessus permet d'afficher les données renvoyées au format JSON pour les actions ayant `@json`.

Voici la liste des évenements propres à Craft :

- `start` : démarrage du processus
- `route` : une règle de routage match l'url
- `resolve` : définition de l'action a executer
- `call` : appel de l'action et retour de données
- `render` : affichage des données
- `end` : fin du processus
- `404` : aucune règle ne match l'url
- `403` : l'utilisateur n'a pas le rang nécessaire


*version de la doc : 0.1, le 16.09.13*