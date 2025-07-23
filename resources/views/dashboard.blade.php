<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard To-Do List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Berhasil!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Tambah Task Baru
                    </a>

                    {{-- Filtering --}}
                    <div class="flex items-center space-x-2">
                        <label for="status-filter" class="text-sm font-medium text-gray-700">Filter Status:</label>
                        <select id="status-filter" onchange="window.location.href = this.value" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="{{ route('dashboard') }}" {{ !request('status') ? 'selected' : '' }}>Semua</option>
                            <option value="{{ route('dashboard', ['status' => 'pending']) }}" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="{{ route('dashboard', ['status' => 'in_progress']) }}" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="{{ route('dashboard', ['status' => 'done']) }}" {{ request('status') == 'done' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Kolom Pending --}}
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Pending Tasks ({{ $pendingTasks->count() }})</h3>
                        @forelse ($pendingTasks as $task)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-4">
                                <h4 class="text-md font-semibold text-gray-800">{{ $task->title }}</h4>
                                @if ($task->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 100) }}</p>
                                @endif
                                @if ($task->due_date)
                                    <p class="text-xs text-gray-500 mt-2">Batas Waktu: {{ $task->due_date->format('d M Y') }}</p>
                                @endif
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Edit
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus task ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Hapus
                                        </button>
                                    </form>
                                    <form action="{{ route('tasks.markAsDone', $task) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Tidak ada task pending.</p>
                        @endforelse
                    </div>

                    {{-- Kolom In Progress --}}
                    <div class="bg-blue-50 p-4 rounded-lg shadow-sm border border-blue-200">
                        <h3 class="text-lg font-semibold text-blue-700 mb-4 border-b pb-2">In Progress Tasks ({{ $inProgressTasks->count() }})</h3>
                        @forelse ($inProgressTasks as $task)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-blue-200 mb-4">
                                <h4 class="text-md font-semibold text-gray-800">{{ $task->title }}</h4>
                                @if ($task->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 100) }}</p>
                                @endif
                                @if ($task->due_date)
                                    <p class="text-xs text-gray-500 mt-2">Batas Waktu: {{ $task->due_date->format('d M Y') }}</p>
                                @endif
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Edit
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus task ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Hapus
                                        </button>
                                    </form>
                                    <form action="{{ route('tasks.markAsDone', $task) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Tidak ada task dalam proses.</p>
                        @endforelse
                    </div>

                    {{-- Kolom Done --}}
                    <div class="bg-green-50 p-4 rounded-lg shadow-sm border border-green-200">
                        <h3 class="text-lg font-semibold text-green-700 mb-4 border-b pb-2">Completed Tasks ({{ $doneTasks->count() }})</h3>
                        @forelse ($doneTasks as $task)
                            <div class="bg-white p-4 rounded-lg shadow-sm border border-green-200 mb-4">
                                <h4 class="text-md font-semibold text-gray-800 line-through">{{ $task->title }}</h4>
                                @if ($task->description)
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 100) }}</p>
                                @endif
                                @if ($task->due_date)
                                    <p class="text-xs text-gray-500 mt-2">Selesai pada: {{ $task->updated_at->format('d M Y') }}</p>
                                @endif
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 focus:bg-blue-600 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Edit
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus task ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Tidak ada task yang sudah selesai.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

