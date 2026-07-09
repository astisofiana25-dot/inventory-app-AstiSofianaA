<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::whereIn('name', ['admin', 'manager', 'staff'])
            ->orderBy('name')
            ->get();

        $users = User::with('role')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%' . $request->query('q') . '%';
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('employee_id', 'like', $term);
                });
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->whereHas('role', fn ($q) => $q->where('id', $request->query('role')));
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users', 'roles'));
    }

    public function destroy(User $user)
    {
        $sessionDriver = Config::get('session.driver');

        if ($sessionDriver === 'database') {
            DB::table(Config::get('session.table', 'sessions'))
                ->where('user_id', $user->id)
                ->delete();
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
