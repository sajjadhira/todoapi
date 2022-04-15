<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todos;
use Illuminate\Support\Facades\Auth;

class TodosController extends Controller
{
    public function index(Request $request){
        
        $skip = 0;
        $take = $item_per_page = 5;
        $page = 1;

        if($request->has('page')){
            $page = $request->page;
            if($page <1 || $page == ""){
                $page =1;
            }

            $take = $item_per_page;
            if($page>1){
                $skip = ($page-1)*$item_per_page;
            }
        }
        
        $total = Todos::where('user',Auth::user()->id)->get()->count();
        $pages_count = ceil($total/$item_per_page);
        
        if($skip>0){
            $allCoins = Todos::where('user',Auth::user()->id)->skip($skip)->take($take)->orderBy('id','DESC')->get();
        }else{
            $allCoins = Todos::where('user',Auth::user()->id)->take($take)->orderBy('id','DESC')->get();
        }

        return response()->json(['result'=>$allCoins,'count'=>$total,'pages'=>$pages_count,'page'=>$page]);


    }


    public function store(Request $request){

         $request->validate([
            'name' => 'required|unique:todos,name',
        ]);

        $new = new Todos;
        $new->name = $request->name;
        $new->user = Auth::user()->id;
        $new->save();
        
        return $new;

    }


    public function show($id){
        $todo = Todos::find($id);
        if(Auth::user()->id != $todo->user){
            return response()->json(['message' => 'Anauthorize']);
        }

        return $todo;
    }

    public function update(Request $request, $id){


        // $request->validate([
        //     'name' => 'required',
        // ]);

        // return $request->name;
    
        $todo = Todos::find($id);

        if(Auth::user()->id != $todo->user){
            return response()->json(['message'=>'Unauthorize request']);
        }
        if($request->has('name')){
            $todo->name = $request->name;
        }

        if($request->has('status')){
            $todo->status = $request->status;
        }
        $todo->save();
        return $todo;
    }
    public function destroy($id){

        $todo = Todos::find($id);

        if(Auth::user()->id != $todo->user){
            return response()->json(['message'=>'Unauthorize request']);
        }
        
        return Todos::destroy($id);
    }


    public function search($q){
        return Todos::where('name','like', '%'. $q . '%')->orWhere('symbol','like', '%'.$q.'%')->orWhere('address','like', '%' . $q . '%')->get();
    }
}
