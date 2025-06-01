<?php
$showError = isset($_POST['error']) && $_POST['error'] == '1';
$errorMessage = '';
$nameValue = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
$emailValue = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';

if ($showError) {
    $missingFields = [];
    if (empty(trim($nameValue))) {
        $missingFields[] = 'Nama Lengkap';
    }
    if (empty(trim($emailValue))) {
        $missingFields[] = 'Email';
    }
    
    if (!empty($missingFields)) {
        $errorMessage = 'Field berikut harus diisi: ' . implode(', ', $missingFields);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="90" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .login-container {
            position: relative;
            z-index: 1;
        }
        
        .login-card {
            max-width: 450px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
        }
        
        .login-card.shake {
            animation: shake 0.6s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 30px 20px;
            position: relative;
            overflow: hidden;
        }
        
        .card-header.error-state {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            animation: pulse-error 1s ease-in-out;
        }
        
        @keyframes pulse-error {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(180deg); }
        }
        
        .card-header h3 {
            font-weight: 600;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
        }
        
        .card-header p {
            font-weight: 300;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        
        .form-floating {
            margin-bottom: 20px;
        }
        
        .form-floating > .form-control {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 1rem 0.75rem;
            height: calc(3.5rem + 2px);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-floating > .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            background: rgba(255, 255, 255, 1);
        }
        
        .form-floating > .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
            animation: shake-input 0.5s ease-in-out;
        }
        
        @keyframes shake-input {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-3px); }
            75% { transform: translateX(3px); }
        }
        
        .form-floating > label {
            color: #6c757d;
            font-weight: 400;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 15px;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .welcome-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.9;
            transition: all 0.3s ease;
        }
        
        .welcome-icon.error-icon {
            color: #ffebee;
            animation: bounce-error 0.6s ease-in-out;
        }
        
        @keyframes bounce-error {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        .feature-list li {
            padding: 8px 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .feature-list li i {
            color: #667eea;
            margin-right: 10px;
        }
        
        .decorative-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        
        .floating-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatShape 6s ease-in-out infinite;
        }
        
        .shape-1 {
            width: 60px;
            height: 60px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .shape-2 {
            width: 40px;
            height: 40px;
            top: 70%;
            right: 15%;
            animation-delay: 2s;
        }
        
        .shape-3 {
            width: 80px;
            height: 80px;
            bottom: 20%;
            left: 5%;
            animation-delay: 4s;
        }
        
        @keyframes floatShape {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.1); }
        }
        
        .error-alert {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: none;
            border-left: 5px solid #dc3545;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 576px) {
            .login-card {
                margin: 10px;
                border-radius: 15px;
            }
            
            .card-header {
                padding: 25px 15px;
            }
            
            .welcome-icon {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="decorative-elements">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
    </div>
    
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-card <?php echo $showError ? 'shake' : ''; ?>" id="loginCard">
                    <div class="card-header <?php echo $showError ? 'error-state' : ''; ?>" id="cardHeader">
                        <div class="welcome-icon <?php echo $showError ? 'error-icon' : ''; ?>" id="welcomeIcon">
                            <?php if ($showError): ?>
                                <i class="bi bi-exclamation-triangle-fill"></i>
                            <?php else: ?>
                                <i class="bi bi-person-circle"></i>
                            <?php endif; ?>
                        </div>
                        <h3 class="mb-0" id="headerTitle">
                            <?php echo $showError ? 'Login Gagal!' : 'Selamat Datang'; ?>
                        </h3>
                        <p class="mb-0" id="headerSubtitle">
                            <?php echo $showError ? 'Mohon periksa kembali data Anda' : 'Silakan masuk untuk melanjutkan'; ?>
                        </p>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($showError && !empty($errorMessage)): ?>
                            <div class="alert error-alert d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-5 text-danger"></i>
                                <div>
                                    <strong>Oops!</strong> <?php echo $errorMessage; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <form action="result.php" method="POST" id="loginForm" novalidate>
                            <div class="form-floating">
                                <input type="text" 
                                       class="form-control <?php echo ($showError && empty(trim($nameValue))) ? 'is-invalid' : ''; ?>" 
                                       id="name" 
                                       name="name" 
                                       placeholder="Nama Lengkap" 
                                       value="<?php echo $nameValue; ?>"
                                       required>
                                <label for="name"><i class="bi bi-person me-2"></i>Nama Lengkap</label>
                                <div class="invalid-feedback">
                                    Nama lengkap harus diisi!
                                </div>
                            </div>
                            
                            <div class="form-floating">
                                <input type="email" 
                                       class="form-control <?php echo ($showError && empty(trim($emailValue))) ? 'is-invalid' : ''; ?>" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Email" 
                                       value="<?php echo $emailValue; ?>"
                                       required>
                                <label for="email"><i class="bi bi-envelope me-2"></i>Alamat Email</label>
                                <div class="invalid-feedback">
                                    Email harus diisi dengan format yang benar!
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login text-white w-100" id="submitBtn">
                                <span class="btn-text">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Masuk ke Portal
                                </span>
                                <div class="loading-spinner" id="loadingSpinner"></div>
                            </button>
                        </form>
                        
                        <ul class="feature-list text-center mt-4">
                            <li><i class="bi bi-shield-check"></i>Keamanan terjamin</li>
                            <li><i class="bi bi-clock"></i>Akses 24/7</li>
                            <li><i class="bi bi-people"></i>Dukungan pelanggan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const submitBtn = document.getElementById('submitBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const btnText = document.querySelector('.btn-text');
            const card = document.getElementById('loginCard');
            const cardHeader = document.getElementById('cardHeader');
            const welcomeIcon = document.getElementById('welcomeIcon');
            const headerTitle = document.getElementById('headerTitle');
            const headerSubtitle = document.getElementById('headerSubtitle');
            
            <?php if ($showError): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal!',
                    text: '<?php echo $errorMessage; ?>',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545',
                    background: 'rgba(255, 255, 255, 0.95)',
                    backdrop: 'rgba(0, 0, 0, 0.4)',
                    customClass: {
                        popup: 'rounded-4'
                    }
                });
            <?php endif; ?>
            
            function validateField(field) {
                const value = field.value.trim();
                const isValid = value !== '';
                
                if (isValid) {
                    field.classList.remove('is-invalid');
                    field.classList.add('is-valid');
                } else {
                    field.classList.remove('is-valid');
                    field.classList.add('is-invalid');
                }
                
                return isValid;
            }
            
            nameInput.addEventListener('input', function() {
                validateField(this);
                resetErrorState();
            });
            
            emailInput.addEventListener('input', function() {
                validateField(this);
                resetErrorState();
            });
            
            function resetErrorState() {
                card.classList.remove('shake');
                cardHeader.classList.remove('error-state');
                welcomeIcon.classList.remove('error-icon');
                
                if (!nameInput.value.trim() && !emailInput.value.trim()) {
                    welcomeIcon.innerHTML = '<i class="bi bi-person-circle"></i>';
                    headerTitle.textContent = 'Selamat Datang';
                    headerSubtitle.textContent = 'Silakan masuk untuk melanjutkan';
                }
            }
            
            form.addEventListener('submit', function(e) {
                const nameValid = validateField(nameInput);
                const emailValid = validateField(emailInput);
                
                if (!nameValid || !emailValid) {
                    e.preventDefault();
                    
                    card.classList.add('shake');
                    cardHeader.classList.add('error-state');
                    welcomeIcon.classList.add('error-icon');
                    welcomeIcon.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>';
                    headerTitle.textContent = 'Data Tidak Lengkap!';
                    headerSubtitle.textContent = 'Mohon isi semua field yang diperlukan';
                    
                    let missingFields = [];
                    if (!nameValid) missingFields.push('Nama Lengkap');
                    if (!emailValid) missingFields.push('Email');
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Lengkap!',
                        text: `Field berikut harus diisi: ${missingFields.join(', ')}`,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc3545',
                        background: 'rgba(255, 255, 255, 0.95)',
                        backdrop: 'rgba(0, 0, 0, 0.4)',
                        customClass: {
                            popup: 'rounded-4'
                        }
                    });
                    
                    return;
                }
                
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                loadingSpinner.style.display = 'inline-block';
            });
            
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>