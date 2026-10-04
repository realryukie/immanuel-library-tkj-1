<?php
$pageTitle = $pageTitle ?? 'Dashboard Perpustakaan';
$pageSubtitle = $pageSubtitle ?? 'Selamat datang di panel admin';
// disini berarti kita kasi data dummy jika lupa didefinisikan/dipanggil di tiap halaman
?>
<header class="app-topbar">
      <div class="page-title">
        <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
        <p><?php echo htmlspecialchars($pageSubtitle); ?></p>
      </div>
      <div class="topbar-user">
        <span class="avatar">BS</span>
        <div>
          Budi Santoso<br>
          <span class="badge badge-member" style="margin-top:2px;">Member</span>
        </div>
      </div>
    </header>