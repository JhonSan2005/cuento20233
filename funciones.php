<?php
// funciones.php

// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "repuestos_jjj";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Función para agregar un producto
function agregarProducto($nombre, $precio, $stock, $proveedor)
{
    global $conn;

    $sql = "INSERT INTO productos (nombre, precio, stock, proveedor) VALUES ('$nombre', $precio, $stock, '$proveedor')";


    if ($conn->query($sql) === TRUE) {
        return "Producto agregado exitosamente";
    } else {
        return "Error al agregar el producto: " . $conn->error;
    }
}

// Función para listar productos
function listarProductos()
{
    global $conn;

    $sql = "SELECT * FROM repuestos_jjj.productos";
    $result = $conn->query($sql);

    $productos = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
    }

    return $productos;
}

// ... otras funciones


// Función para realizar una venta
function realizarVenta($idProducto, $cantidad)
{
global $conn;

// Verificar si hay suficiente stock
$producto = $conn->query("SELECT * FROM productos WHERE id = $idProducto")->fetch_assoc();
if ($producto['stock'] < $cantidad) { return "Error: Stock insuficiente" ; } // Actualizar stock $nuevoStock=$producto['stock'] - $cantidad; $conn->query("UPDATE productos SET stock = $nuevoStock WHERE id = $idProducto");

    // Registrar la venta en la base de datos (puedes tener una tabla de ventas)
    $sql = "INSERT INTO ventas (id_producto, cantidad, fecha) VALUES ($idProducto, $cantidad, NOW())";

    if ($conn->query($sql) === TRUE) {
    return "Venta realizada con éxito";
    } else {
    return "Error al realizar la venta: " . $conn->error;
    }
    }

// ... otras funciones


// ... (configuración de la base de datos y otras funciones)

// Función para eliminar un producto


function eliminarProducto($id_producto)
{
    global $conn;

    // Utilizar una sentencia preparada para prevenir la inyección SQL
    $sql = "DELETE FROM ventas WHERE id_producto = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Verificar si la preparación fue exitosa
    if ($stmt) {
        // Vincular el parámetro
        $stmt->bind_param("i", $id_producto);

        // Ejecutar la sentencia
        if ($stmt->execute()) {
            $stmt->close();
            return "Producto eliminado exitosamente";
        } else {
            $error = $stmt->error;
            $stmt->close();
            return "Error al eliminar el producto: " . $error;
        }
    } else {
        return "Error al preparar la consulta: " . $conn->error;
    }
}

// No cierres la conexión aquí, ya que puede haber otras operaciones después de llamar a esta función
// $conn->close();

function buscarProductosPorNombre($nombre) {
    global $conn;

    $sql = "SELECT * FROM productos WHERE nombre LIKE '%$nombre%'";
    $result = $conn->query($sql);

    $productosEncontrados = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productosEncontrados[] = $row;
        }
    }

    return $productosEncontrados;
}
function calcularTotalVentasPorProducto($idProducto)
{
    global $conn;

    $sql = "SELECT SUM(cantidad) as totalVentas FROM ventas WHERE id_producto = $idProducto";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    return $row['totalVentas'];
}
function obtenerProveedores()
{
    global $conn;

    $sql = "SELECT DISTINCT proveedor FROM productos";
    $result = $conn->query($sql);

    $proveedores = array();

    if ($result) {
        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $proveedores[] = $row['proveedor'];
            }
        } else {
            return "No hay proveedores disponibles.";
        }

        $result->free_result();  // Liberar el conjunto de resultados
    } else {
        return "Error al ejecutar la consulta: " . $conn->error;
    }

    return $proveedores;
}

function obtenerCantidadTotalEnStock()
{
    global $conn;

    $sql = "SELECT SUM(stock) as totalEnStock FROM productos";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    return $row['totalEnStock'];
}

function obtenerHistorialVentas()
{
    global $conn;

    $sql = "SELECT * FROM ventas ORDER BY fecha DESC";
    $result = $conn->query($sql);

    $historialVentas = "";

    if ($result) {
        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            $historialVentas .= "<h2>Historial de Ventas</h2>";
            $historialVentas .= "<table border='1'><tr><th>ID Producto</th><th>Cantidad</th><th>Fecha</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $historialVentas .= "<tr><td>" . $row['id_producto'] . "</td><td>" . $row['cantidad'] . "</td><td>" . $row['fecha'] . "</td></tr>";
            }

            $historialVentas .= "</table>";
        } else {
            $historialVentas = "No hay historial de ventas.";
        }

        $result->free_result();  // Liberar el conjunto de resultados
    } else {
        $historialVentas = "Error al ejecutar la consulta: " . $conn->error;
    }

    return $historialVentas;
}

function obtenerDetallesProducto($id)
{
    global $conn;

    $sql = "SELECT * FROM productos WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Producto no encontrado
    }
}
