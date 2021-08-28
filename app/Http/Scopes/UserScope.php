<?php

namespace App\Http\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UserScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('user_id', '=', Auth::id());
    }
}
