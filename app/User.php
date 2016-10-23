<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function removeRole(Role $role) {
        if ($this->hasRole($role)) {
            $result = $this->roles()->detach($role);
            return $result;
        }
        return false;
    }

    public function assignRole(Role $role) {
        if (!$this->hasRole($role)) {
            return $this->roles()->save($role);
        }
        return false;
    }

    public function hasRole($role) {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        } 
        return !! $this->roles->intersect([$role])->count();
    }
}
