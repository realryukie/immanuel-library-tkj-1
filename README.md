Ok disini saya akan menjawab beberapa isu yang sengaja tidak direvisi.

- components/admin/topbar.php baris 3 dan 4: $pageTitle dan $pageSubtitle dicetak tanpa nilai default. Halaman yang lupa mengisi variabel akan memunculkan warning undefined variable.
Memang saya buat seperti itu, karena variabel akan diisi dan dipanggil di awal setiap page. Untuk mengatasi lupa mengisi variabel, ya cek ulang wkwk, kalau ga cek kan resiko salah wkwk.

- components/admin/sidebar.php baris 12: kelas "active" ditulis tetap pada menu Buku, sehingga menu aktif sama di semua halaman admin.
Untuk yang ini menurut saya ada sedikit kekeliruan, soalnya di wordnya dengan jelas memberi instruksi sebagai berikut di halaman 9-10 di bagian 2.A:
"Buat file components/admin/sidebar.php. Pindahkan (potong dari salah satu halaman, misalnya pages/books/index.php) seluruh markup sidebar (elemen <aside>) ke file ini. Untuk Sub-bagian A ini, sidebar cukup dibuat sama persis di semua halaman, TIDAK perlu ada menu yang otomatis ter-highlight sesuai halaman yang sedang dibuka."
Makanya saya tidak ubah highlightnya.