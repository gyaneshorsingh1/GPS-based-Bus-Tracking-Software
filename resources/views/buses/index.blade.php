@php($page = 'buses')

@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    {{-- =========================
        HEADER
    ========================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Bus Management
            </h3>

            <p class="text-muted mb-0">
                Manage school buses, drivers, routes and transportation.
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <a href="{{ route('buses.create') }}"
               class="btn btn-primary px-4">

                <i class="bi bi-plus-lg me-1"></i>

                Add Bus

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

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================
        STATISTICS
    ========================== --}}
    <div class="row g-4 mb-4">

        {{-- Total Buses --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Buses
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $totalBuses }}
                            </h3>

                        </div>

                        <div class="bg-primary bg-opacity-10
                                    text-primary rounded-circle
                                    d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="bi bi-bus-front fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Active Buses
                            </p>

                            <h3 class="fw-bold text-success mb-0">
                                {{ $activeBuses }}
                            </h3>

                        </div>

                        <div class="bg-success bg-opacity-10
                                    text-success rounded-circle
                                    d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="bi bi-check-circle fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Maintenance --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Maintenance
                            </p>

                            <h3 class="fw-bold text-warning mb-0">
                                {{ $maintenanceBuses }}
                            </h3>

                        </div>

                        <div class="bg-warning bg-opacity-10
                                    text-warning rounded-circle
                                    d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="bi bi-tools fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Capacity --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Active Capacity
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $totalCapacity }}
                            </h3>

                            <small class="text-muted">
                                seats
                            </small>

                        </div>

                        <div class="bg-info bg-opacity-10
                                    text-info rounded-circle
                                    d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="bi bi-people fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================
        SEARCH + FILTER
    ========================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('buses.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Bus number, vehicle number, driver or route..."
                            >

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-3">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="maintenance"
                                {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                                Maintenance
                            </option>

                            <option value="inactive"
                                {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-3">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary flex-grow-1">

                                <i class="bi bi-search me-1"></i>

                                Search

                            </button>

                            <a href="{{ route('buses.index') }}"
                               class="btn btn-light border">

                                <i class="bi bi-arrow-clockwise"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================
        BUS TABLE
    ========================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        School Buses
                    </h5>

                    <small class="text-muted">
                        Showing {{ $buses->count() }}
                        of {{ $buses->total() }} buses
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($buses->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Bus
                                </th>

                                <th>
                                    Vehicle Number
                                </th>

                                <th>
                                    Driver
                                </th>

                                <th>
                                    Route
                                </th>

                                <th>
                                    Capacity
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end px-4">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($buses as $bus)

                                <tr>

                                    {{-- Bus --}}
                                    <td class="px-4">

                                        <div class="d-flex align-items-center">

                                            <div class="rounded-circle
                                                        bg-primary
                                                        bg-opacity-10
                                                        text-primary
                                                        d-flex
                                                        align-items-center
                                                        justify-content-center
                                                        me-3"
                                                 style="width:42px;height:42px;">

                                                <i class="bi bi-bus-front"></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    {{ $bus->bus_number }}
                                                </div>

                                                <small class="text-muted">
                                                    ID #{{ $bus->id }}
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Vehicle --}}
                                    <td>

                                        <span class="fw-medium">
                                            {{ $bus->vehicle_number }}
                                        </span>

                                    </td>


                                    {{-- Driver --}}
                                    <td>

                                        @if($bus->driver_name)

                                            <div class="fw-medium">
                                                {{ $bus->driver_name }}
                                            </div>

                                            @if($bus->driver_phone)

                                                <small class="text-muted">

                                                    <i class="bi bi-telephone me-1"></i>

                                                    {{ $bus->driver_phone }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                Not assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Route --}}
                                    <td>

                                        @if($bus->route)

                                            <span>
                                                {{ $bus->route }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                Not specified
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Capacity --}}
                                    <td>

                                        <span class="fw-semibold">
                                            {{ $bus->capacity }}
                                        </span>

                                        <small class="text-muted">
                                            seats
                                        </small>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($bus->status === 'active')

                                            <span class="badge bg-success-subtle
                                                         text-success px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Active
                                            </span>

                                        @elseif($bus->status === 'maintenance')

                                            <span class="badge bg-warning-subtle
                                                         text-warning px-3 py-2">
                                                <i class="bi bi-tools me-1"></i>
                                                Maintenance
                                            </span>

                                        @else

                                            <span class="badge bg-secondary-subtle
                                                         text-secondary px-3 py-2">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end px-4">

                                        <div class="dropdown">

                                            <button
                                                class="btn btn-sm btn-light border"
                                                type="button"
                                                data-bs-toggle="dropdown">

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route('buses.show', $bus) }}">

                                                        <i class="bi bi-eye me-2"></i>

                                                        View Details

                                                    </a>

                                                </li>

                                                <li>

                                                    <a class="dropdown-item"
                                                       href="{{ route('buses.edit', $bus) }}">

                                                        <i class="bi bi-pencil me-2"></i>

                                                        Edit Bus

                                                    </a>

                                                </li>

                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>

                                                <li>

                                                    <form
                                                        action="{{ route('buses.destroy', $bus) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this bus?');"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger">

                                                            <i class="bi bi-trash me-2"></i>

                                                            Delete Bus

                                                        </button>

                                                    </form>

                                                </li>

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                {{-- Empty State --}}

                <div class="text-center py-5">

                    <div class="mb-3">

                        <div class="rounded-circle
                                    bg-light
                                    d-inline-flex
                                    align-items-center
                                    justify-content-center"
                             style="width:80px;height:80px;">

                            <i class="bi bi-bus-front
                                      text-muted
                                      fs-1"></i>

                        </div>

                    </div>

                    <h5 class="fw-bold">
                        No buses found
                    </h5>

                    <p class="text-muted mb-4">

                        @if(request()->filled('search') || request()->filled('status'))

                            Try changing your search or filter.

                        @else

                            Start by adding your first school bus.

                        @endif

                    </p>

                    @if(request()->filled('search') || request()->filled('status'))

                        <a href="{{ route('buses.index') }}"
                           class="btn btn-light border">

                            Clear Filters

                        </a>

                    @else

                        <a href="{{ route('buses.create') }}"
                           class="btn btn-primary">

                            <i class="bi bi-plus-lg me-1"></i>

                            Add First Bus

                        </a>

                    @endif

                </div>

            @endif

        </div>


        {{-- Pagination --}}

        @if($buses->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                <div class="d-flex justify-content-between
                            align-items-center flex-wrap gap-2">

                    <div class="text-muted small">

                        Showing
                        {{ $buses->firstItem() }}
                        to
                        {{ $buses->lastItem() }}
                        of
                        {{ $buses->total() }}

                    </div>

                    <div>

                        {{ $buses->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection