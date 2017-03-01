<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('DashedRoute');

Router::scope('/', function (RouteBuilder $routes) {
    $routes->extensions(['json', 'xml']);
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);


    $routes->connect('/chatroom-:slug', ['controller' => 'rooms', 'action' => 'view'],['pass' => ['slug']]);
    
    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);
    $routes->connect('/about-us', ['controller' => 'Pages', 'action' => 'view','about-us']);
    $routes->connect('/term-and-conditions', ['controller' => 'Pages', 'action' => 'view','term-and-conditions']);
    $routes->connect('/feedback-and-suggestions', ['controller' => 'Pages', 'action' => 'feedback']);
    $routes->connect('/contact-us', ['controller' => 'Pages', 'action' => 'view','contact-us']);
    $routes->connect('/edit-profile', ['controller' => 'users', 'action' => 'edit']);
    $routes->connect('/users/profile/:slug', ['controller' => 'users', 'action' => 'view'], ['pass' => ['slug']]);
    $routes->connect('/signin', ['controller' => 'users', 'action' => 'login']);
    $routes->connect('/signup', ['controller' => 'users', 'action' => 'add']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
    
});

// Or with prefix()
Router::prefix('admin', ['_namePrefix' => 'admin:'], function ($routes) {
    
    $routes->connect('/', ['controller' => 'dashboard', 'action' => 'index']);

    $routes->connect('/users/profile/:slug', ['controller' => 'users', 'action' => 'view'],['pass' => ['slug']]);
    $routes->connect('/rooms/details/:slug', ['controller' => 'rooms', 'action' => 'view'],['pass' => ['slug']]);
    $routes->fallbacks('DashedRoute');
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
