<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Akademik') - IKMI</title>
    <link rel="icon" href="{{ asset('ikmi.png') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">

    @stack('styles')
    
    <style>
        :root {
            /* Palette Modern */
            --primary-color: #4361ee;
            --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --sidebar-bg-start: #0f172a;
            --sidebar-bg-end: #1e293b;
            --sidebar-width-expanded: 280px;
            --sidebar-width-collapsed: 85px;
            --header-height: 80px;
            --border-radius: 16px;
            
            /* Backgrounds */
            --body-bg: #f1f5f9;
            --glass-bg: rgba(255, 255, 255, 0.85);
            
            /* Text */
            --text-main: #334155;
            --text-muted: #64748b;
            
            /* Effects */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --transition-smooth: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* --- SIDEBAR MODERN --- */
        .sidebar {
            width: var(--sidebar-width-expanded);
            background: linear-gradient(180deg, var(--sidebar-bg-start) 0%, var(--sidebar-bg-end) 100%);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1040;
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
            padding: 1.5rem 1rem;
        }

        .sidebar-header {
            text-align: center;
            padding-bottom: 2rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            background: linear-gradient(to right, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin-bottom: 0.5rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            color: #94a3b8;
            padding: 0.85rem 1rem;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav a i {
            font-size: 1.25rem;
            width: 30px;
            text-align: center;
            margin-right: 12px;
            transition: var(--transition-smooth);
        }

        .sidebar-nav a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .sidebar-nav a.active {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.4);
        }

        /* --- COLLAPSED SIDEBAR LOGIC --- */
        body.sidebar-collapsed .sidebar {
            width: var(--sidebar-width-collapsed);
            padding: 1.5rem 0.75rem;
        }

        body.sidebar-collapsed .sidebar-header h2,
        body.sidebar-collapsed .sidebar-nav span {
            display: none;
        }

        body.sidebar-collapsed .sidebar-nav a {
            justify-content: center;
            padding: 1rem 0;
        }

        body.sidebar-collapsed .sidebar-nav a i {
            margin-right: 0;
            font-size: 1.4rem;
        }

        /* Tooltip Custom for Collapsed */
        body.sidebar-collapsed .sidebar-nav a::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 15px);
            top: 50%;
            transform: translateY(-50%) translateX(-10px);
            background: #1e293b;
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 1050;
            pointer-events: none;
            white-space: nowrap;
        }

        body.sidebar-collapsed .sidebar-nav a:hover::after {
            opacity: 1;
            visibility: visible;
            transform: translateY(-50%) translateX(0);
        }

        /* --- MAIN WRAPPER --- */
        .main-wrapper {
            flex-grow: 1;
            margin-left: var(--sidebar-width-expanded);
            display: flex;
            flex-direction: column;
            transition: var(--transition-smooth);
            width: 100%;
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: var(--sidebar-width-collapsed);
        }

        /* --- HEADER GLASSMORPHISM --- */
        .header {
            height: var(--header-height);
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.4);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: var(--shadow-sm);
        }

        .header-title-static {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-left: 1rem;
        }

        .sidebar-toggle {
            background: transparent;
            border: none;
            color: var(--text-main);
            font-size: 1.4rem;
            cursor: pointer;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle:hover {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }

        /* User Info Styling */
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem;
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .user-details {
            text-align: right;
            line-height: 1.3;
        }
        
        .user-name {
            display: block;
            font-weight: 600;
            color: var(--text-main);
            font-size: 0.95rem;
        }
        
        .user-role {
            display: block;
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .user-avatar img {
            border: 3px solid rgba(255,255,255,0.8);
            box-shadow: var(--shadow-md);
            transition: transform 0.2s;
        }
        
        .user-avatar img:hover {
            transform: scale(1.05);
        }

        .avatar-placeholder {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: var(--shadow-md);
            border: 3px solid #fff;
        }

        /* --- CONTENT AREA --- */
        .content-wrapper {
            padding: 2rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        /* Footer */
        .footer {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
            background: #fff;
            border-top: 1px solid #e2e8f0;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width-expanded) !important;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper, body.sidebar-collapsed .main-wrapper {
                margin-left: 0;
            }
            
            /* Overlay for mobile */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(4px);
                z-index: 1030;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s;
            }
            
            .sidebar.active + .sidebar-overlay {
                opacity: 1;
                visibility: visible;
            }
        }

        @media (max-width: 576px) {
            .header { padding: 0 1rem; }
            .header-title-static { display: none; }
            .user-details { display: none; }
            .content-wrapper { padding: 1rem; }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="mobileOverlay"></div>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>
                <i class="bi bi-mortarboard-fill text-primary me-2"></i>SIAM
            </h2>
        </div>
        
        <nav class="sidebar-nav">
            <ul id="sideMenu">
                @auth
                    @if (Auth::user()->role === 'admin')
                        @include('layouts.sidebar-admin')
                    @elseif (Auth::user()->role === 'dosen')
                        @include('layouts.sidebar-dosen')
                    @elseif (Auth::user()->role === 'mahasiswa')
                        @include('layouts.sidebar-mahasiswa')
                    @endif
                @endauth
            </ul>
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="header">
            <div class="header-left d-flex align-items-center">
                @auth
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                @endauth
                <h3 class="header-title-static">Portal Akademik</h3>
            </div>

            @auth
                <div class="user-info dropdown">
                    <div class="user-details">
                        <span class="user-name">{{ Auth::user()->profile_name ?? Auth::user()->name }}</span>
                        <span class="badge bg-primary-subtle text-primary rounded-pill user-role px-2">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </div>
                    
                    <div class="user-avatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        @if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->foto)
                            <img src="{{ asset('storage/' . Auth::user()->mahasiswa->foto) }}" 
                                 alt="Profile" 
                                 class="rounded-circle" 
                                 style="width: 45px; height: 45px; object-fit: cover;">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2">
                        {{-- <li><h6 class="dropdown-header">Akun Saya</h6></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li> --}}
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </header>

        <div class="content-wrapper">
            <div class="animate-fade-in">
                @yield('content')
            </div>
        </div>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Sistem Informasi Akademik </p>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const body = document.body;
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('mobileOverlay');

            // --- Logic Sidebar ---
            function toggleSidebar() {
                if (window.innerWidth <= 992) {
                    sidebar.classList.toggle('active');
                    // Toggle overlay visibility is handled by CSS based on sidebar.active
                } else {
                    body.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebarState', body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'expanded');
                }
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            // Close sidebar when clicking overlay (Mobile)
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                });
            }

            // Restore State Desktop
            const savedState = localStorage.getItem('sidebarState');
            if (window.innerWidth > 992) {
                if (savedState === 'collapsed') {
                    body.classList.add('sidebar-collapsed');
                } else {
                    body.classList.remove('sidebar-collapsed');
                }
            }

            // Tooltip Logic untuk mode Collapsed
            const navLinks = document.querySelectorAll('.sidebar-nav a');
            navLinks.forEach(link => {
                const span = link.querySelector('span');
                if (span) {
                    link.setAttribute('data-tooltip', span.textContent.trim());
                }
            });

            // Responsive Handler
            window.addEventListener('resize', function () {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('active'); // Reset mobile state
                    // Kembalikan ke state desktop yang tersimpan
                    if (localStorage.getItem('sidebarState') === 'collapsed') {
                        body.classList.add('sidebar-collapsed');
                    } else {
                        body.classList.remove('sidebar-collapsed');
                    }
                } else {
                    body.classList.remove('sidebar-collapsed'); // Reset desktop collapse di mobile
                }
            });

            // --- Global AJAX Error Handling (SweetAlert2) ---
            $(document).ajaxError(function (event, jqxhr) {
                let message = 'Terjadi kesalahan di server.';
                if (jqxhr.status === 419) message = 'Sesi berakhir. Refresh halaman.';
                else if (jqxhr.responseJSON && jqxhr.responseJSON.message) message = jqxhr.responseJSON.message;

                Swal.fire({
                    title: `Oops! (${jqxhr.status})`,
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#4361ee'
                }).then(() => {
                    if (jqxhr.status === 419) window.location.reload();
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>