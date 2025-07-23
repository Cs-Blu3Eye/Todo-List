<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class TaskController extends BaseController
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan daftar task.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = $user->tasks();

        // Filter status jika ada
        if ($request->has('status') && in_array($request->status, ['pending', 'in_progress', 'done'])) {
            $query->where('status', $request->status);
        }

        $tasks = $query
            ->orderBy('due_date', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan berdasarkan status
        return view('dashboard', [
            'pendingTasks' => $tasks->where('status', 'pending'),
            'inProgressTasks' => $tasks->where('status', 'in_progress'),
            'doneTasks' => $tasks->where('status', 'done'),
        ]);
    }

    /**
     * Form tambah task.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Simpan task baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,done',
        ]);

        Auth::user()->tasks()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Task berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail task.
     */
    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    /**
     * Form edit task.
     */
    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        return view('tasks.edit', compact('task'));
    }

    /**
     * Perbarui task.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,done',
        ]);

        $task->update($validated);

        return redirect()->route('dashboard')->with('success', 'Task berhasil diperbarui!');
    }

    /**
     * Hapus task.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $task->delete();

        return redirect()->route('dashboard')->with('success', 'Task berhasil dihapus!');
    }

    /**
     * Tandai task selesai.
     */
    public function markAsDone(Task $task): RedirectResponse
    {
        if (Auth::id() !== $task->user_id) {
            dd($task->user_id);
            // abort(403, 'Kamu bukan pemilik task ini.');
        }

        $task->update(['status' => 'done']);

        return redirect()->route('dashboard')->with('success', 'Task berhasil ditandai selesai!');
    }
}
