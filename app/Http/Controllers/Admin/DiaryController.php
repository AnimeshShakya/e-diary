<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiaryEntry;
use Illuminate\Http\Request;
use Carbon\Carbon;


class DiaryController extends Controller
{
    public function index()
    {
        return view('admin.diary.index');
    }

    public function store(Request $request)
    {
        dd($request);
        // Validate the incoming request data
        $validatedData = $request->validate([
            'fullname' => 'required|string|max:255',
            'diary' => 'required|string',
        ]);

        $diaryEntry = new DiaryEntry();
        $diaryEntry->fullname = $validatedData['fullname'];
        $diaryEntry->diary = $validatedData['diary'];
        // Optionally, you can add more fields as needed

        // Save the diary entry to the database
        $diaryEntry->save();

        // Optionally, you can redirect the user to another page after saving
        return redirect()->route('admin.diary.index')->with('success', 'Diary entry saved successfully.');
    }

}
