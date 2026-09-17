<?php $this->load->view('_heading/_headerContent') ?>

<section class="content">

  <div class="row">
    <div class="col-md-12">

      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-book"></i> Dokumentasi — <code>email_helper</code></h3>
          <div class="box-tools pull-right">
            <span class="label label-success">Helper: email_helper.php</span>
          </div>
        </div>
        <div class="box-body">

          <!-- Deskripsi -->
          <div class="callout callout-info">
            <h4><i class="fa fa-info-circle"></i> Tentang Helper Ini</h4>
            <p>
              Helper <code>email_helper</code> menyediakan fungsi <code>send_email()</code> yang menggunakan <strong>PHPMailer</strong>
              dengan konfigurasi SMTP yang diambil otomatis dari file <code>.env</code>.
              Konfigurasi bisa diubah kapan saja melalui menu <strong>Konfigurasi → Tab Email / SMTP</strong> tanpa perlu edit kode.
            </p>
          </div>

          <!-- Load helper -->
          <h4><i class="fa fa-plug"></i> 1. Load Helper</h4>
          <p>Load helper di controller sebelum memanggil fungsi <code>send_email()</code>:</p>
          <pre class="pre-scrollable" style="background:#272822;color:#f8f8f2;padding:15px;border-radius:4px;"><code><span style="color:#75715e">// Di dalam method controller</span>
$this->load->helper(<span style="color:#e6db74">'email'</span>);
</code></pre>

          <p>Atau agar tersedia di semua controller, tambahkan ke <code>apps/config/autoload.php</code>:</p>
          <pre class="pre-scrollable" style="background:#272822;color:#f8f8f2;padding:15px;border-radius:4px;"><code>$autoload[<span style="color:#e6db74">'helper'</span>] = [<span style="color:#e6db74">'url'</span>, <span style="color:#e6db74">'env'</span>, <span style="color:#e6db74">'email'</span>];
</code></pre>

          <hr>

          <!-- Signature -->
          <h4><i class="fa fa-code"></i> 2. Signature Fungsi</h4>
          <pre class="pre-scrollable" style="background:#272822;color:#f8f8f2;padding:15px;border-radius:4px;"><code><span style="color:#66d9ef">function</span> <span style="color:#a6e22e">send_email</span>(
    <span style="color:#fd971f">$to</span>,           <span style="color:#75715e">// string email | array penerima</span>
    <span style="color:#fd971f">$toName</span>,       <span style="color:#75715e">// string nama (null jika $to adalah array)</span>
    <span style="color:#fd971f">$subject</span>,      <span style="color:#75715e">// string subject email</span>
    <span style="color:#fd971f">$body</span>,         <span style="color:#75715e">// string body HTML</span>
    <span style="color:#fd971f">$attachments</span>,  <span style="color:#75715e">// array path file (opsional)</span>
    <span style="color:#fd971f">$altBody</span>       <span style="color:#75715e">// string plain text fallback (opsional)</span>
): <span style="color:#66d9ef">array</span>  <span style="color:#75715e">// return ['status' => bool, 'message' => string]</span>
</code></pre>

          <hr>

          <!-- Contoh 1 -->
          <h4><i class="fa fa-paper-plane"></i> 3. Contoh Penggunaan</h4>

          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#doc-single" data-toggle="tab">Satu Penerima</a></li>
              <li><a href="#doc-multiple" data-toggle="tab">Banyak Penerima</a></li>
              <li><a href="#doc-attachment" data-toggle="tab">Dengan Attachment</a></li>
              <li><a href="#doc-handle" data-toggle="tab">Handle Response</a></li>
            </ul>

            <div class="tab-content" style="padding:15px;">

              <!-- Tab: Satu penerima -->
              <div class="tab-pane active" id="doc-single">
                <p>Kirim email ke satu penerima:</p>
                <pre style="background:#272822;color:#f8f8f2;padding:15px;border-radius:4px;"><code>$this->load->helper(<span style="color:#e6db74">'email'</span>);

$body = <span style="color:#e6db74">'&lt;p&gt;Halo &lt;b&gt;Budi&lt;/b&gt;,&lt;/p&gt;&lt;p&gt;Ini notifikasi dari sistem.&lt;/p&gt;'</span>;

$result = send_email(
    <span style="color:#e6db74">'budi@mail.com'</span>,
    <span style="color:#e6db74">'Budi'</span>,
    <span style="color:#e6db74">'Notifikasi Sistem'</span>,
    $body
);
</code></pre>
              </div>

              <!-- Tab: Banyak penerima -->
              <div class="tab-pane" id="doc-multiple">
                <p>Kirim ke banyak penerima sekaligus:</p>
                <pre style="background:#272822;color:#f8f8f2;padding:15px;border-radius:4px;"><code>$this->load->helper(<span style="color:#e6db74">'email'</span>);

$penerima = [
    [<span style="color:#e6db74">'email'</span> =&gt; <span style="color:#e6db74">'budi@mail.com'</span>,  <span style="color:#e6db74">'name'</span> =&gt; <span style="color:#e6db74">'Budi'</span>],
    [<span style="color:#e6db74">'email'</span> =&gt; <span style="color:#e6db74">'sinta@mail.com'</span>, <span style="color:#e6db74">'name'</span> =&gt; <span style="color:#e6db74">'Sinta'</span>],
    [<span style="color:#e6db74">'email'</span> =&gt; <span style="color:#e6db74">'andi@mail.com'</span>],  <span style="color:#75715e">// name opsional</span>
];

$result = send_email(
    $penerima,
    <span style="color:#ae81ff">null</span>,
    <span style="color:#e6db74">'Pengumuman Penting'</span>,
    <span style="color:#e6db74">'&lt;p&gt;Pengumuman untuk semua.&lt;/p&gt;'</span>
);
</code></pre>
              </div>

              <!-- Tab: Attachment -->
              <div class="tab-pane" id="doc-attachment">
                <p>Kirim email dengan lampiran file:</p>
                <pre style="background:#272822;color:#f8f8f2;padding:15px;border-radius:4px;"><code>$this->load->helper(<span style="color:#e6db74">'email'</span>);

$attachments = [
    FCPATH . <span style="color:#e6db74">'uploads/laporan.pdf'</span>,
    FCPATH . <span style="color:#e6db74">'uploads/data.xlsx'</span>,
];

$result = send_email(
    <span style="color:#e6db74">'manajer@mail.com'</span>,
    <span style="color:#e6db74">'Pak Manajer'</span>,
    <span style="color:#e6db74">'Laporan Bulan Ini'</span>,
    <span style="color:#e6db74">'&lt;p&gt;Terlampir laporan bulan ini.&lt;/p&gt;'</span>,
    $attachments
);
</code></pre>
              </div>

              <!-- Tab: Handle response -->
              <div class="tab-pane" id="doc-handle">
                <p>Menangani response berhasil / gagal:</p>
                <pre style="background:#272822;color:#f8f8f2;padding:15px;border-radius:4px;"><code>$this->load->helper(<span style="color:#e6db74">'email'</span>);

$result = send_email(
    <span style="color:#e6db74">'budi@mail.com'</span>,
    <span style="color:#e6db74">'Budi'</span>,
    <span style="color:#e6db74">'Subject'</span>,
    <span style="color:#e6db74">'&lt;p&gt;Body email.&lt;/p&gt;'</span>
);

<span style="color:#66d9ef">if</span> ($result[<span style="color:#e6db74">'status'</span>]) {
    <span style="color:#75715e">// Berhasil</span>
    $this->session->set_flashdata(<span style="color:#e6db74">'success'</span>, <span style="color:#e6db74">'Email berhasil dikirim.'</span>);
} <span style="color:#66d9ef">else</span> {
    <span style="color:#75715e">// Gagal — catat error</span>
    log_message(<span style="color:#e6db74">'error'</span>, <span style="color:#e6db74">'Gagal kirim email: '</span> . $result[<span style="color:#e6db74">'message'</span>]);
    $this->session->set_flashdata(<span style="color:#e6db74">'error'</span>, <span style="color:#e6db74">'Email gagal dikirim: '</span> . $result[<span style="color:#e6db74">'message'</span>]);
}
</code></pre>
              </div>

            </div><!-- /.tab-content -->
          </div><!-- /.nav-tabs-custom -->

          <hr>

          <!-- Konfigurasi .env -->
          <h4><i class="fa fa-cog"></i> 4. Key Konfigurasi di <code>.env</code></h4>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="width:220px;">Key</th>
                <th>Keterangan</th>
                <th style="width:180px;">Contoh Nilai</th>
              </tr>
            </thead>
            <tbody>
              <tr><td><code>MAIL_HOST</code></td><td>Alamat server SMTP</td><td><code>mail.domain.com</code></td></tr>
              <tr><td><code>MAIL_PORT</code></td><td>Port SMTP (TLS: 587, SSL: 465)</td><td><code>587</code></td></tr>
              <tr><td><code>MAIL_ENCRYPTION</code></td><td>Jenis enkripsi: <code>tls</code>, <code>ssl</code>, atau kosong</td><td><code>tls</code></td></tr>
              <tr><td><code>MAIL_USERNAME</code></td><td>Akun email / username SMTP</td><td><code>apps@domain.com</code></td></tr>
              <tr><td><code>MAIL_PASSWORD</code></td><td>Password akun SMTP</td><td><code>rahasia123</code></td></tr>
              <tr><td><code>MAIL_FROM_EMAIL</code></td><td>Alamat pengirim (kosong = pakai username)</td><td><code>noreply@domain.com</code></td></tr>
              <tr><td><code>MAIL_FROM_NAME</code></td><td>Nama pengirim yang muncul di email</td><td><code>No Reply</code></td></tr>
            </tbody>
          </table>

          <div class="callout callout-warning">
            <h4><i class="fa fa-exclamation-triangle"></i> Catatan</h4>
            <ul style="margin:0;">
              <li>Ubah konfigurasi SMTP melalui menu <strong>Konfigurasi → Tab Email / SMTP</strong>, bukan langsung edit file <code>.env</code>.</li>
              <li>Gunakan fitur <strong>Test Kirim Email</strong> di halaman konfigurasi untuk memverifikasi SMTP sebelum digunakan di production.</li>
              <li>Path file attachment harus berupa <strong>absolute path</strong> di server. Gunakan <code>FCPATH</code> atau <code>APPPATH</code> sebagai prefix.</li>
            </ul>
          </div>

        </div><!-- /.box-body -->
        <div class="box-footer">
          <a href="<?= site_url('konfigurasi') ?>#tab-email" class="btn btn-primary btn-flat">
            <i class="fa fa-cog"></i> Buka Konfigurasi Email
          </a>
        </div>
      </div><!-- /.box -->

    </div>
  </div>

</section>

<?php $this->load->view('Dashboard/layouts/footer') ?>
