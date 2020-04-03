<?php

/**
  *   Charti CMS
  *   @since 0.1
  **/

use Rareloop\WordPress\Router\Router;

// Let's reserve create a group route so we can pass login/reset/register through it.
Router::group('account', function ($group) {
  
  Router::map(['GET'], 'account', function () {
      get_template_part( 'views/layouts/login');
  })->name('Login');

  // $group->map(['GET'], 'login', function () {
  //   get_template_part( 'views/layouts/login');

  $group->map(['GET'], 'register', function () {
    get_template_part( 'views/layouts/login');
  })->name('Register');

  $group->map(['GET'], 'login?reset', function () {
    get_template_part( 'views/layouts/login');
  })->name('Forgot Password');

  // get registered routes and push to admin dashboard via navigation menu
  Router::pull_registered_routes($group);
});