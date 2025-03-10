<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $users = User::select('id', 'name', 'email', 'role')
        ->when($user->role === 'admin', function ($query) use ($user) {
            $query->where('id', '<>', $user->id);
        })
        ->when($user->role === 'manager', function ($query) use ($user) {
            $query->where('added_by', $user->id);
        })->get();
        
        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.users.add-edit-user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
       
        User::create(array_merge(
            $request->validated(), 
            ['added_by' => auth()->id()] // Automatically assign the logged-in user
        ));

        return redirect()->route('users.index')->with('msg', 'User added successfully!');
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
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.add-edit-user',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('msg', 'User Deleted Successfully!');   
    }
}
