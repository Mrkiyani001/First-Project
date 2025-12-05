<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "post";
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'created_by',
        'updated_by',
        'score',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function attachments()
    {
        return $this->morphMany(Attachments::class, 'attachable');
    }
    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id');
    }
    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactionable');
    }
}
