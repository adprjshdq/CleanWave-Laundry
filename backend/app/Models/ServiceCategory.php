<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'icon', 'description', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function services()
    {
        return $this->hasMany(Service::class, 'category_id');
    }

    public function activeServices()
    {
        return $this->hasMany(Service::class, 'category_id')->where('is_active', true);
    }
}
