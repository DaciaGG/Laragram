<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
      Funtion that validate a comment's format and save it in the database.
     */
    public function save(Request $request) {
        //Validation
        $validate = $this->validate($request, [
            'image_id' => 'integer|required',
            'content' => 'string|required'
        ]);

        //receive data 
        $user = \Auth::user();
        $image_id = $request->input('image_id');
        $content = $request->input('content');

        //asign values to a new objet Comment
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->image_id = $image_id;
        $comment->content = $content;

        //save it in the database
        $comment->save();

        //redirection
        return redirect()->route('image.detail', ['id' => $image_id])
                        ->with([
                            'message' => 'Comentario  publicado correctamente'
        ]);
    }

    /**
      Function that delete a commentari if the user is the commentary's ouwner or the image's one.
     */
    public function delete($id) {
        //get data of the logged user
        $user = \Auth::user();

        //get objet from comment
        $comment = Comment::find($id);

        //verify owner of commentary or image
        if ($user && ($comment->user_id == $user->id|| $comment->image->user_id == $user->id)) {
            $comment->delete();
            return redirect()->route('image.detail', ['id' => $comment->image->id])
                            ->with([
                                'message' => 'Comentario  borrado correctamente'
            ]);
        } else {
            return redirect()->route('image.detail', ['id' => $comment->image->id])
                            ->with([
                                'message' => 'el comentario no se ha eliminado'
            ]);
        }
    }

}
