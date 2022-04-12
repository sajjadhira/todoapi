<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todos;
use Illuminate\Support\Facades\Auth;

class TodosController extends Controller
{
    public function index(Request $request){
        
        $page =1;
        $skip = 0;
        $take = 10;
        $item_per_page = 10;

        if($request->has('page')){
            $page = $request->page;
            if($page <1 || $page == ""){
                $page =1;
            }

            $take = $page*$item_per_page;
            if($page>1){
                $skip = ($page-1)*$item_per_page;
            }
        }

        if($skip>0){
            $allCoins = Todos::where('user',Auth::user()->id)->skip($skip)->take($take)->orderBy('id','DESC')->get();
        }else{
            $allCoins = Todos::where('user',Auth::user()->id)->take($take)->orderBy('id','DESC')->get();
        }

        return response()->json($allCoins);


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


        $request->validate([
            'name' => 'required',
        ]);

        // return $request->name;
    
        $todo = Todos::find($id);
        $todo->name = $request->name;
        $todo->save();
        return $todo;
    }
    public function destroy($id){
        return Todos::destroy($id);
    }


    public function search($q){
        return Todos::where('name','like', '%'. $q . '%')->orWhere('symbol','like', '%'.$q.'%')->orWhere('address','like', '%' . $q . '%')->get();
    }
}
