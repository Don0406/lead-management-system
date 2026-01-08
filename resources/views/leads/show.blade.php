@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Lead Details</h4>
                    <div class="btn-group">
                        <a href="{{ route('leads.edit', $lead) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        @if($lead->status == 'new')
                        <form action="{{ route('leads.markContacted', $lead) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm ms-1">
                                <i class="fas fa-phone me-1"></i> Mark Contacted
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="lead-avatar d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 24px;">
                                {{ substr($lead->first_name, 0, 1) }}{{ substr($lead->last_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h3>{{ $lead->full_name }}</h3>
                            <p class="text-muted mb-1">{{ $lead->title }} at {{ $lead->company ?? 'N/A' }}</p>
                            <span class="status-badge status-{{ $lead->status }}">
                                {{ ucfirst(str_replace('_', ' ', $lead->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Contact Information</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    <strong>Email:</strong> {{ $lead->email }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    <strong>Phone:</strong> {{ $lead->phone ?? 'N/A' }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-building me-2 text-primary"></i>
                                    <strong>Company:</strong> {{ $lead->company ?? 'N/A' }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-briefcase me-2 text-primary"></i>
                                    <strong>Title:</strong> {{ $lead->title ?? 'N/A' }}
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Lead Details</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    <strong>Source:</strong> {{ $lead->source }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-dollar-sign me-2 text-primary"></i>
                                    <strong>Estimated Value:</strong> 
                                    @if($lead->estimated_value)
                                        ${{ number_format($lead->estimated_value, 2) }}
                                    @else
                                        N/A
                                    @endif
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    <strong>Assigned To:</strong> 
                                    @if($lead->assignedUser)
                                        {{ $lead->assignedUser->name }}
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-calendar me-2 text-primary"></i>
                                    <strong>Created:</strong> {{ $lead->created_at->format('F d, Y') }}
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-clock me-2 text-primary"></i>
                                    <strong>Last Contact:</strong> 
                                    @if($lead->contacted_at)
                                        {{ $lead->contacted_at->format('F d, Y') }}
                                    @else
                                        <span class="text-muted">Not contacted yet</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    @if($lead->notes)
                    <div class="mt-4">
                        <h6 class="text-muted">Notes</h6>
                        <div class="card">
                            <div class="card-body">
                                {{ $lead->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Activity Timeline -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Activity Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Lead Created</h6>
                                <p class="text-muted mb-0">{{ $lead->created_at->format('M d, Y h:i A') }}</p>
                                <small>By: {{ $lead->creator->name }}</small>
                            </div>
                        </div>
                        
                        @if($lead->contacted_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Marked as Contacted</h6>
                                <p class="text-muted mb-0">{{ $lead->contacted_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($lead->updated_at != $lead->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-0">Last Updated</h6>
                                <p class="text-muted mb-0">{{ $lead->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:{{ $lead->email }}" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i> Send Email
                        </a>
                        @if($lead->phone)
                        <a href="tel:{{ $lead->phone }}" class="btn btn-outline-success">
                            <i class="fas fa-phone me-2"></i> Call Lead
                        </a>
                        @endif
                        <button class="btn btn-outline-info" onclick="addNote()">
                            <i class="fas fa-sticky-note me-2"></i> Add Note
                        </button>
                        <form action="{{ route('leads.destroy', $lead) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this lead?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="fas fa-trash me-2"></i> Delete Lead
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
    }
    
    .timeline-content {
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    
    .timeline-item:last-child .timeline-content {
        border-bottom: none;
    }
</style>
@endsection

@section('scripts')
<script>
    function addNote() {
        const note = prompt('Enter your note:');
        if (note) {
            // In a real application, you would make an AJAX request here
            alert('Note added! (This would save to database in production)');
        }
    }
</script>
@endsection