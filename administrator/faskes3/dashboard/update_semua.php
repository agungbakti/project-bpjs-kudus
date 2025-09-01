<?php
// Tampilkan semua error PHP saat development (hapus/komentari di production)
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// Pastikan file ini tidak output apapun selain kode PHP
require "../database/function_faskes.php"; // Harusnya berisi koneksi DB ($koneksi)

// Set header untuk memberitahu browser bahwa ini adalah plain text
header('Content-Type: text/plain');

// Pastikan semua parameter yang dibutuhkan diterima
if (isset($_POST['action']) && isset($_POST['id_faskes']) && isset($_POST['value'])) {
    $action = $_POST['action'];
    $id_faskes = (int)$_POST['id_faskes']; // Cast to int for safety
    $value = $_POST['value']; // Value can be int or string, handled in switch

    $query = "";
    $param_type = "";
    $stmt = null; // Initialize $stmt to null

    try {
        // Prepare statement outside switch to avoid repetition, if possible
        // Or keep it inside for clarity for each case. Let's keep it inside for now.

        switch ($action) {
            case 'status':
                $query = "UPDATE tb_faskes SET id_status = ? WHERE id_faskes = ?";
                $stmt = mysqli_prepare($koneksi, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ii", $value, $id_faskes);
                }
                break;
                
            case 'keterangan':
                $query = "UPDATE tb_faskes SET id_keterangan = ? WHERE id_faskes = ?";
                $stmt = mysqli_prepare($koneksi, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ii", $value, $id_faskes);
                }
                break;
                
            case 'nama':
                $query = "UPDATE tb_faskes SET nama = ? WHERE id_faskes = ?";
                $stmt = mysqli_prepare($koneksi, $query);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "si", $value, $id_faskes);
                }
                break;
                
            default:
                echo 'error'; // Invalid action
                exit; // Stop script execution
        }

        // Check if statement was prepared successfully
        if ($stmt) {
            if (mysqli_stmt_execute($stmt)) {
                echo 'success'; // Echo success
            } else {
                // Echo specific database error for debugging
                echo 'error_db: ' . mysqli_stmt_error($stmt); 
            }
            mysqli_stmt_close($stmt); // Close statement regardless of success
        } else {
            // Echo error if prepare failed
            echo 'error_prepare: ' . mysqli_error($koneksi);
        }
        
    } catch (Exception $e) {
        // This catch block will only work for exceptions, not mysqli errors by default
        echo 'error_exception: ' . $e->getMessage();
    }
} else {
    echo 'error_invalid_params'; // Missing required POST parameters
}

// Ensure nothing else is printed after the response
exit; 
// It's a good practice to omit the closing '?>' tag in PHP files that contain only PHP code.
// This prevents accidental whitespace or newlines from being outputted.