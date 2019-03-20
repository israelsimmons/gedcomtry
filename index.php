<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

$capsule = new DB;

// Move this to a config file
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

$parser = new \PhpGedcom\Parser();

if (PHP_SAPI == 'cli') {
    $strFile = $argv[1];
} else {
    $strFile = $_GET['file'];
}

$gedcom = $parser->parse($strFile);

$vals = [];

foreach ($gedcom->getFam() as $key => $value) {

    Models\Family::create([
        'gid' => $value->getId(),
        'husb' => $value->getHusb(),
        'wife' => $value->getWife()
    ]);

    foreach ($value->getChil() as $chil) {
        Models\Child::create([
            'gid' => $value->getId(),
            'chil' => $chil
        ]);
    }
}

foreach ($gedcom->getIndi() as $key => $value) {
    // if ($value->getId() == 'I3'){
    //     print_r(current($value->getEven())->getDate());
    //     die();
    // }
    $date = NULL;
    if (current($value->getEven())) {
        $date = current($value->getEven())->getDate();
    }

    $val = [
        'name' => current($value->getName())->getName(),
        'sex' => $value->getSex(),
        'uid' => $date,
        'gid' => $value->getId()
    ];
    // var_dump( current($value->getName())->getName());

    $person = Models\Person::create($val);
}

echo json_encode($vals);
