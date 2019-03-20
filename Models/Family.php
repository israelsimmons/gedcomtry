<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model {

    protected $fillable = ['gid', 'husb', 'wife'];

    public $timestamps = false;

    public function father() {
        return $this->hasOne('Models\Person', 'gid', 'husb');
    }

    public function mother() {
        return $this->hasOne('Models\Person', 'gid', 'wife');
    }

    public function siblings() {
        return $this->hasMany('Models\Child', 'gid', 'gid');
    }
}
