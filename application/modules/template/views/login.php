
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        <?php echo (!empty($setting->title) ? $setting->title : null) ?> ::
        <?php echo (!empty($title) ? $title : null) ?>
    </title>
    
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon"
        href="<?php echo base_url((!empty($setting->favicon) ? $setting->favicon : 'assets_old/img/icons/favicon.png')) ?>"
        type="image/x-icon">
    <meta name="description" content="Login">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="msapplication-tap-highlight" content="no">
    
    <!-- CSS -->
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="<?php echo base_url() ?>assets/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="<?php echo base_url()?>assets/css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="<?php echo base_url() ?>assets/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="<?php echo base_url() ?>assets/css/fa-brands.css">
    
    <style>
        :root {
            --primary-color: #617a28;
            --primary-hover: #4f6216;
            --secondary-color: #f8f9fa;
            --text-primary: #212529;
            --text-muted: #6c757d;
            --border-color: #dee2e6;
            --shadow: 0 10px 30px rgba(0,0,0,0.1);
            --border-radius: 12px;
        }
        
        body {
            background: url('<?php echo base_url() ?>assets/img/backgrounds/clouds.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), #8ba34a);
        }
        
        .system-title {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: linear-gradient(135deg, rgba(97, 122, 40, 0.1) 0%, rgba(139, 163, 74, 0.1) 100%);
            border-radius: 8px;
            border: 1px solid rgba(97, 122, 40, 0.2);
        }
        
        .system-title h2 {
            color: var(--primary-color);
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo-section img {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }
        
        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .welcome-text h1 {
            color: var(--text-primary);
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 8px 0;
        }
        
        .welcome-text p {
            color: var(--text-muted);
            font-size: 14px;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            color: var(--text-primary);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
            color: var(--text-primary);
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(97, 122, 40, 0.1);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
            opacity: 0.7;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
            transform: scale(1.2);
            accent-color: var(--primary-color);
        }
        
        .checkbox-group label {
            color: var(--text-muted);
            font-size: 14px;
            cursor: pointer;
        }
        
        .login-btn {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .login-btn:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(97, 122, 40, 0.3);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: none;
        }
        
        .alert-info {
            background: rgba(97, 122, 40, 0.1);
            color: var(--primary-color);
            border-left: 4px solid var(--primary-color);
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-left: 4px solid #dc3545;
        }
        
        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border-left: 4px solid #ffc107;
        }
        
        .user-info-card {
            background: linear-gradient(135deg, rgba(97, 122, 40, 0.05) 0%, rgba(139, 163, 74, 0.05) 100%);
            border: 1px solid rgba(97, 122, 40, 0.2);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .user-info-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: var(--primary-color);
        }
        
        .user-info-header i {
            font-size: 24px;
            margin-right: 10px;
        }
        
        .user-info-header span {
            font-size: 16px;
            font-weight: 600;
        }
        
        .user-info-details {
            text-align: left;
        }
        
        .user-info-details p {
            margin: 8px 0;
            color: var(--text-muted);
            font-size: 14px;
        }
        
        .user-info-details strong {
            color: var(--text-primary);
        }
        
        .alert .close {
            float: right;
            font-size: 18px;
            font-weight: bold;
            line-height: 1;
            color: inherit;
            opacity: 0.5;
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .alert .close:hover {
            opacity: 1;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }
        
        .footer a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 12px;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--primary-color);
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .welcome-text h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <img src="<?php echo base_url() ?>assets_old/images/moh.png" alt="Ministry of Health Logo">
            </div>
            
            <!-- Welcome Text -->
            <div class="welcome-text">
                <?php if (!empty($setting->title)): ?>
                    <div class="system-title">
                        <h2><?php echo $setting->title; ?></h2>
                    </div>
                <?php endif; ?>
                <h1>Welcome Back</h1>
                <p>Sign in to your account to continue</p>
            </div>
            
            <!-- Session Timeout Message -->
            <?php if ($this->session->flashdata('session_timeout') != null) { ?>
                <div class="alert alert-warning">
                    <i class="fa fa-clock-o" style="margin-right: 8px;"></i>
                    <strong>Session Expired:</strong> Your session has timed out. Please sign in again to continue.
                </div>
            <?php } ?>
            
            <!-- User Info Display (when session timeout) -->
            <?php if ($this->session->flashdata('user_email') != null || $this->session->flashdata('user_name') != null) { ?>
                <div class="user-info-card">
                    <div class="user-info-header">
                        <i class="fa fa-user-circle"></i>
                        <span>Welcome back, <?php echo $this->session->flashdata('user_name') ?: 'User'; ?>!</span>
                    </div>
                    <div class="user-info-details">
                        <p><strong>Email:</strong> <?php echo $this->session->flashdata('user_email'); ?></p>
                        <p><strong>Name:</strong> <?php echo $this->session->flashdata('user_name'); ?></p>
                    </div>
                </div>
            <?php } ?>
            
            <!-- Alert Messages -->
            <?php if ($this->session->flashdata('message') != null) { ?>
                <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('message');
                    $this->session->unset_userdata('message'); ?>
                </div>
            <?php } ?>
            
            <?php if ($this->session->flashdata('exception') != null) { ?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('exception');
                    $this->session->unset_userdata('exception'); ?>
                </div>
            <?php } ?>
            
            <?php if (validation_errors()) { ?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo validation_errors(); ?>
                </div>
            <?php } ?>
            
            <!-- Login Form -->
            <?php echo form_open('login', 'id="loginForm" novalidate'); ?>
                <div class="form-group">
                    <label class="form-label" for="username">Email Address</label>
                    <input type="email" id="username" class="form-control" name="email" placeholder="Enter your email address" 
                           value="<?php echo $this->session->flashdata('user_email') ?: ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" value="" required>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="rememberme" name="rememberme">
                    <label for="rememberme">Remember me</label>
                </div>
                
                <button id="js-login-btn" type="submit" class="login-btn">
                    <?php echo display('login') ?>
                </button>
            </form>
            
            <!-- Footer -->
            <div class="footer">
                <a href="https://health.go.ug" target="_blank">
                    Copyright © Ministry of Health, All Rights Reserved
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo base_url()?>assets/js/vendors.bundle.js"></script>
    <script src="<?php echo base_url() ?>assets/js/app.bundle.js"></script>
    
    <script type="text/javascript">
        // Auto-hide alerts after 5 seconds
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);
        
        // Close alert functionality
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('.alert .close');
            closeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const alert = this.closest('.alert');
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                });
            });
        });
    </script>
</body>
</html>
