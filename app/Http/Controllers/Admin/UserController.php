<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\DataTableHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function data(Request $request)
    {
        $query = User::query()->where('role', '!=', 'admin');

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        return DataTableHelper::respond(
            $request,
            $query,
            ['name', 'email', 'role', 'status'],
            function (User $u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'phone' => $u->phone,
                    'role' => $u->role,
                    'status' => $u->status,
                    'created_at' => $u->created_at->format('d M Y'),
                ];
            }
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['sales_manager', 'project_manager'])],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = $request->user()->id;

        $user = User::create($data);

        return response()->json(['message' => 'User bana diya gaya hai.', 'user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['sales_manager', 'project_manager'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return response()->json(['message' => 'User update ho gaya hai.', 'user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User delete kar diya gaya hai.']);
    }
}
