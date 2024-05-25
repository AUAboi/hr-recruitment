<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->all('search', 'role');

        $users = User::with(['roles'])->orderBy('first_name')
            ->filter($filters)
            ->paginate(10)
            ->withQueryString();


        return Inertia::render("Recruiter/Users/Index", [
            'users' => UserResource::collection($users),
            'roles' => Role::select('name', 'id')->get(),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render("Recruiter/Users/Create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('recruiter.users.index')->with('success', 'Recruiter added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return Redirect::route('recruiter.users.index')->with('success', 'User deleted.');
    }
}
