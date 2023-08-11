<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $user;
    public function __construct(User $user) {
        $this->user == $user;
    }
    public function index()
    {
        return view('dashboard.users.index');
    }

    public function create()
    {
        return view('dashboard.users.add');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getUsersDatatable()
    {
        if (auth()->user()->can('viewAny' , $this->user)) {
            $data = User::select('*');

        }else {
            $data = User::where('id' , auth()->user()->id);
        }
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $btn = '';
            if (auth()->user()->can('update', $row)) {
                $btn .= '<a href="' . Route('dashboard.users.edit', $row->id) . '"  class="edit btn btn-success btn-sm" ><i class="fa fa-edit"></i></a>';
            }
            if (auth()->user()->can('delete', $row)) {
                $btn .= '

                    <a id="deleteBtn" data-id="' . $row->id . '" class="edit btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
            }
            return $btn;
        })
        ->addColumn('status' , function($row){
            return $row->status == null? __('words.not activated') : $row->status;
        })
        ->rawColumns(['action','status'])
        ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('update', $this->user);
        $data = [
            'name' => 'required|string',
            'status' => 'nullable|in:null,admin,writer',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ];
        $validatedData = $request->validate($data);
        $user = User::create([
            'name' => $request->name,
            'status' => $request->status,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->status = $request->input('status');
        $user->save();
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('dashboard.users.edit' , compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update', $this->user);
        $user = User::find($id);
        $user->update($request->all());
        $user->status = $request->input('status');
        $user->save();
        return redirect()->route('dashboard.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete(Request $request)
    {
        $this->authorize('delete', $this->user);
        if (is_numeric($request->id)) {
            User::where('id' , $request->id)->delete();
        }
        return redirect()->route('dashboard.users.index');
    }
}