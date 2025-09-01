// main.js
document.addEventListener('DOMContentLoaded', function() {
    // Panggil antrean
    document.querySelectorAll('.btn-panggil').forEach(btn => {
      btn.addEventListener('click', () => {
        let id = btn.dataset.id;
        fetch('panggil.php', {
          method: 'POST',
          headers: {'Content-Type':'application/x-www-form-urlencoded'},
          body: 'id_antrian=' + id
        })
        .then(res => res.text())
        .then(msg => { alert(msg); location.reload(); })
        .catch(err => console.error(err));
      });
    });
  
    // Selesai antrean
    document.querySelectorAll('.btn-selesai').forEach(btn => {
      btn.addEventListener('click', () => {
        let id = btn.dataset.id;
        fetch('selesai.php', {
          method: 'POST',
          headers: {'Content-Type':'application/x-www-form-urlencoded'},
          body: 'id_antrian=' + id
        })
        .then(res => res.text())
        .then(msg => { alert(msg); location.reload(); })
        .catch(err => console.error(err));
      });
    });
  
    // Reset semua antrean
    const resetBtn = document.getElementById('btn-reset');
    if (resetBtn) {
      resetBtn.addEventListener('click', () => {
        if (!confirm('Yakin reset semua antrean?')) return;
        fetch('reset.php', { method: 'POST' })
          .then(res => res.text())
          .then(msg => { alert(msg); location.reload(); })
          .catch(err => console.error(err));
      });
    }
  });
  