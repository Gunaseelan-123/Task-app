@extends('layouts.admin', ['title' => 'Customers | Northstar Admin'])

@section('content')
    <div class="section-head">
        <div><div class="eyebrow">Customer management</div><h1>Customers</h1></div>
    </div>

    <section class="table-card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Orders</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->orders_count }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>{{ ucfirst($user->status) }}</td>
                        <td>
                            <form method="post" action="{{ route('admin.users.update', $user) }}" class="stack">
                                @csrf
                                @method('PATCH')
                                <select class="field" name="role">
                                    <option value="user" @selected($user->role === 'user')>User</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                </select>
                                <select class="field" name="status">
                                    <option value="active" @selected($user->status === 'active')>Active</option>
                                    <option value="inactive" @selected($user->status === 'inactive')>Inactive</option>
                                </select>
                                <button class="ghost-btn" type="submit">Save</button>
                            </form>
                            <form method="post" action="{{ route('admin.users.destroy', $user) }}" class="inline-form" style="margin-top:8px;">
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
        <div style="margin-top:20px;">{{ $users->links() }}</div>
    </section>
@endsection
