<?php

/**
 * Configure manually all the routes here
 *
 * use .+ when expecting a parameter
 *
 * @author Vince Urag
 */

$route['/'] = "/";
$route['/dogs'] = "dogs";
$route['/about'] = "test";
$route['/about/name/:param'] = "test/name";
$route['about/name/:param/age/:param'] = "test/nameage";
