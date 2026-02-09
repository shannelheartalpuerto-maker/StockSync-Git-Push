@extends('layouts.app')

@section('content')
<link href="{{ asset('css/admin-dashboard-design.css') }}?v={{ time() }}" rel="stylesheet">
<div class="container-fluid px-4 admin-dashboard-container animate-fade-up">
    <!-- Header -->
    <div class="dashboard-header">
        <h2 class="dashboard-title"><i class="fa-solid fa-user-shield me-3 text-primary"></i>Admin Management</h2>
        <p class="dashboard-subtitle">Manage fellow admin accounts and access levels.</p>
    </div>

    <div class="content-card mb-4">
        <div class="card-header-custom">
            <h5 class="card-title-custom mb-0"><i class="fa-solid fa-users-gear me-2"></i>Manage Admins</h5>
        </div>

        <div class="card-body-custom">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row g-4">
                <!-- Add Admin Section -->
                <div class="col-md-4">
                    <div class="p-3 bg-light rounded-3 h-100 border">
                        <h5 class="mb-3 fw-bold text-dark"><i class="fa-solid fa-user-plus me-2 text-success"></i>Add New Admin</h5>
                        <form method="POST" action="{{ route('admin.admins.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold small text-muted text-uppercase">Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="Full Name">
                                </div>
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold small text-muted text-uppercase">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="email@example.com">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold small text-muted text-uppercase">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="********">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label fw-bold small text-muted text-uppercase">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required placeholder="********">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-plus me-2"></i>Create Admin Account
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Admin List Section -->
                <div class="col-md-8">
                    <h5 class="mb-3 fw-bold text-dark"><i class="fa-solid fa-list me-2 text-primary"></i>Admin List</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th class="ps-3" style="min-width: 200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($admins as $admin)
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $admin->status == 'active' ? 'success' : 'danger' }} rounded-pill">
                                            {{ ucfirst($admin->status) }}
                                        </span>
                                    </td>
                                    <td class="ps-3">
                                        <div class="dropdown dropend">
                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="viewport">
                                                <i class="fa-solid fa-gear"></i>
                                            </button>
                                            <div class="dropdown-menu p-2" style="min-width: auto;">
                                                <div class="d-flex gap-2">
                                                    @if($admin->status == 'active')
                                                        <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#suspendAdminModal{{ $admin->id }}">
                                                            <i class="fa-solid fa-ban me-1"></i>Suspend
                                                        </button>
                                                    @else
                                                        <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="active">
                                                            <button type="submit" class="btn btn-success btn-sm text-white">
                                                                <i class="fa-solid fa-check me-1"></i>Activate
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAdminModal{{ $admin->id }}">
                                                        <i class="fa-solid fa-trash me-1"></i>Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                @if($admins->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-users-slash fa-2x mb-3 opacity-50"></i>
                                        <p class="mb-0">No other admin accounts found.</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals Section -->
@foreach($admins as $admin)
    <!-- Suspend Confirmation Modal -->
    <div class="modal fade" id="suspendAdminModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-lock me-2"></i>Suspend Admin Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-warning">
                        <i class="fa-solid fa-circle-pause fa-4x opacity-75"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Are you sure?</h5>
                    <p class="text-muted mb-0">
                        Do you really want to suspend <strong>{{ $admin->name }}</strong>?<br>
                        <span class="text-warning fw-bold small">They will no longer be able to log in.</span>
                    </p>
                </div>
                <div class="modal-footer bg-light border-top-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="suspended">
                        <button type="submit" class="btn btn-warning px-4 fw-bold">
                            <i class="fa-solid fa-ban me-2"></i>Suspend
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteAdminModal{{ $admin->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-trash-can me-2"></i>Delete Admin Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger">
                        <i class="fa-solid fa-triangle-exclamation fa-4x opacity-75"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Are you sure?</h5>
                    <p class="text-muted mb-0">
                        Do you really want to delete <strong>{{ $admin->name }}</strong>?<br>
                        <span class="text-danger fw-bold small">Warning: This action cannot be undone.</span>
                    </p>
                </div>
                <div class="modal-footer bg-light border-top-0 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 fw-bold">
                            <i class="fa-solid fa-trash me-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
