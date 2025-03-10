<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'judul' => 'Manajemen Karyawan',
            'DataK' => Employee::latest()->get(),
        ];
        return view('pages.admin.employee', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'judul' => 'Tambah Karyawan',
        ];
        return view('pages.admin.employee_add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nama'  => 'required|max:255',
            'NIP'   => 'required|max:255',
        ]);

        Employee::create([
            'id_employees'          => 'Employee'.Str::random(33),
            'nip_employees'         => $request->NIP,
            'name_employees'        => $request->Nama,
            'position_employees'    => $request->Position,
            'status_employees'      => $request->status,
            'created_by'            => Auth::user()->email,
            'modified_by'           => Auth::user()->email
        ]);

        return redirect()->route('employee.add')->with(['success' => 'Karyawan telah Ditambahkan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        //
    }

    public function aktif(string $id)
    {
        //get by ID
        $employee = Employee::findOrFail($id);

        //aktifkan
        $employee->update([
            'status_employees'   => 'Aktif',
            'modified_by'       => Auth::user()->email,
        ]);

        //redirect
        return redirect()->route('employee.data')->with(['success' => 'Karyawan telah Di Aktifkan!']);
    }

    public function nonaktif(string $id)
    {
        //get by ID
        $employee = Employee::findOrFail($id);

        //nonaktifkan
        $employee->update([
            'status_employees'   => 'Nonaktif',
            'modified_by'       => Auth::user()->email,
        ]);

        //redirect
        return redirect()->route('employee.data')->with(['success' => 'Karyawan telah Di Nonaktifkan!']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
