@php($page = 'drivers')

<x-app-layout page="drivers">

```
<div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">

    {{-- PAGE HEADER --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Edit Driver
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Update driver information — {{ $driver->employee_id }}
            </p>
        </div>

        <a
            href="{{ route('drivers.show', $driver) }}"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
        >
            Back
        </a>

    </div>


    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())

        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">

            <p class="font-medium">
                Please fix the following errors:
            </p>

            <ul class="mt-2 list-inside list-disc">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))

        <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
            {{ session('success') }}
        </div>

    @endif


    {{-- FORM --}}
    <form
        action="{{ route('drivers.update', $driver) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        {{-- PERSONAL INFORMATION --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Personal Information
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update the driver's personal details.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2 lg:grid-cols-3">


                {{-- PROFILE PHOTO --}}
                <div class="md:col-span-2 lg:col-span-3">

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Profile Photo
                    </label>


                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                        @if($driver->profile_photo)

                            <img
                                src="{{ asset('storage/' . $driver->profile_photo) }}"
                                alt="{{ $driver->full_name }}"
                                class="h-20 w-20 rounded-full object-cover"
                            >

                        @else

                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-100 text-2xl font-semibold text-gray-500 dark:bg-gray-800 dark:text-gray-300">
                                {{ strtoupper(substr($driver->first_name, 0, 1)) }}
                            </div>

                        @endif


                        <div class="flex-1">

                            <input
                                type="file"
                                name="profile_photo"
                                accept="image/*"
                                class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-900 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium hover:file:bg-gray-200 focus:border-brand-500 focus:outline-none dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:file:bg-gray-700 dark:file:text-gray-200"
                            >

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Leave empty to keep the current photo.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- EMPLOYEE ID --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Employee ID <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="employee_id"
                        value="{{ old('employee_id', $driver->employee_id) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- FIRST NAME --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        First Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="first_name"
                        value="{{ old('first_name', $driver->first_name) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- LAST NAME --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Last Name <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="last_name"
                        value="{{ old('last_name', $driver->last_name) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- GENDER --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Gender <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="gender"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                        <option value="">Select Gender</option>

                        <option value="Male" {{ old('gender', $driver->gender) === 'Male' ? 'selected' : '' }}>
                            Male
                        </option>

                        <option value="Female" {{ old('gender', $driver->gender) === 'Female' ? 'selected' : '' }}>
                            Female
                        </option>

                        <option value="Other" {{ old('gender', $driver->gender) === 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>

                </div>


                {{-- DATE OF BIRTH --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date of Birth <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth', $driver->date_of_birth?->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- PHONE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Phone Number <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $driver->phone) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- EMAIL --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $driver->email) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- ADDRESS --}}
                <div class="md:col-span-2 lg:col-span-3">

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Address <span class="text-red-500">*</span>
                    </label>

                    <textarea
                        name="address"
                        rows="3"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >{{ old('address', $driver->address) }}</textarea>

                </div>


                {{-- CITY --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        City
                    </label>

                    <input
                        type="text"
                        name="city"
                        value="{{ old('city', $driver->city) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- STATE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        State
                    </label>

                    <input
                        type="text"
                        name="state"
                        value="{{ old('state', $driver->state) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- COUNTRY --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Country
                    </label>

                    <input
                        type="text"
                        name="country"
                        value="{{ old('country', $driver->country ?? 'Nepal') }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- POSTAL CODE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Postal Code
                    </label>

                    <input
                        type="text"
                        name="postal_code"
                        value="{{ old('postal_code', $driver->postal_code) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>

            </div>

        </div>


        {{-- LICENSE INFORMATION --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    License Information
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update the driver's driving license details.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2 lg:grid-cols-3">


                {{-- LICENSE NUMBER --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        License Number <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        name="license_number"
                        value="{{ old('license_number', $driver->license_number) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- LICENSE TYPE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        License Type <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="license_type"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                        <option value="">Select License Type</option>

                        <option value="Heavy Vehicle" {{ old('license_type', $driver->license_type) === 'Heavy Vehicle' ? 'selected' : '' }}>
                            Heavy Vehicle
                        </option>

                        <option value="Bus" {{ old('license_type', $driver->license_type) === 'Bus' ? 'selected' : '' }}>
                            Bus
                        </option>

                        <option value="Other" {{ old('license_type', $driver->license_type) === 'Other' ? 'selected' : '' }}>
                            Other
                        </option>

                    </select>

                </div>


                {{-- EXPERIENCE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Driving Experience (Years)
                    </label>

                    <input
                        type="number"
                        name="experience_years"
                        value="{{ old('experience_years', $driver->experience_years) }}"
                        min="0"
                        max="80"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- ISSUE DATE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        License Issue Date <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="license_issue_date"
                        value="{{ old('license_issue_date', $driver->license_issue_date?->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- EXPIRY DATE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        License Expiry Date <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="license_expiry_date"
                        value="{{ old('license_expiry_date', $driver->license_expiry_date?->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>

            </div>

        </div>


        {{-- EMPLOYMENT INFORMATION --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Employment Information
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update school assignment and employment status.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2 lg:grid-cols-3">


                {{-- SCHOOL --}}
                @if(auth()->user()->hasAnyRole(['School Admin', 'Principal']))

                    <div class="lg:col-span-1">

                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            School
                        </label>

                        <input
                            type="text"
                            value="{{ $driver->school->name ?? 'School not assigned' }}"
                            readonly
                            class="w-full cursor-not-allowed rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-600 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                        >

                    </div>

                @else

                    <div class="lg:col-span-1">

                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            School <span class="text-red-500">*</span>
                        </label>

                        <select
                            name="school_id"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        >

                            <option value="">
                                Select School
                            </option>

                            @foreach($schools as $school)

                                <option
                                    value="{{ $school->id }}"
                                    {{ old('school_id', $driver->school_id) == $school->id ? 'selected' : '' }}
                                >
                                    {{ $school->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif


                {{-- JOINING DATE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Joining Date <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="date"
                        name="joining_date"
                        value="{{ old('joining_date', $driver->joining_date?->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- STATUS --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Status <span class="text-red-500">*</span>
                    </label>

                    <select
                        name="status"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                        <option value="Active" {{ old('status', $driver->status) === 'Active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="Inactive" {{ old('status', $driver->status) === 'Inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                        <option value="Suspended" {{ old('status', $driver->status) === 'Suspended' ? 'selected' : '' }}>
                            Suspended
                        </option>

                    </select>

                </div>


                {{-- REMARKS --}}
                <div class="md:col-span-2 lg:col-span-3">

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Remarks
                    </label>

                    <textarea
                        name="remarks"
                        rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >{{ old('remarks', $driver->remarks) }}</textarea>

                </div>

            </div>

        </div>


        {{-- EMERGENCY CONTACT --}}
        <div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Emergency Contact
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Update emergency contact information.
                </p>

            </div>


            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">


                {{-- CONTACT NAME --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contact Name
                    </label>

                    <input
                        type="text"
                        name="emergency_contact_name"
                        value="{{ old('emergency_contact_name', $driver->emergency_contact_name) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>


                {{-- CONTACT PHONE --}}
                <div>

                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contact Number
                    </label>

                    <input
                        type="text"
                        name="emergency_contact_phone"
                        value="{{ old('emergency_contact_phone', $driver->emergency_contact_phone) }}"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                    >

                </div>

            </div>

        </div>


        {{-- ACTION BUTTONS --}}
        <div class="mb-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('drivers.show', $driver) }}"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                Cancel
            </a>

            <button
                type="submit"
                class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600"
            >
                Update Driver
            </button>

        </div>

    </form>

</div>
```

</x-app-layout>
