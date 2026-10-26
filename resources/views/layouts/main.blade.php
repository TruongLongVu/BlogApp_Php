<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Website Tin Tức HUIT')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; display: flex; flex-direction: column; }
        footer { margin-top: auto; }
    </style>
</head>
<body class="bg-light">

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
        <a class="navbar-brand fw-bold text-uppercase d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="bg-white rounded-circle p-1 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px;">
                <img src="{{ asset('images/logo.png') }}" alt="Logo HUIT" style="max-height: 100%; max-width: 100%; object-fit: contain;">
            </div>
            <span class="fs-5">HUIT NEWS</span>
        </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    @auth
                        @if(auth()->user()->role === 'user')
                            <li class="nav-item"><a class="nav-link" href="{{ route('my.posts') }}"><i class="bi bi-journal-text"></i> Bài viết của tôi</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('my.posts.create') }}"><i class="bi bi-pencil-square"></i> Gửi bài viết</a></li>
                        @endif
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-semibold" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-semibold" href="{{ route('admin.posts.index') }}">
                                    <i class="bi bi-journal-text"></i> Quản lý Bài viết
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-semibold" href="{{ route('admin.categories.index') }}">
                                    <i class="bi bi-folder"></i> Quản lý Chuyên mục
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning fw-semibold" href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-people"></i> Quản lý Người dùng
                                </a>
                            </li>
                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-sm btn-success text-white fw-bold" href="{{ route('admin.posts.create') }}">
                                    <i class="bi bi-plus-circle me-1"></i> Viết bài mới
                                </a>
                            </li>
                    @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                <span>{{ auth()->user()->name }}</span>
                                <span class="badge {{ auth()->user()->role === 'admin' ? 'bg-danger' : 'bg-primary' }} ms-1">
                                    {{ strtoupper(auth()->user()->role) }}
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><span class="dropdown-item-text text-muted small">{{ auth()->user()->email }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-gear me-1"></i> Hồ sơ cá nhân
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">Đăng ký</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-4 flex-grow-1">
        @if(session('success'))
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1090;">
                <div id="successToast" class="toast text-bg-success border-0" role="status" aria-live="polite" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-describedby="confirmationMessage" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Xác nhận thao tác</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0" id="confirmationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmAction">Tiếp tục</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white text-center py-3 border-top">
        <div class="container">
            <span class="text-muted small">&copy; 2026 - Đồ án Lập trình mã nguồn mở (Laravel Framework).</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const successToast = document.getElementById('successToast');

        if (successToast) {
            bootstrap.Toast.getOrCreateInstance(successToast).show();
        }

        const confirmationModalElement = document.getElementById('confirmationModal');
        const confirmationModal = bootstrap.Modal.getOrCreateInstance(confirmationModalElement);
        const confirmationMessage = document.getElementById('confirmationMessage');
        const confirmAction = document.getElementById('confirmAction');
        let pendingForm = null;

        document.querySelectorAll('form[data-confirm]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (form.dataset.confirmed === 'true') {
                    delete form.dataset.confirmed;
                    return;
                }

                event.preventDefault();
                pendingForm = form;
                confirmationMessage.textContent = form.dataset.confirm;
                confirmationModal.show();
            });
        });

        confirmAction.addEventListener('click', () => {
            if (!pendingForm) {
                return;
            }

            const form = pendingForm;
            pendingForm = null;
            form.dataset.confirmed = 'true';
            confirmationModal.hide();
            form.requestSubmit();
        });
    </script>
</body>
</html>
