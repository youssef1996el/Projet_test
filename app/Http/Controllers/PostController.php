<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use DB;
class PostController extends Controller
{
    public function index()
    {

        $Users =User::all();
        return view('Post.index')->with('users',$Users);
    }
    public function getPost()
    {
        $Data = DB::table('posts')
        ->join('users','users.id','=','posts.iduser')
        ->select('posts.*','users.name')
        ->get();

        return response()->json([
            'statut'        =>200,
            'data'          =>$Data,
        ]);
    }

    public function StorePost(Request $request)
    {
        $validatorData =Validator::make($request->all(),[
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255', ],
            'iduser' => ['required'],
        ]);
        if ($validatorData->fails()) {
            return redirect()->back()->withErrors($validatorData)->withInput();
        }
        $Post = Post::create([
            'title'                 => $request->title,
            'content'               => $request->content,
            'iduser'                => $request->iduser
        ]);
       return redirect()->back();
    }

    public function updatePost(Request $request)
    {

        $validatorData =Validator::make($request->all(),[
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255', ],

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
            $users = Post::where('id','=',$request->id)->update([
                'title' => $request->title,
                'content' => $request->content,
                'iduser' => $request->iduser,
            ]);
            return response()->json([
                'statut'    =>200,
            ]);
        }

    }
    public function DeletePost(Request $request)
    {
        $user = Post::where('id', $request->id)->delete();
        return response()->json([
            'statut'    =>200,
        ]);
    }

}
