<?php
session_start();
session_unset();
session_destroy();

// Redirigir a la página principal con mensaje
header("Location: /principal.php?msg=Has cerrado sesión correctamente");
exit();
?>