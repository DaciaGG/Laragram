<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
/* To get acces to the table 'Image' */

use App\Image;

Route::get('/', function () {
/*
    $images = Image::all();
    foreach ($images as $image) {
        echo $image->image_path . "<br/>";
        echo $image->description . "<br/>";
        echo $image->user->name . ' ' . $image->user->surname ;

        if (count($image->comments)>=1) {
            echo "<h4> Commentarios </h4>";
            foreach ($image->comments as $comment) {
                echo $comment->user->name . ' ' . $comment->user->surname . " dice :".$comment->content. "<br/>";
            }
        }
        
        echo 'LIKES: '.count($image->likes);
        echo "<hr/>";
    }
    die();
*/
    return view('welcome');
});


Auth::routes();


//GENERAL ROUTES
Auth::routes(); //Authorisation route
Route::get('/', 'HomeController@index')->name('home');

//USERS ROUTES
Route::get('/configuracion', 'UserController@config')->name('config');
Route::post('/user/update', 'UserController@update')->name('user.update');
Route::get('/configuracionContrasena', 'UserController@configPassword')->name('configPassword');
Route::post('/user/updatePassword', 'UserController@updatePassword')->name('user.updatePassword');
Route::get('/user/avatar/{filename}', 'UserController@getImage')->name('user.avatar');
Route::get('/perfil/{id}', 'UserController@profile')->name('profile');
Route::get('/gente/{search?}', 'UserController@index')->name('user.index');

//IMAGE ROUTES
Route::get('/subir-imagen', 'ImageController@create')->name('image.create');
Route::post('/image/save', 'ImageController@save')->name('image.save');
Route::get('/image/file/{filename}', 'ImageController@getImage')->name('image.file');
Route::get('/imagen/{id}', 'ImageController@detail')->name('image.detail');
Route::get('/image/delete/{id}', 'ImageController@delete')->name('image.delete');
Route::get('/imagen/editar/{id}', 'ImageController@edit')->name('image.edit');
Route::post('/image/update', 'ImageController@update')->name('image.update');

//COMMENT ROUTES
Route::post('/comment/save', 'CommentController@save')->name('comment.save');
Route::get('/comment/delete/{id}', 'CommentController@delete')->name('comment.delete');

//LIKE ROUTES
Route::get('/like/{image_id}', 'LikeController@like')->name('like.save');
Route::get('/dislike/{image_id}', 'LikeController@dislike')->name('like.delete');
Route::get('/likes', 'LikeController@index')->name('likes');



