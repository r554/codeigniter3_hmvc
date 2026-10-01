<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo env('APP_NAME', 'AdminBRO'); ?> | Backup Codes</title>
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
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    
    .backup-box {
      max-width: 600px;
      width: 100%;
    }
    
    .backup-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 15px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      animation: slideUp 0.5s ease-out;
    }
    
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .backup-header {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      padding: 30px;
      text-align: center;
      color: white;
    }
    
    .backup-header i {
      font-size: 48px;
      margin-bottom: 15px;
    }
    
    .backup-header h3 {
      margin: 0;
      font-size: 24px;
      font-weight: 300;
    }
    
    .backup-body {
      padding: 40px;
    }
    
    .success-message {
      background: #d1fae5;
      border: 2px solid #10b981;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 30px;
      text-align: center;
      color: #065f46;
    }
    
    .success-message i {
      font-size: 48px;
      color: #10b981;
      margin-bottom: 10px;
    }
    
    .warning-box {
      background: #fef3c7;
      border: 2px solid #f59e0b;
      border-radius: 10px;
      padding: 20px;
      margin-bottom: 25px;
    }
    
    .warning-box i {
      color: #f59e0b;
      margin-right: 10px;
    }
    
    .warning-box strong {
      color: #92400e;
    }
    
    .codes-container {
      background: #f8f9fa;
      border-radius: 15px;
      padding: 30px;
      margin: 25px 0;
    }
    
    .codes-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
      margin-bottom: 20px;
    }
    
    .code-item {
      background: white;
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      border: 2px solid #e0e0e0;
      transition: all 0.3s;
    }
    
    .code-item:hover {
      border-color: #667eea;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    }
    
    .code-text {
      font-size: 18px;
      font-weight: 600;
      color: #667eea;
      letter-spacing: 3px;
      font-family: 'Courier New', monospace;
    }
    
    .action-buttons {
      display: flex;
      gap: 15px;
      margin-top: 25px;
    }
    
    .btn-download, .btn-print, .btn-copy {
      flex: 1;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-size: 14px;
      font-weight: 600;
      color: white;
      transition: all 0.3s;
      cursor: pointer;
    }
    
    .btn-download {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-download:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }
    
    .btn-print {
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
    }
    
    .btn-print:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.6);
    }
    
    .btn-copy {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
    }
    
    .btn-copy:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6);
    }
    
    .btn-continue {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      border: none;
      border-radius: 10px;
      padding: 15px;
      font-size: 16px;
      font-weight: 600;
      color: white;
      width: 100%;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
      margin-top: 25px;
    }
    
    .btn-continue:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(16, 185, 129, 0.6);
      color: white;
    }
    
    .instructions {
      margin-top: 25px;
      padding: 20px;
      background: #e0e7ff;
      border-radius: 10px;
      border-left: 4px solid #667eea;
    }
    
    .instructions h5 {
      color: #4338ca;
      margin-top: 0;
    }
    
    .instructions ul {
      margin-bottom: 0;
      padding-left: 20px;
    }
    
    .instructions li {
      margin-bottom: 8px;
      color: #4338ca;
    }
    
    @media (max-width: 768px) {
      .codes-grid {
        grid-template-columns: 1fr;
      }
      
      .action-buttons {
        flex-direction: column;
      }
      
      .backup-body {
        padding: 25px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="backup-box">
    <div class="backup-card">
      <div class="backup-header">
        <i class="fa fa-check-circle"></i>
        <h3>2FA Berhasil Diaktifkan!</h3>
      </div>
      
      <div class="backup-body">
        <div class="success-message">
          <i class="fa fa-shield"></i>
          <h4>Akun Anda Sekarang Lebih Aman</h4>
          <p>Two-Factor Authentication telah berhasil diaktifkan</p>
        </div>
        
        <div class="warning-box">
          <i class="fa fa-exclamation-triangle"></i>
          <strong>PENTING:</strong> Simpan backup codes di bawah ini di tempat yang aman. Anda akan membutuhkannya jika kehilangan akses ke aplikasi authenticator Anda. Setiap code hanya dapat digunakan sekali.
        </div>
        
        <div class="codes-container">
          <h4 style="text-align: center; margin-bottom: 20px;">
            <i class="fa fa-key"></i> Backup Codes Anda
          </h4>
          
          <div class="codes-grid" id="codesGrid">
            <?php foreach ($backup_codes as $index => $code): ?>
              <div class="code-item">
                <div class="code-text"><?php echo $code; ?></div>
              </div>
            <?php endforeach; ?>
          </div>
          
          <div class="action-buttons">
            <button class="btn-download" onclick="downloadCodes()">
              <i class="fa fa-download"></i> Download
            </button>
            <button class="btn-print" onclick="printCodes()">
              <i class="fa fa-print"></i> Print
            </button>
            <button class="btn-copy" onclick="copyCodes()">
              <i class="fa fa-copy"></i> Copy
            </button>
          </div>
        </div>
        
        <div class="instructions">
          <h5><i class="fa fa-info-circle"></i> Cara Menggunakan Backup Codes:</h5>
          <ul>
            <li>Gunakan backup code saat Anda tidak dapat mengakses aplikasi authenticator</li>
            <li>Setiap code hanya bisa digunakan satu kali</li>
            <li>Simpan codes ini di tempat yang aman (password manager, safe deposit box, dll)</li>
            <li>Jangan bagikan codes ini kepada siapapun</li>
          </ul>
        </div>
        
        <a href="<?php echo base_url('Dashboard'); ?>" class="btn btn-continue">
          <i class="fa fa-arrow-right"></i> Lanjutkan ke Dashboard
        </a>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
  
  <script>
    var backupCodes = <?php echo json_encode($backup_codes); ?>;
    
    function downloadCodes() {
      var content = "Two-Factor Authentication Backup Codes\n";
      content += "Generated: " + new Date().toLocaleString() + "\n\n";
      content += "IMPORTANT: Keep these codes in a safe place!\n";
      content += "Each code can only be used once.\n\n";
      content += "Your Backup Codes:\n";
      content += "==================\n\n";
      
      backupCodes.forEach(function(code, index) {
        content += (index + 1) + ". " + code + "\n";
      });
      
      var blob = new Blob([content], { type: 'text/plain' });
      var link = document.createElement('a');
      link.href = window.URL.createObjectURL(blob);
      link.download = '2fa-backup-codes.txt';
      link.click();
    }
    
    function printCodes() {
      var printWindow = window.open('', '', 'height=600,width=800');
      printWindow.document.write('<html><head><title>2FA Backup Codes</title>');
      printWindow.document.write('<style>body{font-family:Arial,sans-serif;padding:40px;}.code{font-size:18px;font-weight:bold;letter-spacing:3px;margin:10px 0;}</style>');
      printWindow.document.write('</head><body>');
      printWindow.document.write('<h2>Two-Factor Authentication Backup Codes</h2>');
      printWindow.document.write('<p>Generated: ' + new Date().toLocaleString() + '</p>');
      printWindow.document.write('<p><strong>IMPORTANT:</strong> Keep these codes in a safe place! Each code can only be used once.</p>');
      printWindow.document.write('<hr>');
      
      backupCodes.forEach(function(code, index) {
        printWindow.document.write('<div class="code">' + (index + 1) + '. ' + code + '</div>');
      });
      
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
    }
    
    function copyCodes() {
      var text = backupCodes.join('\n');
      
      if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
          alert('Backup codes berhasil disalin ke clipboard!');
        });
      } else {
        // Fallback for older browsers
        var textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        alert('Backup codes berhasil disalin ke clipboard!');
      }
    }
  </script>
</body>
</html>
