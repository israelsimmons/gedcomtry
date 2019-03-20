<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

$capsule = new DB;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'gedcom',
    'username'  => 'root',
    'password'  => 'root',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$gid =$_GET['gid'];

if ($gid) {
    $people = Models\Person::where('gid', $gid)->first();
    $family = Models\Child::where('chil', $gid)->first();
    $parents = Models\Family::with('father', 'mother')->where('gid', $family->gid)->first();
    $siblings = Models\Child::with('person')->where('gid', $family->gid)->get();
    $gente = [
        'people' => $people->toArray(),
        'parents' => $parents->toArray(),
        'siblings' => $siblings->toArray(),
    ];
} else {
    $people = Models\Person::with('children')->get();
}


echo json_encode($gente);
