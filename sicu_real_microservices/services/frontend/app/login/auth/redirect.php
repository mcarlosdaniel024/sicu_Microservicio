<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /principal.php");
    exit();
}

switch ($_SESSION['user_role']) {
    case 'estudiante':
        header("Location: /estudiante/dashboard.php");
        break;
    case 'profesor':
        header("Location: /profesor/dashboard.php");
        break;
    case 'admin':
        header("Location: /admin/dashboard.php");
        break;
    case 'directivo':
        header("Location: /directivo/dashboard.php");
        break;
    case 'personal_administrativo':
        header("Location: /admin/dashboard.php");
        break;
    default:
        header("Location: /principal.php");
}
exit();
?>