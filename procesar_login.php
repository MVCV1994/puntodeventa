<?php
session_start();
include('db_config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conexion->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conexion->query($sql);

    if ($result->num_rows == 1) {
        $usuario = $result->fetch_assoc();
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Redirigir según el rol
            switch($usuario['rol']) {
                case 'administrador':
                    header("Location: administrador.php");
                    break;
                case 'cajero':
                    header("Location: cajero.php");
                    break;
                case 'mesero':
                    header("Location: mesero.php");
                    break;
            }
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Correo no registrado.";
        header("Location: index.php");
        exit();
    }
}
?>