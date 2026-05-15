@extends('layouts.admin', ['title' => 'Coupons | Northstar Admin'])

@section('content')
    <div class="section-head">
        <div><div class="eyebrow">Discount engine</div><h1>Coupons</h1></div>
    </div>
    <div class="admin-grid">
        <section class="table-card">
            <h3>Create coupon</h3>
            <form method="post" action="{{ route('admin.coupons.store') }}" class="stack">
                @csrf
                <input class="field" type="text" name="code" placeholder="SAVE10">
                <input class="field" type="text" name="description" placeholder="Description">
                <select class="field" name="type">
                    <option value="percentage">Percentage</option>
                    <option value="flat">Flat</option>
                </select>
                <div class="field-row">
                    <input class="field" type="number" step="0.01" name="value" placeholder="Value">
                    <input class="field" type="number" step="0.01" name="minimum_amount" placeholder="Minimum amount">
                </div>
                <button class="primary-btn" type="submit">Save coupon</button>
            </form>
        </section>
        <section class="table-card" style="grid-column: span 2;">
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ ucfirst($coupon->type) }}</td>
                            <td>{{ $coupon->value }}</td>
                            <td>{{ $coupon->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <form class="inline-form" method="post" action="{{ route('admin.coupons.destroy', $coupon) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ghost-btn" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
