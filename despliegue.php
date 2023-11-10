<?php

// despliegue.php
include("funciones.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Verificar si se proporcionó un parámetro 'action'
    if (isset($_GET["action"])) {
        $action = $_GET["action"];

        // Realizar acciones basadas en el parámetro 'action'
        switch ($action) {
            case "listarProductos":
                $productos = listarProductos();
                mostrarTablaProductos($productos);
                break;

            case "realizarVenta":
                // Asumiendo que se proporcionarán los parámetros 'idProducto' y 'cantidad'
                if (isset($_GET["idProducto"]) && isset($_GET["cantidad"])) {
                    $idProducto = $_GET["idProducto"];
                    $cantidad = $_GET["cantidad"];
                    $resultado = realizarVenta($idProducto, $cantidad);
                    mostrarResultados($resultado);
                } else {
                    mostrarResultados("Parámetros faltantes para realizar la venta.");
                }
                break;

            case "eliminarProducto":
                // Asumiendo que se proporcionará el parámetro 'id_producto'
                if (isset($_GET["id_producto"])) {
                    $id_producto = $_GET["id_producto"];
                    $resultado = eliminarProducto($id_producto);
                    mostrarResultados($resultado);
                } else {
                    mostrarResultados("Parámetro 'id_producto' faltante para eliminar el producto.");
                }
                break;

            case "buscarProductosPorNombre":
                // Asumiendo que se proporcionará el parámetro 'nombre'
                if (isset($_GET["nombre"])) {
                    $nombre = $_GET["nombre"];
                    $productosEncontrados = buscarProductosPorNombre($nombre);
                    mostrarResultados($productosEncontrados);
                } else {
                    mostrarResultados("Parámetro 'nombre' faltante para buscar productos por nombre.");
                }
                break;

            case "calcularTotalVentasPorProducto":
                // Asumiendo que se proporcionará el parámetro 'idProducto'
                if (isset($_GET["idProducto"])) {
                    $idProducto = $_GET["idProducto"];
                    $totalVentas = calcularTotalVentasPorProducto($idProducto);
                    mostrarResultados($totalVentas);
                } else {
                    mostrarResultados("Parámetro 'idProducto' faltante para calcular el total de ventas por producto.");
                }
                break;

            case "obtenerCantidadTotalEnStock":
                // Obtener la cantidad total en stock
                $cantidadTotalEnStock = obtenerCantidadTotalEnStock();
                mostrarResultados($cantidadTotalEnStock);
                break;

            // Agrega más casos según las funciones que desees habilitar

            default:
                mostrarResultados("Acción no válida.");
                break;
        }
    } else {
        mostrarResultados("Parámetro 'action' no proporcionado.");
    }
} else {
    mostrarResultados("Método no permitido.");
}

// Agregar enlaces para llamar a cada función
echo "<h2>Enlaces</h2>";
echo "<ul>";
echo "<li><a href='despliegue.php?action=listarProductos'>Listar Productos</a></li>";
echo "<li><a href='despliegue.php?action=realizarVenta&idProducto=1&cantidad=1'>Realizar Venta</a></li>";
echo "<li><a href='despliegue.php?action=eliminarProducto&id_producto=1'>Eliminar Producto</a></li>";
echo "<li><a href='despliegue.php?action=buscarProductosPorNombre&nombre=piston'>Buscar Productos por Nombre</a></li>";
echo "<li><a href='despliegue.php?action=calcularTotalVentasPorProducto&idProducto=1'>Calcular Total Ventas por Producto</a></li>";
echo "<li><a href='despliegue.php?action=obtenerCantidadTotalEnStock'>Obtener Cantidad Total en Stock</a></li>";
echo "</ul>";

function mostrarTablaProductos($productos)
{
    echo "<h2>Lista de Productos</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th></tr>";

    foreach ($productos as $producto) {
        echo "<tr>";
        echo "<td>" . $producto['id'] . "</td>";
        echo "<td>" . $producto['nombre'] . "</td>";
        echo "<td>" . $producto['precio'] . "</td>";
        echo "<td>" . $producto['stock'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

function mostrarResultados($resultado)
{
    // Mostrar los resultados de las funciones
    echo "<pre>";
    print_r($resultado);
    echo "</pre>";
}
