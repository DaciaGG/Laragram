<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Image;
use App\Comment;
use App\Like;

class ImageController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
      Funtion that return the view to create a new image.
     */
    public function create() {
        return view('image.create');
    }

    /**
      Funtion that return the view to save a new image  in the database.
     */
    public function save(Request $request) {
        //Validation
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'required|image'
        ]);


        //collect data
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        //asign values to objects
        $user = \Auth::user();
        $image = new Image;
        $image->user_id = $user->id;
        $image->image_path = null;
        $image->description = $description;

        //upload File
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }

        //save object in database
        $image->save();

        return redirect()->route('home')->with([
                    'message' => 'Foto subida correctamente'
        ]);
    }

    /**
      Funtion that return the image to show at dashboard.
     */
    public function getImage($filename) {
        $file = Storage::disk('images')->get($filename);
        return new Response($file, 200);
    }

    /**
     * Funtion that return an image to show all its details.
     */
    public function detail($id) {
        $image = Image::find($id);

        return view('image.detail', [
            'image' => $image
        ]);
    }

    /**
     * Funtion that manage the owner user of an image to delete it.
     */
    public function delete($id) {
        $user = \Auth::user();
        $image = Image::find($id);
        $comments = Comment::where('image_id', $id)->get();
        $likes = Like::where('image_id', $id)->get();

        if ($user && $image && $image->user->id == $user->id) {
            //delete comments
            if ($comments && count($comments) >= 1) {
                foreach ($comments as $comment) {
                    $comment->delete;
                }
            }

            //deletes likes
            if ($likes && count($likes) >= 1) {
                foreach ($likes as $like) {
                    $like->delete;
                }
            }

            //delete the image
            Storage::disk('images')->delete($image->image_path);

            //delete the image's register
            $image->delete;

            $message = array('message' => 'La imagen se ha borrado correctamente');
            return redirect()->route('home')->with($message);
        } else {
            $message = array('message' => 'La imagen no se ha borrado correctamente');
        }
    }

    /**
     * Funtion that edit an image.
     */
    public function edit($id) {
        $user = \Auth::user();
        $image = Image::find($id);
        if ($user && $image && $image->user->id == $user->id) {
            return view("image.edit", [
                'image' => $image
            ]);
        } else {
            return redirect()->route('home');
        }
    }

    /**
     * Funtion that update an image.
     */
    public function update(Request $request) {
        //Validation
        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'image'
        ]);

        $image_id = $request->input('image_id');
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        //get objet Image from database
        $image = Image::find($image_id);
        $image->description = $description;
        
        //upload File
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            $image->image_path = $image_path_name;
        }
        
        //update objet
        $image->update();
        
        return redirect()->route('image.detail',['id' => $image_id])
                ->with(['message'=>"Foto actualizada correctamente"]);
    }

}
