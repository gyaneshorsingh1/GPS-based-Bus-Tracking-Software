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
            Bus Details
        </h3>

        <p class="text-muted mb-0">
            View complete information about this school bus.
        </p>
    </div>

    <div class="d-flex gap-2">

        <a href="{{ route('buses.index') }}"
           class="btn btn-light border">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Buses

        </a>

        <a href="{{ route('buses.edit', $bus) }}"
           class="btn btn-primary">

            <i class="bi bi-pencil me-1"></i>

            Edit Bus

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


<div class="row g-4">

    {{-- =========================
        LEFT COLUMN
    ========================== --}}
    <div class="col-xl-8">

        {{-- Bus Overview --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body p-4">

                <div class="d-flex flex-wrap
                            justify-content-between
                            align-items-start">

                    <div class="d-flex align-items-center">

                        <div class="rounded-3
                                    bg-primary
                                    bg-opacity-10
                                    text-primary
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                    me-3"
                             style="width:70px;height:70px;">

                            <i class="bi bi-bus-front fs-1"></i>

                        </div>

                        <div>

                            <h3 class="fw-bold mb-1">
                                {{ $bus->bus_number }}
                            </h3>

                            <p class="text-muted mb-0">
                                {{ $bus->vehicle_number }}
                            </p>

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="mt-3 mt-md-0">

                        @if($bus->status === 'active')

                            <span class="badge bg-success-subtle
                                         text-success
                                         px-3 py-2 fs-6">

                                <i class="bi bi-check-circle me-1"></i>

                                Active

                            </span>

                        @elseif($bus->status === 'maintenance')

                            <span class="badge bg-warning-subtle
                                         text-warning
                                         px-3 py-2 fs-6">

                                <i class="bi bi-tools me-1"></i>

                                Maintenance

                            </span>

                        @else

                            <span class="badge bg-secondary-subtle
                                         text-secondary
                                         px-3 py-2 fs-6">

                                <i class="bi bi-x-circle me-1"></i>

                                Inactive

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
            VEHICLE INFORMATION
        ========================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-bus-front me-2 text-primary"></i>

                    Vehicle Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    {{-- Bus Number --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Bus Number
                        </div>

                        <div class="fw-semibold fs-5">
                            {{ $bus->bus_number }}
                        </div>

                    </div>


                    {{-- Vehicle Number --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Vehicle Number
                        </div>

                        <div class="fw-semibold fs-5">
                            {{ $bus->vehicle_number }}
                        </div>

                    </div>


                    {{-- Capacity --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Seating Capacity
                        </div>

                        <div class="fw-semibold">

                            <i class="bi bi-people me-1 text-primary"></i>

                            {{ $bus->capacity }} seats

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Current Status
                        </div>

                        <div class="fw-semibold text-capitalize">

                            {{ $bus->status }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
            DRIVER INFORMATION
        ========================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-person-badge me-2 text-primary"></i>

                    Driver Information

                </h5>

            </div>

            <div class="card-body">

                @if($bus->driver_name || $bus->driver_phone)

                    <div class="row g-4">

                        {{-- Driver Name --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Driver Name
                            </div>

                            <div class="fw-semibold">

                                <i class="bi bi-person me-1"></i>

                                {{ $bus->driver_name ?: 'Not provided' }}

                            </div>

                        </div>


                        {{-- Driver Phone --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Phone Number
                            </div>

                            @if($bus->driver_phone)

                                <a href="tel:{{ $bus->driver_phone }}"
                                   class="fw-semibold text-decoration-none">

                                    <i class="bi bi-telephone me-1"></i>

                                    {{ $bus->driver_phone }}

                                </a>

                            @else

                                <span class="text-muted">
                                    Not provided
                                </span>

                            @endif

                        </div>

                    </div>

                @else

                    <div class="text-center py-4">

                        <i class="bi bi-person-x
                                  text-muted
                                  fs-1"></i>

                        <p class="text-muted mt-2 mb-0">
                            No driver has been assigned to this bus.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- =========================
            ROUTE INFORMATION
        ========================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-signpost-2 me-2 text-primary"></i>

                    Route Information

                </h5>

            </div>

            <div class="card-body">

                @if($bus->route ||
                    $bus->pickup_location ||
                    $bus->drop_location)

                    <div class="row g-4">

                        {{-- Route --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Route
                            </div>

                            <div class="fw-semibold fs-5">

                                {{ $bus->route ?: 'Not specified' }}

                            </div>

                        </div>


                        {{-- Pickup --}}
                        <div class="col-md-6">

                            <div class="d-flex">

                                <div class="text-success me-3">

                                    <i class="bi bi-geo-alt-fill fs-4"></i>

                                </div>

                                <div>

                                    <div class="text-muted small">
                                        Pickup Location
                                    </div>

                                    <div class="fw-semibold mt-1">

                                        {{ $bus->pickup_location ?: 'Not specified' }}

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Drop --}}
                        <div class="col-md-6">

                            <div class="d-flex">

                                <div class="text-danger me-3">

                                    <i class="bi bi-geo-alt-fill fs-4"></i>

                                </div>

                                <div>

                                    <div class="text-muted small">
                                        Drop Location
                                    </div>

                                    <div class="fw-semibold mt-1">

                                        {{ $bus->drop_location ?: 'Not specified' }}

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @else

                    <div class="text-center py-4">

                        <i class="bi bi-signpost
                                  text-muted
                                  fs-1"></i>

                        <p class="text-muted mt-2 mb-0">
                            No route information has been added.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- =========================
            DESCRIPTION
        ========================== --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">

                    <i class="bi bi-card-text me-2 text-primary"></i>

                    Description

                </h5>

            </div>

            <div class="card-body">

                @if($bus->description)

                    <p class="text-muted mb-0"
                       style="white-space: pre-line;">

                        {{ $bus->description }}

                    </p>

                @else

                    <p class="text-muted mb-0">
                        No additional description available.
                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================
        RIGHT COLUMN
    ========================== --}}
    <div class="col-xl-4">

        {{-- Quick Information --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">
                    Quick Information
                </h5>

            </div>

            <div class="card-body p-0">

                {{-- Capacity --}}
                <div class="d-flex align-items-center
                            px-4 py-3
                            border-bottom">

                    <div class="rounded-circle
                                bg-primary
                                bg-opacity-10
                                text-primary
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3"
                         style="width:42px;height:42px;">

                        <i class="bi bi-people"></i>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Capacity
                        </small>

                        <span class="fw-semibold">
                            {{ $bus->capacity }} seats
                        </span>

                    </div>

                </div>


                {{-- Driver --}}
                <div class="d-flex align-items-center
                            px-4 py-3
                            border-bottom">

                    <div class="rounded-circle
                                bg-success
                                bg-opacity-10
                                text-success
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3"
                         style="width:42px;height:42px;">

                        <i class="bi bi-person-badge"></i>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Driver
                        </small>

                        <span class="fw-semibold">

                            {{ $bus->driver_name ?: 'Not assigned' }}

                        </span>

                    </div>

                </div>


                {{-- Route --}}
                <div class="d-flex align-items-center
                            px-4 py-3
                            border-bottom">

                    <div class="rounded-circle
                                bg-info
                                bg-opacity-10
                                text-info
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3"
                         style="width:42px;height:42px;">

                        <i class="bi bi-signpost-2"></i>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Route
                        </small>

                        <span class="fw-semibold">

                            {{ $bus->route ?: 'Not specified' }}

                        </span>

                    </div>

                </div>


                {{-- Added Date --}}
                <div class="d-flex align-items-center
                            px-4 py-3">

                    <div class="rounded-circle
                                bg-secondary
                                bg-opacity-10
                                text-secondary
                                d-flex
                                align-items-center
                                justify-content-center
                                me-3"
                         style="width:42px;height:42px;">

                        <i class="bi bi-calendar3"></i>

                    </div>

                    <div>

                        <small class="text-muted d-block">
                            Added On
                        </small>

                        <span class="fw-semibold">

                            {{ $bus->created_at?->format('d M Y') }}

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
            ACTIONS
        ========================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">
                    Actions
                </h5>

            </div>

            <div class="card-body">

                <a href="{{ route('buses.edit', $bus) }}"
                   class="btn btn-primary w-100 mb-2">

                    <i class="bi bi-pencil me-1"></i>

                    Edit Bus

                </a>


                <button
                    type="button"
                    class="btn btn-outline-danger w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteBusModal"
                >

                    <i class="bi bi-trash me-1"></i>

                    Delete Bus

                </button>

            </div>

        </div>


        {{-- =========================
            SYSTEM INFORMATION
        ========================== --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h6 class="fw-bold mb-3">
                    System Information
                </h6>

                <div class="mb-3">

                    <small class="text-muted d-block">
                        Bus ID
                    </small>

                    <span class="fw-semibold">
                        #{{ $bus->id }}
                    </span>

                </div>


                <div class="mb-3">

                    <small class="text-muted d-block">
                        Created
                    </small>

                    <span class="fw-semibold">

                        {{ $bus->created_at?->format('d M Y, h:i A') }}

                    </span>

                </div>


                <div>

                    <small class="text-muted d-block">
                        Last Updated
                    </small>

                    <span class="fw-semibold">

                        {{ $bus->updated_at?->format('d M Y, h:i A') }}

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================
    DELETE MODAL
========================== --}}
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

                        This will permanently remove this bus
                        from the transportation system.

                    </p>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light border"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <form
                    action="{{ route('buses.destroy', $bus) }}"
                    method="POST"
                >

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-danger"
                    >

                        <i class="bi bi-trash me-1"></i>

                        Delete Bus

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
```

</div>

@endsection
