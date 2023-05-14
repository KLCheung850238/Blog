<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;
class Category extends Model {

    protected $table = 'categories';

    protected $primaryKey = 'cate_id';


    
    public function articles()
    {
        return $this->hasMany('Article');
    }

    

}



?>