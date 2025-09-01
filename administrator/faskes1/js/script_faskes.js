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
        $.get('../ajax/data_peserta_faskes.php?keyword=' + $('#keyword').val(), function(data){
            $('#container').html(data);
            $('.loader').hide();
        });

        ;
    });
});