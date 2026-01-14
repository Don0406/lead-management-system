@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Create New Lead</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('leads.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                    name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" class="form-control @error('company') is-invalid @enderror"
                                    id="company" name="company" value="{{ old('company') }}">
                                @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}">
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="source" class="form-label">Source *</label>
                                <select class="form-select @error('source') is-invalid @enderror" id="source"
                                    name="source" required>
                                    <option value="">Select Source</option>
                                    <option value="Website" {{ old('source')=='Website' ? 'selected' : '' }}>Website
                                    </option>
                                    <option value="Referral" {{ old('source')=='Referral' ? 'selected' : '' }}>Referral
                                    </option>
                                    <option value="Social Media" {{ old('source')=='Social Media' ? 'selected' : '' }}>
                                        Social Media</option>
                                    <option value="Email Campaign" {{ old('source')=='Email Campaign' ? 'selected' : ''
                                        }}>Email Campaign</option>
                                    <option value="Event" {{ old('source')=='Event' ? 'selected' : '' }}>Event</option>
                                    <option value="Other" {{ old('source')=='Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('source')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="estimated_value" class="form-label">
                                    Estimated Value (USD)
                                </label>

                                <input type="number" step="0.01" min="0" inputmode="decimal" placeholder="e.g. 5000.00"
                                    class="form-control @error('estimated_value') is-invalid @enderror"
                                    id="estimated_value" name="estimated_value" value="{{ old('estimated_value') }}">

                                <small class="text-muted">
                                    Enter the approximate expected revenue from this lead.
                                </small>

                                @error('estimated_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Assign To</label>
                            <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to"
                                name="assigned_to">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to')==$user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ ucfirst($user->role) }})
                                </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('leads.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Create Lead
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Simple phone number formatting
    document.getElementById('phone').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 3 && value.length <= 6) {
            value = value.replace(/(\d{3})(\d+)/, '$1-$2');
        } else if (value.length > 6) {
            value = value.replace(/(\d{3})(\d{3})(\d+)/, '$1-$2-$3');
        }
        e.target.value = value;
    });
</script>
@endsection