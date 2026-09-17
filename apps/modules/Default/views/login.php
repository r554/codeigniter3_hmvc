<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminBRO | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">
  <!-- Favicon -->
  <link rel="icon" href="<?php echo base_url(); ?><?php echo env('APP_FAVICON', 'assets/tambahan/gambar/Coates_Indonesia.jpg'); ?>">
  
  <style type="text/css">
    /* AdminLTE Official Login Style */
    body {
      background: #d2d6de;
      min-height: 100vh;
      font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
    }
    
    .login-box {
      width: 360px;
      margin: 7% auto;
    }
    
    .login-logo {
      font-size: 35px;
      text-align: center;
      margin-bottom: 25px;
      font-weight: 300;
    }
    
    .login-logo a {
      color: #444;
      text-decoration: none;
    }
    
    .login-box-body {
      background: #fff;
      padding: 20px;
      border-top: 0;
      color: #666;
    }
    
    .login-box-msg {
      margin: 0;
      text-align: center;
      padding: 0 20px 20px 20px;
      font-size: 14px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-control {
      border-radius: 0;
      box-shadow: none;
      border-color: #d2d6de;
      height: 34px;
      padding: 6px 12px;
      font-size: 14px;
    }
    
    .form-control:focus {
      border-color: #3c8dbc;
      box-shadow: none;
    }
    
    .form-control-feedback {
      color: #777;
      height: 34px;
      line-height: 34px;
    }
    
    .btn-primary {
      background-color: #3c8dbc;
      border-color: #367fa9;
      border-radius: 3px;
      box-shadow: none;
      border-width: 1px;
    }
    
    .btn-primary:hover {
      background-color: #367fa9;
      border-color: #204d74;
    }
    
    .btn-block {
      display: block;
      width: 100%;
    }
    
    .btn-flat {
      border-radius: 0;
      border-width: 1px;
    }
    
    .checkbox label {
      font-weight: 400;
      color: #666;
      font-size: 14px;
    }
    
    .alert {
      border-radius: 3px;
      margin-top: 15px;
    }
    
    .social-auth-links {
      margin: 10px 0;
    }
    
    .social-auth-links a {
      color: #fff;
    }
    
    .btn-social {
      position: relative;
      padding-left: 44px;
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .btn-social > :first-child {
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 32px;
      line-height: 34px;
      font-size: 1.6em;
      text-align: center;
      border-right: 1px solid rgba(0,0,0,0.2);
    }
    
    .btn-facebook {
      color: #fff;
      background-color: #3b5998;
      border-color: rgba(0,0,0,0.2);
    }
    
    .btn-facebook:hover {
      background-color: #30497c;
      border-color: rgba(0,0,0,0.2);
    }
    
    .btn-google {
      color: #fff;
      background-color: #dd4b39;
      border-color: rgba(0,0,0,0.2);
    }
    
    .btn-google:hover {
      background-color: #c23321;
      border-color: rgba(0,0,0,0.2);
    }
    
    /* Input with icon styling */
    .has-feedback .form-control {
      padding-right: 42px;
    }
    
    .icheck > label {
      padding-left: 0;
    }
    
    /* Responsive */
    @media (max-width: 480px) {
      .login-box {
        width: 90%;
        margin-top: 20px;
      }
    }
  </style>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Admin</b>BRO</a>
    </div>
    
    <div class="login-box-body">
      <p class="login-box-msg">Sign in to start your session</p>
      
      <form action="<?php echo base_url('Default/Auth/login'); ?>" method="post">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Username" name="username" required autofocus>
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        
        <div class="form-group has-feedback">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" id="showPassword"> Show Password
              </label>
            </div>
          </div>
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
        </div>
      </form>
      
      <div class="social-auth-links text-center">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
        <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
      </div>
      
      <a href="#">I forgot my password</a><br>
      <a href="#" class="text-center">Register a new membership</a>
      
      <?php echo show_err_msg($this->session->flashdata('error_msg')); ?>
    </div>
  </div>

  <!-- jQuery 2.2.3 -->
  <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
  <!-- iCheck -->
  <script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
  
  <script>
    $(function () {
      // Initialize iCheck
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
      });
      
      // Show/Hide Password Toggle
      $('#showPassword').on('ifChanged', function() {
        if ($(this).is(':checked')) {
          $('#password').attr('type', 'text');
        } else {
          $('#password').attr('type', 'password');
        }
      });
    });
  </script>
</body>
</html>
