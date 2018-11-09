<?php
//Autoload
$loader = require 'vendor/autoload.php';

//Initialize Object
$app = new \Slim\Slim(array(
    'templates.path' => 'templates'
));

//Listing all person
$app->get('/person/', function() use ($app){
	(new \controllers\Person($app))->list();
});

//Get Person
$app->get('/person/:id', function($id) use ($app){
	(new \controllers\Person($app))->get($id);
});

//New Person
$app->post('/person/', function() use ($app){
	(new \controllers\Person($app))->create();
});

//Edit Person
$app->put('/person/:id', function($id) use ($app){
	(new \controllers\Person($app))->edit($id);
});

//Delete Person
$app->delete('/person/:id', function($id) use ($app){
	(new \controllers\Person($app))->delete($id);
});

//Run Slim Application
$app->run();