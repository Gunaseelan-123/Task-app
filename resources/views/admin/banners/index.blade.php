@extends('layouts.admin', ['title' => 'Banners | Northstar Admin'])

@section('content')
    <div class="section-head">
        <div><div class="eyebrow">Promotions</div><h1>Banners</h1></div>
    </div>
    <div class="admin-grid">
        <section class="table-card">
            <h3>Create banner</h3>
            <form method="post" action="{{ route('admin.banners.store') }}" class="stack">
                @csrf
                <input class="field" type="text" name="title" placeholder="Title">
                <input class="field" type="text" name="subtitle" placeholder="Subtitle">
                <input class="field" type="url" name="image_url" placeholder="Image URL">
                <input class="field" type="text" name="cta_label" placeholder="CTA label">
                <input class="field" type="text" name="cta_url" placeholder="CTA URL">
                <label><input type="checkbox" name="is_active" value="1" checked> Active</label>
                <button class="primary-btn" type="submit">Save banner</button>
            </form>
        </section>
        <section class="table-card" style="grid-column: span 2;">
            <div class="stack">
                @foreach($banners as $banner)
                    <div class="panel" style="padding:18px;">
                        <strong>{{ $banner->title }}</strong>
                        <div class="helper">{{ $banner->subtitle }}</div>
                        <div style="display:flex;gap:10px;margin-top:10px;">
                            <form class="inline-form" method="post" action="{{ route('admin.banners.destroy', $banner) }}">
                                @csrf
                                @method('DELETE')
                                <button class="ghost-btn" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
