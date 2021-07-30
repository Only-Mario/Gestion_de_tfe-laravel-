<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TfeRequest;
use App\Models\Document;
use App\Models\Tfe;
use App\Models\User;
use Illuminate\Support\Arr;
use PhpParser\Comment\Doc;
use Illuminate\Support\Facades\Storage;


class TfeController extends Controller
{
    public function student(){
        $users=User::get();
        return view('admin.store', compact('users'));
    }

    public function index(){
        $years = Tfe::years();
        $tfes = Tfe::orderByDate()->get();
        return view('admin.home', compact('tfes', 'years'));
    }

    public function showDashboard(){
        $tfes0 = Tfe::where(['status'=>0])->get();
        $tfes1 = Tfe::where(['status'=>1])->get();
        $tfes2 = Tfe::where(['status'=>2])->get();

        return view('admin.dashboard', compact('tfes0','tfes2','tfes1'));
    }
    
}

