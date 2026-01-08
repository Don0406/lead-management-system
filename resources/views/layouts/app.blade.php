<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lead Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background-color: var(--secondary-color);
            padding: 0;
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-bottom: 1px solid #34495e;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #34495e;
            color: white;
            padding-left: 25px;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-new { background-color: #d4edda; color: #155724; }
        .status-contacted { background-color: #cce5ff; color: #004085; }
        .status-qualified { background-color: #d1ecf1; color: #0c5460; }
        .status-proposal { background-color: #fff3cd; color: #856404; }
        .status-negotiation { background-color: #f8d7da; color: #721c24; }
        .status-closed_won { background-color: #28a745; color: white; }
        .status-closed_lost { background-color: #dc3545; color: white; }
        
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 15px;
        }
        
        .stat-total { background: linear-gradient(45deg, #3498db, #2980b9); }
        .stat-new { background: linear-gradient(45deg, #2ecc71, #27ae60); }
        .stat-contacted { background: linear-gradient(45deg, #9b59b6, #8e44ad); }
        .stat-qualified { background: linear-gradient(45deg, #e67e22, #d35400); }
        
        .lead-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    @auth
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center p-4">
                        <h4 class="text-white">
                            <i class="fas fa-chart-line"></i> Lead Manager
                        </h4>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('leads') ? 'active' : '' }}" href="{{ route('leads.index') }}">
                                <i class="fas fa-users"></i> All Leads
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('leads/create') ? 'active' : '' }}" href="{{ route('leads.create') }}">
                                <i class="fas fa-plus-circle"></i> Add New Lead
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="statusDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-filter"></i> Filter by Status
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('leads.byStatus', 'new') }}">New</a></li>
                                <li><a class="dropdown-item" href="{{ route('leads.byStatus', 'contacted') }}">Contacted</a></li>
                                <li><a class="dropdown-item" href="{{ route('leads.byStatus', 'qualified') }}">Qualified</a></li>
                                <li><a class="dropdown-item" href="{{ route('leads.byStatus', 'proposal') }}">Proposal</a></li>
                                <li><a class="dropdown-item" href="{{ route('leads.byStatus', 'negotiation') }}">Negotiation</a></li>
                            </ul>
                        </li>
                        
                        @if(auth()->user()->isAdmin() || auth()->user()->isSalesManager())
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fas fa-chart-bar"></i> Reports
                            </a>
                        </li>
                        @endif
                    </ul>
                    
                    <div class="mt-5 p-3 text-center text-white-50">
                        <small>Lead Management System v1.0</small>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="d-flex align-items-center ms-auto">
                            <span class="me-3 text-muted">
                                <i class="fas fa-user-circle me-1"></i> 
                                {{ auth()->user()->name }} 
                                <span class="badge bg-primary ms-2">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                            </span>
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="container-fluid py-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @else
    <!-- Show for non-authenticated users (login/register pages) -->
    <div class="container py-5">
        @yield('content')
    </div>
    @endauth

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
    
    @yield('scripts')
</body>
</html>