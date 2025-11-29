<?php
session_start();

$mensajeError = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if ($email === '' || $clave === '') {
        $mensajeError = 'Por favor ingresa tu correo y tu contraseña.';
    } else {
        $emailValido = 'admin@correo.com';
        $claveValida = '12345';

        if ($email === $emailValido && $clave === $claveValida) {
            // Crear sesión
            $_SESSION['usuario_email'] = $email;

            // Redirigir al panel principal
            header('Location: panel.php');
            exit;
        } else {
            $mensajeError = 'Credenciales incorrectas. Intenta de nuevo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/login.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Iniciar sesión</h2>

        <?php if ($mensajeError !== ''): ?>
            <div class="error-box">
                <?php echo htmlspecialchars($mensajeError); ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" novalidate>
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required
                    placeholder="correo@ejemplo.com"
                >
            </div>

            <div class="form-group">
                <label for="clave">Contraseña</label>
                <input
                    type="password"
                    id="clave"
                    name="clave"
                    required
                    placeholder="Tu contraseña"
                >
            </div>

            <button type="submit" class="btn">Entrar</button>
        </form>

        <p class="hint">
            <strong>Demo:</strong> correo: <code>prueba@ejemplo.com</code>,
            contraseña: <code>12345</code>
        </p>
    </div>
</div>
</body>
</html>
