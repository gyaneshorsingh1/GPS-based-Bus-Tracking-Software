@php($page = 'buses')

@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Add Bus
            </h3>

            <p class="text-muted mb-0">
                Add a new school bus to the transportation system.
            </p>
        </div>

        <a href="{{ route('buses.index') }}"
           class="btn btn-light border">

            <i class="bi bi-arrow-left me-1"></i>

            Back

        </a>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <form action="{{ route('buses.store') }}"
                  method="POST">

                @csrf

                <div class="row g-4">

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Bus Number *
                        </label>

                        <input
                            type="text"
                            name="bus_number"
                            class="form-control"
                            placeholder="BUS-001"
                            value="{{ old('bus_number') }}"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Vehicle Number *
                        </label>

                        <input
                            type="text"
                            name="vehicle_number"
                            class="form-control"
                            placeholder="BA 1 KHA 1234"
                            value="{{ old('vehicle_number') }}"
                            required
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Driver Name
                        </label>

                        <input
                            type="text"
                            name="driver_name"
                            class="form-control"
                            placeholder="Driver name"
                            value="{{ old('driver_name') }}"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Driver Phone
                        </label>

                        <input
                            type="text"
                            name="driver_phone"
                            class="form-control"
                            placeholder="98XXXXXXXX"
                            value="{{ old('driver_phone') }}"
                        >

                    </div>


                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Capacity *
                        </label>

                        <input
                            type="number"
                            name="capacity"
                            class="form-control"
                            min="1"
                            max="200"
                            value="{{ old('capacity', 40) }}"
                            required
                        >

                    </div>


                    <div class="col-md-8">

                        <label class="form-label fw-semibold">
                            Route
                        </label>

                        <input
                            type="text"
                            name="route"
                            class="form-control"
                            placeholder="Janakpur → School"
                            value="{{ old('route') }}"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Pickup Location
                        </label>

                        <input
                            type="text"
                            name="pickup_location"
                            class="form-control"
                            placeholder="Main pickup point"
                            value="{{ old('pickup_location') }}"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Drop Location
                        </label>

                        <input
                            type="text"
                            name="drop_location"
                            class="form-control"
                            placeholder="School / final stop"
                            value="{{ old('drop_location') }}"
                        >

                    </div>


                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Status *
                        </label>

                        <select name="status"
                                class="form-select"
                                required>

                            <option value="active"
                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="maintenance"
                                {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                Maintenance
                            </option>

                            <option value="inactive"
                                {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    <div class="col-12">

                        <label class="form-label fw-semibold">
                            Description
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"
                            placeholder="Additional information about this bus..."
                        >{{ old('description') }}</textarea>

                    </div>


                    <div class="col-12">

                        <hr>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('buses.index') }}"
                               class="btn btn-light border">

                                Cancel

                            </a>

                            <button type="submit"
                                    class="btn btn-primary px-4">

                                <i class="bi bi-check-lg me-1"></i>

                                Save Bus

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection