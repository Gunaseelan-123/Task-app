@extends('layouts.app', ['title' => 'Architecture | Northstar'])

@section('content')
    <section class="section">
        <div class="section-head">
            <div>
                <div class="eyebrow">System blueprint</div>
                <h2>Laravel-first commerce architecture</h2>
                <p class="section-subtitle">This build uses Blade for SSR pages, Sanctum for API auth, and a normalized MySQL-ready relational design.</p>
            </div>
        </div>

        <div class="info-grid">
            <article class="table-card">
                <h3>Frontend delivery</h3>
                <div class="stack">
                    @foreach($frontendStack as $item)
                        <div class="panel" style="padding:14px;">{{ $item }}</div>
                    @endforeach
                </div>
            </article>
            <article class="table-card">
                <h3>Database design</h3>
                <div class="stack">
                    @foreach($databaseTables as $table)
                        <div class="panel" style="padding:14px;">{{ $table }}</div>
                    @endforeach
                </div>
            </article>
            <article class="table-card">
                <h3>Admin coverage</h3>
                <div class="stack">
                    @foreach($adminModules as $module)
                        <div class="panel" style="padding:14px;">{{ $module }}</div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>
@endsection
