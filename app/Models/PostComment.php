<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'comment',
        'likes'
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function blog(){
        return $this->belongsTo(Blog::class, 'post_id', 'id');
    }
    public function replies(){
        return $this->hasMany(PostComment::class, 'parent_id', 'id');
    }
}
