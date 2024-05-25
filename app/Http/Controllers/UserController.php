<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Redirect;

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        if ($user->hasRole('student')) {
            $user->load(['enrollments', 'enrollments.program']);
        }
        return Inertia::render("Admin/Users/Show", [
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return Redirect::route('admin.users')->with('success', 'User deleted.');
    }
}
