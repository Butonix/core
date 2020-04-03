<?php

use Rareloop\WordPress\Router\Router;

Router::map(['GET'], 'backdoor/welcome', 'WelcomePage\WelcomePageController@init');
Router::map(['GET'], 'backdoor/tools', 'ToolsPage\ToolsPageController@init');
//Router::map(['GET'], 'backdoor/orice', 'OricePage\OricePageController@init');
