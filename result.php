<?php
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';

if (empty(trim($name)) || empty(trim($email))) {
    ?>
    <form id="errorForm" action="index.php" method="POST" style="display: none;">
        <input type="hidden" name="error" value="1">
        <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
    </form>
    <script>
        document.getElementById('errorForm').submit();
    </script>
    <?php
    exit();
}

$currentTime = date('H:i:s');
$currentDay = date('l');
$currentDate = date('d-m-Y');

$dayMapping = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu'
];

$dayIndonesian = $dayMapping[$currentDay] ?? $currentDay;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Informasi Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="90" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .dashboard-container {
            position: relative;
            z-index: 1;
        }
        
        .result-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        
        .result-card:hover {
            transform: translateY(-5px);
        }
        
        .card-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            text-align: center;
            padding: 25px 20px;
            position: relative;
            overflow: hidden;
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
        
        .status-icon {
            font-size: 3rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
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
        
        .info-section {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: #667eea;
        }
        
        .data-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin-bottom: 10px;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        
        .data-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .data-label {
            font-weight: 500;
            color: #495057;
            display: flex;
            align-items: center;
        }
        
        .data-label i {
            margin-right: 8px;
            color: #667eea;
        }
        
        .data-value {
            font-weight: 400;
            color: #212529;
        }
        
        .btn-back {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        
        .btn-back::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-back:hover::before {
            left: 100%;
        }
        
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .time-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            .data-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
            
            .result-card {
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card result-card">
                    <div class="card-header">
                        <div class="status-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h3 class="mb-0">Login Berhasil!</h3>
                        <p class="mb-0">Selamat datang di dashboard Anda</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="info-section">
                            <h5 class="section-title">
                                <i class="bi bi-person-lines-fill"></i>
                                Informasi Pengguna
                            </h5>
                            
                            <div class="data-item">
                                <span class="data-label">
                                    <i class="bi bi-person"></i>
                                    Nama Lengkap
                                </span>
                                <span class="data-value"><?php echo $name; ?></span>
                            </div>
                            
                            <div class="data-item">
                                <span class="data-label">
                                    <i class="bi bi-envelope"></i>
                                    Alamat Email
                                </span>
                                <span class="data-value"><?php echo $email; ?></span>
                            </div>
                        </div>
                        
                        <div class="info-section">
                            <h5 class="section-title">
                                <i class="bi bi-clock-history"></i>
                                Informasi Sesi Login
                            </h5>
                            
                            <div class="data-item">
                                <span class="data-label">
                                    <i class="bi bi-clock"></i>
                                    Waktu Login
                                </span>
                                <span class="time-badge"><?php echo $currentTime; ?> WIB</span>
                            </div>
                            
                            <div class="data-item">
                                <span class="data-label">
                                    <i class="bi bi-calendar-day"></i>
                                    Hari Login
                                </span>
                                <span class="data-value"><?php echo $dayIndonesian; ?></span>
                            </div>
                            
                            <div class="data-item">
                                <span class="data-label">
                                    <i class="bi bi-calendar3"></i>
                                    Tanggal Login
                                </span>
                                <span class="data-value"><?php echo $currentDate; ?></span>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button onclick="goBack()" class="btn btn-back text-white">
                                <i class="bi bi-arrow-left me-2"></i>
                                Kembali ke Halaman Login
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="backForm" action="index.php" method="POST" style="display: none;">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function goBack() {
            document.getElementById('backForm').submit();
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                text: 'Selamat datang, <?php echo $name; ?>!',
                confirmButtonText: 'Lanjutkan',
                confirmButtonColor: '#28a745',
                background: 'rgba(255, 255, 255, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.4)',
                customClass: {
                    popup: 'rounded-4'
                }
            });
            
            const card = document.querySelector('.result-card');
            const dataItems = document.querySelectorAll('.data-item');
            
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
            
            dataItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    item.style.transition = 'all 0.4s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, 300 + (index * 100));
            });
        });
    </script>
</body>
</html>