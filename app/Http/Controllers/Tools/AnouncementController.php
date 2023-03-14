<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Anouncement;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnouncementController extends Controller {
    public function index() {
        $data = Anouncement::where('created_by', Auth()->user()->uuid)->paginate(5);
        return view('tools.announcement.index',[
            'title' => 'Announcement',
            'data' => $data,
        ]);
    }

    public function create() {
        $targetRole = Role::all();
        return view('tools.announcement.add', [
            'targetRole' => $targetRole,
        ]);
    }

    public function store(Request $request) {
        $trl = $request->targetRole;
        $trl = implode(',',$trl);
        $data = [
            'headline' => $request->headline,
            'message' => $request->message,
            'targetRole' => $trl,
            'created_by' => Auth::user()->uuid,
            'created_at' => now(),
            'updated_by' => Auth::user()->uuid,
            'updatesed_at' => now(),
            'deleted_by' => Auth::user()->uuid,
            'deleted_at' => now(),
        ];

        Anouncement::create($data);
        return redirect()->route('anouncement.index')->with('status', 'Succesfully Created Anouncement');
    }

    public function edit($id) {
        $data = Anouncement::where('id', $id)->first();
        if($data->created_by == Auth::user()->uuid) {
            $targetRole = Role::all();
            return view('tools.announcement.edit', [
                'data' => $data,
                'targetRole' => $targetRole,
            ]);
        }
    }
    public function update(Request $request, $id) {
        $trl = $request->targetRole;
        $trl = implode(',',$trl);
        $data = [
            'headline' => $request->headline,
            'message' => $request->message,
            'targetRole' => $trl,
            'created_by' => Auth::user()->uuid,
            'updated_by' => Auth::user()->uuid,
            'updated_at' => now(),
            'deleted_by' => Auth::user()->uuid,
            'deleted_at' => now(),
        ];

        $save = Anouncement::where('id', $id)->first();
        if($save->created_by == Auth::user()->uuid) {
            $save->update($data);
            return redirect()->route('anouncement.index')->with('status', 'Succesfully Updated Anouncement');
        }
    }
    public function show($id) {
        $data = Anouncement::where('id', $id)->get();
        if($data->created_by == Auth::user()->uuid){
            return $data;
        }
    }
    public function destroy($id) {
        Anouncement::where('id', $id)->delete();
        return redirect()->route('anouncement.index');
    }
}
?>