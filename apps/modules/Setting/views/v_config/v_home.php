<?php $this->load->view('_heading/_headerContent') ?>

<section class="content">
  <!-- style loading -->
  <div class="loading2"></div>

  <div class="row">
    <div class="col-md-12">
      <div class="nav-tabs-custom">

        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-website" data-toggle="tab"><i class="fa fa-globe"></i> Website</a></li>
          <li><a href="#tab-database" data-toggle="tab"><i class="fa fa-database"></i> Database</a></li>
          <li><a href="#tab-email" data-toggle="tab"><i class="fa fa-envelope"></i> Email / SMTP</a></li>
          <li><a href="#tab-storage" data-toggle="tab"><i class="fa fa-cloud"></i> Storage</a></li>
          <li><a href="#tab-2fa" data-toggle="tab"><i class="fa fa-shield"></i> Keamanan 2FA</a></li>
          <li><a href="<?= site_url('backup') ?>"><i class="fa fa-archive"></i> Backup & Restore</a></li>
        </ul>

        <div class="tab-content">

          <!-- ======== TAB WEBSITE ======== -->
          <div class="tab-pane active" id="tab-website">
            <form class="form-horizontal" id="form-config" method="POST" enctype="multipart/form-data">

              <div class="box-header">
                <h3 class="box-title">Konfigurasi Website</h3>
              </div>

              <!-- Nama Aplikasi -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Nama Aplikasi</label>
                <div class="col-sm-5">
                  <input type="text" name="app_name" class="form-control"
                         value="<?= htmlspecialchars($cfg['app_name']) ?>" placeholder="Nama Aplikasi">
                </div>
              </div>

              <!-- Skin / Tema -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Tema / Skin</label>
                <div class="col-sm-5">
                  <select name="app_skin" id="app_skin" class="form-control">
                    <?php
                    $skins = [
                      'skin-red'          => 'Red',
                      'skin-blue'         => 'Blue',
                      'skin-black'        => 'Black',
                      'skin-green'        => 'Green',
                      'skin-purple'       => 'Purple',
                      'skin-yellow'       => 'Yellow',
                      'skin-blue-light'   => 'Blue Light',
                      'skin-black-light'  => 'Black Light',
                      'skin-green-light'  => 'Green Light',
                      'skin-purple-light' => 'Purple Light',
                      'skin-red-light'    => 'Red Light',
                      'skin-yellow-light' => 'Yellow Light',
                    ];
                    foreach ($skins as $val => $label):
                    ?>
                    <option value="<?= $val ?>" <?= ($cfg['app_skin'] == $val) ? 'selected' : '' ?>>
                      <?= $label ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-sm-2">
                  <span id="skin-preview" class="label" style="padding:6px 12px; font-size:13px;">Preview</span>
                </div>
              </div>

              <!-- Layout Options -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Opsi Layout</label>
                <div class="col-sm-6">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="app_fixed_layout" value="1"
                             <?= !empty($cfg['app_fixed_layout']) ? 'checked' : '' ?>>
                      <strong>Fixed layout</strong>
                      <p class="text-muted" style="margin:0; font-size:12px;">Aktifkan layout fixed. Tidak bisa digunakan bersamaan dengan Boxed layout.</p>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="app_sidebar_collapse" value="1"
                             <?= !empty($cfg['app_sidebar_collapse']) ? 'checked' : '' ?>>
                      <strong>Sidebar Collapse</strong>
                      <p class="text-muted" style="margin:0; font-size:12px;">Sidebar dalam kondisi collapsed saat pertama kali dibuka.</p>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="app_boxed_layout" value="1"
                             <?= !empty($cfg['app_boxed_layout']) ? 'checked' : '' ?>>
                      <strong>Boxed layout</strong>
                      <p class="text-muted" style="margin:0; font-size:12px;">Aktifkan layout boxed. Tidak bisa digunakan bersamaan dengan Fixed layout.</p>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Footer Text -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Footer Text</label>
                <div class="col-sm-5">
                  <input type="text" name="app_footer" class="form-control"
                         value="<?= htmlspecialchars($cfg['app_footer']) ?>" placeholder="Footer text">
                </div>
              </div>

              <hr>
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-image"></i> Aset Gambar</h3>
              </div>

              <!-- Favicon -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Favicon</label>
                <div class="col-sm-4">
                  <input type="file" name="app_favicon" accept=".ico,.png,.jpg,.gif">
                  <p class="help-block">Format: .ico, .png, .jpg | Maks 2MB</p>
                </div>
                <div class="col-sm-2">
                  <?php if (!empty($cfg['app_favicon'])): ?>
                  <img src="<?= base_url($cfg['app_favicon']) ?>" height="32" title="Favicon saat ini">
                  <p class="help-block text-muted" style="font-size:11px;">Saat ini</p>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Logo Sidebar -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Logo Sidebar</label>
                <div class="col-sm-4">
                  <input type="file" name="app_logo" accept=".png,.jpg,.gif">
                  <p class="help-block">Format: .png, .jpg | Maks 2MB</p>
                </div>
                <div class="col-sm-2">
                  <?php if (!empty($cfg['app_logo'])): ?>
                  <img src="<?= base_url($cfg['app_logo']) ?>" height="32" title="Logo saat ini">
                  <p class="help-block text-muted" style="font-size:11px;">Saat ini</p>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Logo Mini -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Logo Mini (Collapsed)</label>
                <div class="col-sm-4">
                  <input type="file" name="app_logo_mini" accept=".png,.jpg,.gif">
                  <p class="help-block">Format: .png, .jpg | Maks 2MB</p>
                </div>
                <div class="col-sm-2">
                  <?php if (!empty($cfg['app_logo_mini'])): ?>
                  <img src="<?= base_url($cfg['app_logo_mini']) ?>" height="32" title="Logo mini saat ini">
                  <p class="help-block text-muted" style="font-size:11px;">Saat ini</p>
                  <?php endif; ?>
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-success btn-flat">
                  <i class="fa fa-save"></i> Simpan Konfigurasi
                </button>
                <button type="reset" class="btn btn-default btn-flat">
                  <i class="fa fa-retweet"></i> Reset
                </button>
              </div>

            </form>
          </div>
          <!-- /.tab-pane website -->

          <!-- ======== TAB DATABASE ======== -->
          <div class="tab-pane" id="tab-database">
            <form class="form-horizontal" id="form-database" method="POST">

              <div class="box-header">
                <h3 class="box-title">Konfigurasi Database</h3>
              </div>

              <div class="callout callout-warning" style="margin:15px;">
                <h4><i class="fa fa-warning"></i> Perhatian</h4>
                <p>Perubahan akan langsung diterapkan ke <code>apps/config/database.php</code>. Pastikan nilai sudah benar sebelum menyimpan.</p>
              </div>

              <!-- Hostname -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Hostname</label>
                <div class="col-sm-5">
                  <input type="text" name="db_hostname" id="db_hostname" class="form-control"
                         value="<?= htmlspecialchars($db_cfg['hostname']) ?>" placeholder="localhost">
                </div>
              </div>

              <!-- Username -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Username</label>
                <div class="col-sm-5">
                  <input type="text" name="db_username" id="db_username" class="form-control"
                         value="<?= htmlspecialchars($db_cfg['username']) ?>" placeholder="root">
                </div>
              </div>

              <!-- Password -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Password</label>
                <div class="col-sm-5">
                  <div class="input-group">
                    <input type="password" name="db_password" id="db_password" class="form-control"
                           value="<?= htmlspecialchars($db_cfg['password']) ?>" placeholder="Password database">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default btn-flat" id="toggleDbPass">
                        <i class="fa fa-eye"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Nama Database -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Nama Database</label>
                <div class="col-sm-5">
                  <input type="text" name="db_database" id="db_database" class="form-control"
                         value="<?= htmlspecialchars($db_cfg['database']) ?>" placeholder="nama_database">
                </div>
              </div>

              <!-- Driver -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Driver</label>
                <div class="col-sm-3">
                  <select name="db_driver" class="form-control">
                    <option value="mysqli" <?= ($db_cfg['dbdriver'] == 'mysqli') ? 'selected' : '' ?>>mysqli</option>
                    <option value="mysql"  <?= ($db_cfg['dbdriver'] == 'mysql')  ? 'selected' : '' ?>>mysql</option>
                    <option value="pdo"    <?= ($db_cfg['dbdriver'] == 'pdo')    ? 'selected' : '' ?>>pdo</option>
                  </select>
                </div>
              </div>

              <!-- Charset -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Charset</label>
                <div class="col-sm-3">
                  <input type="text" name="db_charset" class="form-control"
                         value="<?= htmlspecialchars($db_cfg['char_set']) ?>" placeholder="utf8">
                </div>
              </div>

              <!-- Test Koneksi -->
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-5">
                  <button type="button" class="btn btn-info btn-flat" id="btn-test-db">
                    <i class="fa fa-plug"></i> Test Koneksi
                  </button>
                  <span id="test-db-result" style="margin-left:10px;"></span>
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-success btn-flat">
                  <i class="fa fa-save"></i> Simpan Konfigurasi Database
                </button>
                <button type="reset" class="btn btn-default btn-flat">
                  <i class="fa fa-retweet"></i> Reset
                </button>
              </div>

            </form>
          </div>
          <!-- /.tab-pane database -->

          <!-- Tab Email -->
          <div class="tab-pane" id="tab-email">
            <form id="form-email" class="form-horizontal">
              <?= $this->security->get_csrf_token_name() ?>
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-server"></i> Konfigurasi SMTP</h3>
                <div class="box-tools pull-right">
                  <a href="<?= site_url('docs-email') ?>" target="_blank" class="btn btn-default btn-sm btn-flat">
                    <i class="fa fa-book"></i> Dokumentasi Cara Pakai
                  </a>
                </div>
              </div>

              <!-- SMTP Host -->
              <div class="form-group">
                <label class="col-sm-3 control-label">SMTP Host</label>
                <div class="col-sm-5">
                  <input type="text" name="mail_host" id="mail_host" class="form-control"
                         value="<?= htmlspecialchars(env('MAIL_HOST','localhost')) ?>"
                         placeholder="Contoh: mail.domain.com">
                </div>
              </div>

              <!-- SMTP Port -->
              <div class="form-group">
                <label class="col-sm-3 control-label">SMTP Port</label>
                <div class="col-sm-3">
                  <input type="number" name="mail_port" id="mail_port" class="form-control"
                         value="<?= htmlspecialchars(env('MAIL_PORT','587')) ?>"
                         placeholder="587">
                </div>
              </div>

              <!-- Enkripsi -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Enkripsi</label>
                <div class="col-sm-3">
                  <select name="mail_encryption" id="mail_encryption" class="form-control">
                    <option value="tls"  <?= env('MAIL_ENCRYPTION','tls')=='tls'  ? 'selected':'' ?>>TLS</option>
                    <option value="ssl"  <?= env('MAIL_ENCRYPTION','tls')=='ssl'  ? 'selected':'' ?>>SSL</option>
                    <option value=""     <?= env('MAIL_ENCRYPTION','tls')==''     ? 'selected':'' ?>>None</option>
                  </select>
                </div>
              </div>

              <!-- Username -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Username / Email</label>
                <div class="col-sm-5">
                  <input type="text" name="mail_username" id="mail_username" class="form-control"
                         value="<?= htmlspecialchars(env('MAIL_USERNAME','')) ?>"
                         placeholder="akun@domain.com" autocomplete="off">
                </div>
              </div>

              <!-- Password -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Password</label>
                <div class="col-sm-5">
                  <div class="input-group">
                    <input type="password" name="mail_password" id="mail_password" class="form-control"
                           value="<?= htmlspecialchars(env('MAIL_PASSWORD','')) ?>"
                           placeholder="Password SMTP" autocomplete="new-password">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default btn-flat" id="toggleMailPass">
                        <i class="fa fa-eye"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </div>

              <hr>
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-paper-plane"></i> Identitas Pengirim</h3>
              </div>

              <!-- From Email -->
              <div class="form-group">
                <label class="col-sm-3 control-label">From Email</label>
                <div class="col-sm-5">
                  <input type="text" name="mail_from_email" id="mail_from_email" class="form-control"
                         value="<?= htmlspecialchars(env('MAIL_FROM_EMAIL','')) ?>"
                         placeholder="noreply@domain.com">
                  <p class="help-block">Jika kosong, menggunakan Username di atas.</p>
                </div>
              </div>

              <!-- From Name -->
              <div class="form-group">
                <label class="col-sm-3 control-label">From Name</label>
                <div class="col-sm-5">
                  <input type="text" name="mail_from_name" id="mail_from_name" class="form-control"
                         value="<?= htmlspecialchars(env('MAIL_FROM_NAME','No Reply')) ?>"
                         placeholder="No Reply">
                </div>
              </div>

              <hr>
              <!-- Test Kirim Email -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Test Kirim Email</label>
                <div class="col-sm-5">
                  <div class="input-group">
                    <input type="email" id="mail_test_to" class="form-control" placeholder="Kirim ke email...">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" id="btn-test-mail">
                        <i class="fa fa-send"></i> Test
                      </button>
                    </span>
                  </div>
                  <span id="test-mail-result" style="display:block; margin-top:5px;"></span>
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-success btn-flat">
                  <i class="fa fa-save"></i> Simpan Konfigurasi Email
                </button>
                <button type="reset" class="btn btn-default btn-flat">
                  <i class="fa fa-retweet"></i> Reset
                </button>
              </div>

            </form>
          </div>
          <!-- /.tab-pane email -->

          <!-- Tab Storage -->
          <div class="tab-pane" id="tab-storage">
            <form id="form-storage" class="form-horizontal">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cloud"></i> Konfigurasi Storage</h3>
                <div class="box-tools pull-right">
                  <a href="<?= site_url('docs-storage') ?>" target="_blank" class="btn btn-default btn-sm btn-flat">
                    <i class="fa fa-book"></i> Dokumentasi Cara Pakai
                  </a>
                </div>
              </div>

              <!-- Driver -->  
              <div class="form-group">
                <label class="col-sm-3 control-label">Driver</label>
                <div class="col-sm-4">
                  <select name="storage_driver" id="storage_driver" class="form-control">
                    <option value="local" <?= env('STORAGE_DRIVER','local')=='local' ? 'selected':'' ?>>Local (Server)</option>
                    <option value="s3"    <?= env('STORAGE_DRIVER','local')=='s3'    ? 'selected':'' ?>>S3-Compatible (AWS / R2 / MinIO / DO Spaces)</option>
                  </select>
                </div>
              </div>

              <!-- ===== SECTION LOCAL ===== -->
              <div id="section-local">
                <div class="box-header">
                  <h4 class="box-title"><i class="fa fa-hdd-o"></i> Local Storage</h4>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Folder Upload</label>
                  <div class="col-sm-4">
                    <input type="text" name="storage_local_path" class="form-control"
                           value="<?= htmlspecialchars(env('STORAGE_LOCAL_PATH','uploads')) ?>"
                           placeholder="uploads">
                    <p class="help-block">Relatif dari root project. Contoh: <code>uploads</code></p>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Base URL</label>
                  <div class="col-sm-5">
                    <input type="text" name="storage_local_url" class="form-control"
                           value="<?= htmlspecialchars(env('STORAGE_LOCAL_URL','')) ?>"
                           placeholder="<?= base_url() ?>">
                    <p class="help-block">Kosongkan untuk pakai <code>base_url()</code> otomatis.</p>
                  </div>
                </div>
              </div>

              <!-- ===== SECTION S3 ===== -->
              <div id="section-s3">
                <div class="box-header">
                  <h4 class="box-title"><i class="fa fa-cloud-upload"></i> S3-Compatible Storage</h4>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Bucket</label>
                  <div class="col-sm-4">
                    <input type="text" name="storage_bucket" class="form-control"
                           value="<?= htmlspecialchars(env('STORAGE_BUCKET','')) ?>"
                           placeholder="nama-bucket">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Region</label>
                  <div class="col-sm-4">
                    <input type="text" name="storage_region" class="form-control"
                           value="<?= htmlspecialchars(env('STORAGE_REGION','us-east-1')) ?>"
                           placeholder="us-east-1">
                    <p class="help-block">Untuk Cloudflare R2 / MinIO bisa diisi bebas, misal <code>auto</code>.</p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Access Key</label>
                  <div class="col-sm-5">
                    <input type="text" name="storage_key" class="form-control"
                           value="<?= htmlspecialchars(env('STORAGE_KEY','')) ?>"
                           placeholder="Access Key ID" autocomplete="off">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Secret Key</label>
                  <div class="col-sm-5">
                    <div class="input-group">
                      <input type="password" name="storage_secret" id="storage_secret" class="form-control"
                             value="<?= htmlspecialchars(env('STORAGE_SECRET','')) ?>"
                             placeholder="Secret Access Key" autocomplete="new-password">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-default btn-flat" id="toggleStorageSecret">
                          <i class="fa fa-eye"></i>
                        </button>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Custom Endpoint</label>
                  <div class="col-sm-5">
                    <input type="text" name="storage_endpoint" class="form-control"
                           value="<?= htmlspecialchars(env('STORAGE_ENDPOINT','')) ?>"
                           placeholder="https://xxx.r2.cloudflarestorage.com">
                    <p class="help-block">Kosongkan untuk AWS S3. Isi untuk R2 / MinIO / DO Spaces.</p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Custom Domain / CDN</label>
                  <div class="col-sm-5">
                    <input type="text" name="storage_custom_domain" class="form-control"
                           value="<?= htmlspecialchars(env('STORAGE_CUSTOM_DOMAIN','')) ?>"
                           placeholder="https://cdn.domain.com">
                    <p class="help-block">Opsional. Jika diisi, URL publik file akan menggunakan domain ini.</p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">ACL</label>
                  <div class="col-sm-3">
                    <select name="storage_acl" class="form-control">
                      <option value="public-read"  <?= env('STORAGE_ACL','public-read')=='public-read'  ? 'selected':'' ?>>public-read</option>
                      <option value="private"      <?= env('STORAGE_ACL','public-read')=='private'      ? 'selected':'' ?>>private</option>
                      <option value=""             <?= env('STORAGE_ACL','public-read')==''             ? 'selected':'' ?>>None (tidak set)</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Path Style URL</label>
                  <div class="col-sm-4">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="storage_path_style" value="1"
                               <?= env('STORAGE_PATH_STYLE','1')=='1' ? 'checked':'' ?>>
                        Aktifkan path-style URL
                        <p class="text-muted" style="margin:0;font-size:12px;">Wajib untuk MinIO dan beberapa provider non-AWS.</p>
                      </label>
                    </div>
                  </div>
                </div>

                <hr>
                <!-- Test Koneksi S3 -->
                <div class="form-group">
                  <label class="col-sm-3 control-label">Test Koneksi</label>
                  <div class="col-sm-5">
                    <button type="button" class="btn btn-info btn-flat" id="btn-test-storage">
                      <i class="fa fa-plug"></i> Test Upload
                    </button>
                    <span id="test-storage-result" style="margin-left:10px;"></span>
                  </div>
                </div>

              </div><!-- /#section-s3 -->

              <div class="box-footer">
                <button type="submit" class="btn btn-success btn-flat">
                  <i class="fa fa-save"></i> Simpan Konfigurasi Storage
                </button>
                <button type="reset" class="btn btn-default btn-flat">
                  <i class="fa fa-retweet"></i> Reset
                </button>
              </div>

            </form>
          </div>
          <!-- /.tab-pane storage -->

          <!-- ======== TAB 2FA ======== -->
          <div class="tab-pane" id="tab-2fa">
            <form id="form-2fa" class="form-horizontal">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-shield"></i> Konfigurasi Two-Factor Authentication (2FA)</h3>
              </div>

              <div class="callout callout-info" style="margin:15px;">
                <h4><i class="fa fa-info-circle"></i> Tentang 2FA</h4>
                <p>Two-Factor Authentication menambahkan lapisan keamanan ekstra pada proses login. Setelah memasukkan username dan password, pengguna harus memasukkan kode 6 digit dari aplikasi authenticator.</p>
                <p><strong>Catatan:</strong> Setiap admin dapat mengaktifkan/menonaktifkan 2FA untuk akun mereka sendiri melalui halaman profil.</p>
              </div>

              <!-- Status 2FA Global -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Status 2FA</label>
                <div class="col-sm-6">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="twofa_enabled" id="twofa_enabled" value="1"
                             <?= !empty($cfg['twofa_enabled']) ? 'checked' : '' ?>>
                      <strong>Aktifkan fitur 2FA</strong>
                      <p class="text-muted" style="margin:0; font-size:12px;">Mengaktifkan fitur Two-Factor Authentication untuk seluruh sistem. Admin dapat mengatur 2FA mereka masing-masing.</p>
                    </label>
                  </div>
                </div>
              </div>

              <hr>
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-users"></i> Status Admin</h3>
              </div>

              <!-- Tabel Daftar Admin dengan Status 2FA -->
              <div class="form-group">
                <div class="col-sm-12">
                  <div class="table-responsive" style="padding:0 15px;">
                    <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th width="50">#</th>
                          <th>Username</th>
                          <th>Nama</th>
                          <th>Email</th>
                          <th>Grup</th>
                          <th width="120" class="text-center">Status 2FA</th>
                          <th width="120" class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (!empty($admin_list)): ?>
                          <?php $no = 1; foreach ($admin_list as $adm): ?>
                          <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($adm['username']) ?></td>
                            <td><?= htmlspecialchars($adm['nama']) ?></td>
                            <td><?= htmlspecialchars($adm['email']) ?></td>
                            <td><?= htmlspecialchars($adm['nama_grup']) ?></td>
                            <td class="text-center">
                              <?php if (!empty($adm['two_factor_enabled'])): ?>
                                <span class="label label-success"><i class="fa fa-check"></i> Aktif</span>
                              <?php else: ?>
                                <span class="label label-default"><i class="fa fa-times"></i> Tidak Aktif</span>
                              <?php endif; ?>
                            </td>
                            <td class="text-center">
                              <?php if (!empty($adm['two_factor_enabled']) && $adm['id'] != $userdata->id): ?>
                                <button type="button" class="btn btn-danger btn-xs btn-flat btn-reset-2fa"
                                        data-admin-id="<?= $adm['id'] ?>"
                                        data-username="<?= htmlspecialchars($adm['username']) ?>">
                                  <i class="fa fa-undo"></i> Reset 2FA
                                </button>
                              <?php else: ?>
                                <span class="text-muted">-</span>
                              <?php endif; ?>
                            </td>
                          </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data admin</td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                  <p class="help-block" style="padding:0 15px;">
                    <i class="fa fa-info-circle"></i> Admin dapat mengaktifkan atau menonaktifkan 2FA mereka sendiri melalui menu Profil.
                  </p>
                </div>
              </div>

              <hr>
              <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cogs"></i> Pengaturan Lanjutan</h3>
              </div>

              <!-- Rate Limiting -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Rate Limit Verifikasi</label>
                <div class="col-sm-3">
                  <div class="input-group">
                    <input type="number" name="twofa_rate_limit" class="form-control" min="3" max="10"
                           value="<?= !empty($cfg['twofa_rate_limit']) ? $cfg['twofa_rate_limit'] : 5 ?>"
                           placeholder="5">
                    <span class="input-group-addon">percobaan</span>
                  </div>
                  <p class="help-block">Maksimal percobaan verifikasi kode 2FA dalam 5 menit. Default: 5</p>
                </div>
              </div>

              <!-- Backup Codes Count -->
              <div class="form-group">
                <label class="col-sm-3 control-label">Jumlah Backup Codes</label>
                <div class="col-sm-3">
                  <div class="input-group">
                    <input type="number" name="twofa_backup_codes_count" class="form-control" min="5" max="20"
                           value="<?= !empty($cfg['twofa_backup_codes_count']) ? $cfg['twofa_backup_codes_count'] : 10 ?>"
                           placeholder="10">
                    <span class="input-group-addon">kode</span>
                  </div>
                  <p class="help-block">Jumlah backup codes yang dihasilkan saat setup 2FA. Default: 10</p>
                </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-success btn-flat">
                  <i class="fa fa-save"></i> Simpan Konfigurasi 2FA
                </button>
                <button type="reset" class="btn btn-default btn-flat">
                  <i class="fa fa-retweet"></i> Reset
                </button>
              </div>

            </form>
          </div>
          <!-- /.tab-pane 2fa -->

        </div>
        <!-- /.tab-content -->

      </div>
      <!-- /.nav-tabs-custom -->
    </div>
  </div>

</section>

<script>
// ---- Tab Website: Preview skin ----
var skinColors = {
  'skin-red':           '#dd4b39',
  'skin-blue':          '#3c8dbc',
  'skin-black':         '#333333',
  'skin-green':         '#00a65a',
  'skin-purple':        '#9b59b6',
  'skin-yellow':        '#f39c12',
  'skin-blue-light':    '#3c8dbc',
  'skin-black-light':   '#555555',
  'skin-green-light':   '#00a65a',
  'skin-purple-light':  '#9b59b6',
  'skin-red-light':     '#dd4b39',
  'skin-yellow-light':  '#f39c12',
};

function updatePreview() {
  var skin = $('#app_skin').val();
  var color = skinColors[skin] || '#333';
  $('#skin-preview').css({'background-color': color, 'color': '#fff'}).text(skin);
}
$('#app_skin').on('change', updatePreview);
updatePreview();

// ---- Tab Website: Submit AJAX ----
$('#form-config').submit(function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    type: 'POST',
    url: '<?= site_url('simpan-konfigurasi') ?>',
    data: formData,
    contentType: false,
    processData: false,
    beforeSend: function() { $('.loading2').show(); },
    complete:   function() { $('.loading2').hide(); },
    success: function(res) {
      var result = jQuery.parseJSON(res);
      if (result.status === 'berhasil') {
        save_berhasil();
        setTimeout(function() { location.reload(); }, 800);
      } else {
        gagal();
      }
    }
  });
});

// ---- Tab Database: Toggle password ----
$('#toggleDbPass').on('click', function() {
  var inp = $('#db_password');
  var icon = $(this).find('i');
  if (inp.attr('type') === 'password') {
    inp.attr('type', 'text');
    icon.removeClass('fa-eye').addClass('fa-eye-slash');
  } else {
    inp.attr('type', 'password');
    icon.removeClass('fa-eye-slash').addClass('fa-eye');
  }
});

// ---- Tab Database: Test koneksi ----
$('#btn-test-db').on('click', function() {
  var data = {
    db_hostname: $('#db_hostname').val(),
    db_username: $('#db_username').val(),
    db_password: $('#db_password').val(),
    db_database: $('#db_database').val(),
  };
  $('#test-db-result').html('<i class="fa fa-spinner fa-spin"></i> Menguji...');
  $.post('<?= site_url('test-koneksi-db') ?>', data, function(res) {
    var r = jQuery.parseJSON(res);
    if (r.status === 'ok') {
      $('#test-db-result').html('<span class="text-green"><i class="fa fa-check"></i> Koneksi berhasil!</span>');
    } else {
      $('#test-db-result').html('<span class="text-red"><i class="fa fa-times"></i> ' + r.message + '</span>');
    }
  });
});

// ---- Tab Database: Submit ----
$('#form-database').submit(function(e) {
  e.preventDefault();
  var data = $(this).serialize();
  $.ajax({
    type: 'POST',
    url: '<?= site_url('simpan-konfigurasi-db') ?>',
    data: data,
    beforeSend: function() { $('.loading2').show(); },
    complete:   function() { $('.loading2').hide(); },
    success: function(res) {
      var result = jQuery.parseJSON(res);
      if (result.status === 'berhasil') {
        save_berhasil();
      } else {
        alert('Gagal: ' + result.message);
      }
    }
  });
});

// ---- Tab Email: Toggle password ----
$('#toggleMailPass').on('click', function() {
  var inp = $('#mail_password');
  var icon = $(this).find('i');
  if (inp.attr('type') === 'password') {
    inp.attr('type', 'text');
    icon.removeClass('fa-eye').addClass('fa-eye-slash');
  } else {
    inp.attr('type', 'password');
    icon.removeClass('fa-eye-slash').addClass('fa-eye');
  }
});

// ---- Tab Email: Submit AJAX ----
$('#form-email').submit(function(e) {
  e.preventDefault();
  var data = $(this).serialize();
  $.ajax({
    type: 'POST',
    url: '<?= site_url('simpan-konfigurasi-email') ?>',
    data: data,
    beforeSend: function() { $('.loading2').show(); },
    complete:   function() { $('.loading2').hide(); },
    success: function(res) {
      var result = jQuery.parseJSON(res);
      if (result.status === 'berhasil') {
        save_berhasil();
      } else {
        alert('Gagal: ' + result.message);
      }
    }
  });
});

// ---- Tab Email: Test kirim ----
$('#btn-test-mail').on('click', function() {
  var email = $('#mail_test_to').val();
  if (!email) { alert('Masukkan email tujuan test.'); return; }
  $('#test-mail-result').html('<i class="fa fa-spinner fa-spin"></i> Mengirim...');
  $.post('<?= site_url('test-kirim-email') ?>', { test_to: email }, function(res) {
    var r = jQuery.parseJSON(res);
    if (r.status === 'ok') {
      $('#test-mail-result').html('<span class="text-green"><i class="fa fa-check"></i> Email berhasil dikirim!</span>');
    } else {
      $('#test-mail-result').html('<span class="text-red"><i class="fa fa-times"></i> ' + r.message + '</span>');
    }
  });
});

// ---- Tab Storage: Toggle section ----
function toggleStorageSection() {
  var driver = $('#storage_driver').val();
  if (driver === 's3') {
    $('#section-s3').show();
    $('#section-local').hide();
  } else {
    $('#section-local').show();
    $('#section-s3').hide();
  }
}
$('#storage_driver').on('change', toggleStorageSection);
toggleStorageSection();

// ---- Tab Storage: Toggle secret key visibility ----
$('#toggleStorageSecret').on('click', function() {
  var inp = $('#storage_secret');
  var icon = $(this).find('i');
  if (inp.attr('type') === 'password') {
    inp.attr('type', 'text');
    icon.removeClass('fa-eye').addClass('fa-eye-slash');
  } else {
    inp.attr('type', 'password');
    icon.removeClass('fa-eye-slash').addClass('fa-eye');
  }
});

// ---- Tab Storage: Test upload ----
$('#btn-test-storage').on('click', function() {
  $('#test-storage-result').html('<i class="fa fa-spinner fa-spin"></i> Menguji...');
  var data = $('#form-storage').serialize();
  $.post('<?= site_url('test-koneksi-storage') ?>', data, function(res) {
    var r = jQuery.parseJSON(res);
    if (r.status === 'ok') {
      $('#test-storage-result').html('<span class="text-green"><i class="fa fa-check"></i> ' + r.message + '</span>');
    } else {
      $('#test-storage-result').html('<span class="text-red"><i class="fa fa-times"></i> ' + r.message + '</span>');
    }
  });
});

// ---- Tab Storage: Submit AJAX ----
$('#form-storage').submit(function(e) {
  e.preventDefault();
  var data = $(this).serialize();
  $.ajax({
    type: 'POST',
    url: '<?= site_url('simpan-konfigurasi-storage') ?>',
    data: data,
    beforeSend: function() { $('.loading2').show(); },
    complete:   function() { $('.loading2').hide(); },
    success: function(res) {
      var result = jQuery.parseJSON(res);
      if (result.status === 'berhasil') {
        save_berhasil();
      } else {
        alert('Gagal: ' + result.message);
      }
    }
  });
});

// ---- Tab 2FA: Submit AJAX ----
$('#form-2fa').submit(function(e) {
  e.preventDefault();
  var data = $(this).serialize();
  $.ajax({
    type: 'POST',
    url: '<?= site_url('simpan-konfigurasi-2fa') ?>',
    data: data,
    beforeSend: function() { $('.loading2').show(); },
    complete:   function() { $('.loading2').hide(); },
    success: function(res) {
      var result = jQuery.parseJSON(res);
      if (result.status === 'berhasil') {
        save_berhasil();
        setTimeout(function() { location.reload(); }, 800);
      } else {
        alert('Gagal: ' + result.message);
      }
    }
  });
});

// ---- Tab 2FA: Reset 2FA Admin ----
$(document).on('click', '.btn-reset-2fa', function(e) {
  e.preventDefault();
  var btn = $(this);
  var adminId = btn.data('admin-id');
  var username = btn.data('username');

  swal({
    title: 'Reset 2FA?',
    text: 'Apakah Anda yakin ingin mereset 2FA untuk admin \"' + username + '\"? Tindakan ini akan menghapus secret dan backup codes admin tersebut.',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#dd4b39',
    confirmButtonText: 'Ya, Reset 2FA',
    cancelButtonText: 'Batal',
    closeOnConfirm: false
  }, function(isConfirm) {
    if (!isConfirm) return;

    swal.disableButtons();

    $.ajax({
      type: 'POST',
      url: '<?= site_url('reset-2fa-admin') ?>',
      data: { admin_id: adminId, <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>' },
      beforeSend: function() { btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Mereset...'); },
      complete: function() { btn.prop('disabled', false).html('<i class="fa fa-undo"></i> Reset 2FA'); },
      success: function(res) {
        var result = jQuery.parseJSON(res);
        if (result.status === 'berhasil') {
          swal({
            title: 'Berhasil!',
            text: result.message,
            type: 'success',
            confirmButtonText: 'OK'
          }, function() {
            location.reload();
          });
        } else {
          swal('Gagal!', result.message, 'error');
        }
      },
      error: function() {
        swal('Error!', 'Terjadi kesalahan saat menghubungi server.', 'error');
      }
    });
  });
});
</script>
