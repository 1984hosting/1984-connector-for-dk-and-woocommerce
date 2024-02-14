<?php

declare(strict_types = 1);

$tmp_dir = sys_get_temp_dir();

// As WordPress is still using and supporting PHPUnit 6, we need to create
// certain empty classes expected for that version of PHPUnit in order to
// supress errors when we run PHPUnit 9.
require __DIR__ . '/_/SupressFrameworkError.php';
require __DIR__ . '/_/SupressFramework.php';

// Include the WordPress test suite.
require "{$tmp_dir}/wordpress-tests-lib/includes/functions.php";
require "{$tmp_dir}/wordpress-tests-lib/includes/bootstrap.php";
