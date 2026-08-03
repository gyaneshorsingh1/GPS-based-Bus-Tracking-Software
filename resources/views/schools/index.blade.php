<x-app-layout page="school-management">
    <div class="mx-auto max-w-(--breakpoint-2xl) p-4 md:p-6">
<form action="{{ route('schools.index') }}" method="GET" class="mb-4">
    <div class="flex gap-2">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search schools..."
            class="w-full rounded-lg border px-4 py-2"
        >

        <button
            type="submit"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white"
        >
            Search
        </button>

        <a
            href="{{ route('schools.index') }}"
            class="rounded-lg bg-gray-500 px-4 py-2 text-white"
        >
            Reset
        </a>
    </div>
</form>
    </div>
</x-app-layout>