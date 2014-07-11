<?php

namespace Craft\Bundle;

use Craft\App\Event\NotFound;
use Craft\App\Layer;
use Craft\Box\Auth;
use Craft\Box\Flash;
use Craft\Orm\Syn;

class Blog extends App
{

    /** @var array */
    protected $config = [
        'admin.username'    => 'Babor',
        'admin.password'    => '3f6f6d7c89d3c8b71750424d1ffc3c481ac351c5',
        'article.perpage'   => 10,
        'article.model'     => 'Craft\Bundle\Blog\Article'
    ];


    /**
     * Init blog settings
     * @param string $root
     * @param array $config
     */
    public function __construct($root = __DIR__, $config = [])
    {
        // init
        $root = rtrim($root, '/') . '/';
        $this->config = $config + $this->config;

        // setup db
        Syn::SQLite($root . 'craft-blog.db')
            ->map('article', $this->config['article.model'])
            ->build();

        // setup router
        $routes = [
            '/'             => [$this, 'all'],
            '/page/:page'   => [$this, 'all'],
            '/read/:slug'   => [$this, 'read'],
            '/login'        => [$this, 'login'],
            '/write'        => [$this, 'write'],
            '/write/:id'    => [$this, 'write'],
            '/oops'         => [$this, 'oops'],
        ];

        // init app
        parent::__construct($routes, $root);

        // errors
        $this->on(404, function(){
            go('/oops');
        });

        $this->on(403, function(){
            go('/login');
        });
    }


    /**
     * List all articles
     * @param int $page
     * @return array
     * @render views/front.all
     */
    protected function all($page = 1)
    {
        // calc limit
        $from = ($page - 1) * $this->config['article.perpage'];
        $step = $this->config['article.perpage'];

        // get all articles
        $articles = Syn::get('article')
                    ->where('published', true)
                    ->limit($from, $step)
                    ->all();

        return ['articles' => $articles];
    }


    /**
     * Read an article
     * @param string $slug
     * @throws NotFound
     * @return array
     * @render views/front.read
     */
    protected function read($slug)
    {
        // get article
        $article = Syn::get('article')
                   ->where('published', true)
                   ->where('slug', $slug)
                   ->one();

        // no such article
        if(!$article) {
            throw new NotFound;
        }

        return ['article' => $article];
    }


    /**
     * Login user
     * @render views/admin.login
     */
    protected function login()
    {
        // init
        $username = null;

        // login attempt
        if($data = post()) {

            // success
            if($data['username'] == $this->config['admin.username']
               and sha1($data['password']) == $this->config['admin.password']) {
                Auth::login();
                go('/write');
            }
            // fail
            else {
                $username = $data['username'];
                Flash::set('error', 'Wrong ID');
            }

        }

        return ['username' => $username];
    }


    /**
     * Write an article
     * @render views/admin.write
     */
    protected function write($id = null)
    {
        // no id : create

        // id and content : write

        // id and no content : delete

    }

}