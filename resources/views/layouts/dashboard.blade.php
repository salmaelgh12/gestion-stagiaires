<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - STAGE-UP')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f7f9fc; margin: 0; }

        .topnav {
            background: white;
            border-bottom: 1px solid #eef0f4;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topnav .logo-wrap { display: flex; align-items: center; gap: 0.5rem; }
        .topnav .logo-text { font-weight: 800; font-size: 1.2rem; color: #1e293b; }
        .topnav .logo-text .accent { color: #0F6E56; }

        .topnav .right-side { display: flex; align-items: center; gap: 1.2rem; }
        .messages-icon {
            position: relative;
            color: #64748b;
            font-size: 1.3rem;
            text-decoration: none;
        }
        .messages-icon:hover { color: #0F6E56; }
        .messages-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            background: #dc2626;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            line-height: 1;
        }
        .user-info { text-align: right; }
        .user-info .name { font-weight: 700; color: #1e293b; font-size: 0.9rem; }
        .role-badge {
            background: #eafaf5;
            color: #0F6E56;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.7rem;
            border-radius: 20px;
            display: inline-block;
        }
        .btn-logout {
            border: 1px solid #fca5a5;
            color: #dc2626;
            background: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .btn-logout:hover { background: #fef2f2; }

        .page-header { padding: 2rem 2rem 0; }
        .page-header h2 { font-weight: 800; color: #1e293b; margin-bottom: 0.2rem; }
        .page-header p { color: #64748b; font-size: 0.9rem; }
        .page-header p strong { color: #1e293b; }

        .tabs-row {
            padding: 0 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }
        .tabs-row a {
            padding: 0.8rem 1rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .tabs-row a.active {
            color: #0F6E56;
            border-bottom-color: #0F6E56;
        }
        .tabs-row a .badge-count {
            background: #dc2626;
            color: white;
            font-size: 0.7rem;
            border-radius: 50%;
            width: 18px; height: 18px;
            display: flex; align-items: center; justify-content: center;
        }

        .main-content { padding: 2rem; }

        .welcome-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #0F6E56;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            z-index: 9999;
            animation: slideIn 0.4s ease, fadeOut 0.5s ease 4.5s forwards;
        }
        @keyframes slideIn { from { transform: translateX(100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; transform: translateX(20px); } }

        .ia-bubble {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: #0F6E56;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            border: none;
            box-shadow: 0 8px 24px rgba(15,110,86,0.35);
            cursor: pointer;
            z-index: 9998;
            transition: transform 0.2s;
        }
        .ia-bubble:hover { transform: scale(1.08); }

        .ia-chat-window {
            position: fixed;
            bottom: 95px;
            right: 24px;
            width: 360px;
            height: 480px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 9998;
        }
        .ia-chat-window.open { display: flex; }
        .ia-chat-header {
            background: #0F6E56;
            color: white;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .ia-chat-header .title { font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; }
        .ia-chat-header button { background: none; border: none; color: white; font-size: 1.1rem; }

        .ia-chat-body {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            background: #f7f9fc;
            display: flex;
            flex-direction: column;
            gap: 0.7rem;
        }
        .ia-msg {
            max-width: 80%;
            padding: 0.6rem 0.9rem;
            border-radius: 12px;
            font-size: 0.85rem;
            line-height: 1.4;
        }
        .ia-msg.user {
            align-self: flex-end;
            background: #0F6E56;
            color: white;
            border-bottom-right-radius: 4px;
        }
        .ia-msg.assistant {
            align-self: flex-start;
            background: white;
            color: #1e293b;
            border: 1px solid #eef0f4;
            border-bottom-left-radius: 4px;
        }
        .ia-msg.typing { font-style: italic; color: #94a3b8; }

        .ia-chat-footer {
            padding: 0.8rem;
            border-top: 1px solid #eef0f4;
            display: flex;
            gap: 0.5rem;
        }
        .ia-chat-footer input {
            flex: 1;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }
        .ia-chat-footer button {
            background: #0F6E56;
            color: white;
            border: none;
            border-radius: 50%;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
        }
    </style>
</head>
<body>

    @if(session('just_logged_in'))
    <div id="welcomeToast" class="welcome-toast">
        <i class="bi bi-check-circle-fill"></i>
        <div>Bienvenue, <strong>{{ trim(Auth::user()->prenom.' '.Auth::user()->nom) }}</strong> !</div>
    </div>
    @endif

    <div class="topnav">
        <div class="logo-wrap">
            <svg width="26" height="26" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="22" fill="#0F6E56"/>
                <circle cx="20" cy="24" r="9" fill="#0F6E56"/>
                <circle cx="80" cy="24" r="9" fill="#0F6E56"/>
                <circle cx="50" cy="86" r="9" fill="#0F6E56"/>
                <line x1="20" y1="24" x2="50" y2="50" stroke="#0F6E56" stroke-width="3"/>
                <line x1="80" y1="24" x2="50" y2="50" stroke="#0F6E56" stroke-width="3"/>
                <line x1="50" y1="86" x2="50" y2="50" stroke="#0F6E56" stroke-width="3"/>
            </svg>
            <div class="logo-text">STAGE<span class="accent">-UP</span></div>
        </div>

        <div class="right-side">
            @php
                $nonLusTotal = Auth::check()
                    ? \App\Models\Message::where('destinataire_id', Auth::id())->where('lu', false)->count()
                    : 0;
            @endphp
            <a href="/messages" class="messages-icon" title="Messagerie">
                <i class="bi bi-envelope-fill"></i>
                @if($nonLusTotal > 0)
                <span class="messages-badge">{{ $nonLusTotal > 9 ? '9+' : $nonLusTotal }}</span>
                @endif
            </a>
            <div class="user-info">
                <div class="name">{{ trim(Auth::user()->prenom.' '.Auth::user()->nom) }}</div>
                <span class="role-badge">{{ Auth::user()->role->nom_role }}</span>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="btn-logout">Déconnexion</button>
            </form>
        </div>
    </div>

    <div class="page-header">
        <h2>@yield('page-title', 'Dashboard')</h2>
        <p>Bienvenue, <strong>{{ trim(Auth::user()->prenom.' '.Auth::user()->nom) }}</strong> — @yield('page-subtitle', '')</p>
    </div>

    <div class="tabs-row">
        @yield('tabs')
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <button class="ia-bubble" id="iaBubble">
        <i class="bi bi-chat-dots-fill"></i>
    </button>

    <div class="ia-chat-window" id="iaChatWindow">
        <div class="ia-chat-header">
            <div class="title"><i class="bi bi-stars"></i> Assistant STAGE-UP</div>
            <button id="iaCloseBtn"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="ia-chat-body" id="iaChatBody">
            <div class="ia-msg assistant">Bonjour ! Comment puis-je vous aider aujourd'hui ?</div>
        </div>
        <div class="ia-chat-footer">
            <input type="text" id="iaInput" placeholder="Écrivez un message...">
            <button id="iaSendBtn"><i class="bi bi-send-fill"></i></button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
    <script>
        setTimeout(function() {
            const toast = document.getElementById('welcomeToast');
            if (toast) toast.remove();
        }, 5000);
    </script>
    <script>
        $(document).ready(function() {
            const bubble = $('#iaBubble');
            const chatWindow = $('#iaChatWindow');
            const chatBody = $('#iaChatBody');
            const input = $('#iaInput');

            let historyLoaded = false;

            bubble.on('click', function() {
                chatWindow.toggleClass('open');
                if (chatWindow.hasClass('open') && !historyLoaded) {
                    loadHistory();
                    historyLoaded = true;
                }
            });

            $('#iaCloseBtn').on('click', function() {
                chatWindow.removeClass('open');
            });

            function loadHistory() {
                $.get('/ia/historique', function(data) {
                    if (data.messages && data.messages.length > 0) {
                        chatBody.empty();
                        data.messages.forEach(function(msg) {
                            appendMessage(msg.role, msg.contenu);
                        });
                    }
                });
            }

            function appendMessage(role, text) {
                const cssClass = role === 'user' ? 'user' : 'assistant';
                chatBody.append('<div class="ia-msg ' + cssClass + '">' + $('<div>').text(text).html() + '</div>');
                chatBody.scrollTop(chatBody[0].scrollHeight);
            }

            function sendMessage() {
                const message = input.val().trim();
                if (!message) return;

                appendMessage('user', message);
                input.val('');

                const typingIndicator = $('<div class="ia-msg assistant typing">L\'assistant écrit...</div>');
                chatBody.append(typingIndicator);
                chatBody.scrollTop(chatBody[0].scrollHeight);

                $.ajax({
                    url: '/ia/envoyer',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        message: message
                    },
                    success: function(data) {
                        typingIndicator.remove();
                        appendMessage('assistant', data.reponse);
                    },
                    error: function() {
                        typingIndicator.remove();
                        appendMessage('assistant', 'Une erreur est survenue. Réessayez.');
                    }
                });
            }

            $('#iaSendBtn').on('click', sendMessage);
            input.on('keypress', function(e) {
                if (e.which === 13) sendMessage();
            });
        });
    </script>
    @yield('scripts')
</body>
</html>