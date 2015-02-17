<?php
/**
 * Autoloader for tests
 *
 * Autoloader for tests
 *
 * PHP version 5.4
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmDynamicNavigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
$autoload = '';


if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    //Get the composer autoloader from vendor folder as a standalone module
    $autoload = __DIR__ . '/../../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../../../autoload.php')) {
    //Get the composer autoloader when you're in the vendor folder
    $autoload = __DIR__ . '/../../../../autoload.php';
}

if (empty($autoload)) {
    trigger_error(
        'Please make sure to run composer install before running unit tests',
        E_USER_ERROR
    );
}

require_once $autoload;
