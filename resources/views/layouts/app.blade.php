<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TodoList - Kelola Tugas Harian Anda')</title>

    <!-- Google Font (Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --bs-font-sans-serif: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --header-gradient: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            --card-hover-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
        }

        body {
            font-family: var(--bs-font-sans-serif);
            background-color: #f8fafc;
            color: #334155;
            min-height: 100vh;
        }

        .navbar-brand-custom {
            font-weight: 800;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }

        .card-stat {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: var(--card-hover-shadow);
        }

        .todo-item {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            transition: all 0.2s ease;
            position: relative;
        }

        .todo-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .todo-item.completed {
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }

        .todo-item.completed .todo-title {
            text-decoration: line-through;
            color: #94a3b8;
        }

        .todo-item.completed .todo-desc {
            color: #cbd5e1;
        }

        .badge-priority-high {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .badge-priority-medium {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .badge-priority-low {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .custom-checkbox {
            width: 22px;
            height: 22px;
            cursor: pointer;
            border-radius: 6px !important;
        }

        .btn-rounded {
            border-radius: 10px;
        }

        .filter-pill {
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 6px 16px;
            transition: all 0.2s ease;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            padding: 0;
            transition: all 0.15s ease;
        }

        .progress-thin {
            height: 6px;
            border-radius: 3px;
        }

        /* Priority border left strip */
        .priority-strip-high {
            border-left: 4px solid #ef4444 !important;
        }
        .priority-strip-medium {
            border-left: 4px solid #f59e0b !important;
        }
        .priority-strip-low {
            border-left: 4px solid #0ea5e9 !important;
        }
        .priority-strip-completed {
            border-left: 4px solid #10b981 !important;
        }
    </style>
</head>
<body>

    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm" style="background: var(--header-gradient) !important;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('todos.index') }}">
                <div class="bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: var(--primary-gradient) !important;">
                    <i class="bi bi-check2-square fs-5"></i>
                </div>
                <span class="fw-bold tracking-tight text-white fs-4">TodoList<span class="text-primary-emphasis opacity-75">App</span></span>
            </a>

            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-white bg-opacity-10 text-light py-2 px-3 rounded-pill d-none d-sm-inline-flex align-items-center gap-2">
                    <i class="bi bi-calendar-check text-info"></i>
                    <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                </span>
                <button type="button" class="btn btn-primary btn-sm px-3 py-2 btn-rounded shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createTodoModal" style="background: var(--primary-gradient); border: none;">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span class="fw-semibold">Tambah Tugas</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container py-4">
        <!-- Toast / Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show card-custom border-success-subtle bg-success-subtle text-success-emphasis d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div class="flex-grow-1 fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show card-custom border-danger-subtle bg-danger-subtle text-danger-emphasis mb-4" role="alert">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <strong>Terdapat kesalahan pada input:</strong>
                </div>
                <ul class="mb-0 ps-4 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-4 mt-5 text-center text-muted small border-top bg-white">
        <div class="container">
            <span>&copy; {{ date('Y') }} TodoList Web App &bull; Dibuat dengan Laravel & Bootstrap 5</span>
        </div>
    </footer>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
