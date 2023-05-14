<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $table = 'articles';

    protected $primaryKey = 'article_no';

    public $timestamps = false;

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

}



?>