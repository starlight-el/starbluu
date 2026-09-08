<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('created_at')->get();

        return view('admin.admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $admin->role = 'admin';
        $admin->save();

        return redirect()->route('admin.admins.index')->with('info', 'Data Admin berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $admin)
    {
        abort_if($admin->role !== 'admin', 404);

        return view('admin.admin.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $admin)
    {
        abort_if($admin->role !== 'admin', 404);

        return view('admin.admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $admin)
    {
        abort_if($admin->role !== 'admin', 404);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('info', 'Data Admin berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $admin)
    {
        abort_if($admin->role !== 'admin', 404);

        $totalAdmin = User::where('role', 'admin')->count();

        if ($totalAdmin <= 1) {
            return back()->with('error', 'Data Admin terakhir tidak bisa dihapus.');
        }

        $isSelf = $admin->id === Auth::id();

        $admin->delete();

        if ($isSelf) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('admin.login')->with('info', 'Akun Anda berhasil dihapus, silakan login ulang.');
        }

        return redirect()->route('admin.admins.index')->with('info', 'Data Admin berhasil dihapus.');
    }
}
