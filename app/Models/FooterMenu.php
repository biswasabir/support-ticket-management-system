<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterMenu extends Model
{
    use HasFactory;

    protected $table = "footer_menu";

    protected $fillable = [
        'name',
        'link',
        'sort_id',
    ];
}
