<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function upload(){
            $file = Document::all(); // Fetch all documents from the database
            return view('upload', compact('file'));
    }

    public function index()
    {
        $file = Document::all(); 
        return view('welcome', compact('file'));
    }

    public function store(Request $request){
        $request->validate([
            'path' => 'required',
        ]);

        foreach($request->file('path') as $key => $value){
            if(Auth::id()){
                $user_id=Auth::user()->id;
               } 
            $originalName = $value->getClientOriginalName();
            $fileName = $originalName;
            $value->storeAs('public/documents', $fileName);
            
            $file = new Document;
            $file->user_id = $user_id ?? null;
            $file->fileName = $originalName;
            $file->path = 'public/documents/' . $fileName;
            $file->size = $value->getSize();
            $file->save();
    }
    return redirect()->back()->with('message','Document uploaded successfully');
}

public function delete_file($id){

    $data=Document::find($id);

    $data->delete();

    //$data->save();

    return redirect()->back()->with('message','Document deleted successfully');
}

}