<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar karyawan.
     */
    public function index()
    {
        // Eager load 'user' dan roles untuk performa query
        $employees = Employee::with(['user.roles'])->latest()->paginate(10);
        return view('employees.index', compact('employees'));
    }

    /**
     * Menampilkan form tambah karyawan.
     */
    public function create()
    {
        // Ambil semua role dari Spatie untuk dropdown
        $roles = Role::pluck('name', 'name')->all();
        return view('employees.create', compact('roles'));
    }

    /**
     * Menyimpan data karyawan baru beserta akun login-nya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'position' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'role' => 'required|exists:roles,name', // Validasi role harus ada di DB
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat User Login
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2. Assign Role Spatie
            $user->assignRole($request->role);

            // 3. Buat Data Profil Karyawan
            Employee::create([
                'user_id' => $user->id,
                'name' => $request->name, // Redundan tapi memudahkan jika user dihapus
                'position' => $request->position,
                'phone' => $request->phone,
                'address' => $request->address,
                'join_date' => $request->join_date,
                'status' => 'Aktif',
            ]);
        });

        return redirect()->route('employees.index')->with('success', 'Karyawan & Akun Login berhasil dibuat.');
    }

    /**
     * Menampilkan form edit karyawan.
     */
    public function edit(Employee $employee)
    {
        $roles = Role::pluck('name', 'name')->all();
        // Load relasi user untuk mengambil email & role saat ini
        $employee->load('user');

        // Ambil role user saat ini (jika ada)
        $userRole = $employee->user ? $employee->user->roles->pluck('name')->first() : '';

        return view('employees.edit', compact('employee', 'roles', 'userRole'));
    }

    /**
     * Memperbarui data karyawan dan akun login.
     */
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Email unik, tapi abaikan untuk user id milik karyawan ini
            'email' => ['required', 'email', Rule::unique('users')->ignore($employee->user_id)],
            'position' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:6', // Password opsional saat edit
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        DB::transaction(function () use ($request, $employee) {
            // 1. Update Data Karyawan
            $employee->update([
                'name' => $request->name,
                'position' => $request->position,
                'phone' => $request->phone,
                'address' => $request->address,
                'join_date' => $request->join_date,
                'status' => $request->status,
            ]);

            // 2. Update Data User Login
            if ($employee->user) {
                $userData = [
                    'name' => $request->name,
                    'email' => $request->email,
                ];

                // Hanya update password jika diisi
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }

                $employee->user->update($userData);

                // 3. Sync Role Spatie (Ganti role lama dengan yang baru)
                $employee->user->syncRoles($request->role);
            }
        });

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    /**
     * Menghapus data karyawan dan akun login terkait.
     */
    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            // Cari user terkait sebelum employee dihapus
            $user = $employee->user;

            // Hapus data karyawan
            $employee->delete();

            // Hapus user login agar tidak jadi akun sampah (orphan)
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('employees.index')->with('success', 'Karyawan dan akun login berhasil dihapus.');
    }
}
