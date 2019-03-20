<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model {

    protected $fillable = ['gid', 'chil'];

    public $timestamps = false;

    public function family() {
        return $this->hasOne('Models\Family', 'gid', 'gid');
    }

    public function person() {
        return $this->hasOne('Models\Person', 'gid', 'chil');
    }
}
