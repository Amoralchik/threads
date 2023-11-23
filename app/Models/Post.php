<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_post_id', 'author', 'author_email', 'content', 'attached_file', 'id'
    ];

    protected $attributes = [
        'author' => 'Anonymous',
    ];


    public function children()
    {
        return $this->hasMany(Post::class, 'parent_post_id', 'id');
    }

    public function descendants()
    {
        return $this->children()->with('descendants');
    }
}
