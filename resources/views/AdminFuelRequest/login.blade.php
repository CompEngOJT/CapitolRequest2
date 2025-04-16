    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login | Online Request System</title>
        <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>
        <div class="login-container">
            <!-- Left Panel with background image and logo -->
            <div class="left-panel">
                <div class="logo-container">
                    <img src="images/logo.png" alt="Province of Misamis Oriental Official Seal" class="logo">
                </div>
                <div class="left-content">
                    <h1>Online Request System</h1>
                    <p>Hassle-free, anytime</p>
                </div>
            </div>
            
            <!-- Right Panel with login form -->
            <div class="right-panel">
                <div class="form-wrapper">
                    <div class="profile-icon-container">
                        <div class="profile-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="admin-badge">ADMIN</div>
                    </div>
                    
                    <div class="form-container">
                        <h2>LOG IN YOUR ACCOUNT</h2>
                        <p class="subtitle">Effortlessly request online</p>

                        <meta name="csrf-token" content="{{ csrf_token() }}">                        
                        <form action="/login" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="input-icon">
                                    <i class="fas fa-user"></i>
                                    <input type="text" id="username" name="username" placeholder="Username" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" id="password" name="password" placeholder="Password" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="login-btn">LOGIN</button>
                        </form>
                        
                        <p class="footer-text">By Provincial Government of Misamis Oriental</p>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('assets/js/login.js') }}"></script>
    </body>
    </html>