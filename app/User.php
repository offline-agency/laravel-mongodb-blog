<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use OfflineAgency\MongoAutoSync\Traits\ModelAdditionalMethod;
use OfflineAgency\MongoAutoSync\Traits\MongoSyncTrait;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class User extends Eloquent implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, Notifiable, MongoSyncTrait, ModelAdditionalMethod;

    protected $collection = 'users';



    protected $items = array(
        'name' => [],
        'surname' => [],
        'email' => [],
        'password' => [],
        'remember_token' => [],
        'email_verified_at' => [
            'is-md' => true
       ],
    );

}
