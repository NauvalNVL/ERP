<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error in UserController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengambil data users');
        }
    }

    public function create()
    {
        try {
            return view('users.create');
        } catch (\Exception $e) {
            Log::error('Error in UserController@create: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuka form create');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|unique:users',
            'username' => 'required|unique:users',
            'official_name' => 'required',
            'password_expiry_date' => 'integer|min:0',
            'status' => 'required|in:A,O',
            'amend_expired_password' => 'required|in:Yes,No'
        ]);

        $user = User::create([
            'user_id' => $validated['user_id'],
            'username' => $validated['username'],
            'official_name' => $validated['official_name'],
            'official_title' => $request->official_title,
            'mobile_number' => $request->mobile_number,
            'official_tel' => $request->official_tel,
            'password' => bcrypt('temporary_password'), // Password default
            'status' => $validated['status'],
            'password_expiry_date' => $validated['password_expiry_date'],
            'amend_expired_password' => $validated['amend_expired_password']
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User '.$user->user_id.' berhasil dibuat');
    }

    public function edit(User $user)
    {
        try {
            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error in UserController@edit: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuka form edit');
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'role' => ['required', Rule::in(['admin', 'manager', 'user'])],
                'status' => ['required', Rule::in(['active', 'inactive'])]
            ];

            if ($request->filled('password')) {
                $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            }

            $validated = $request->validate($rules);

            // Update basic info
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->role = $validated['role'];
            $user->status = $validated['status'];

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            Log::info('User updated successfully', ['user_id' => $user->id]);
            return redirect()->route('users.index')
                ->with('success', 'User berhasil diperbarui');

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) { // MySQL error code for duplicate entry
                return back()
                    ->withInput()
                    ->withErrors(['email' => 'Email sudah digunakan.']);
            }
            
            Log::error('Database error in UserController@update: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan database saat memperbarui user.']);
        } catch (\Exception $e) {
            Log::error('Error in UserController@update: ' . $e->getMessage());
            return back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui user.']);
        }
    }

    public function destroy(User $user)
    {
        try {
            // Prevent self-deletion
            if ($user->id === Auth::id()) {
                return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }

            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error in UserController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus user');
        }
    }

    public function showAmendForm(Request $request)
    {
        $user = null;
        
        if($request->has('search_user_id')) {
            $user = User::where('user_id', $request->search_user_id)->first();
            
            if(!$user) {
                return redirect()->route('users.amend-password')
                    ->with('error', 'User ID tidak ditemukan');
            }
        }
        
        return view('users.amend', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('user_id', $request->user_id)->first();
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);

        return redirect()->route('users.amend-password')
            ->with('success', 'Password berhasil diperbarui untuk user: '.$user->user_id);
    }
}


