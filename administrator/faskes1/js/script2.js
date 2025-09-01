// ambil elemen2 yang dibutuhkan
let keyword = document.getElementById('keyword');
let tombol_cari = document.getElementById('tombol_cari');
let container = document.getElementById('container');

// tombol_cari.addEventListener('click', function(){
//     alert('berhasil!!');
// });

// tambahkan event ketika keyword ditulis
keyword.addEventListener('keyup', function(){
    
    // buat objek ajax
    let xhr = new XMLHttpRequest();

    // cek kesiapan ajax
    xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            container.innerHTML = xhr.responseText;
        }
    }

    // eksekusi ajax
    xhr.open('GET', '../ajax/data_peserta.php?keyword=' + keyword.value, true);
    xhr.send();

});