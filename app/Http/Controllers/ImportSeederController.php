<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PegawaiImport;

class ImportSeederController extends Controller
{
    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx']);

        Excel::import(new PegawaiImport, $request->file('file'));

        return back()->with('success', 'Data berhasil diimpor!');
    }
}
