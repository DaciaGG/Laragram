<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';
    
    //One to many - Relationship between ( model(Image) -> model(Comment) )
    public function comments(){
        return $this->hasMany('App\Comment')->orderBy('id','desc');
    }
    
    //One to many - Relationship between ( model(Image) -> model(Like) )
    public function likes(){
        return $this->hasMany('App\Like');
    } 
    
    
    //Many to One - Relationship between ( model(Image) -> model(User) )
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
