<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return view('auth.user', [
            'user' => $user
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->get('username');
        $user->email = $request->get('email');
        $user->department = $request->get('department');
        $user->password = bcrypt('password');
        $user->assignRole($request->get('roles') == 'admin' ? 'admin' : 'user');
        $user->save();

        return redirect()->route('users.index')->with('success', 'User Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name')->all();
        $userRole = $user->roles->pluck('name')->all();

        return view('auth.show', compact('user', 'roles', 'userRole'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name')->all();
        $userRole = $user->roles->pluck('name')->all();

        return view('auth.edituser', compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->department = $request->get('department');
        $user->save();

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->get('roles') == 'admin' ? 'admin' : 'user');

        return redirect()->route('users.index')->with('success', 'User Berhasil Diubah!!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
            $user->removeRole('admin', 'user', 'manager');
        }

        return redirect()->route('users.index')->with('success', 'User Berhasil Dihapus!!');
    }

    public function changePassword(Request $request, string $id)
    {
        $userChange = User::findOrFail($id);
        $userChange->password = $request->get('pass');
        $userChange->save();

        return redirect()->route('users.index')->with('success', 'Kata Sandi berhasil diubah');
    }
}
