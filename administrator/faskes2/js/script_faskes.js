$(document).ready(function() {
    var keyword = "";
    var request;
    var currentPage = 1;

    // Ambil keyword dari input saat halaman dimuat
    keyword = $("#keyword").val().trim();

    // Live search functionality
    $("#keyword").on("keyup", function() {
        var newKeyword = $(this).val().trim();
        
        // Jika keyword berubah, reset ke halaman 1
        if (newKeyword !== keyword) {
            currentPage = 1;
            keyword = newKeyword;
        }
        
        // Cancel previous request if it exists
        if (request && request.readyState !== 4) {
            request.abort();
        }

        if (keyword.length > 0) {
            request = $.ajax({
                url: "../ajax/data_peserta_faskes.php",
                method: "GET",
                data: {
                    keyword: keyword,
                    halaman: currentPage
                },
                success: function(data) {
                    $("#container").html(data);
                    setupPaginationHandlers();
                    
                    // Update URL untuk mencerminkan pencarian
                    var newUrl = window.location.pathname + '?keyword=' + encodeURIComponent(keyword);
                    if (currentPage > 1) {
                        newUrl += '&halaman=' + currentPage;
                    }
                    window.history.replaceState({}, '', newUrl);
                }
            });
        } else {
            // Jika keyword kosong, reset dan muat semua data dari halaman 1
            currentPage = 1;
            keyword = "";
            $("#keyword").val(""); // Pastikan input kosong
            window.history.replaceState({}, '', window.location.pathname);
            loadAllData(1);
        }
    });

    // Function to load all data
    function loadAllData(halaman = 1) {
        currentPage = halaman;
        $.ajax({
            url: "../ajax/",
            method: "GET",
            data: {
                halaman: halaman
            },
            success: function(data) {
                $("#container").html(data);
                setupPaginationHandlers();
                
                // Update URL untuk mencerminkan halaman yang benar
                if (halaman === 1) {
                    window.history.replaceState({}, '', window.location.pathname);
                } else {
                    window.history.replaceState({}, '', window.location.pathname + '?halaman=' + halaman);
                }
            }
        });
    }

    // Function to setup pagination handlers
    function setupPaginationHandlers() {
        // Handle pagination clicks
        $(document).off('click', '.pagination-link').on('click', '.pagination-link', function(e) {
            e.preventDefault();
            
            var halaman = $(this).data('halaman');
            var currentKeyword = $("#keyword").val().trim();
            
            currentPage = halaman;
            
            $.ajax({
                url: "../ajax/",
                method: "GET",
                data: {
                    keyword: currentKeyword,
                    halaman: halaman
                },
                success: function(data) {
                    $("#container").html(data);
                    setupPaginationHandlers();
                    
                    // Update URL
                    var newUrl = window.location.pathname;
                    var params = [];
                    
                    if (currentKeyword.length > 0) {
                        params.push('keyword=' + encodeURIComponent(currentKeyword));
                    }
                    
                    if (halaman > 1) {
                        params.push('halaman=' + halaman);
                    }
                    
                    if (params.length > 0) {
                        newUrl += '?' + params.join('&');
                    }
                    
                    window.history.replaceState({}, '', newUrl);
                }
            });
        });
    }

    // Detect if we're on a specific page when the page loads
    var urlParams = new URLSearchParams(window.location.search);
    var initialPage = parseInt(urlParams.get('halaman')) || 1;
    var initialKeyword = urlParams.get('keyword') || '';
    
    // Set initial values
    currentPage = initialPage;
    if (initialKeyword) {
        $("#keyword").val(initialKeyword);
        keyword = initialKeyword;
    }

    // Initial setup
    setupPaginationHandlers();
});