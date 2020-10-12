<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use \App\User;

class UserController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Function to get a list of users & search fonctionality.
     */
    public function index($search = null) {
        if (!empty($search)) {
            $users = User::where('nick', 'LIKE', '%' . $search . '%')
                    ->orWhere('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('surname', 'LIKE', '%' . $search . '%')
                    ->orderBy('id', 'desc')
                    ->paginate(5);
        } else {
            $users = User::orderBy('id', 'desc')->paginate(5);
        }
        return view("user.index", [
            'users' => $users
        ]);
    }

    /**
      Funtion that return the config form.
     */
    public function config() {
        return view('user.config');
    }

    /**
     * Funtion that Update the user's information in the project's db.
     */
    public function update(Request $request) {


        //Get identified user
        $user = \Auth::user();
        $id = $user->id;

        //Get data from form
        $name = $request->input('name');
        $surname = $request->input('surname');
        $nick = $request->input('nick');
        $email = $request->input('email');

        //form validation
        $validate = $this->validate($request, [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'nick' => 'required|string|max:255|unique:users,nick,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id
        ]);

        //asign new values to user's object
        $user->name = $name;
        $user->surname = $surname;
        $user->nick = $nick;
        $user->email = $email;

        //update image
        $image_path = $request->file('image_path');
        if ($image_path) {
            //unique name to the image
            $image_path_name = time() . $image_path->getClientOriginalName();

            //Save image in Storage folder (storage/app/users)
            Storage::disk('users')->put($image_path_name, File::get($image_path));

            //Set image's name to object
            $user->image = $image_path_name;
        }



        //Execute query and changes in db
        $user->update();
        return redirect()->route('config')
                        ->with(['message' => 'Usuario actualizado correctamente']);
    }

    /**
     * Function that return the view to the parameters of new password.
     */
    Public function configPassword() {
        return view('user.passconfig');
    }

    /**
     * Function to manage the changes in a password.
     */
    public function updatePassword(Request $request) {

        if (!(Hash::check($request->get('password'), \Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error", "La contrasena actual no es correcta. Favor de volver a intentar.");
        }

        if (strcmp($request->get('password'), $request->get('newPassword')) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("error", "Nueva y vieja contrasenas no puede ser iguales.");
        }

        $validate = $this->validate($request, [
            'password' => 'required',
            'newPassword' => 'required|string|min:6|confirmed',
        ]);



        $user = \Auth::user();
        $user->password = bcrypt($request->get('newPassword'));
        $user->update();

        return redirect()->back()
                        ->with("success", "Contrasena cambiada correctamente !");
    }

    /**
     * Funtion that returns an image related to the logged user.
     */
    public function getImage($filename) {
        $file = Storage::disk('users')->get($filename);
        return new Response($file, 200);
    }

    /**
     * Funtion that return a view to the user's settings.
     */
    public function profile($id) {
        $user = User::find($id);

        return view('user.profile', [
            'user' => $user
        ]);
    }

}
