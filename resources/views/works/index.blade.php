@extends('layouts.app')

@section('content')
<h2>作業一覧</h2>

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('works.create') }}">＋ 新しい作業を追加</a>
    <a href="{{ route('works.calendar') }}" class="btn btn-outline-secondary">カレンダー表示</a>
</div>

@if(session('message'))
<div class="flash-message">{{ session('message') }}</div>
@endif

<table>
    <thead>
        <tr>
            <th>作業日</th>
            <th>天気</th>
            <th>タイトル</th>
            <th>カテゴリ</th>
            <th>作業時間（分）</th>
            <th>詳細</th>
            <th>画像</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        @foreach($works as $work)
        <tr>
            <td>{{ $work->work_date }}</td>
            <td>{{ $work->weather }}</td>
            <td>{{ $work->title }}</td>
            <td>{{ $work->category_name }}</td>
            <td>{{ $work->work_time }}</td>
            <td>
                <a href="{{ route('works.show', $work->id) }}">{{ $work->title }}</a>
            </td>
            <td>
                @if($work->image_path)
                <img src="{{ asset('storage/' . $work->image_path) }}" style="width: 80px; height: auto;">
                @endif
            </td>

            <td>
                <a href="{{ route('works.edit', $work) }}">編集</a> |
                <form action="{{ route('works.destroy', $work) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('削除してもよろしいですか？')">削除</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $works->links() }}
@endsection