<?php
/**
 * Created by PhpStorm.
 * User: fvasquez
 * Date: 19/11/18
 * Time: 04:30 PM
 */

namespace App\Scopes;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SellerScope implements Scope
{
    public function apply(Builder $builder,Model $model)
    {
        $builder->has('products');
    }
}