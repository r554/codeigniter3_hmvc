<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
  <h1><i class="fa fa-book"></i> Dokumentasi Storage Helper</h1>
  <ol class="breadcrumb">
    <li><a href="<?= site_url('dashboard') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li><a href="<?= site_url('konfigurasi') ?>">Konfigurasi</a></li>
    <li class="active">Dokumentasi Storage</li>
  </ol>
</section>

<section class="content">

  <div class="row">
    <div class="col-md-3">
      <!-- Sidebar navigasi -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-list"></i> Navigasi</h3>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-stacked" id="doc-nav">
            <li><a href="#overview">Overview</a></li>
            <li><a href="#fungsi">Daftar Fungsi</a></li>
            <li><a href="#upload">storage_upload()</a></li>
            <li><a href="#upload-content">storage_upload_content()</a></li>
            <li><a href="#url">storage_url()</a></li>
            <li><a href="#delete">storage_delete()</a></li>
            <li><a href="#exists">storage_exists()</a></li>
            <li><a href="#provider">Panduan Provider</a></li>
            <li><a href="#contoh">Contoh Integrasi</a></li>
          </ul>
        </div>
      </div>
      <a href="<?= site_url('konfigurasi') ?>#tab-storage" class="btn btn-default btn-flat btn-block">
        <i class="fa fa-cog"></i> Buka Konfigurasi Storage
      </a>
    </div>

    <div class="col-md-9">

      <!-- Overview -->
      <div class="box box-primary" id="overview">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-cloud"></i> Overview</h3>
        </div>
        <div class="box-body">
          <p><code>storage_helper.php</code> menyediakan API terpadu untuk upload, akses URL, dan hapus file, baik di <strong>local storage</strong> maupun <strong>S3-compatible storage</strong> (AWS S3, Cloudflare R2, MinIO, DigitalOcean Spaces).</p>
          <div class="callout callout-success">
            <h4><i class="fa fa-check-circle"></i> Tidak butuh Composer / SDK</h4>
            <p>Helper ini menggunakan cURL murni dengan implementasi AWS Signature V4 sendiri, sehingga tidak memerlukan library eksternal.</p>
          </div>
          <p>Load helper sebelum digunakan:</p>
          <pre class="pre-scrollable"><code>$this->load->helper('storage');</code></pre>
          <p>Atau autoload di <code>apps/config/autoload.php</code>:</p>
          <pre class="pre-scrollable"><code>$autoload['helper'] = array('env', 'email', 'storage');</code></pre>
        </div>
      </div>

      <!-- Daftar Fungsi -->
      <div class="box box-default" id="fungsi">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-table"></i> Daftar Fungsi</h3>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-bordered table-hover">
            <thead class="bg-light-blue-active">
              <tr>
                <th>Fungsi</th>
                <th>Deskripsi</th>
                <th>Return</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><code>storage_upload($file_path, $key)</code></td>
                <td>Upload file dari path absolut server</td>
                <td><code>['status'=>bool, 'url'=>string]</code></td>
              </tr>
              <tr>
                <td><code>storage_upload_content($key, $content, $mime)</code></td>
                <td>Upload dari string content (tidak perlu file fisik)</td>
                <td><code>['status'=>bool, 'url'=>string]</code></td>
              </tr>
              <tr>
                <td><code>storage_url($key)</code></td>
                <td>Dapatkan URL publik file</td>
                <td><code>string</code></td>
              </tr>
              <tr>
                <td><code>storage_delete($key)</code></td>
                <td>Hapus file dari storage</td>
                <td><code>bool</code></td>
              </tr>
              <tr>
                <td><code>storage_exists($key)</code></td>
                <td>Cek apakah file ada di storage</td>
                <td><code>bool</code></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- storage_upload -->
      <div class="box box-default" id="upload">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-upload"></i> storage_upload()</h3>
        </div>
        <div class="box-body">
          <p>Upload file dari path absolut di server.</p>
          <pre class="pre-scrollable"><code><?= htmlspecialchars(
'$this->load->helper(\'storage\');

// Upload file dari form
if (!empty($_FILES[\'foto\'][\'tmp_name\'])) {
    $tmp     = $_FILES[\'foto\'][\'tmp_name\'];
    $ext     = pathinfo($_FILES[\'foto\'][\'name\'], PATHINFO_EXTENSION);
    $key     = \'avatars/\' . uniqid() . \'.\' . $ext;

    $result = storage_upload($tmp, $key);

    if ($result[\'status\']) {
        $url = $result[\'url\']; // Simpan URL ke database
    } else {
        echo $result[\'message\']; // Tampilkan error
    }
}'
          ) ?></code></pre>
          <table class="table table-bordered table-sm">
            <thead><tr><th>Parameter</th><th>Tipe</th><th>Keterangan</th></tr></thead>
            <tbody>
              <tr><td><code>$file_path</code></td><td>string</td><td>Path absolut file di server (tmp_name dari $_FILES)</td></tr>
              <tr><td><code>$key</code></td><td>string</td><td>Path/nama file tujuan di storage. Contoh: <code>images/foto.jpg</code></td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- storage_upload_content -->
      <div class="box box-default" id="upload-content">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-file-text-o"></i> storage_upload_content()</h3>
        </div>
        <div class="box-body">
          <p>Upload langsung dari string, berguna untuk generate file (PDF, CSV, JSON) tanpa menyimpan ke disk dulu.</p>
          <pre class="pre-scrollable"><code><?= htmlspecialchars(
'// Upload string JSON
$json_data = json_encode([\'name\' => \'John\', \'date\' => date(\'Y-m-d\')]);
$result = storage_upload_content(\'exports/data-\' . date(\'Ymd\') . \'.json\', $json_data, \'application/json\');

// Upload HTML
$html = \'<h1>Laporan</h1>\';
$result = storage_upload_content(\'reports/laporan.html\', $html, \'text/html\');

if ($result[\'status\']) {
    $url = $result[\'url\'];
}'
          ) ?></code></pre>
          <table class="table table-bordered table-sm">
            <thead><tr><th>Parameter</th><th>Tipe</th><th>Keterangan</th></tr></thead>
            <tbody>
              <tr><td><code>$key</code></td><td>string</td><td>Path/nama file tujuan di storage</td></tr>
              <tr><td><code>$content</code></td><td>string</td><td>Isi file sebagai string</td></tr>
              <tr><td><code>$mime_type</code></td><td>string</td><td>MIME type. Default: <code>application/octet-stream</code></td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- storage_url -->
      <div class="box box-default" id="url">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-link"></i> storage_url()</h3>
        </div>
        <div class="box-body">
          <p>Dapatkan URL publik sebuah file tanpa perlu tahu driver yang aktif.</p>
          <pre class="pre-scrollable"><code><?= htmlspecialchars(
'// Dapatkan URL dari key yang tersimpan di database
$key = $row->foto; // misal: "avatars/abc123.jpg"
$url = storage_url($key);

echo \'<img src="\' . $url . \'" alt="Foto">\';'
          ) ?></code></pre>
          <div class="callout callout-info">
            <p>Jika <code>STORAGE_CUSTOM_DOMAIN</code> diisi, URL akan menggunakan domain tersebut. Jika tidak, URL akan dibentuk dari endpoint + bucket + key (S3) atau base_url + local_path + key (local).</p>
          </div>
        </div>
      </div>

      <!-- storage_delete -->
      <div class="box box-default" id="delete">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-trash"></i> storage_delete()</h3>
        </div>
        <div class="box-body">
          <pre class="pre-scrollable"><code><?= htmlspecialchars(
'$key = $row->foto; // "avatars/abc123.jpg"

if (storage_delete($key)) {
    // Hapus record dari database
    $this->db->delete(\'users\', [\'id\' => $id]);
} else {
    echo \'Gagal menghapus file.\';
}'
          ) ?></code></pre>
        </div>
      </div>

      <!-- storage_exists -->
      <div class="box box-default" id="exists">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-search"></i> storage_exists()</h3>
        </div>
        <div class="box-body">
          <pre class="pre-scrollable"><code><?= htmlspecialchars(
'if (storage_exists(\'avatars/abc123.jpg\')) {
    echo \'File ada di storage.\';
} else {
    echo \'File tidak ditemukan.\';
}'
          ) ?></code></pre>
        </div>
      </div>

      <!-- Panduan Provider -->
      <div class="box box-warning" id="provider">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-info-circle"></i> Panduan Konfigurasi per Provider</h3>
        </div>
        <div class="box-body">

          <ul class="nav nav-tabs" id="provider-tabs">
            <li class="active"><a href="#p-local"  data-toggle="tab">Local</a></li>
            <li><a href="#p-r2"     data-toggle="tab">Cloudflare R2</a></li>
            <li><a href="#p-aws"    data-toggle="tab">AWS S3</a></li>
            <li><a href="#p-minio"  data-toggle="tab">MinIO</a></li>
            <li><a href="#p-do"     data-toggle="tab">DO Spaces</a></li>
          </ul>

          <div class="tab-content" style="padding-top:15px;">

            <div class="tab-pane active" id="p-local">
              <table class="table table-bordered table-sm">
                <tr><th style="width:200px;">STORAGE_DRIVER</th><td><code>local</code></td></tr>
                <tr><th>STORAGE_LOCAL_PATH</th><td><code>uploads</code> (relatif dari root project)</td></tr>
                <tr><th>STORAGE_LOCAL_URL</th><td>Kosongkan (otomatis pakai <code>base_url()</code>)</td></tr>
              </table>
              <div class="callout callout-warning">Pastikan folder <code>uploads/</code> memiliki izin tulis (chmod 775).</div>
            </div>

            <div class="tab-pane" id="p-r2">
              <table class="table table-bordered table-sm">
                <tr><th style="width:200px;">STORAGE_DRIVER</th><td><code>s3</code></td></tr>
                <tr><th>STORAGE_BUCKET</th><td>Nama bucket R2 Anda</td></tr>
                <tr><th>STORAGE_REGION</th><td><code>auto</code></td></tr>
                <tr><th>STORAGE_KEY</th><td>R2 Access Key ID</td></tr>
                <tr><th>STORAGE_SECRET</th><td>R2 Secret Access Key</td></tr>
                <tr><th>STORAGE_ENDPOINT</th><td><code>https://&lt;account-id&gt;.r2.cloudflarestorage.com</code></td></tr>
                <tr><th>STORAGE_CUSTOM_DOMAIN</th><td>Domain publik R2 (jika ada), misal: <code>https://cdn.domain.com</code></td></tr>
                <tr><th>STORAGE_PATH_STYLE</th><td><code>1</code></td></tr>
                <tr><th>STORAGE_ACL</th><td>Kosongkan (R2 tidak mendukung ACL header)</td></tr>
              </table>
            </div>

            <div class="tab-pane" id="p-aws">
              <table class="table table-bordered table-sm">
                <tr><th style="width:200px;">STORAGE_DRIVER</th><td><code>s3</code></td></tr>
                <tr><th>STORAGE_BUCKET</th><td>Nama bucket S3</td></tr>
                <tr><th>STORAGE_REGION</th><td>Misal: <code>ap-southeast-1</code></td></tr>
                <tr><th>STORAGE_KEY</th><td>AWS Access Key ID</td></tr>
                <tr><th>STORAGE_SECRET</th><td>AWS Secret Access Key</td></tr>
                <tr><th>STORAGE_ENDPOINT</th><td>Kosongkan (pakai endpoint AWS default)</td></tr>
                <tr><th>STORAGE_PATH_STYLE</th><td><code>0</code></td></tr>
                <tr><th>STORAGE_ACL</th><td><code>public-read</code></td></tr>
              </table>
            </div>

            <div class="tab-pane" id="p-minio">
              <table class="table table-bordered table-sm">
                <tr><th style="width:200px;">STORAGE_DRIVER</th><td><code>s3</code></td></tr>
                <tr><th>STORAGE_BUCKET</th><td>Nama bucket MinIO</td></tr>
                <tr><th>STORAGE_REGION</th><td><code>us-east-1</code> (bebas)</td></tr>
                <tr><th>STORAGE_KEY</th><td>MinIO Access Key</td></tr>
                <tr><th>STORAGE_SECRET</th><td>MinIO Secret Key</td></tr>
                <tr><th>STORAGE_ENDPOINT</th><td><code>http://localhost:9000</code></td></tr>
                <tr><th>STORAGE_PATH_STYLE</th><td><code>1</code> (wajib)</td></tr>
                <tr><th>STORAGE_ACL</th><td><code>public-read</code> atau kosongkan</td></tr>
              </table>
            </div>

            <div class="tab-pane" id="p-do">
              <table class="table table-bordered table-sm">
                <tr><th style="width:200px;">STORAGE_DRIVER</th><td><code>s3</code></td></tr>
                <tr><th>STORAGE_BUCKET</th><td>Nama Space</td></tr>
                <tr><th>STORAGE_REGION</th><td>Misal: <code>sgp1</code></td></tr>
                <tr><th>STORAGE_KEY</th><td>Spaces Access Key</td></tr>
                <tr><th>STORAGE_SECRET</th><td>Spaces Secret Key</td></tr>
                <tr><th>STORAGE_ENDPOINT</th><td><code>https://sgp1.digitaloceanspaces.com</code></td></tr>
                <tr><th>STORAGE_PATH_STYLE</th><td><code>0</code></td></tr>
                <tr><th>STORAGE_ACL</th><td><code>public-read</code></td></tr>
              </table>
            </div>

          </div><!-- /.tab-content provider -->
        </div>
      </div>

      <!-- Contoh Integrasi Upload Profile -->
      <div class="box box-success" id="contoh">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-code"></i> Contoh Integrasi: Upload Foto Profil</h3>
        </div>
        <div class="box-body">
          <p>Contoh lengkap mengganti logika upload lama dengan <code>storage_helper</code> di controller:</p>
          <pre class="pre-scrollable"><code><?= htmlspecialchars(
'public function update_foto()
{
    $this->load->helper(\'storage\');

    if (empty($_FILES[\'foto\'][\'name\'])) {
        echo json_encode([\'status\' => \'gagal\', \'message\' => \'Pilih file foto.\']);
        return;
    }

    $allowed = [\'jpg\',\'jpeg\',\'png\',\'gif\'];
    $ext = strtolower(pathinfo($_FILES[\'foto\'][\'name\'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo json_encode([\'status\' => \'gagal\', \'message\' => \'Format tidak didukung.\']);
        return;
    }

    // Hapus foto lama jika ada
    $user = $this->db->get_where(\'users\', [\'id\' => $this->userdata->id])->row();
    if (!empty($user->foto)) {
        storage_delete($user->foto);
    }

    // Upload foto baru
    $key    = \'avatars/\' . $this->userdata->id . \'_\' . time() . \'.\' . $ext;
    $result = storage_upload($_FILES[\'foto\'][\'tmp_name\'], $key);

    if (!$result[\'status\']) {
        echo json_encode([\'status\' => \'gagal\', \'message\' => $result[\'message\']]);
        return;
    }

    // Simpan key (bukan URL) ke database untuk portabilitas
    $this->db->update(\'users\', [\'foto\' => $key], [\'id\' => $this->userdata->id]);

    echo json_encode([\'status\' => \'berhasil\', \'url\' => $result[\'url\']]);
}'
          ) ?></code></pre>
          <div class="callout callout-info">
            <p><strong>Best practice:</strong> Simpan <code>$key</code> (bukan URL) ke database. Gunakan <code>storage_url($key)</code> saat menampilkan. Ini memudahkan migrasi antara driver lokal dan S3 tanpa mengubah data.</p>
          </div>
        </div>
      </div>

    </div><!-- /.col-md-9 -->
  </div><!-- /.row -->

</section>

<script>
// Smooth scroll ke anchor
$('#doc-nav a').on('click', function(e) {
  e.preventDefault();
  var target = $(this).attr('href');
  $('html, body').animate({ scrollTop: $(target).offset().top - 60 }, 400);
});
</script>
