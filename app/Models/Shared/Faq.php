<?php

namespace App\Models\Shared;

use Database\Factories\Shared\FaqFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'shared_faqs';

    protected $fillable = [
        'question',
        'answer',
        'is_published',
        'sort',
    ];

    protected static function newFactory(): FaqFactory
    {
        return FaqFactory::new();
    }
}
