<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use Redirect;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function GetUser()
    {
        $users = User::all();

        return response()->json([
            'statut'            =>200,
            'data'              =>$users
        ]);
    }

    public function updateUser(Request $request)
    {
        $validatorData =Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', ],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if($validatorData->fails())
        {

            return response()->json([
                'statut'        =>400,
                'errors'        => $validatorData->messages(),
            ]);
        }
        else
        {
            $users = User::where('id','=',$request->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'statut'    =>200,
            ]);
        }

    }

    public function StoreUsers(Request $request)
    {
        $validatorData =Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', ],
            'password' => ['required', 'string', 'min:8'],
        ]);
        if ($validatorData->fails()) {
            return redirect()->back()->withErrors($validatorData)->withInput();
        }

            $user = user::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'password'          => Hash::make($request->password)
            ]);
            return view('home');
    }

    public function checkUser(Request $request)
    {
        $Post = Post::where('iduser','=',$request->iduser)->count();

        if($Post == 0)
        {
            return response()->json([
                'statut'    =>200,
            ]);
        }
        else
        {
            return response()->json([
                'statut'    =>400,
            ]);
        }


    }

    public function DeleteUser(Request $request)
    {
        $user = User::where('id', $request->id)->delete();
        return response()->json([
            'statut'    =>200,
        ]);
    }
}
