<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavbarMenu extends Model
{
    use HasFactory;

    protected $table = "navbar_menu";

    public function scopeByOrder($query)
    {
        return $query->orderBy('order', 'asc');
    }

    protected $fillable = [
        'name',
        'link',
        'parent_id',
        'order',
    ];

    public function children()
    {
        return $this->hasMany(NavbarMenu::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(NavbarMenu::class, 'parent_id');
    }
}
