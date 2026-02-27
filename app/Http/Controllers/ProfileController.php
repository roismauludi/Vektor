<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('Profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
    
        $validated = $request->validate([
            'foto' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:2048'],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'foto.image' => 'File harus berupa gambar (jpeg, jpg, png, gif, webp).',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            'current_password.required_with' => 'Password saat ini wajib diisi jika ingin mengubah password.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);
    
        // Upload Foto
        if ($request->hasFile('foto')) {

            // Hapus foto lama
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
        
            $file = $request->file('foto');
        
            // Buat slug dari nama (atau bisa pakai username kalau ada field username)
            $usernameSlug = Str::slug($user->nama ?? 'user');
        
            // Buat nama file: username + timestamp + random
            $fileName = $usernameSlug . '_' . time() . '_' . Str::random(5) 
                . '.' . $file->getClientOriginalExtension();
        
            // Simpan langsung di folder profile
            $path = $file->storeAs('profile', $fileName, 'public');
        
            $user->foto = $path;
        }
    
        // Update Password
        if ($request->filled('password')) {
    
            if (! Hash::check($request->current_password, $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'Password saat ini salah.'])
                    ->withInput();
            }
    
            $user->password = Hash::make($validated['password']);
        }
    
        $user->save();
    
        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}