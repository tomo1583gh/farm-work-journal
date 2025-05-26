<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Work;
use Illuminate\Support\Facades\Storage;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Work::where('user_id', auth()->id());

        // キーワード検索
        if ($request->filled('keyword')) {
            $keyword = mb_convert_kana(trim($request->keyword), 's');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('category_name', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }
        // 期間検索
        if ($request->filled('start_date')) {
            $query->whereDate('work_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('work_date', '<=', $request->end_date);
        }

        $works = $query->latest()->paginate(10);

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
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('works', 'public');
        }

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
            'image' => 'nullable|image|max:2048',
        ]);

        $work = Work::where('user_id', auth()->id())->findOrFail($id);

        // ★ 元画像削除 + 新画像保存
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($work->image_path && Storage::disk('public')->exists($work->image_path)) {
                Storage::disk('public')->delete($work->image_path);
            }
            // 新しい画像を保存
            $validated['image_path'] = $request->file('image')->store('works', 'public');
        }

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
