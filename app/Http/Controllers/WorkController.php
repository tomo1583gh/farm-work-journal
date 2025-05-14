<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\work;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $works = Work::where('user_id', auth()->id())->latest()->paginate(10);
        return view('works.index', compact('works'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('works.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category_name' => 'nullable|max:255',
            'work_time' => 'nullable|integer|min:0',
            'content' => 'nullable',
            'work_date' => 'required|date',
            'weather' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        Work::create($validated);

        return redirect()->route('works.index')->with('message', '作業を登録しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $work = Work::where('user_id', auth()->id())->findOrFail($id);
        return view('works.show', compact('work'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $work = Work::where('user_id', auth()->id())->findOrFail($id);
        return view('works.edit', compact('work'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'category_name' => 'nullable|max:255',
            'work_time' => 'nullable|integer|min:0',
            'content' => 'nullable',
            'work_date' => 'required|date',
            'weather' => 'nullable|string|max:255',
        ]);

        $work = Work::where('user_id', auth()->id())->findOrFail($id);
        $work->update($validated);

        return redirect()->route('works.index')->with('message', '作業を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $work = Work::where('user_id', auth()->id())->findOrFail($id);
        $work->delete();

        return redirect()->route('works.index')->with('message', '作業を削除しました');
    }
}
