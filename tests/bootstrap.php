<?php

declare(strict_types = 1);

// As WordPress is still using and supporting PHPUnit 6, we need to create
// certain empty classes expected for that version of PHPUnit in order to
// supress errors when we run PHPUnit 9.
require dirname( __FILE__ ) . '/_/SupressFrameworkError.php';
require dirname( __FILE__ ) . '/_/SupressFramework.php';

// Include the WordPress test suite
//
// Remember to run the following in order to get the test suite running:
// `$ bin/install-wp-tests.sh wordpress-test root password 127.0.0.1 latest`
require '/tmp/wordpress-tests-lib/includes/functions.php';
require '/tmp/wordpress-tests-lib/includes/bootstrap.php';
