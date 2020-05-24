<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    protected $fillable = [
        'postId', 'name', 'email', 'body'
    ];
}
