<?php

/**
 * Configure manually all the routes here
 *
 * use .+ when expecting a parameter
 *
 * @author Vince Urag
 */

$route['/dogs'] = "dogs";
$route['/about'] = "test";
$route['/about/name/.+'] = "test/name";
$route['about/name/.+/age/.+'] = "test/nameage";
