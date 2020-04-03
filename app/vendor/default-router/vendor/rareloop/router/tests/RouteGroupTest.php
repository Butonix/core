<?php

namespace Rareloop\Router\Test;

use PHPUnit\Framework\TestCase;
use Rareloop\Router\Route;
use Rareloop\Router\Router;

class RouteGroupTest extends TestCase
{
    /** @test */
    public function group_function_is_chainable()
    {
        $router = new Router;

        $this->assertInstanceOf(Router::class, $router->group('test/123', function () {}));
    }

    /** @test */
    public function can_add_get_request_to_a_group()
    {
        $router = new Router;
        $count = 0;

        $router->group('test', function ($group) use (&$count) {
            $count++;
            $route = $group->get('all', function () {});

            $this->assertInstanceOf(Route::class, $route);
            $this->assertSame(['GET'], $route->getMethods());
            $this->assertSame('test/all', $route->getUri());
        });

        $this->assertSame(1, $count);
    }

    /** @test */
    public function can_add_post_request_to_a_group()
    {
        $router = new Router;
        $count = 0;

        $router->group('test', function ($group) use (&$count) {
            $count++;
            $route = $group->post('all', function () {});

            $this->assertInstanceOf(Route::class, $route);
            $this->assertSame(['POST'], $route->getMethods());
            $this->assertSame('test/all', $route->getUri());
        });

        $this->assertSame(1, $count);
    }

    /** @test */
    public function can_add_put_request_to_a_group()
    {
        $router = new Router;
        $count = 0;

        $router->group('test', function ($group) use (&$count) {
            $count++;
            $route = $group->put('all', function () {});

            $this->assertInstanceOf(Route::class, $route);
            $this->assertSame(['PUT'], $route->getMethods());
            $this->assertSame('test/all', $route->getUri());
        });

        $this->assertSame(1, $count);
    }

    /** @test */
    public function can_add_patch_request_to_a_group()
    {
        $router = new Router;
        $count = 0;

        $router->group('test', function ($group) use (&$count) {
            $count++;
            $route = $group->patch('all', function () {});

            $this->assertInstanceOf(Route::class, $route);
            $this->assertSame(['PATCH'], $route->getMethods());
            $this->assertSame('test/all', $route->getUri());
        });

        $this->assertSame(1, $count);
    }

    /** @test */
    public function can_add_delete_request_to_a_group()
    {
        $router = new Router;
        $count = 0;

        $router->group('test', function ($group) use (&$count) {
            $count++;
            $route = $group->delete('all', function () {});

            $this->assertInstanceOf(Route::class, $route);
            $this->assertSame(['DELETE'], $route->getMethods());
            $this->assertSame('test/all', $route->getUri());
        });

        $this->assertSame(1, $count);
    }

    /** @test */
    public function can_add_options_request_to_a_group()
    {
        $router = new Router;
        $count = 0;

        $router->group('test', function ($group) use (&$count) {
            $count++;
            $route = $group->options('all', function () {});

            $this->assertInstanceOf(Route::class, $route);
            $this->assertSame(['OPTIONS'], $route->getMethods());
            $this->assertSame('test/all', $route->getUri());
        });

        $this->assertSame(1, $count);
    }
}
