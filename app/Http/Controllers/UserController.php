<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{    /**
     * Registrasi user baru (public register)
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:3',
            'last_name'  => 'required|string|min:3',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6'
        ]);

        $createData = User::create([
            'name'     => $request->first_name . " " . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user'
        ]);

        return $createData
            ? redirect()->route('login')->with('success', 'Register berhasil, silahkan login!')
            : redirect()->back()->with('error', 'Register gagal, silahkan coba lagi.');
    }

     public function show(User $user)
    {
        //
    }

    /**
     * Autentikasi login
     */
    public function loginAuth(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $data = $request->only(['email', 'password']);
      if (Auth::attempt($data)) {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil Login!');
        } elseif (Auth::user()-> role == 'staff') {
            return redirect()->route('staff.dashboard')->with('success', 'Berhasil Login');
        } else {
            return redirect()-> route('home')->with('success', 'Berhasil Login!');
        }
    } else {
        return redirect()->back()->with('error', 'Gagal! pastikan email dan password sesuai');
       }
    }


    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Anda berhasil Logout!');
    }

    /**
     * List semua admin & staff
     */
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        return view('admin.user.staff', compact('users'));
    }

    public function datatables() {
        $users = User::query();
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('btnActions', function($user) {
          $btnEdit = '<a href="'.route('admin.users.edit', $user['id']).'"class="btn btn-primary me-2">Edit</a>';
          $btnDelete = '<form action="'. route('admin.users.destroy', $user['id']). '" method="post">'.
          @csrf_field().
          @method_field('DELETE').'
          <button type="submit" class="btn btn-danger me-2">Hapus</button>
          </form>';
          return '<div class="d-flex">'. $btnEdit . $btnDelete . '</div>';
        })
        ->rawColumns([ 'btnActions'])
        ->make(true);

    }

    /**
     * Form tambah user baru
     */
    public function create()
    {
        return view('admin.user.create2');
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|min:3',
            'email'      => 'required|email:dns|unique:users,email',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,staff,user'
        ]);

        $createData = User::create([
            'name'     => $request->first_name . " " . $request->last_name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role
        ]);

        return $createData
            ? redirect()->route('admin.users.index')->with('success', 'Berhasil tambah data user')
            : redirect()->back()->with('error', 'Gagal, silahkan coba lagi');
    }

    /**
     * Form edit user
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit2', compact('user'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name' => 'required|string|min:3',
            'email'      => 'required|email:dns|unique:users,email,' . $id,
            'role'       => 'required|in:admin,staff,user'
        ]);

        $user = User::findOrFail($id);
        $user->name  = $request->first_name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if ($request->password) {
            $request->validate([
                'password' => 'nullable|min:6'
            ]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Berhasil update data user');
    }

    /**
     * Hapus user
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->forceDelete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }

    public function exportExcel() {
        $filename = 'data-user.xlsx';
        return Excel::download(new UserExport, $filename);
    }

    public function trash() {
        $userTrash = User::onlyTrashed()->whereIn('role', ['admin', 'staff'])->get();
        return view('admin.user.trash', compact('userTrash'));
    }

    public function restore ($id) {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id) {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }
}
