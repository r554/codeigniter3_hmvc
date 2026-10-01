<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo env('APP_NAME', 'AdminBRO'); ?> | Setup 2FA</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <link rel="icon" href="<?php echo base_url(); ?><?php echo env('APP_FAVICON', 'assets/tambahan/gambar/Coates_Indonesia.jpg'); ?>">
  
  <style type="text/css">
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      padding: 40px 20px;
    }
    
    .setup-box {
      max-width: 600px;
      margin: 0 auto;
    }
    
    .setup-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    
    .setup-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 30px;
      text-align: center;
      color: white;
    }
    
    .setup-header i {
      font-size: 48px;
      margin-bottom: 15px;
    }
    
    .setup-header h3 {
      margin: 0;
      font-size: 24px;
      font-weight: 300;
    }
    
    .setup-body {
      padding: 40px;
    }
    
    .step-indicator {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
      position: relative;
    }
    
    .step {
      flex: 1;
      text-align: center;
      position: relative;
      z-index: 1;
    }
    
    .step-number {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #e0e0e0;
      color: #666;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      margin-bottom: 8px;
    }
    
    .step.active .step-number {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .step-label {
      font-size: 12px;
      color: #666;
    }
    
    .qr-container {
      text-align: center;
      padding: 30px;
      background: #f8f9fa;
      border-radius: 15px;
      margin: 25px 0;
    }
    
    .qr-code {
      display: inline-block;
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .qr-code img {
      max-width: 250px;
      height: auto;
    }
    
    .secret-key {
      margin-top: 20px;
      padding: 15px;
      background: white;
      border-radius: 10px;
      border: 2px dashed #667eea;
    }
    
    .secret-key code {
      font-size: 16px;
      color: #667eea;
      font-weight: 600;
      letter-spacing: 2px;
      background: none;
      padding: 0;
    }
    
    .instruction-list {
      margin: 25px 0;
    }
    
    .instruction-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 15px;
      padding: 15px;
      background: #f8f9fa;
      border-radius: 10px;
      transition: all 0.3s;
    }
    
    .instruction-item:hover {
      background: #e9ecef;
      transform: translateX(5px);
    }
    
    .instruction-number {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      margin-right: 15px;
      flex-shrink: 0;
    }
    
    .instruction-text {
      flex: 1;
      padding-top: 3px;
    }
    
    .verify-section {
      margin-top: 30px;
      padding-top: 30px;
      border-top: 2px solid #e0e0e0;
    }
    
    .code-input {
      font-size: 20px;
      text-align: center;
      letter-spacing: 8px;
      font-weight: 600;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      transition: all 0.3s;
    }
    
    .code-input:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-confirm {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-size: 16px;
      font-weight: 600;
      color: white;
      width: 100%;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-confirm:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
      color: white;
    }
    
    .alert {
      border-radius: 10px;
      margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
      .setup-body {
        padding: 25px 20px;
      }
      
      .qr-code img {
        max-width: 200px;
      }
    }
  </style>
</head>
<body>
  <div class="setup-box">
    <div class="setup-card">
      <div class="setup-header">
        <i class="fa fa-shield"></i>
        <h3>Aktifkan Two-Factor Authentication</h3>
      </div>
      
      <div class="setup-body">
        <?php echo show_err_msg($this->session->flashdata('error_msg')); ?>
        
        <div class="step-indicator">
          <div class="step active">
            <div class="step-number">1</div>
            <div class="step-label">Scan QR Code</div>
          </div>
          <div class="step">
            <div class="step-number">2</div>
            <div class="step-label">Verifikasi</div>
          </div>
          <div class="step">
            <div class="step-number">3</div>
            <div class="step-label">Selesai</div>
          </div>
        </div>
        
        <div class="instruction-list">
          <div class="instruction-item">
            <div class="instruction-number">1</div>
            <div class="instruction-text">
              Download aplikasi <strong>Google Authenticator</strong> atau <strong>Authy</strong> di smartphone Anda
            </div>
          </div>
          <div class="instruction-item">
            <div class="instruction-number">2</div>
            <div class="instruction-text">
              Buka aplikasi dan scan QR code di bawah ini
            </div>
          </div>
          <div class="instruction-item">
            <div class="instruction-number">3</div>
            <div class="instruction-text">
              Masukkan kode 6 digit yang muncul di aplikasi untuk verifikasi
            </div>
          </div>
        </div>
        
        <div class="qr-container">
          <div class="qr-code">
            <img src="<?php echo $qr_code_url; ?>" alt="QR Code">
          </div>
          
          <div class="secret-key">
            <small>Atau masukkan kode manual ini:</small><br>
            <code><?php echo $secret; ?></code>
          </div>
        </div>
        
        <div class="verify-section">
          <h4 style="text-align: center; margin-bottom: 20px;">
            <i class="fa fa-check-circle"></i> Verifikasi Setup
          </h4>
          
          <form action="<?php echo base_url('Default/Auth/confirm_2fa'); ?>" method="post">
            <input type="text" class="form-control code-input" name="code" placeholder="000000" maxlength="6" required autofocus pattern="[0-9]{6}">
            
            <button type="submit" class="btn btn-confirm">
              <i class="fa fa-check"></i> Aktifkan 2FA
            </button>
          </form>
          
          <div style="text-align: center; margin-top: 20px;">
            <a href="<?php echo base_url('Dashboard'); ?>" style="color: #666; text-decoration: none;">
              <i class="fa fa-times"></i> Batal
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
  
  <script>
    $(document).ready(function() {
      // Auto-format code input
      $('input[name="code"]').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
      });
    });
  </script>
</body>
</html>
