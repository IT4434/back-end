<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get admins of the role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admins(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Admin::class);
    }

    /**
     * Get permissions of the role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
