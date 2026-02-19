<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Komunitas Mekanik Indonesia')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Arial', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #f5f5f5 0%, #e9e9e9 100%);
            color: #333;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0052A3 0%, #003D7A 100%);
            color: white;
            padding: 2rem 0;
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.15);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 1.5rem;
        }

        .sidebar-logo {
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .sidebar-logo i {
            font-size: 2rem;
            color: #FFD700;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            margin: 0.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(230, 57, 70, 0.2);
            color: white;
            border-right: 4px solid #E63946;
            padding-left: calc(1.5rem - 4px);
        }

        .sidebar-menu i {
            width: 24px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 1.5rem;
            border-top: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            width: 100%;
            padding: 0.8rem 1rem;
            background: linear-gradient(135deg, #E63946 0%, #A4161A 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            text-align: left;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(230, 57, 70, 0.3);
        }

        /* Main Content */
        .admin-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            overflow-y: auto;
        }

        .admin-header {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-title h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .admin-title p {
            color: #666;
            font-size: 0.95rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: white;
            color: #0052A3;
            border: 2px solid #0052A3;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #0052A3;
            color: white;
            transform: translateX(-5px);
        }

        .admin-section {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .section-header h2 i {
            color: #E63946;
            font-size: 1.3rem;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, #E63946 0%, #A4161A 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(230, 57, 70, 0.3);
        }

        .form-container {
            background: linear-gradient(135deg, #f9f9f9 0%, #f0f0f0 100%);
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border: 2px dashed #E63946;
        }

        .form-container.hidden {
            display: none;
        }

        .form-close {
            float: right;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #E63946;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .form-close:hover {
            transform: rotate(90deg);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.9rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.3s;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #E63946;
            background: white;
            box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .btn-submit {
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #E63946 0%, #A4161A 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(230, 57, 70, 0.3);
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .item-card {
            background: white;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.3s;
        }

        .item-card:hover {
            border-color: #E63946;
            box-shadow: 0 8px 20px rgba(230, 57, 70, 0.15);
        }

        .item-card h3 {
            color: #0052A3;
            margin-bottom: 0.5rem;
        }

        .item-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .item-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-delete {
            flex: 1;
            padding: 0.6rem;
            background: #E63946;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-delete:hover {
            background: #A4161A;
        }

        .no-data {
            text-align: center;
            padding: 2rem;
            color: #999;
        }

        .no-data i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 240px;
            }

            .admin-content {
                margin-left: 240px;
                padding: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .admin-wrapper {
                flex-direction: column;
            }

            .admin-sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 1rem 0;
            }

            .admin-content {
                margin-left: 0;
                padding: 1rem;
            }

            .admin-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .items-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-cogs"></i>
                    Admin Panel
                </div>
            </div>

            <ul class="sidebar-menu">
                <li><a href="#gallery" class="active"><i class="fas fa-images"></i> Gallery</a></li>
                <li><a href="#bengkel"><i class="fas fa-tools"></i> Bengkel</a></li>
                <li><a href="#produk"><i class="fas fa-box"></i> Produk</a></li>
                <li><a href="#sponsor"><i class="fas fa-star"></i> Sponsor</a></li>
            </ul>

            <div class="sidebar-footer">
                <a href="{{ route('home') }}" class="logout-btn" style="background: rgba(255, 255, 255, 0.1); margin-bottom: 0.8rem;">
                    <i class="fas fa-arrow-left"></i>Kembali Website
                </a>
                <a href="{{ route('admin.logout') }}" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-content">
            <div class="admin-header">
                <div class="admin-title">
                    <h1>Dashboard Admin</h1>
                    <p>Kelola konten website Komunitas Mekanik Indonesia</p>
                </div>
            </div>

            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/admin-api.js') }}"></script>
    @yield('extra_js')
</body>
</html>
