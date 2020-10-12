<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Like;

class LikeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * function to show only the images that the logged user has liked.
     *
     * @return void
     */
    public function index() {
        $user = \Auth::user();
        $likes = Like::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->paginate(5);

        return view('like.index', [
            'likes' => $likes
        ]);
    }

    /**
     * function to manage the like fonctionality
     *
     * @return void
     */
    public function like($image_id) {
        //get user and image's data 
        $user = \Auth::user();


        //condition to not duplicate likes
        $isset_like = Like::where('user_id', $user->id)
                ->where('image_id', $image_id)
                ->count();


        if ($isset_like == 0) {
            $like = new Like();
            $like->user_id = $user->id;
            $like->image_id = (int) $image_id;

            //save in database
            $like->save();

            return response()->json([
                        'like' => $like
            ]);
        } else {
            return response()->json([
                        'message' => 'el like ya existe'
            ]);
        }
    }

    /**
     * function to manage the dislike fonctionality
     *
     * @return void
     */
    public function dislike($image_id) {
        //get user and image's data 
        $user = \Auth::user();


        //condition to get the first like
        $like = Like::where('user_id', $user->id)
                ->where('image_id', $image_id)
                ->first();


        if ($like) {
            //delete in database
            $like->delete();

            return response()->json([
                        'like' => $like
            ]);
        } else {
            return response()->json([
                        'message' => 'el like ya NO existe'
            ]);
        }
    }

}
