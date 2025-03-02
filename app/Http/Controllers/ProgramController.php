<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'judul' => 'Manajemen Program',
            'DataP' => Program::latest()->get(),
            // 'cOP' => Order::where('status_orders', 'Pending')->count(),
        ];
        return view('pages.admin.program', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'judul' => 'Program Baru',
            // 'cOP' => Order::where('status_orders', 'Pending')->count(),
        ];
        return view('pages.admin.program_add', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Title'     => 'required|max:255',
            'Subtitle'  => 'required|max:255',
            'Price'     => 'required|numeric|min:1',
            'AdminP'    => 'required|numeric|min:0',
            'Benefit'   => 'required',
        ]);

        //create
        Program::create([
            'id_programs'       => 'Program'.Str::random(33),
            'code_programs'     => Str::random(9),
            'category_programs' => $request->category,
            'title_programs'    => $request->Title,
            'subtitle_programs' => $request->Subtitle,
            'price_programs'    => $request->Price,
            'admin_programs'    => $request->AdminP,
            'benefit_programs'  => $request->Benefit,
            'visib_programs'    => $request->visibility,
            'created_by'        => Auth::user()->email,
            'modified_by'       => Auth::user()->email,
        ]);

        //redirect to index
        return redirect()->route('program.add')->with(['success' => 'Program telah Ditambahkan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = [
            'judul' => 'Edit Program',
            'EditProgram' => Program::findOrFail($id),
            // 'cOP' => Order::where('status_orders', 'Pending')->count(),
        ];
        return view('pages.admin.program_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'Title'     => 'required|max:255',
            'Subtitle'  => 'required|max:255',
            'Price'     => 'required|numeric|min:1',
            'AdminP'    => 'required|numeric|min:0',
            'Benefit'   => 'required',
        ]);

        $program = Program::findOrFail($id);

        $program->update([
            'category_programs' => $request->category,
            'title_programs'    => $request->Title,
            'subtitle_programs' => $request->Subtitle,
            'price_programs'    => $request->Price,
            'admin_programs'    => $request->AdminP,
            'benefit_programs'  => $request->Benefit,
            'visib_programs'    => $request->visibility,
            'modified_by'       => Auth::user()->email,
        ]);

        return redirect()->route('program.data')->with(['success' => 'Program telah Diperbarui!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //get by ID
        $program = Program::findOrFail($id);

        //delete
        $program->delete();

        //redirect to index
        return redirect()->route('program.data')->with(['success' => 'Program telah Dihapus!']);
    }
}
