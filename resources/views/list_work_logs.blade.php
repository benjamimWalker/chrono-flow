@vite('resources/css/app.css')
@vite(['resources/js/app.js'])

<div class="fixed inset-0 z-40 bg-white dark:bg-gray-900 overflow-auto">
    <div class="absolute inset-0 flex flex-col p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Work Logs</h2>
            <div class="flex space-x-4">
                <a href="{{ route('home') }}"
                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:bg-green-500 dark:hover:bg-green-600">
                    Import CSV
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="flex-1 overflow-hidden bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="overflow-auto h-full">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Employee
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Hours
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Description
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($workLogs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $log->employee_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $log->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $log->hours }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <div class="line-clamp-2">
                                    {{ $log->description }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No work logs found. <a href="{{ route('home') }}" class="text-green-600 hover:text-green-500 dark:text-green-400 dark:hover:text-green-300">Import a CSV</a> to get started.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 10-Page Pagination -->
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Showing {{ $workLogs->firstItem() }} to {{ $workLogs->lastItem() }} of {{ $workLogs->total() }} results
            </div>
            <div class="flex items-center space-x-1">
                {{-- Previous Page --}}
                @if ($workLogs->onFirstPage())
                    <span class="px-3 py-1 rounded-md text-gray-400 dark:text-gray-500 cursor-not-allowed">&lsaquo;</span>
                @else
                    <a href="{{ $workLogs->previousPageUrl() }}" class="px-3 py-1 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">&lsaquo;</a>
                @endif

                {{-- Always show first page --}}
                @if ($workLogs->currentPage() > 5)
                    <a href="{{ $workLogs->url(1) }}" class="px-3 py-1 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">1</a>
                    <span class="px-1">...</span>
                @endif

                {{-- Dynamic page numbers (show 10 pages total) --}}
                @php
                    $start = max($workLogs->currentPage() - 4, 1);
                    $end = min($start + 9, $workLogs->lastPage());

                    // Adjust if we're near the end
                    if ($end - $start < 9) {
                        $start = max($end - 9, 1);
                    }
                @endphp

                @for ($page = $start; $page <= $end; $page++)
                    @if ($page == $workLogs->currentPage())
                        <span class="px-3 py-1 rounded-md bg-green-600 text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $workLogs->url($page) }}" class="px-3 py-1 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Always show last page --}}
                @if ($workLogs->currentPage() < $workLogs->lastPage() - 5)
                    <span class="px-1">...</span>
                    <a href="{{ $workLogs->url($workLogs->lastPage()) }}" class="px-3 py-1 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">{{ $workLogs->lastPage() }}</a>
                @endif

                {{-- Next Page --}}
                @if ($workLogs->hasMorePages())
                    <a href="{{ $workLogs->nextPageUrl() }}" class="px-3 py-1 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">&rsaquo;</a>
                @else
                    <span class="px-3 py-1 rounded-md text-gray-400 dark:text-gray-500 cursor-not-allowed">&rsaquo;</span>
                @endif
            </div>
        </div>

        <!-- Close Button -->
        <button
            class="absolute top-6 right-6 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
            @click="$dispatch('close-worklogs')"
        >
        </button>
    </div>
</div>
