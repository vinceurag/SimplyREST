<?php

/**
 * Configure manually all the routes here
 *
 * use .+ when expecting a parameter
 *
 * @author Vince Urag
 */

$route['/'] = "index";
$route['/users'] = "users";
$route['/about'] = "test";
$route['/users/:param/edit'] = "users/edit";
$route['/users/signin'] = "users/signin";
$route['about/name/:param/age/:param'] = "test/nameage";
