<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of todos with filter, search, and sorting.
     */
    public function index(Request $request)
    {
        $query = Todo::query();

        // 1. Filter by Status (all, active, completed, overdue)
        $status = $request->query('status', 'all');
        if ($status === 'active') {
            $query->where('is_completed', false);
        } elseif ($status === 'completed') {
            $query->where('is_completed', true);
        } elseif ($status === 'overdue') {
            $query->where('is_completed', false)
                  ->whereNotNull('due_date')
                  ->where('due_date', '<', now()->toDateString());
        }

        // 2. Filter by Priority (low, medium, high)
        if ($request->filled('priority') && in_array($request->query('priority'), ['low', 'medium', 'high'])) {
            $query->where('priority', $request->query('priority'));
        }

        // 3. Search by title / description
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 4. Sorting
        $sortBy = $request->query('sort', 'default');
        switch ($sortBy) {
            case 'deadline_asc':
                $query->orderByRaw('due_date IS NULL, due_date ASC');
                break;
            case 'deadline_desc':
                $query->orderByRaw('due_date IS NULL, due_date DESC');
                break;
            case 'priority_desc':
                $query->orderByRaw("CASE priority WHEN 'high' THEN 3 WHEN 'medium' THEN 2 WHEN 'low' THEN 1 ELSE 0 END DESC")
                      ->orderByRaw('due_date IS NULL, due_date ASC');
                break;
            case 'priority_asc':
                $query->orderByRaw("CASE priority WHEN 'high' THEN 3 WHEN 'medium' THEN 2 WHEN 'low' THEN 1 ELSE 0 END ASC")
                      ->orderByRaw('due_date IS NULL, due_date ASC');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            default:
                // Default: Incomplete first, then highest priority, then nearest deadline
                $query->orderBy('is_completed', 'asc')
                      ->orderByRaw("CASE priority WHEN 'high' THEN 3 WHEN 'medium' THEN 2 WHEN 'low' THEN 1 ELSE 0 END DESC")
                      ->orderByRaw('due_date IS NULL, due_date ASC')
                      ->orderBy('created_at', 'desc');
                break;
        }

        $todos = $query->paginate(12)->withQueryString();

        // Statistics for overview cards & badges
        $stats = [
            'total' => Todo::count(),
            'active' => Todo::where('is_completed', false)->count(),
            'completed' => Todo::where('is_completed', true)->count(),
            'overdue' => Todo::where('is_completed', false)
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->toDateString())
                ->count(),
            'high_priority' => Todo::where('is_completed', false)
                ->where('priority', 'high')
                ->count(),
        ];

        return view('todos.index', compact('todos', 'stats', 'status', 'sortBy'));
    }

    /**
     * Store a newly created todo in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ], [
            'title.required' => 'Judul kegiatan wajib diisi.',
            'title.max' => 'Judul tidak boleh melebihi 255 karakter.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
            'due_date.date' => 'Format tanggal deadline tidak valid.',
        ]);

        $todo = Todo::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
            'is_completed' => false,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil ditambahkan!',
                'data' => $todo,
            ]);
        }

        return redirect()->back()->with('success', 'Tugas "' . $todo->title . '" berhasil ditambahkan!');
    }

    /**
     * Return single todo as JSON for view / edit modal or API.
     */
    public function show(Todo $todo)
    {
        return response()->json([
            'id' => $todo->id,
            'title' => $todo->title,
            'description' => $todo->description,
            'priority' => $todo->priority,
            'priority_label' => $todo->priority_label,
            'priority_badge' => $todo->priority_badge_class,
            'due_date' => $todo->due_date ? $todo->due_date->format('Y-m-d') : null,
            'due_date_formatted' => $todo->due_date ? $todo->due_date->format('d M Y') : 'Tanpa Deadline',
            'is_completed' => $todo->is_completed,
            'due_status' => $todo->due_status,
            'created_at' => $todo->created_at->format('d M Y H:i'),
            'updated_at' => $todo->updated_at->format('d M Y H:i'),
            'completed_at' => $todo->completed_at ? $todo->completed_at->format('d M Y H:i') : null,
        ]);
    }

    /**
     * Update the specified todo in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ], [
            'title.required' => 'Judul kegiatan wajib diisi.',
            'title.max' => 'Judul tidak boleh melebihi 255 karakter.',
            'priority.required' => 'Prioritas wajib dipilih.',
            'priority.in' => 'Prioritas tidak valid.',
            'due_date.date' => 'Format tanggal deadline tidak valid.',
        ]);

        $todo->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil diperbarui!',
                'data' => $todo,
            ]);
        }

        return redirect()->back()->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Toggle the completed status of the specified todo.
     */
    public function toggle(Request $request, Todo $todo)
    {
        $todo->is_completed = ! $todo->is_completed;
        $todo->completed_at = $todo->is_completed ? now() : null;
        $todo->save();

        $message = $todo->is_completed ? 'Tugas ditandai selesai! 🎉' : 'Tugas dikembalikan ke aktif.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_completed' => $todo->is_completed,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified todo from storage.
     */
    public function destroy(Request $request, Todo $todo)
    {
        $title = $todo->title;
        $todo->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tugas "' . $title . '" berhasil dihapus!',
            ]);
        }

        return redirect()->back()->with('success', 'Tugas "' . $title . '" berhasil dihapus!');
    }
}
