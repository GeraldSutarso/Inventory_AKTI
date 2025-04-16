<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function admin () {
        $admins = User::where('role', 'admin')->paginate(10);
        return view('dashboard.admin.index', ['admins'=>$admins]);
    }

    public function officer (Request $request) {
        if($request->has('search')){
        $officers = User::where('name', 'LIKE', "%{$request->search}%")->where('role', 'officer')->paginate(10);
        } else {
        $officers = User::where('role', 'officer')->paginate(10);
        }
        return view('dashboard.officer.index', ['officers'=>$officers]);
    }

    public function head(Request $request) {
        $heads = User::where('role', 'head')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->paginate(10);
    
        return view('dashboard.head.index', ['heads' => $heads]);
    }
    
    public function sarpras(Request $request) {
        $sarpras = User::where('role', 'sarpras')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->paginate(10);
    
        return view('dashboard.sarpras.index', ['sarpras' => $sarpras]);
    }
    

    public function delete($id) {
        $user = User::findOrFail($id);
        $deletedUser = $user->delete();

        if($deletedUser){
            session()->flash('message', 'berhasil menghapus data');
            return response()->json(['message'=> 'success'],200);
        }
    }

    public function createOfficer () {
       return view('dashboard.officer.input');
    }

    public function storeOfficer (Request $request) {
        $this->validate($request, [
                'name'=>['required'],
                'email'=>['required'],
                'password'=>['required']
        ]);

        $userCreated = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'officer'
        ]);

        if($userCreated){
            return redirect('/petugas')->with('message','data berhasil ditambahkan');
        }
    }

    public function editOfficer ($id) {
        $officer = User::findOrFail($id);
        return view('dashboard.officer.update', ['officer'=>$officer]);
    }

    public function updateOfficer (Request $request, $id) {
        $this->validate($request, [
                'name'=>['required'],
                'email'=>['required'],
                'password'=>['required']
        ]);

        $officer = User::findOrFail($id);

        if($request->has('password')) {
            $password = Hash::make($request->password);
        } else {
             $password = $officer->password;
        }   

        $updated = $officer->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$password,
            'role'=>'officer'
        ]);

        if($updated){
            return redirect('/petugas')->with('message','data berhasil diubah');
        }
    }

    public function createAdmin () {
       return view('dashboard.admin.input');
    }


    public function storeAdmin (Request $request) {
        $this->validate($request, [
                'name'=>['required'],
                'email'=>['required'],
                'password'=>['required']
        ]);

        $userCreated = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'admin'
        ]);

        if($userCreated){
            return redirect('/admin')->with('message','data berhasil ditambahkan');
        }
    }

    public function editAdmin ($id) {
        $admin = User::findOrFail($id);
        return view('dashboard.admin.update', ['admin'=>$admin]);
    }

    public function updateAdmin (Request $request, $id) {
    $this->validate($request, [
            'name'=>['required'],
            'email'=>['required'],
    ]);

    $admin = User::findOrFail($id);

    if($request->has('password')) {
        $password = Hash::make($request->password);
    } else {
        $password = $admin->password;
    }

    $updated = $admin->update([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>$password,
        'role'=>'admin'
    ]);

    if($updated){
        return redirect('/admin')->with('message','data berhasil diubah');
    }
    }

    public function createHead() {
        return view('dashboard.head.input');
    }
    
    public function storeHead(Request $request) {
        $this->validate($request, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
    
        $created = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'head'
        ]);
    
        if ($created) {
            return redirect('/kepala-unit')->with('message', 'data berhasil ditambahkan');
        }
    }
    
    public function editHead($id) {
        $head = User::findOrFail($id);
        return view('dashboard.head.update', ['head' => $head]);
    }
    
    public function updateHead(Request $request, $id) {
        $this->validate($request, [
            'name' => ['required'],
            'email' => ['required', 'email'],
        ]);
    
        $head = User::findOrFail($id);
        $password = $request->filled('password') ? Hash::make($request->password) : $head->password;
    
        $updated = $head->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => 'head'
        ]);
    
        if ($updated) {
            return redirect('/kepala-unit')->with('message', 'data berhasil diubah');
        }
    }
    
    public function createSarpras() {
        return view('dashboard.sarpras.input');
    }
    public function storeSarpras(Request $request) {
        $this->validate($request, [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
    
        $created = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'sarpras'
        ]);
    
        if ($created) {
            return redirect('/sarpras')->with('message', 'data berhasil ditambahkan');
        }
    }
    public function editSarpras($id) {
        $sarpras = User::findOrFail($id);
        return view('dashboard.sarpras.update', ['sarpras' => $sarpras]);
    }
    public function updateSarpras(Request $request, $id) {
        $this->validate($request, [
            'name' => ['required'],
            'email' => ['required', 'email'],
        ]);
    
        $sarpras = User::findOrFail($id);
        $password = $request->filled('password') ? Hash::make($request->password) : $sarpras->password;
    
        $updated = $sarpras->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => 'sarpras'
        ]);
    
        if ($updated) {
            return redirect('/sarpras')->with('message', 'data berhasil diubah');
        }
    }

    public function uploadTtdAdmin(Request $request)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'ttd' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $user = User::findOrFail($request->admin_id);
    
        // Simpan gambar ke storage/app/public/users/{id}/ttd
        $ttdPath = 'users/' . $user->id . '/ttd';
        $fileName = 'ttd_admin.' . $request->file('ttd')->getClientOriginalExtension();
        $path = $request->file('ttd')->storeAs($ttdPath, $fileName, 'public');
    
        $user->ttd = $path;
        $user->save();
    
        return redirect()->back()->with('message', 'TTD berhasil diupload.');
    }
    
    public function uploadTtdOfficer(Request $request)
    {
        $request->validate([
            'officer_id' => 'required|exists:users,id',
            'ttd' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $user = User::findOrFail($request->officer_id);
    
        $ttdPath = 'users/' . $user->id . '/ttd';
        $fileName = 'ttd_officer.' . $request->file('ttd')->getClientOriginalExtension();
        $path = $request->file('ttd')->storeAs($ttdPath, $fileName, 'public');
    
        $user->ttd = $path;
        $user->save();
    
        return redirect()->back()->with('message', 'Tanda tangan berhasil diupload.');
    }
    
    public function uploadTtdSarpras(Request $request)
    {
        $request->validate([
            'sarpras_id' => 'required|exists:users,id',
            'ttd' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);
    
        $user = User::findOrFail($request->sarpras_id);
    
        // Hapus TTD lama jika ada
        if ($user->ttd) {
            Storage::disk('public')->delete($user->ttd);
        }
    
        $ttdPath = 'users/' . $user->id . '/ttd';
        $fileName = 'ttd_sarpras.' . $request->file('ttd')->getClientOriginalExtension();
        $path = $request->file('ttd')->storeAs($ttdPath, $fileName, 'public');
    
        $user->ttd = $path;
        $user->save();
    
        return redirect()->back()->with('message', 'Tanda tangan berhasil diupload.');
    }
    
    public function uploadTtdKepala(Request $request)
    {
        $request->validate([
            'kepala_id' => 'required|exists:users,id',
            'ttd' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);
    
        $user = User::findOrFail($request->kepala_id);
    
        // Hapus TTD lama jika ada
        if ($user->ttd) {
            Storage::disk('public')->delete($user->ttd);
        }
    
        $ttdPath = 'users/' . $user->id . '/ttd';
        $fileName = 'ttd_kepala.' . $request->file('ttd')->getClientOriginalExtension();
        $path = $request->file('ttd')->storeAs($ttdPath, $fileName, 'public');
    
        $user->ttd = $path;
        $user->save();
    
        return redirect()->back()->with('message', 'TTD Kepala berhasil diupload.');
    }
    


}
