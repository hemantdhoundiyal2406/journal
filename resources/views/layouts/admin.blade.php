<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Editorial Journal Management</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-active: #1e3a8a;
            --topbar-bg: #ffffff;
            --main-bg: #f1f5f9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--main-bg);
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            min-height: 100vh;
            color: #94a3b8;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 1050;
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
        }

        .admin-sidebar .brand-box {
            padding: 20px;
            color: #ffffff;
            font-weight: 700;
            border-bottom: 1px solid #1e293b;
        }

        .admin-sidebar .nav-link {
            color: #94a3b8;
            padding: 12px 20px;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }

        .admin-sidebar .nav-link:hover, .admin-sidebar .nav-link.active {
            color: #ffffff;
            background: #1e293b;
            border-left-color: #38bdf8;
        }

        .admin-sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Topbar & Content */
        .admin-topbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 25px;
            margin-left: 260px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: margin-left 0.3s ease-in-out;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .admin-content {
            margin-left: 260px;
            padding: 25px;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease-in-out;
        }

        /* Sidebar Backdrop Overlay on Mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            z-index: 1040;
            display: none;
            backdrop-filter: blur(3px);
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-topbar, .admin-content {
                margin-left: 0 !important;
            }
            .sidebar-overlay.show {
                display: block;
            }
            .admin-content {
                padding: 15px;
            }
            .admin-topbar {
                padding: 12px 15px;
            }
        }

        .card-stat {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-2px);
        }

        /* Scrollable Nav Pills / Tabs inside Single Article Screen */
        .article-tabs-wrapper {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        .article-tabs {
            display: inline-flex;
            flex-wrap: nowrap;
        }
        .article-tabs .nav-link {
            color: #64748b;
            font-weight: 600;
            border: none;
            padding: 12px 18px;
            border-bottom: 3px solid transparent;
            border-radius: 0;
            white-space: nowrap;
        }
        .article-tabs .nav-link.active {
            color: #1e3a8a;
            border-bottom-color: #1e3a8a;
            background: transparent;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand-box d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-journal-check fs-3 text-info me-2"></i>
                <div>
                    <div class="lh-1 text-white fw-bold">IJASER Admin</div>
                    <small class="text-secondary" style="font-size: 0.72rem;">Editorial Portal</small>
                </div>
            </div>
            <button class="btn btn-link text-white d-lg-none p-0" id="closeSidebarBtn">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>

        <nav class="mt-3">
            <div class="px-3 pb-2 text-uppercase fs-7 text-secondary fw-bold" style="font-size: 0.7rem;">Main Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
            <a href="{{ route('admin.inbox.index') }}" class="nav-link {{ request()->routeIs('admin.inbox.*') ? 'active' : '' }}">
                <i class="bi bi-inbox-fill"></i> Submission Inbox
                @php
                    $newCount = \App\Models\Article::where('status', 'Submitted')->count();
                @endphp
                @if($newCount > 0)
                    <span class="badge bg-danger ms-auto rounded-pill">{{ $newCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.articles.index') }}" class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                <i class="bi bi-journals"></i> All Articles
            </a>
            <a href="{{ route('admin.published.index') }}" class="nav-link {{ request()->routeIs('admin.published.*') ? 'active' : '' }}">
                <i class="bi bi-check-circle-fill"></i> Published & DOI
            </a>

            <div class="px-3 pt-3 pb-2 text-uppercase fs-7 text-secondary fw-bold" style="font-size: 0.7rem;">Publication & Structure</div>
            <a href="{{ route('admin.volumes.index') }}" class="nav-link {{ request()->routeIs('admin.volumes.*') ? 'active' : '' }}">
                <i class="bi bi-layers-fill"></i> Volumes & Issues
            </a>
            <a href="{{ route('admin.authors.index') }}" class="nav-link {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Authors Directory
            </a>
            <a href="{{ route('admin.editorial.index') }}" class="nav-link {{ request()->routeIs('admin.editorial.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Editorial Board
            </a>
            <a href="{{ route('admin.reviewers.index') }}" class="nav-link {{ request()->routeIs('admin.reviewers.*') ? 'active' : '' }}">
                <i class="bi bi-person-lines-fill"></i> Reviewer Database
            </a>

            <div class="px-3 pt-3 pb-2 text-uppercase fs-7 text-secondary fw-bold" style="font-size: 0.7rem;">CMS & Communications</div>
            <a href="{{ route('admin.cms.index') }}" class="nav-link {{ request()->routeIs('admin.cms.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i> CMS Pages
            </a>
            <a href="{{ route('admin.email-templates.index') }}" class="nav-link {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}">
                <i class="bi bi-envelope-paper-fill"></i> Email Templates
            </a>
            <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill"></i> Announcements
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Website & SEO Settings
            </a>

            <div class="p-3 mt-4 mb-4">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-light btn-sm w-100 rounded-pill">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Visit Public Site
                </a>
            </div>
        </nav>
    </aside>

    <!-- Topbar -->
    <header class="admin-topbar">
        <div class="d-flex align-items-center me-3">
            <button class="btn btn-outline-dark me-3 d-lg-none" id="sidebarToggleBtn">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div>
                <h5 class="mb-0 fw-bold fs-6 fs-md-5">@yield('page_header', 'Editorial Control Center')</h5>
                <small class="text-muted d-none d-sm-inline">Research Journal Management System</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 gap-sm-3">
            <span class="badge bg-success px-2 px-sm-3 py-2 rounded-pill d-none d-md-inline"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> System Live</span>
            <div class="vr d-none d-md-block"></div>
            <div class="d-flex align-items-center">
                <i class="bi bi-person-circle fs-3 text-secondary me-2"></i>
                <div class="lh-1 me-2 me-sm-3 d-none d-sm-block">
                    <div class="fw-bold small">{{ auth()->user()?->name ?? 'Editorial Admin' }}</div>
                    <small class="text-muted" style="font-size: 0.75rem;">Super Administrator</small>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold px-2 px-sm-3">
                        <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline ms-1">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script>
        $(document).ready(function() {
            function openSidebar() {
                $('#adminSidebar').addClass('show');
                $('#sidebarOverlay').addClass('show');
                $('body').css('overflow', 'hidden');
            }

            function closeSidebar() {
                $('#adminSidebar').removeClass('show');
                $('#sidebarOverlay').removeClass('show');
                $('body').css('overflow', '');
            }

            $('#sidebarToggleBtn').click(openSidebar);
            $('#closeSidebarBtn, #sidebarOverlay').click(closeSidebar);
        });
    </script>
    @yield('scripts')
</body>
</html>
