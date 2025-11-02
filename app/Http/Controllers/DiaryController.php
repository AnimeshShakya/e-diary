<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiaryRequest;
use App\Http\Requests\UpdateDiaryRequest;
use App\Models\Diary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Diary::class);

        $diaries = Auth::user()->diaries()
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('diaries.index', compact('diaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Diary::class);

        return view('diaries.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiaryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Diary::class);

        $diary = Auth::user()->diaries()->create($request->validated());

        return redirect()
            ->route('diaries.show', $diary)
            ->with('success', 'Diary entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Diary $diary): View
    {
        Gate::authorize('view', $diary);

        return view('diaries.show', compact('diary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diary $diary): View
    {
        Gate::authorize('update', $diary);

        return view('diaries.edit', compact('diary'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiaryRequest $request, Diary $diary): RedirectResponse
    {
        $diary->update($request->validated());

        return redirect()
            ->route('diaries.show', $diary)
            ->with('success', 'Diary entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diary $diary): RedirectResponse
    {
        Gate::authorize('delete', $diary);

        $diary->delete();

        return redirect()
            ->route('diaries.index')
            ->with('success', 'Diary entry deleted successfully.');
    }
}
