<?php

namespace App\Http\Controllers;
use App\Http\Requests\TfeRequest;
use App\Models\Document;
use App\Models\Tfe;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class TfeController extends Controller
{
    //

    public function index(){
        $years = Tfe::years();
        $tfes = Tfe::orderByDate()->get();
        return view('welcome', compact('tfes', 'years'));
    }
    
    
    public function create(Guard $auth){

        $years = Tfe::years();
        if (Auth::guard('admin')->check() || $auth->check()) {
            return view('tfe.create', compact('years'));
        }
    }


    public function store(TfeRequest $request){


        $name = Document::getName($request);
        
        // store the file into directory
        $path = $request->file('document')->storeAs('public/tfe_documents',$name);

        // store in file infos into the database
        Document::create([
            'name' => $name,
            'path' => $path,
        ]);

        $id = Arr::last(Document::select('id')->get()->toArray())['id'];
    
        // store the tfe into DB
        $data = $request->all();
        $data = Arr::add($data, 'document_id', $id);
        Tfe::create($data);

        return redirect(route('tfe.index'))->with('success', 'Tfe bien ajouté');
    }


    public function show($id, Guard $auth){

        if ($auth->check()) {
            $document_id = Arr::first(Tfe::where('document_id', $id)->select('document_id')->get()->toArray())['document_id'];
           
            $path = Arr::first(Document::where('id', $document_id)->select('path')->get()->toArray())['path'];
            
            $path = Storage::path($path);

            header('Content-type: application/pdf');
            readfile($path);
        }
        return view('tfe.unauthorize');
    
    }


    public function edit($id){
        $years = Tfe::years();       
        $tfe = Tfe::findOrFail($id);
        return view('tfe.edit', compact('years', 'tfe'));
    }


    public function update(TfeRequest $request, $id){
        
        // $document = Document::findOrFail($id);
        // $name = Document::name($request);
        // $path = $request->file('document')->storeAs('public/tfe_documents',$name);
        // $document->update(['name' => $name, 'path' => $path]);

        $tfe = Tfe::findOrFail($id);
        $tfe->update($request->all());
        return redirect(route('welcome'))->with('success', 'Tfe bien modifié !');
    }


    public static function destroy($id,$call=false){
    

        // on prend l'id du document
        $document_id = Arr::first(Tfe::where('id', $id)->select('document_id')->get()->toArray())['document_id'];
    
        // suppression du fichier
        $path = Arr::first(Document::where('id', $document_id)->select('path')->get()->toArray())['path'];
        $path = Storage::path($path);
        
        unlink($path);

        // suppression du document de la base de donnée
        $document = Document::findOrFail($document_id);
        $document->delete();

        // suppression de tfe
        $tfe = Tfe::findOrFail($id);
        $tfe->delete();

        //si la fonction n'a pas été appelée par une autre 
        if($call==false){
             return redirect(route('welcome'))->with('success', 'Le tfe a été bien supprimé.');
        }
       

    }
}
