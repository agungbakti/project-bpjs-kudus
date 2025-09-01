$(document).ready(function(){
    // menghilngkan tombol cari
    $('#tombol_cari').hide();


    // event ketika keyword ditulis
    $('#keyword').keyup(function(){

        // memunculkan ikon loading
        $('.loader').show();

        // ajax menggunakan load
        // $('#container').load('../ajax/data_peserta.php?keyword=' + $('#keyword').val());

        // $.get()
        $.get('../ajax/data_peserta.php?keyword=' + $('#keyword').val(), function(data){
            $('#container').html(data);
            $('.loader').hide();
        });

        ;
    });
});

$(document).ready(function () {
    // STATUS
    $('.btn-update-status').click(function () {
        const form = $(this).closest('.form-status');
        const id = form.data('id');
        const status = form.find('select[name="status"]').val();
        const button = $(this);

        $.post('update_status.php', {
            id_faskes: id,
            status: status,
            ajax: true
        }, function () {
            button.text('✔'); // Ganti teks tombol jadi centang
            setTimeout(() => button.text('Update'), 1000); // Kembali ke "Update" setelah 1 detik
        });
    });

    // KETERANGAN
    $('.btn-update-keterangan').click(function () {
        const form = $(this).closest('.form-keterangan');
        const id = form.data('id');
        const keterangan = form.find('select[name="keterangan"]').val();
        const button = $(this);

        $.post('update_keterangan.php', {
            id_faskes: id,
            keterangan: keterangan,
            ajax: true
        }, function () {
            button.text('✔');
            setTimeout(() => button.text('Update'), 1000);
        });
    });

    // NAMA
    $('.btn-update-name').click(function () {
        const form = $(this).closest('.form-nama');
        const id = form.data('id');
        const nama = form.find('input[name="nama"]').val();
        const button = $(this);

        $.post('nama.php', {
            id_faskes: id,
            nama: nama,
            ajax: true
        }, function () {
            button.text('✔');
            setTimeout(() => button.text('Update'), 1000);
        });
    });
});

