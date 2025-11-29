<?php
session_start();
require_once 'conexion.php'; // 👈 IMPORTANTE: aquí se crea $pdo

$mensajeOk = '';
$mensajeError = '';

$nombre       = '';
$categoria    = '';
$cantidad     = '';
$precio       = '';
$proveedor_id = ''; // ahora guardamos el ID del proveedor

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = trim($_POST['nombre'] ?? '');
    $categoria    = trim($_POST['categoria'] ?? '');
    $cantidad     = trim($_POST['cantidad'] ?? '');
    $precio       = trim($_POST['precio'] ?? '');
    $proveedor_id = trim($_POST['proveedor_id'] ?? '');

    if ($nombre === '' || $categoria === '' || $cantidad === '' || $precio === '' || $proveedor_id === '') {
        $mensajeError = 'Por favor llena todos los campos.';
    } elseif (!is_numeric($cantidad) || !is_numeric($precio) || !is_numeric($proveedor_id)) {
        $mensajeError = 'Cantidad, precio y proveedor deben ser valores numéricos.';
    } else {
        try {
            // NO mandamos id, ni creado_en ni actualizado_en. Los pone MySQL.
            $sql = "INSERT INTO medicamentos (nombre, categoria, cantidad, precio, proveedor_id)
                    VALUES (:nombre, :categoria, :cantidad, :precio, :proveedor_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre'       => $nombre,
                ':categoria'    => $categoria,
                ':cantidad'     => (int)$cantidad,
                ':precio'       => (float)$precio,
                ':proveedor_id' => (int)$proveedor_id,
            ]);

            $mensajeOk = 'Medicamento registrado correctamente.';

            // Limpiar campos del formulario
            $nombre = $categoria = $cantidad = $precio = $proveedor_id = '';

        } catch (Exception $e) {
            $mensajeError = 'Ocurrió un error al registrar el medicamento.';
            // Si quieres ver el detalle mientras pruebas:
            // $mensajeError .= ' Detalle: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar medicamento</title>
    <link rel="stylesheet" href="CSS/registro.css">
</head>
<body>
<div class="container">
    <h1>Registrar medicamento</h1>

    <?php if ($mensajeOk !== ''): ?>
        <div class="mensaje-ok">
            <?php echo htmlspecialchars($mensajeOk); ?>
        </div>
    <?php endif; ?>

    <?php if ($mensajeError !== ''): ?>
        <div class="mensaje-error">
            <?php echo htmlspecialchars($mensajeError); ?>
        </div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
        <div class="form-group">
            <label for="nombre">Nombre del medicamento</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
        </div>

        <div class="form-group">
            <label for="categoria">Categoría</label>
            <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>" required>
        </div>

        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($cantidad); ?>" min="0" required>
        </div>

        <div class="form-group">
            <label for="precio">Precio</label>
            <input type="number" step="0.01" id="precio" name="precio" value="<?php echo htmlspecialchars($precio); ?>" min="0" required>
        </div>

        <div class="form-group">
            <label for="proveedor_id">ID del proveedor</label>
            <input type="number" id="proveedor_id" name="proveedor_id" value="<?php echo htmlspecialchars($proveedor_id); ?>" min="1" required>
        </div>

        <button type="submit" class="btn">Guardar medicamento</button>
    </form>

    <a href="panel.php" class="link-back">← Volver al panel</a>
</div>
</body>
</html>
