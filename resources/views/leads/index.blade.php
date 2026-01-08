@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            @isset($status)
                {{ ucfirst($status) }} Leads
            @else
                All Leads
            @endisset
        </h2>
        <a href="{{ route('leads.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Add New Lead
        </a>
    </div>

    <!-- Stats -->
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

    <!-- Leads Table -->
    <div class="card">
        <div class="card-body">
            @if($leads->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Lead</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Source</th>
                                <th>Value</th>
                                <th>Assigned To</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leads as $lead)
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
                                    @if($lead->estimated_value)
                                        ${{ number_format($lead->estimated_value, 2) }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
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
                                        @if($lead->status == 'new')
                                        <form action="{{ route('leads.markContacted', $lead) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Mark as Contacted">
                                                <i class="fas fa-phone"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this lead?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $leads->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No leads found</h5>
                    <p class="text-muted mb-4">
                        @isset($status)
                            You don't have any {{ $status }} leads yet.
                        @else
                            You don't have any leads yet. Start by adding your first lead!
                        @endisset
                    </p>
                    <a href="{{ route('leads.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add New Lead
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection