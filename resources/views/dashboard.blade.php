<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - STAGE-UP')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f7f9fc; margin: 0; }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: #1e293b;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
        }
        .sidebar .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            font-weight: 800;
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            padding: 0 0.5rem;
        }
        .sidebar .logo .accent { color: #34d399; }
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            color: #cbd5e1;
            text-decoration: none;
            padding: 0.7rem 0.9rem;
            border-radius: 10px;
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
            transition: all 0.15s;
        }
        .sidebar a:hover { background: rgba(255,255,255,0.06); color: white; }
        .sidebar a.active { background: #0F6E56; color: white; font-weight: 600; }
        .sidebar .logout-btn {
            margin-top: auto;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem 2.5rem;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .topbar h4 { font-weight: 700; color: #1e293b; margin: 0; }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            background: white;
            padding: 0.5rem 1rem;
            border-radius: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .user-chip .avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: #0F6E56;
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            font-size: 0.8rem;
        }
        .user-chip .role-badge {
            font-size: 0.72rem;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo">
            🎓 STAGE<span class="accent">-UP</span>
        </div>

        @yield('sidebar-links')

        <form method="POST" action="/logout" class="logout-btn">
            @csrf
            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                🚪 Déconnexion
            </a>
        </form>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h4>@yield('page-title', 'Dashboard')</h4>
            <div class="user-chip">
                <div class="avatar">{{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}</div>
                <div>
                    <div style="font-size:0.85rem; font-weight:600;">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                    <div class="role-badge">{{ Auth::user()->role->nom_role }}</div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>