<?php
session_start();
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $email = $conexion->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $rol = $conexion->real_escape_string($_POST['rol']);

    // Validar el formato de email https://www.w3schools.com/php/filter_validate_email.asp
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El correo electrónico no es válido.";
        header("Location: registro.php");
        exit();
    }

    // Verificar si el email ya existe en la base de datos
    $sql = "SELECT id FROM usuarios WHERE email = '$email'";
    $result = $conexion->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "El correo ya está registrado.";
        header("Location: registro.php");
        exit();
    } else {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES ('$nombre', '$email', '$password', '$rol')";
        if ($conexion->query($sql) === TRUE) {
            $_SESSION['success'] = "Registro exitoso. Inicia sesión.";
            // se puede cambiar la direccion para ir a cualquier pagina 
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Error en el registro: " . $conexion->error;
            header("Location: registro.php");
            exit();
        }
    }
}
?>
