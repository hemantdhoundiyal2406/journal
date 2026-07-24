<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\JournalSetting::getByKey('meta_title', 'International Research Journal'))</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', \App\Models\JournalSetting::getByKey('meta_description'))">
    <meta name="keywords" content="@yield('meta_keywords', \App\Models\JournalSetting::getByKey('meta_keywords'))">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Social Meta -->
    <meta property="og:title" content="@yield('title', \App\Models\JournalSetting::getByKey('meta_title'))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\JournalSetting::getByKey('meta_description'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Google Citation / Scholar Meta (Dynamic Override on Single Article page) -->
    @yield('google_scholar_meta')

    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Merriweather:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-navy: #0f172a;
            --accent-blue: #1e3a8a;
            --brand-cyan: #0284c7;
            --dark-surface: #1e293b;
            --light-bg: #f8fafc;
            --card-border: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, .serif-font {
            font-family: 'Merriweather', serif;
        }

        /* Top Announcement Bar */
        .top-announcement-bar {
            background: linear-gradient(90deg, #1e3a8a 0%, #0284c7 100%);
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 8px 0;
        }
        .top-announcement-bar a {
            color: #fef08a;
            text-decoration: underline;
            font-weight: 600;
        }

        /* Journal Top Header */
        .journal-masthead {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 0;
        }

        /* Navigation Bar */
        .navbar-main {
            background: var(--primary-navy);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }
        .navbar-main .nav-link {
            color: #e2e8f0;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 12px 18px !important;
            transition: all 0.2s ease-in-out;
        }
        .navbar-main .nav-link:hover, .navbar-main .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }

        /* Hero Banner */
        .hero-banner {
            background: radial-gradient(circle at 50% 0%, #1e3a8a 0%, #0f172a 100%);
            color: #ffffff;
            padding: 60px 0 70px 0;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle, rgba(2, 132, 199, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Modern Cards */
        .journal-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .journal-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px -3px rgba(15, 23, 42, 0.08);
        }

        /* Badges */
        .badge-category {
            background-color: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
        }
        .badge-status-published {
            background-color: #dcfce7;
            color: #15803d;
            font-weight: 600;
        }

        /* Indexing Grid */
        .indexing-badge {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px 20px;
            text-align: center;
            font-weight: 700;
            color: #334155;
            transition: all 0.2s ease;
        }
        .indexing-badge:hover {
            border-color: #0284c7;
            color: #0284c7;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.1);
        }

        /* Footer */
        .footer-main {
            background: #0f172a;
            color: #94a3b8;
            margin-top: auto;
            padding: 60px 0 25px 0;
            border-top: 4px solid #1e3a8a;
        }
        .footer-main h5 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .footer-main a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .footer-main a:hover {
            color: #38bdf8;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Announcement Top Bar -->
    @php
        $topAnn = \App\Models\Announcement::where('type', 'top_bar')->where('is_active', true)->first();
    @endphp
    @if($topAnn)
    <div class="top-announcement-bar text-center">
        <div class="container">
            <i class="bi bi-megaphone-fill me-2"></i> {{ $topAnn->content }}
            @if($topAnn->link)
                <a href="{{ $topAnn->link }}" class="ms-2">Submit Paper &rarr;</a>
            @endif
        </div>
    </div>
    @endif

    <!-- Journal Masthead Header -->
    <header class="journal-masthead">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 58px; height: 58px; background: linear-gradient(135deg, #0f172a, #1e3a8a) !important;">
                            <i class="bi bi-journal-bookmark-fill fs-2"></i>
                        </div>
                        <div>
                            <h2 class="mb-0 fs-4 text-dark fw-bold serif-font">
                                {{ \App\Models\JournalSetting::getByKey('journal_name', 'International Journal of Research') }}
                            </h2>
                            <p class="text-muted mb-0 small">
                                {{ \App\Models\JournalSetting::getByKey('tagline', 'A Peer-Reviewed Open Access Multidisciplinary Journal') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <div class="d-inline-block text-start bg-light p-2 px-3 rounded border">
                        <div class="small fw-semibold text-dark"><i class="bi bi-shield-check text-success me-1"></i> {{ \App\Models\JournalSetting::getByKey('print_issn', 'ISSN: 2831-9042') }}</div>
                        <div class="small fw-semibold text-dark"><i class="bi bi-globe text-primary me-1"></i> {{ \App\Models\JournalSetting::getByKey('online_issn', 'E-ISSN: 2831-9050') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-main sticky-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i class="bi bi-house-door me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('current-issue') ? 'active' : '' }}" href="{{ route('current-issue') }}"><i class="bi bi-journal-album me-1"></i> Current Issue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('archives') ? 'active' : '' }}" href="{{ route('archives') }}"><i class="bi bi-archive me-1"></i> Archives</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('editorial-board') ? 'active' : '' }}" href="{{ route('editorial-board') }}"><i class="bi bi-people me-1"></i> Editorial Board</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('page/aim-and-scope') ? 'active' : '' }}" href="{{ route('cms.page', 'aim-and-scope') }}">Aim & Scope</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('page/author-guidelines') ? 'active' : '' }}" href="{{ route('cms.page', 'author-guidelines') }}">Author Guidelines</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('page/publication-ethics') ? 'active' : '' }}" href="{{ route('cms.page', 'publication-ethics') }}">Ethics</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('search') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill">
                        <i class="bi bi-search me-1"></i> Search
                    </a>
                    <a href="{{ route('submission.form') }}" class="btn btn-warning btn-sm fw-bold px-3 rounded-pill text-dark shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Submit Article
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-main">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <h5 class="serif-font">{{ \App\Models\JournalSetting::getByKey('journal_name') }}</h5>
                    <p class="small text-secondary mb-3">
                        A peer-reviewed, open-access international journal providing rapid publishing of original multidisciplinary scientific articles with DOI indexation and CC BY 4.0 license.
                    </p>
                    <div class="d-flex gap-3 fs-5">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                        <a href="#"><i class="bi bi-google"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('submission.form') }}">Submit Manuscript</a></li>
                        <li><a href="{{ route('current-issue') }}">Current Issue</a></li>
                        <li><a href="{{ route('archives') }}">Archive Volumes</a></li>
                        <li><a href="{{ route('editorial-board') }}">Editorial Board</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-6">
                    <h5>Policies & Ethics</h5>
                    <ul class="list-unstyled small d-flex flex-column gap-2">
                        <li><a href="{{ route('cms.page', 'author-guidelines') }}">Author Guidelines</a></li>
                        <li><a href="{{ route('cms.page', 'publication-ethics') }}">Publication Ethics</a></li>
                        <li><a href="{{ route('cms.page', 'peer-review-policy') }}">Peer Review Policy</a></li>
                        <li><a href="{{ route('cms.page', 'about') }}">About Journal</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h5>Editorial Office</h5>
                    <p class="small mb-1"><i class="bi bi-envelope-fill me-2 text-primary"></i> {{ \App\Models\JournalSetting::getByKey('contact_email', 'editor@ijaser-journal.org') }}</p>
                    <p class="small mb-1"><i class="bi bi-telephone-fill me-2 text-primary"></i> {{ \App\Models\JournalSetting::getByKey('contact_phone', '+1 (800) 555-0199') }}</p>
                    <p class="small mb-3"><i class="bi bi-geo-alt-fill me-2 text-primary"></i> {{ \App\Models\JournalSetting::getByKey('contact_address') }}</p>
                </div>
            </div>

            <!-- Indexing Badges Grid -->
            <div class="border-top border-secondary pt-4 pb-3">
                <div class="text-center mb-3">
                    <span class="small text-uppercase fw-bold text-light tracking-wider" style="letter-spacing: 1px;">Journal Indexing & Abstracting Databases</span>
                </div>
                <div class="row row-cols-2 row-cols-md-4 g-3 justify-content-center text-center">
                    <div class="col">
                        <div class="bg-dark p-2 rounded border border-secondary text-light small"><i class="bi bi-journal-text me-1 text-warning"></i> Google Scholar</div>
                    </div>
                    <div class="col">
                        <div class="bg-dark p-2 rounded border border-secondary text-light small"><i class="bi bi-link-45deg me-1 text-info"></i> Crossref DOI</div>
                    </div>
                    <div class="col">
                        <div class="bg-dark p-2 rounded border border-secondary text-light small"><i class="bi bi-unlock-fill me-1 text-success"></i> DOAJ Open Access</div>
                    </div>
                    <div class="col">
                        <div class="bg-dark p-2 rounded border border-secondary text-light small"><i class="bi bi-award-fill me-1 text-primary"></i> Scopus Metadata</div>
                    </div>
                </div>
            </div>

            <div class="text-center border-top border-secondary pt-3 mt-3 small text-secondary">
                &copy; {{ date('Y') }} {{ \App\Models\JournalSetting::getByKey('publisher_name', 'Global Academic Publishing') }}. All Rights Reserved. Released under CC BY 4.0 License.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @yield('scripts')
</body>
</html>
