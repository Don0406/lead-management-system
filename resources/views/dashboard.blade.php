@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Dashboard</h2>
        @auth
        <span class="text-muted">Welcome back, {{ auth()->user()->name }}!</span>
        @endauth
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Leads</h6>
                    <h3 class="fw-bold">{{ $stats['total'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">New Leads</h6>
                    <h3 class="fw-bold">{{ $stats['new'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Contacted</h6>
                    <h3 class="fw-bold">{{ $stats['contacted'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Qualified</h6>
                    <h3 class="fw-bold">{{ $stats['qualified'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <a href="{{ route('leads.create') }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-plus me-2"></i> Add New Lead
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('leads.byStatus', 'new') }}" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-filter me-2"></i> View New Leads
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('leads.index') }}" class="btn btn-info w-100 mb-2">
                        <i class="fas fa-list me-2"></i> View All Leads
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('leads.byStatus', 'contacted') }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-phone me-2"></i> Contacted Leads
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Leads -->
    <div class="card">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Leads</h5>
            <a href="{{ route('leads.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body">
            @if(isset($recentLeads) && $recentLeads->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Lead</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Source</th>
                                <th>Assigned To</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLeads as $lead)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="lead-avatar me-3">
                                            {{ substr($lead->first_name, 0, 1) }}{{ substr($lead->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $lead->full_name }}</strong><br>
                                            <small class="text-muted">{{ $lead->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $lead->company ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ $lead->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                                    </span>
                                </td>
                                <td>{{ $lead->source }}</td>
                                <td>
                                    @if($lead->assignedUser)
                                        {{ $lead->assignedUser->name }}
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </td>
                                <td>{{ $lead->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('leads.show', $lead) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('leads.edit', $lead) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No leads found</h5>
                    <p class="text-muted mb-4">Start by adding your first lead!</p>
                    <a href="{{ route('leads.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Lead
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection