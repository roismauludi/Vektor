<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function user()
    {
        $users = User::whereDoesntHave('roles', fn ($q) => $q->where('name', 'Admin'))->get();
        return view('User.index', compact('users'));
    }
    
    public function base()
    {
        return view('base.base');
    }

    public function showregistrationform()
    {
        return view('User.create_user');
    }

    public function register(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'jabatan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'lokasi_penugasan' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:security,PIC,view',
        ]);

        $user = User::create([
            'nama' => $validate['nama'],
            'username' => $validate['username'],
            'email' => $validate['email'],
            'jabatan' => $validate['jabatan'],
            'divisi' => $validate['divisi'],
            'lokasi_penugasan' => $validate['lokasi_penugasan'],
            'password' => Hash::make($validate['password'])
        ]);

        // Assign role (kecuali Admin) jika dipilih
        if (!empty($validate['role'])) {
            $user->assignRole($validate['role']);
        }

        return redirect()->route('user')->with('success', 'User Berhasil Ditambahkan');
    }

    public function detailUser($id)
    {
        $user = User::findOrFail($id);
        return view('User.detail_user', compact('user'));
    }

    public function EditUser($id)
    {
        $user = User::findOrFail($id);
        return view('User.edit_user', compact('user'));
    }

    public function updateuser(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'jabatan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'lokasi_penugasan' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string|in:Admin,security,PIC,view',
        ]);

        $user = User::findOrFail($request->id);
        
        $updateData = [
            'nama' => $validate['nama'],
            'username' => $validate['username'],
            'email' => $validate['email'],
            'jabatan' => $validate['jabatan'],
            'divisi' => $validate['divisi'],
            'lokasi_penugasan' => $validate['lokasi_penugasan'],
        ];

        if (!empty($validate['password'])) {
            $updateData['password'] = Hash::make($validate['password']);
        }

        $user->update($updateData);

        // Update role jika ada
        if (isset($validate['role'])) {
            $user->syncRoles([$validate['role']]);
        }

        return redirect()->route('user.detail', $user->id)->with('success', 'User Berhasil Diupdate');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
 
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Simpan waktu aktivitas terakhir saat login
            session(['last_activity_time' => now()->toDateTimeString()]);
            // Simpan informasi apakah user menggunakan remember me
            session(['remember_me' => $remember]);
            
            session()->flash('success', 'Berhasil login!');
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

     /**
     * Halaman edit profil user oleh Admin/PIC (edit data user lain).
     */
    public function profil($id)
    {
        $user = User::findOrFail($id);
        return view('User.profil', compact('user'));
    }

    /**
     * Update profil user oleh Admin/PIC.
     */
    public function updateprofil(Request $request)
    {
        $validate = $request->validate([
            'id' => 'required|exists:users,id',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'jabatan' => 'nullable|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'lokasi_penugasan' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($request->id);

        $user->email = $validate['email'];
        $user->jabatan = $validate['jabatan'] ?? null;
        $user->divisi = $validate['divisi'] ?? null;
        $user->lokasi_penugasan = $validate['lokasi_penugasan'] ?? null;

        if (! empty($validate['password'])) {
            $user->password = Hash::make($validate['password']);
        }

        $user->save();

        return redirect()->route('user.detail', $user->id)->with('success', 'Profil user berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
