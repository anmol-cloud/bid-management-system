<?php

namespace App\Http\Controllers\SalesManager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\DataTableHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProjectManagerController extends Controller
{
    public function index()
    {
        return view('sales-manager.project-managers.index');
    }

    public function data(Request $request)
    {
        $query = User::query()
            ->where('role', 'project_manager')
            ->where('created_by', $request->user()->id);

        return DataTableHelper::respond(
            $request,
            $query,
            ['name', 'email', 'status'],
            function (User $u) {
                return [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'phone' => $u->phone,
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
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'project_manager';
        $data['created_by'] = $request->user()->id;

        $pm = User::create($data);

        return response()->json(['message' => 'Project Manager add ho gaya hai.', 'user' => $pm]);
    }

    public function update(Request $request, User $projectManager)
    {
        abort_unless($projectManager->created_by === $request->user()->id, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($projectManager->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $projectManager->update($data);

        return response()->json(['message' => 'Project Manager update ho gaya hai.']);
    }

    public function destroy(Request $request, User $projectManager)
    {
        abort_unless($projectManager->created_by === $request->user()->id, 403);

        $projectManager->delete();

        return response()->json(['message' => 'Project Manager delete kar diya gaya hai.']);
    }
}
