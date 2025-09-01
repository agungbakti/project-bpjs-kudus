// script_admin.js

$(document).ready(function(){
    // Menghilangkan tombol cari (if it exists)
    $('#tombol_cari').hide();

    // Event ketika keyword ditulis (using event delegation for dynamic content)
    // We attach the keyup listener to a static parent element (e.g., document or a container)
    // and specify the selector for the element that actually triggers the event.
    $(document).on('keyup', '#keyword', function(){
        // Memunculkan ikon loading
        $('.loader').show();

        // $.get()
        $.get('../ajax/data_peserta.php?keyword=' + $('#keyword').val(), function(data){
            $('#container').html(data);
            $('.loader').hide();
            // *** IMPORTANT: Call the function to re-initialize visual status after AJAX load ***
            initializeRowStatuses();
        });
    });

    // Auto-save for Status (using event delegation)
    $(document).on('change', '.status-select', function() {
        const element = $(this);
        const idFaskes = element.data('id');
        const status = element.val();

        // Tampilkan loading state
        element.prop('disabled', true);

        $.ajax({
            url: '../dashboard/update_semua.php',
            type: 'POST',
            data: {
                action: 'status',
                id_faskes: idFaskes,
                value: status
            },
            success: function(response) {
                if(response.trim() === 'success') {
                    showToast('Status berhasil diupdate', 'success');
                    // Update the data-original-value after successful update
                    element.attr('data-original-value', status);
                    // Update visual status for the row
                    updateRowStatus(element.closest('.data-row')[0]);
                } else {
                    showToast('Gagal update status', 'error');
                    // Reset ke nilai sebelumnya jika gagal
                    element.val(element.attr('data-original-value'));
                }
            },
            error: function() {
                showToast('Gagal update status', 'error');
                // Reset ke nilai sebelumnya jika gagal
                element.val(element.attr('data-original-value'));
            },
            complete: function() {
                // Aktifkan kembali element
                element.prop('disabled', false);
            }
        });
    });

    // Auto-save for Keterangan (using event delegation)
    $(document).on('change', '.keterangan-select', function() {
        const element = $(this);
        const idFaskes = element.data('id');
        const keterangan = element.val();

        // Tampilkan loading state
        element.prop('disabled', true);

        $.ajax({
            url: '../dashboard/update_semua.php',
            type: 'POST',
            data: {
                action: 'keterangan',
                id_faskes: idFaskes,
                value: keterangan
            },
            success: function(response) {
                if(response.trim() === 'success') {
                    showToast('Keterangan berhasil diupdate', 'success');
                    // Update the data-original-value after successful update
                    element.attr('data-original-value', keterangan);
                    // Update visual status for the row
                    updateRowStatus(element.closest('.data-row')[0]);
                } else {
                    showToast('Gagal update keterangan', 'error');
                    // Reset ke nilai sebelumnya jika gagal
                    element.val(element.attr('data-original-value'));
                }
            },
            error: function() {
                showToast('Gagal update keterangan', 'error');
                // Reset ke nilai sebelumnya jika gagal
                element.val(element.attr('data-original-value'));
            },
            complete: function() {
                // Aktifkan kembali element
                element.prop('disabled', false);
            }
        });
    });

    // Auto-save for Nama (saat kehilangan fokus) (using event delegation)
    $(document).on('blur', '.nama-input', function() {
        const element = $(this);
        const idFaskes = element.data('id');
        const nama = element.val();
        const originalValue = element.attr('data-original-value'); // Use data-original-value

        // Cek apakah ada perubahan
        if (nama === originalValue) {
            return; // Tidak ada perubahan, tidak perlu update
        }

        // Tampilkan loading state
        element.prop('disabled', true);
        element.css('background-color', '#f8f9fa');

        $.ajax({
            url: '../dashboard/update_semua.php',
            type: 'POST',
            data: {
                action: 'nama',
                id_faskes: idFaskes,
                value: nama
            },
            success: function(response) {
                if(response.trim() === 'success') {
                    showToast('Nama berhasil diupdate', 'success');
                    // Update original value
                    element.attr('data-original-value', nama);
                    element.css('background-color', '#d4edda'); // Green background untuk sukses
                    setTimeout(function() {
                        element.css('background-color', '');
                    }, 1500);
                    // Update visual status for the row
                    updateRowStatus(element.closest('.data-row')[0]);
                } else {
                    showToast('Gagal update nama', 'error');
                    // Reset ke nilai sebelumnya jika gagal
                    element.val(originalValue);
                    element.css('background-color', '#f8d7da'); // Red background untuk error
                    setTimeout(function() {
                        element.css('background-color', '');
                    }, 1500);
                }
            },
            error: function() {
                showToast('Gagal update nama', 'error');
                // Reset ke nilai sebelumnya jika gagal
                element.val(originalValue);
                element.css('background-color', '#f8d7da'); // Red background untuk error
                setTimeout(function() {
                    element.css('background-color', '');
                }, 1500);
            },
            complete: function() {
                // Aktifkan kembali element
                element.prop('disabled', false);
            }
        });
    });

    // Simpan nilai original saat focus untuk input nama (using event delegation)
    $(document).on('focus', '.nama-input', function() {
        $(this).attr('data-original-value', $(this).val());
    });

    // Auto-save for nama with delay (debounce) - alternative for real-time update (using event delegation)
    let nameTimeout;
    $(document).on('input', '.nama-input', function() {
        const element = $(this);
        clearTimeout(nameTimeout);

        nameTimeout = setTimeout(function() {
            element.trigger('blur'); // Trigger blur event untuk auto-save
        }, 1000); // Delay 1 detik setelah user berhenti mengetik
    });

    // --- Visual Status Update Functions ---

    // Function to update row status visually
    // This function will now be called for initial loading and after updates
    function updateRowStatus(rowElement) {
        // Ensure rowElement is a DOM element, not a jQuery object
        const statusSelect = rowElement.querySelector('.status-select');
        const keteranganSelect = rowElement.querySelector('.keterangan-select');
        const namaInput = rowElement.querySelector('.nama-input');

        let filledCount = 0;

        // Check and update class for each field
        if (statusSelect && statusSelect.value) { // Check if element exists and has a value
            filledCount++;
            statusSelect.classList.add('field-filled');
            statusSelect.classList.remove('field-empty');
        } else if (statusSelect) {
            statusSelect.classList.add('field-empty');
            statusSelect.classList.remove('field-filled');
        }

        if (keteranganSelect && keteranganSelect.value) {
            filledCount++;
            keteranganSelect.classList.add('field-filled');
            keteranganSelect.classList.remove('field-empty');
        } else if (keteranganSelect) {
            keteranganSelect.classList.add('field-empty');
            keteranganSelect.classList.remove('field-filled');
        }

        if (namaInput && namaInput.value.trim()) {
            filledCount++;
            namaInput.classList.add('field-filled');
            namaInput.classList.remove('field-empty');
        } else if (namaInput) {
            namaInput.classList.add('field-empty');
            namaInput.classList.remove('field-filled');
        }

        // Update row background color based on filled status
        if (filledCount === 3) {
            rowElement.classList.add('row-complete'); // New class for complete rows
            rowElement.classList.remove('row-partial', 'row-empty');
        } else if (filledCount > 0) {
            rowElement.classList.add('row-partial'); // New class for partial rows
            rowElement.classList.remove('row-complete', 'row-empty');
        } else {
            rowElement.classList.add('row-empty'); // New class for empty rows
            rowElement.classList.remove('row-complete', 'row-partial');
        }

        // If you had status indicators within the row, you'd update them here:
        // const statusIndicator = rowElement.querySelector('.status-indicator');
        // const statusCount = rowElement.querySelector('small');
        // if (statusIndicator) statusIndicator.textContent = (filledCount === 3) ? 'COMPLETE' : (filledCount > 0 ? 'PARTIAL' : 'EMPTY');
        // if (statusCount) statusCount.textContent = filledCount + '/3';
    }

    // Function to initialize visual status for all rows after data load
    function initializeRowStatuses() {
        document.querySelectorAll('.data-row').forEach(rowElement => {
            updateRowStatus(rowElement);
        });
    }

    // Initial load of data when the page first loads
    function loadData() {
        $.ajax({
            url: '../ajax/data_peserta.php', // Ensure this points to your get_data.php if it serves the content
            type: 'GET',
            success: function (data) {
                $('#container').html(data);
                initializeRowStatuses(); // Call after initial data load
            }
        });
    }

    // Call loadData initially when the document is ready
    loadData();
    // No need for setInterval(loadData, 5000); if you're doing a search function
    // and updating on individual field changes. This might cause data conflicts.

    // Fungsi untuk menampilkan toast notification
    function showToast(message, type) {
        // Cek apakah SweetAlert2 tersedia
        if (typeof Swal !== 'undefined') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: type,
                title: message
            });
        } else {
            // Fallback jika SweetAlert2 tidak tersedia
            alert(message);
        }
    }

    // SweetAlert for Delete (using event delegation)
    $(document).on('click', '.btn-hapus', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');

        Swal.fire({
            title: 'Hapus Data?',
            text: "Apakah Anda yakin ingin menghapus data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });

    // SweetAlert for Logout (assuming logout-btn is static)
    // If #logout-btn is dynamically loaded, you'd need event delegation here too.
    $('#logout-btn').on('click', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Keluar?',
            text: 'Apakah Anda yakin ingin keluar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../../includes/logout.php';
            }
        });
    });

    // IMPORTANT: Call initializeRowStatuses on initial page load as well
    // if the table is present on page load before any AJAX.
    // Otherwise, the call after loadData() is sufficient.
    initializeRowStatuses();

}); // End of document.ready