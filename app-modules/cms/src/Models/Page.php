<?php

namespace Kaster\Cms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kaster\Cms\Database\Factories\PageFactory;

class Page extends Model
{
    use HasFactory;

    protected static function newFactory(): PageFactory
    {
        return PageFactory::new();
    }
}
