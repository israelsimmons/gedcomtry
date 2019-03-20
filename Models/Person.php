<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model {

    protected $fillable = ['name', 'sex', 'uid', 'gid'];

    public $timestamps = false;

    public function children() {
        return $this->belongsTo('Models\Child', 'gid', 'chil');
    }
}
