<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo env('APP_NAME', 'AdminBRO'); ?> | Two-Factor Authentication</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <link rel="icon" href="<?php echo base_url(); ?><?php echo env('APP_FAVICON', 'assets/tambahan/gambar/Coates_Indonesia.jpg'); ?>">

  <style type="text/css">
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    body {
      background: #d2d6de;
      font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .login-box {
      width: 360px;
      margin: 0 auto;
    }

    .login-logo {
      margin-bottom: 10px;
    }

    .login-logo a {
      color: #444;
      font-weight: 600;
      font-size: 24px;
    }

    .login-box-body {
      background: #fff;
      padding: 20px;
      border-radius: 4px;
      border-top: 3px solid #3c8dbc;
      box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    }

    .login-box-msg {
      margin: 0 0 10px 0;
      text-align: center;
      font-size: 14px;
      color: #666;
    }

    .hint-text {
      margin: 0 0 15px 0;
      text-align: center;
      font-size: 13px;
      color: #777;
    }

    .login-box .form-control {
      border-radius: 0;
      box-shadow: none;
      border-color: #d2d6de;
      font-size: 18px;
      letter-spacing: 6px;
      text-align: center;
      height: 42px;
      padding: 6px 12px;
    }

    .login-box .form-control:focus {
      border-color: #3c8dbc;
      box-shadow: none;
    }

    .login-box .btn {
      border-radius: 3px;
      padding: 8px 16px;
      font-size: 15px;
    }

    .login-box .btn-primary {
      background-color: #3c8dbc;
      border-color: #367fa9;
    }

    .login-box .btn-primary:hover,
    .login-box .btn-primary:active {
      background-color: #367fa9;
      border-color: #2e6da4;
    }

    .text-with-link {
      margin-top: 15px;
      text-align: center;
      font-size: 13px;
    }

    .text-with-link a {
      color: #3c8dbc;
    }

    .text-with-link a:hover {
      color: #2e6da4;
      text-decoration: underline;
    }

    .divider {
      display: block;
      margin: 10px 0;
      border-top: 1px solid #eee;
    }

    @media (max-width: 480px) {
      .login-box {
        width: 90%;
      }
    }
  </style>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b><?php echo env('APP_NAME', 'AdminBRO'); ?></b></a>
    </div>

    <div class="login-box-body">
      <h4 class="login-box-msg">
        <i class="fa fa-shield text-primary"></i> Two-Factor Authentication
      </h4>

      <p class="hint-text" id="verifyHint">Masukkan kode verifikasi dari aplikasi authenticator Anda.</p>

      <?php echo show_err_msg($this->session->flashdata('error_msg')); ?>

      <form action="<?php echo base_url('Default/Auth/verify_2fa_code'); ?>" method="post" id="verifyForm">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="code" id="code" placeholder="000000" maxlength="8" required autofocus pattern="[0-9A-Za-z]{6,8}">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <input type="hidden" name="use_backup" id="use_backup" value="0">

        <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block">
              <i class="fa fa-check-circle"></i> Verifikasi
            </button>
          </div>
        </div>
      </form>

      <div class="text-with-link">
        <a href="#" id="useBackupCodeLink">
          <i class="fa fa-key"></i> <span id="backupLinkText">Gunakan Backup Code</span>
        </a>
        <span class="divider"></span>
        <a href="<?php echo base_url('login'); ?>">
          <i class="fa fa-arrow-left"></i> Kembali ke Login
        </a>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>

  <script>
    $(document).ready(function() {
      // Auto-format code input
      $('#code').on('input', function() {
        this.value = this.value.replace(/[^0-9A-Za-z]/g, '');
      });

      // Toggle backup code mode
      $('#useBackupCodeLink').click(function(e) {
        e.preventDefault();
        var isBackupMode = $('#use_backup').val() == '1';

        if (isBackupMode) {
          $('#use_backup').val('0');
          $('#code').attr('placeholder', '000000');
          $('#code').attr('maxlength', '8');
          $('#code').attr('pattern', '[0-9A-Za-z]{6,8}');
          $('#verifyHint').text('Masukkan kode verifikasi dari aplikasi authenticator Anda.');
          $('#backupLinkText').text('Gunakan Backup Code');
        } else {
          $('#use_backup').val('1');
          $('#code').attr('placeholder', 'XXXXXXXX');
          $('#code').attr('maxlength', '8');
          $('#code').attr('pattern', '[0-9A-Za-z]{8}');
          $('#verifyHint').text('Masukkan salah satu backup code Anda.');
          $('#backupLinkText').text('Gunakan Authenticator App');
        }

        $('#code').val('').focus();
      });

      // Auto-submit when code is complete (only for TOTP, not backup codes)
      $('#code').on('input', function() {
        if ($('#use_backup').val() == '0' && this.value.length === 6) {
          setTimeout(function() {
            $('#verifyForm').submit();
          }, 300);
        }
      });
    });
  </script>
</body>
</html>
