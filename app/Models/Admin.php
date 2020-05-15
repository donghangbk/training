<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Admin extends User
{
    protected $table="users";

    public static function boot()
    {
        parent::boot();

        // static::addGlobalScope('admin', function (Builder $builder) {
        //     $builder->where('role_id', User::ROLE_ADMIN);
        // });
    }
}
