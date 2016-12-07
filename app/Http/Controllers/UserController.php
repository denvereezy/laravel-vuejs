<?php

namespace App\Http\Controllers;

use App\User;
use Mail;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $users;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $currentId = $request->user()->id;
        $users = $request->user()->where('id', '!=', $currentId)->get();

        return response()->json($users);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $email = $user->email;
        $subject = 'Task App Account';
        $data = ['name' => 'Task App'];                                           //inject variable into the scope of the closure
      Mail::raw($user->name.' your account has been deleted', function ($message) use ($email, $subject) {
          $message->to($email)
                  ->subject($subject);
          $message->from('denverdaniels52@gmail.com', 'Task App');
      });
        $this->authorize('destroy', $user);
        $user->delete();

        return response()->json($user);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json($user);
    }

    public function editUserProfile(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        // $this->validate($request, [
        //   'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        // ]);

        // $imageName = time().'.'.$request->image->getClientOriginalExtension();
        // $imagePath = $request->image->move(public_path('images'), $imageName);
        // $image = str_replace('/home/Projects/denver/public', '', $imagePath);
        $user->update($request->all());
        return response()->json($user);
    }
}
