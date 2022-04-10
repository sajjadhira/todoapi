<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todos;

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
            $allCoins = Todos::skip($skip)->take($take)->get();
        }else{
            $allCoins = Todos::take($take)->get();
        }

        return response()->json($allCoins);


    }


    public function store(Request $request){

         $request->validate([
            'name' => 'required|unique:todos,name',
        ]);
        
        return Todos::create($request->all());

    }


    public function show($id){
        return Todos::find($id);
    }

    public function update(Request $request, $id){
    
        $coin = Todos::find($id);
        $coin->update($request->all());
        return $coin;
    }
    public function destroy($id){
        return Todos::destroy($id);
    }


    public function search($q){
        return Todos::where('name','like', '%'. $q . '%')->orWhere('symbol','like', '%'.$q.'%')->orWhere('address','like', '%' . $q . '%')->get();
    }
}
