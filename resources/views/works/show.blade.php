@extends('layouts.app')

@section('content')
<h2>作業の詳細</h2>

<div class="work-detail">
    <p><strong>作業日：</strong>{{ $work->work_date }}</p>
    <p><strong>天気：</strong>{{ $work->weather }}</p>
    <p><strong>タイトル：</strong>{{ $work->title }}</p>
    <p><strong>カテゴリ:</strong>{{ $work->category_name }}</p>
    <p><strong>作業時間（分）:</strong>{{ $work->work_time }}</p>
    <p><strong>内容：</strong></p>
    <p>{!! nl2br(e($work->content)) !!}</p>
</div>

<div class="actions">
    <a href="{{ route('works.edit', $work->id) }}">編集する</a> |
    <a href="{{ route('works.index') }}">一覧に戻る</a>
</div>
@endsection