@extends('layouts.app')

@section('title', 'TodoList - Daftar & Manajemen Tugas')

@section('content')
    @php
        $completionRate = $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0;
    @endphp

    <!-- card -->
    <div class="row g-3 mb-4">
        <!-- Total Tasks -->
        <div class="col-6 col-md-3">
            <div class="card card-stat h-100 p-3 shadow-sm border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Total Tugas</span>
                    <span class="badge bg-primary-subtle text-primary rounded-circle p-2">
                        <i class="bi bi-list-task fs-6"></i>
                    </span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['total'] }}</div>
                <div class="text-muted small">Semua catatan tugas anda</div>
            </div>
        </div>

        <!-- Active Tasks -->
        <div class="col-6 col-md-3">
            <div class="card card-stat h-100 p-3 shadow-sm border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Belum Selesai mas</span>
                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-circle p-2">
                        <i class="bi bi-hourglass-split fs-6"></i>
                    </span>
                </div>
                <div class="fs-3 fw-bold text-dark">{{ $stats['active'] }}</div>
                <div class="text-muted small">Perlu dikerjakan mas</div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="col-6 col-md-3">
            <div class="card card-stat h-100 p-3 shadow-sm border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Selesai</span>
                    <span class="badge bg-success-subtle text-success rounded-circle p-2">
                        <i class="bi bi-check2-all fs-6"></i>
                    </span>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-3 fw-bold text-dark">{{ $stats['completed'] }}</span>
                    <span class="text-success small fw-semibold">({{ $completionRate }}%)</span>
                </div>
                <div class="progress progress-thin mt-2">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completionRate }}%" aria-valuenow="{{ $completionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Overdue Tasks -->
        <div class="col-6 col-md-3">
            <div class="card card-stat h-100 p-3 shadow-sm border-start border-4 border-danger">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-medium">Terlambat</span>
                    <span class="badge bg-danger-subtle text-danger rounded-circle p-2">
                        <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                    </span>
                </div>
                <div class="fs-3 fw-bold text-danger">{{ $stats['overdue'] }}</div>
                <div class="text-muted small">Melewati batas deadline</div>
            </div>
        </div>
    </div>

    <!-- Quick Add Accordion / Form Card -->
    <div class="card card-custom mb-4 bg-white shadow-sm overflow-hidden">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-plus-square-fill text-primary fs-5"></i>
                <h5 class="card-title mb-0 fw-bold fs-6">Tambah Tugas Baru</h5>
            </div>
            <button class="btn btn-sm btn-link text-decoration-none text-muted p-0" type="button" data-bs-toggle="collapse" data-bs-target="#quickAddCollapse" aria-expanded="true">
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="quickAddCollapse">
            <div class="card-body p-4">
                <form action="{{ route('todos.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="create_title" class="form-label fw-semibold small text-secondary">Judul Tugas <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-type"></i></span>
                                <input type="text" name="title" id="create_title" class="form-control border-start-0 ps-1 @error('title') is-invalid @enderror" placeholder="Contoh: Menyelesaikan laporan mingguan" value="{{ old('title') }}" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="create_priority" class="form-label fw-semibold small text-secondary">Prioritas <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-flag"></i></span>
                                <select name="priority" id="create_priority" class="form-select border-start-0 ps-1" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>🔵 Rendah</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>🟡 Sedang</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>🔴 Tinggi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="create_due_date" class="form-label fw-semibold small text-secondary">Tenggat Waktu (Deadline)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="due_date" id="create_due_date" class="form-control border-start-0 ps-1" value="{{ old('due_date') }}">
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="create_description" class="form-label fw-semibold small text-secondary">Deskripsi / Catatan Tambahan (Opsional)</label>
                            <textarea name="description" id="create_description" rows="2" class="form-control" placeholder="Tuliskan rincian atau catatan tugas jika ada...">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2 btn-rounded shadow-sm fw-semibold" style="background: var(--primary-gradient); border: none;">
                                <i class="bi bi-plus-lg me-1"></i> Simpan Tugas
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Filter, Search, & Sort Bar -->
    <div class="card card-custom mb-4 p-3 bg-white shadow-sm">
        <form method="GET" action="{{ route('todos.index') }}" id="filterForm">
            <div class="row g-2 align-items-center">
                <!-- Status Tabs / Pills -->
                <div class="col-12 col-lg-auto mb-2 mb-lg-0">
                    <div class="btn-group w-100 flex-wrap" role="group">
                        <a href="{{ route('todos.index', array_merge(request()->except('status', 'page'), ['status' => 'all'])) }}" 
                           class="btn btn-sm {{ ($status === 'all') ? 'btn-primary text-white shadow-sm' : 'btn-outline-secondary' }} px-3">
                            Semua <span class="badge bg-light text-dark ms-1">{{ $stats['total'] }}</span>
                        </a>
                        <a href="{{ route('todos.index', array_merge(request()->except('status', 'page'), ['status' => 'active'])) }}" 
                           class="btn btn-sm {{ ($status === 'active') ? 'btn-warning text-dark shadow-sm fw-medium' : 'btn-outline-secondary' }} px-3">
                            Aktif <span class="badge bg-secondary text-white ms-1">{{ $stats['active'] }}</span>
                        </a>
                        <a href="{{ route('todos.index', array_merge(request()->except('status', 'page'), ['status' => 'completed'])) }}" 
                           class="btn btn-sm {{ ($status === 'completed') ? 'btn-success text-white shadow-sm' : 'btn-outline-secondary' }} px-3">
                            Selesai <span class="badge bg-light text-dark ms-1">{{ $stats['completed'] }}</span>
                        </a>
                        @if($stats['overdue'] > 0)
                        <a href="{{ route('todos.index', array_merge(request()->except('status', 'page'), ['status' => 'overdue'])) }}" 
                           class="btn btn-sm {{ ($status === 'overdue') ? 'btn-danger text-white shadow-sm' : 'btn-outline-danger' }} px-3">
                            Terlambat <span class="badge bg-white text-danger ms-1">{{ $stats['overdue'] }}</span>
                        </a>
                        @endif
                    </div>
                    <!-- Hidden input to maintain status filter during search submit -->
                    <input type="hidden" name="status" value="{{ $status }}">
                </div>

                <!-- Search Input -->
                <div class="col-12 col-md-5 col-lg flex-grow-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari judul atau rincian tugas..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('todos.index', request()->except('search', 'page')) }}" class="btn btn-outline-secondary" title="Hapus pencarian">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Priority Filter -->
                <div class="col-6 col-md-3 col-lg-auto">
                    <select name="priority" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Semua Prioritas</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>🔴 Prioritas Tinggi</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🟡 Prioritas Sedang</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>🔵 Prioritas Rendah</option>
                    </select>
                </div>

                <!-- Sort Filter -->
                <div class="col-6 col-md-4 col-lg-auto">
                    <select name="sort" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="default" {{ $sortBy == 'default' ? 'selected' : '' }}>Urutkan: Default</option>
                        <option value="deadline_asc" {{ $sortBy == 'deadline_asc' ? 'selected' : '' }}>⏰ Deadline Terdekat</option>
                        <option value="deadline_desc" {{ $sortBy == 'deadline_desc' ? 'selected' : '' }}>📅 Deadline Terjauh</option>
                        <option value="priority_desc" {{ $sortBy == 'priority_desc' ? 'selected' : '' }}>⚡ Prioritas Tertinggi</option>
                        <option value="priority_asc" {{ $sortBy == 'priority_asc' ? 'selected' : '' }}>💤 Prioritas Terendah</option>
                        <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>✨ Terbaru Dibuat</option>
                        <option value="oldest" {{ $sortBy == 'oldest' ? 'selected' : '' }}>⏳ Terlama Dibuat</option>
                        <option value="title_asc" {{ $sortBy == 'title_asc' ? 'selected' : '' }}>🔤 Abjad (A-Z)</option>
                    </select>
                </div>

                <!-- Filter Actions (Submit & Reset) -->
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-dark px-3 btn-rounded">
                        <i class="bi bi-funnel-fill me-1"></i> Filter
                    </button>
                    @if(request()->hasAny(['search', 'priority', 'sort']) || $status !== 'all')
                        <a href="{{ route('todos.index') }}" class="btn btn-sm btn-light border px-2 btn-rounded text-muted" title="Reset Semua Filter">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Todo List Items -->
    <div class="card card-custom bg-white shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-card-checklist text-primary fs-5"></i>
                <h6 class="fw-bold mb-0 text-dark">Daftar Kegiatan</h6>
            </div>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small">
                Menampilkan {{ $todos->count() }} dari {{ $todos->total() }} tugas
            </span>
        </div>

        <div class="card-body p-3 p-md-4">
            @if ($todos->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle text-muted" style="width: 80px; height: 80px;">
                            <i class="bi bi-clipboard2-check fs-1 text-secondary opacity-50"></i>
                        </div>
                    </div>
                    @if (request()->hasAny(['search', 'priority', 'status']) && (request('search') || request('priority') || $status !== 'all'))
                        <h5 class="fw-bold text-dark">Tidak Ada Tugas yang Cocok</h5>
                        <p class="text-muted small mb-3">Tidak ditemukan tugas yang sesuai dengan kriteria filter atau pencarian Anda.</p>
                        <a href="{{ route('todos.index') }}" class="btn btn-outline-primary btn-sm px-3 btn-rounded">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                        </a>
                    @else
                        <h5 class="fw-bold text-dark">Belum Ada Tugas</h5>
                        <p class="text-muted small mb-3">Mulai produktif hari ini dengan menambahkan tugas pertama Anda!</p>
                        <button type="button" class="btn btn-primary btn-sm px-3 btn-rounded shadow-sm" data-bs-toggle="modal" data-bs-target="#createTodoModal" style="background: var(--primary-gradient); border: none;">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Tugas Sekarang
                        </button>
                    @endif
                </div>
            @else
                <!-- List of Todos -->
                <div class="d-flex flex-column gap-3">
                    @foreach ($todos as $todo)
                        @php
                            $dueStatus = $todo->due_status;
                            $stripClass = $todo->is_completed 
                                ? 'priority-strip-completed' 
                                : ($todo->priority === 'high' ? 'priority-strip-high' : ($todo->priority === 'medium' ? 'priority-strip-medium' : 'priority-strip-low'));
                        @endphp
                        <div class="todo-item p-3 {{ $todo->is_completed ? 'completed' : '' }} {{ $stripClass }}">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                <!-- Left side: Checkbox + Title + Description + Badges -->
                                <div class="d-flex align-items-start gap-3 flex-grow-1">
                                    <!-- Toggle Complete Form -->
                                    <form action="{{ route('todos.toggle', $todo) }}" method="POST" class="mt-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn p-0 border-0 bg-transparent" title="{{ $todo->is_completed ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                            @if($todo->is_completed)
                                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                                            @else
                                                <i class="bi bi-circle text-secondary fs-4 opacity-75"></i>
                                            @endif
                                        </button>
                                    </form>

                                    <!-- Content -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="todo-title fw-bold text-dark fs-6 {{ $todo->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                                                {{ $todo->title }}
                                            </span>

                                            <!-- Priority Badge -->
                                            <span class="badge {{ $todo->priority_badge_class }} rounded-pill px-2 py-1 small">
                                                <i class="bi bi-flag-fill me-1"></i>{{ $todo->priority_label }}
                                            </span>

                                            <!-- Deadline Badge -->
                                            <span class="badge {{ $dueStatus['badge'] }} rounded-pill px-2 py-1 small">
                                                <i class="bi {{ $dueStatus['icon'] }} me-1"></i>{{ $dueStatus['text'] }}
                                            </span>

                                            @if($todo->is_completed && $todo->completed_at)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">
                                                    <i class="bi bi-check2 me-1"></i>Selesai: {{ $todo->completed_at->format('d M H:i') }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($todo->description)
                                            <p class="todo-desc text-secondary small mb-2 text-break" style="line-height: 1.4;">
                                                {{ Str::limit($todo->description, 130) }}
                                            </p>
                                        @endif

                                        <div class="text-muted small" style="font-size: 0.775rem;">
                                            <i class="bi bi-clock me-1"></i>Dibuat {{ $todo->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Right side: Action Buttons -->
                                <div class="d-flex align-items-center gap-2 align-self-end align-self-md-center">
                                    <!-- View Details Button -->
                                    <button type="button" 
                                            class="btn btn-outline-info action-btn text-info-emphasis" 
                                            title="Lihat Detail"
                                            onclick="viewTodoDetails({{ $todo->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    <!-- Edit Button (Modal trigger) -->
                                    <button type="button" 
                                            class="btn btn-outline-warning action-btn text-warning-emphasis" 
                                            title="Edit Tugas"
                                            onclick="openEditModal({{ json_encode($todo) }})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>

                                    <!-- Delete Button (Modal trigger) -->
                                    <button type="button" 
                                            class="btn btn-outline-danger action-btn" 
                                            title="Hapus Tugas"
                                            onclick="confirmDelete({{ $todo->id }}, {{ json_encode($todo->title) }})">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($todos->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $todos->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- ==================== MODALS ==================== -->

    <!-- 1. Modal Tambah Tugas (Via Button Header) -->
    <div class="modal fade" id="createTodoModal" tabindex="-1" aria-labelledby="createTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-custom border-0 shadow">
                <form action="{{ route('todos.store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-bottom py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 bg-primary-subtle text-primary rounded-3">
                                <i class="bi bi-plus-circle-fill"></i>
                            </div>
                            <h5 class="modal-title fw-bold fs-6" id="createTodoModalLabel">Tambah Tugas Baru</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="modal_create_title" class="form-label fw-semibold small text-secondary">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="modal_create_title" class="form-control" placeholder="Tuliskan judul kegiatan..." required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="modal_create_priority" class="form-label fw-semibold small text-secondary">Prioritas <span class="text-danger">*</span></label>
                                <select name="priority" id="modal_create_priority" class="form-select" required>
                                    <option value="low">🔵 Rendah</option>
                                    <option value="medium" selected>🟡 Sedang</option>
                                    <option value="high">🔴 Tinggi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="modal_create_due_date" class="form-label fw-semibold small text-secondary">Deadline (Opsional)</label>
                                <input type="date" name="due_date" id="modal_create_due_date" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="modal_create_description" class="form-label fw-semibold small text-secondary">Deskripsi / Rincian</label>
                            <textarea name="description" id="modal_create_description" rows="3" class="form-control" placeholder="Tambahkan catatan pendukung..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2 px-4">
                        <button type="button" class="btn btn-light btn-rounded px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-rounded px-4" style="background: var(--primary-gradient); border: none;">Simpan Tugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 2. Modal Edit Tugas (Via Modal) -->
    <div class="modal fade" id="editTodoModal" tabindex="-1" aria-labelledby="editTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-custom border-0 shadow">
                <form id="editTodoForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-bottom py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 bg-warning-subtle text-warning-emphasis rounded-3">
                                <i class="bi bi-pencil-fill"></i>
                            </div>
                            <h5 class="modal-title fw-bold fs-6" id="editTodoModalLabel">Edit Kegiatan</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label fw-semibold small text-secondary">Judul Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="edit_priority" class="form-label fw-semibold small text-secondary">Prioritas <span class="text-danger">*</span></label>
                                <select name="priority" id="edit_priority" class="form-select" required>
                                    <option value="low">🔵 Rendah</option>
                                    <option value="medium">🟡 Sedang</option>
                                    <option value="high">🔴 Tinggi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_due_date" class="form-label fw-semibold small text-secondary">Deadline</label>
                                <input type="date" name="due_date" id="edit_due_date" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="edit_description" class="form-label fw-semibold small text-secondary">Deskripsi / Catatan</label>
                            <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top py-2 px-4">
                        <button type="button" class="btn btn-light btn-rounded px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-dark fw-semibold btn-rounded px-4">Perbarui Tugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. Modal Lihat Detail Tugas -->
    <div class="modal fade" id="viewTodoModal" tabindex="-1" aria-labelledby="viewTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-custom border-0 shadow">
                <div class="modal-header border-bottom py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-2 bg-info-subtle text-info-emphasis rounded-3">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <h5 class="modal-title fw-bold fs-6" id="viewTodoModalLabel">Rincian Tugas</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span id="view_status_badge" class="badge rounded-pill px-3 py-1"></span>
                        <span id="view_priority_badge" class="badge rounded-pill px-3 py-1"></span>
                        <span id="view_due_badge" class="badge rounded-pill px-3 py-1"></span>
                    </div>

                    <h5 id="view_title" class="fw-bold text-dark mb-3"></h5>

                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <span class="text-secondary small fw-semibold d-block mb-1">Deskripsi:</span>
                        <p id="view_description" class="mb-0 text-muted small text-break" style="white-space: pre-line;"></p>
                    </div>

                    <div class="row g-2 text-muted small pt-2 border-top">
                        <div class="col-6">
                            <span>Dibuat:</span> <strong id="view_created_at" class="text-dark d-block"></strong>
                        </div>
                        <div class="col-6">
                            <span>Deadline:</span> <strong id="view_due_date" class="text-dark d-block"></strong>
                        </div>
                        <div class="col-12 mt-2" id="view_completed_wrapper" style="display: none;">
                            <span>Selesai pada:</span> <strong id="view_completed_at" class="text-success d-block"></strong>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm btn-rounded px-3" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteTodoModal" tabindex="-1" aria-labelledby="deleteTodoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content card-custom border-0 shadow">
                <form id="deleteTodoForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header border-bottom py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 bg-danger-subtle text-danger rounded-3">
                                <i class="bi bi-trash3-fill"></i>
                            </div>
                            <h5 class="modal-title fw-bold fs-6 text-danger" id="deleteTodoModalLabel">Konfirmasi Hapus Tugas</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <i class="bi bi-exclamation-circle text-danger display-4 mb-3 d-block"></i>
                        <p class="mb-2">Apakah Anda yakin ingin menghapus tugas ini?</p>
                        <strong id="delete_todo_title" class="text-dark d-block mb-3 fs-6 p-2 bg-light rounded border"></strong>
                        <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                    </div>
                    <div class="modal-footer border-top py-2 px-4 justify-content-center">
                        <button type="button" class="btn btn-light btn-rounded px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-rounded px-4">Ya, Hapus Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Edit Modal Handler
    function openEditModal(todo) {
        const form = document.getElementById('editTodoForm');
        form.action = `/todos/${todo.id}`;

        document.getElementById('edit_title').value = todo.title || '';
        document.getElementById('edit_priority').value = todo.priority || 'medium';
        document.getElementById('edit_description').value = todo.description || '';

        if (todo.due_date) {
            // If due_date is in 'YYYY-MM-DD' or timestamp format
            const dateStr = todo.due_date.substring(0, 10);
            document.getElementById('edit_due_date').value = dateStr;
        } else {
            document.getElementById('edit_due_date').value = '';
        }

        const editModal = new bootstrap.Modal(document.getElementById('editTodoModal'));
        editModal.show();
    }

    // View Details Modal Handler
    function viewTodoDetails(todoId) {
        fetch(`/todos/${todoId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('view_title').innerText = data.title;
            document.getElementById('view_description').innerText = data.description || 'Tidak ada catatan tambahan.';

            // Status Badge
            const statusBadge = document.getElementById('view_status_badge');
            if (data.is_completed) {
                statusBadge.className = 'badge rounded-pill px-3 py-1 bg-success-subtle text-success border border-success-subtle';
                statusBadge.innerHTML = '<i class="bi bi-check2-circle me-1"></i>Selesai';
            } else {
                statusBadge.className = 'badge rounded-pill px-3 py-1 bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                statusBadge.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Aktif';
            }

            // Priority Badge
            const priorityBadge = document.getElementById('view_priority_badge');
            priorityBadge.className = `badge rounded-pill px-3 py-1 ${data.priority_badge}`;
            priorityBadge.innerHTML = `<i class="bi bi-flag-fill me-1"></i>${data.priority_label}`;

            // Due Badge
            const dueBadge = document.getElementById('view_due_badge');
            dueBadge.className = `badge rounded-pill px-3 py-1 ${data.due_status.badge}`;
            dueBadge.innerHTML = `<i class="bi ${data.due_status.icon} me-1"></i>${data.due_status.text}`;

            document.getElementById('view_created_at').innerText = data.created_at;
            document.getElementById('view_due_date').innerText = data.due_date_formatted;

            const completedWrapper = document.getElementById('view_completed_wrapper');
            if (data.is_completed && data.completed_at) {
                completedWrapper.style.display = 'block';
                document.getElementById('view_completed_at').innerText = data.completed_at;
            } else {
                completedWrapper.style.display = 'none';
            }

            const modal = new bootstrap.Modal(document.getElementById('viewTodoModal'));
            modal.show();
        })
        .catch(err => {
            console.error('Error fetching todo:', err);
            alert('Gagal mengambil rincian tugas.');
        });
    }

    // Delete Confirmation Modal Handler
    function confirmDelete(id, title) {
        const form = document.getElementById('deleteTodoForm');
        form.action = `/todos/${id}`;
        document.getElementById('delete_todo_title').innerText = title;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteTodoModal'));
        deleteModal.show();
    }
</script>
@endpush
