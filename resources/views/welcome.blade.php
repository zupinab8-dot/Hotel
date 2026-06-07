<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MadeiraBR - Login</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-content">
            <!-- Left side - Branding -->
            <div class="branding">
                <h1>MadeiraBR</h1>
                <p class="subtitle">Sistema completo de agendamento e venda de madeiras brasileiras</p>
                
                <div class="features">
                    <div class="feature">
                        <div class="feature-icon">🌳</div>
                        <div>
                            <h3>15 Tipos de Madeiras</h3>
                            <p>Catálogo completo com as melhores madeiras brasileiras</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">📅</div>
                        <div>
                            <h3>Agendamento Fácil</h3>
                            <p>Agende sua compra de forma rápida e prática</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🚚</div>
                        <div>
                            <h3>Entrega Garantida</h3>
                            <p>Receba no endereço de sua preferência</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right side - Login Form -->
            <div class="login-card">
                <div class="login-icon">🔐</div>
                <h2>Entrar no Sistema</h2>
                <p class="login-description">Digite suas credenciais para acessar sua conta</p>
                
                <form id="loginForm">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" placeholder="seu@email.com" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" id="password" placeholder="••••••" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                </form>

                <div class="divider">
                    <span>Contas de teste</span>
                </div>

                <div class="test-buttons">
                    <button onclick="fillClient()" class="btn btn-outline">👤 Cliente</button>
                    <button onclick="fillAdmin()" class="btn btn-outline">🔒 Admin</button>
                </div>

                <div class="credentials-info">
                    <p><strong>Credenciais de teste:</strong></p>
                    <p><strong>Cliente:</strong> cliente@email.com / 123456</p>
                    <p><strong>Admin:</strong> admin@email.com / admin123</p>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
        function fillClient() {
            document.getElementById('email').value = 'cliente@email.com';
            document.getElementById('password').value = '123456';
        }

        function fillAdmin() {
            document.getElementById('email').value = 'admin@email.com';
            document.getElementById('password').value = 'admin123';
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            const user = login(email, password);
            
            if (user) {
                showToast(`Bem-vindo, ${user.name}!`, 'success');
                setTimeout(() => {
                    if (user.role === 'admin') {
                        window.location.href = '/admin';
                    } else {
                        window.location.href = '/cliente';
                    }
                }, 1000);
            } else {
                showToast('Email ou senha incorretos', 'error');
            }
        });

        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast toast-${type} toast-show`;
            setTimeout(() => {
                toast.className = 'toast';
            }, 3000);
        }
    </script>
</body>
</html>
