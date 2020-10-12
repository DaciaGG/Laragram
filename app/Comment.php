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
    
    //Many to One - Relationship between ( model(Comment) -> model(User) )
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    
    //Many to One - Relationship between ( model(Comment) -> model(Image) )
    public function image(){
        return $this->belongsTo('App\Image', 'image_id');
    }
}
