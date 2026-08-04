 
@php($page = 'buses')
   @extends('layouts.app')

    @section('content')

    <div class="container-fluid py-4">

        ```
        {{-- =========================
    HEADER
========================== --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    Edit Bus
                </h3>

                <p class="text-muted mb-0">
                    Update bus, driver, route and transportation details.
                </p>
            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('buses.show', $bus) }}"
                    class="btn btn-light border">

                    <i class="bi bi-eye me-1"></i>

                    View

                </a>

                <a href="{{ route('buses.index') }}"
                    class="btn btn-light border">

                    <i class="bi bi-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>


        {{-- =========================
    SUCCESS MESSAGE
========================== --}}
        @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
            role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        @endif


        {{-- =========================
    VALIDATION ERRORS
========================== --}}
        @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show"
            role="alert">

            <div class="fw-semibold mb-2">
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

                @endforeach

            </ul>

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

        @endif


        <div class="row g-4">

            {{-- =========================
        MAIN FORM
    ========================== --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-0 py-3">

                        <div class="d-flex align-items-center">

                            <div class="rounded-circle
                                bg-primary
                                bg-opacity-10
                                text-primary
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3"
                                style="width:45px;height:45px;">

                                <i class="bi bi-bus-front fs-5"></i>

                            </div>

                            <div>

                                <h5 class="fw-bold mb-0">
                                    Bus Information
                                </h5>

                                <small class="text-muted">
                                    Update the vehicle information below.
                                </small>

                            </div>

                        </div>

                    </div>


                    <div class="card-body p-4">

                        <form action="{{ route('buses.update', $bus) }}"
                            method="POST">

                            @csrf

                            @method('PUT')


                            <div class="row g-4">

                                {{-- Bus Number --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Bus Number
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        name="bus_number"
                                        class="form-control @error('bus_number') is-invalid @enderror"
                                        value="{{ old('bus_number', $bus->bus_number) }}"
                                        placeholder="BUS-001"
                                        required>

                                    @error('bus_number')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Vehicle Number --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Vehicle Number
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input
                                        type="text"
                                        name="vehicle_number"
                                        class="form-control @error('vehicle_number') is-invalid @enderror"
                                        value="{{ old('vehicle_number', $bus->vehicle_number) }}"
                                        placeholder="BA 1 KHA 1234"
                                        required>

                                    @error('vehicle_number')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Driver Name --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Driver Name
                                    </label>

                                    <input
                                        type="text"
                                        name="driver_name"
                                        class="form-control @error('driver_name') is-invalid @enderror"
                                        value="{{ old('driver_name', $bus->driver_name) }}"
                                        placeholder="Driver name">

                                    @error('driver_name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Driver Phone --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Driver Phone
                                    </label>

                                    <input
                                        type="text"
                                        name="driver_phone"
                                        class="form-control @error('driver_phone') is-invalid @enderror"
                                        value="{{ old('driver_phone', $bus->driver_phone) }}"
                                        placeholder="98XXXXXXXX">

                                    @error('driver_phone')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Capacity --}}
                                <div class="col-md-4">

                                    <label class="form-label fw-semibold">

                                        Capacity
                                        <span class="text-danger">*</span>

                                    </label>

                                    <input
                                        type="number"
                                        name="capacity"
                                        class="form-control @error('capacity') is-invalid @enderror"
                                        value="{{ old('capacity', $bus->capacity) }}"
                                        min="1"
                                        max="200"
                                        required>

                                    @error('capacity')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Route --}}
                                <div class="col-md-8">

                                    <label class="form-label fw-semibold">
                                        Route
                                    </label>

                                    <input
                                        type="text"
                                        name="route"
                                        class="form-control @error('route') is-invalid @enderror"
                                        value="{{ old('route', $bus->route) }}"
                                        placeholder="Janakpur → School">

                                    @error('route')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Pickup --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Pickup Location
                                    </label>

                                    <input
                                        type="text"
                                        name="pickup_location"
                                        class="form-control @error('pickup_location') is-invalid @enderror"
                                        value="{{ old('pickup_location', $bus->pickup_location) }}"
                                        placeholder="Main pickup point">

                                    @error('pickup_location')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Drop --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">
                                        Drop Location
                                    </label>

                                    <input
                                        type="text"
                                        name="drop_location"
                                        class="form-control @error('drop_location') is-invalid @enderror"
                                        value="{{ old('drop_location', $bus->drop_location) }}"
                                        placeholder="School / final stop">

                                    @error('drop_location')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Status --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">

                                        Status
                                        <span class="text-danger">*</span>

                                    </label>

                                    <select
                                        name="status"
                                        class="form-select @error('status') is-invalid @enderror"
                                        required>

                                        <option value="active"
                                            {{ old('status', $bus->status) === 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="maintenance"
                                            {{ old('status', $bus->status) === 'maintenance' ? 'selected' : '' }}>
                                            Maintenance
                                        </option>

                                        <option value="inactive"
                                            {{ old('status', $bus->status) === 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>

                                    @error('status')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Description --}}
                                <div class="col-12">

                                    <label class="form-label fw-semibold">
                                        Description
                                    </label>

                                    <textarea
                                        name="description"
                                        class="form-control @error('description') is-invalid @enderror"
                                        rows="4"
                                        placeholder="Additional information about this bus...">{{ old('description', $bus->description) }}</textarea>

                                    @error('description')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                    @enderror

                                </div>


                                {{-- Buttons --}}
                                <div class="col-12">

                                    <hr class="my-1">

                                    <div class="d-flex justify-content-between align-items-center mt-3">

                                        {{-- Delete --}}
                                        <button
                                            type="button"
                                            class="btn btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteBusModal">
                                            <i class="bi bi-trash me-1"></i>
                                            Delete Bus
                                        </button>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('buses.index') }}"
                                                class="btn btn-light border">
                                                Cancel
                                            </a>

                                            <button
                                                type="submit"
                                                class="btn btn-primary px-4">
                                                <i class="bi bi-check-lg me-1"></i>
                                                Update Bus
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </form>
                        <div class="modal fade"
                            id="deleteBusModal"
                            tabindex="-1"
                            aria-hidden="true">

                            <div class="modal-dialog modal-dialog-centered">

                                <div class="modal-content border-0 shadow">

                                    <div class="modal-header">

                                        <h5 class="modal-title fw-bold">
                                            Delete Bus
                                        </h5>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal">
                                        </button>

                                    </div>

                                    <div class="modal-body">

                                        <div class="text-center py-2">

                                            <div class="text-danger mb-3">
                                                <i class="bi bi-exclamation-triangle fs-1"></i>
                                            </div>

                                            <h5 class="fw-bold">
                                                Delete {{ $bus->bus_number }}?
                                            </h5>

                                            <p class="text-muted mb-0">
                                                This action cannot be undone.
                                                Are you sure you want to delete this bus?
                                            </p>

                                        </div>

                                    </div>

                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-light border"
                                            data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <form
                                            action="{{ route('buses.destroy', $bus) }}"
                                            method="POST">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger">
                                                <i class="bi bi-trash me-1"></i>
                                                Delete Bus
                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =========================
        CURRENT BUS SUMMARY
    ========================== --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-0">
                            Current Bus
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="text-center mb-4">

                            <div class="rounded-circle
                                bg-primary
                                bg-opacity-10
                                text-primary
                                d-inline-flex
                                align-items-center
                                justify-content-center
                                mb-3"
                                style="width:80px;height:80px;">

                                <i class="bi bi-bus-front fs-1"></i>

                            </div>

                            <h4 class="fw-bold mb-1">
                                {{ $bus->bus_number }}
                            </h4>

                            <p class="text-muted mb-0">
                                {{ $bus->vehicle_number }}
                            </p>

                        </div>


                        {{-- Status --}}
                        <div class="d-flex justify-content-between
                            align-items-center
                            border-bottom
                            py-3">

                            <span class="text-muted">
                                Status
                            </span>

                            @if($bus->status === 'active')

                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>
                                Active
                            </span>

                            @elseif($bus->status === 'maintenance')

                            <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                <i class="bi bi-tools me-1"></i>
                                Maintenance
                            </span>

                            @else

                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                <i class="bi bi-x-circle me-1"></i>
                                Inactive
                            </span>

                            @endif

                        </div>


                        {{-- Driver --}}
                        <div class="d-flex justify-content-between
                            align-items-center
                            border-bottom
                            py-3">

                            <span class="text-muted">
                                Driver
                            </span>

                            <span class="fw-semibold text-end">

                                {{ $bus->driver_name ?: 'Not assigned' }}

                            </span>

                        </div>


                        {{-- Capacity --}}
                        <div class="d-flex justify-content-between
                            align-items-center
                            border-bottom
                            py-3">

                            <span class="text-muted">
                                Capacity
                            </span>

                            <span class="fw-semibold">

                                <i class="bi bi-people me-1"></i>

                                {{ $bus->capacity }} seats

                            </span>

                        </div>


                        {{-- Route --}}
                        <div class="d-flex justify-content-between
                            align-items-start
                            border-bottom
                            py-3">

                            <span class="text-muted">
                                Route
                            </span>

                            <span class="fw-semibold text-end"
                                style="max-width:60%;">

                                {{ $bus->route ?: 'Not specified' }}

                            </span>

                        </div>


                        {{-- Created --}}
                        <div class="d-flex justify-content-between
                            align-items-center
                            py-3">

                            <span class="text-muted">
                                Added
                            </span>

                            <span class="fw-semibold">

                                {{ $bus->created_at?->format('d M Y') }}

                            </span>

                        </div>

                    </div>

                </div>


                {{-- =========================
            WARNING CARD
        ========================== --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex">

                            <div class="text-warning me-3">

                                <i class="bi bi-info-circle fs-4"></i>

                            </div>

                            <div>

                                <h6 class="fw-bold">
                                    Important
                                </h6>

                                <p class="text-muted small mb-0">

                                    Changes made here will immediately
                                    update this bus throughout the
                                    transportation management system.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    </div>

    @endsection