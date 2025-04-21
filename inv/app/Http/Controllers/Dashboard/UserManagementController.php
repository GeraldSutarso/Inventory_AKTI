<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
public function index(Request $request)
{
    $roles = ['admin', 'officer', 'head', 'sarpras'];

    $query = User::query();

    // Filter by role (if selected)
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // Search by name, email, or role (case-insensitive partial match)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('role', 'like', "%{$search}%");
        });
    }

    // Get paginated results
    $users = $query->orderBy('name')->paginate(10)->withQueryString();

    return view('dashboard.user.index', compact('users', 'roles'))
        ->with('currentRole', $request->role)
        ->with('search', $request->search);
}


    public function create()
    {
        $roles = ['admin', 'officer', 'head', 'sarpras'];
        return view('dashboard.user.input', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required|in:admin,officer,head,sarpras'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('message', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $roles = ['admin', 'officer', 'head', 'sarpras'];
        $user = User::findOrFail($id);
        return view('dashboard.user.update', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,officer,head,sarpras',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('message', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function uploadTtd(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ttd' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::findOrFail($request->user_id);

        // Delete old
        if ($user->ttd && file_exists(public_path('users/' . $user->id . '/ttd/' . basename($user->ttd)))) {
            unlink(public_path('users/' . $user->id . '/ttd/' . basename($user->ttd)));
        }

        $slug = Str::slug($user->name);
        $ext = $request->file('ttd')->getClientOriginalExtension();
        $fileName = "ttd_{$slug}_{$user->role}.{$ext}";

        $ttdDir = public_path('../public_html/users/' . $user->id . '/ttd');
        if (!file_exists($ttdDir)) mkdir($ttdDir, 0755, true);

        $request->file('ttd')->move($ttdDir, $fileName);

        $user->ttd = 'users/' . $user->id . '/ttd/' . $fileName;
        $user->save();

        return redirect()->back()->with('message', 'TTD berhasil diupload');
    }
}
