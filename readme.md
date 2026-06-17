# CRUD de Productos con PHP (PDO) y Fetch API

Este Laboratorio es un sistema CRUD de productos desarrollado en PHP utilizando PDO para la conexión a la base de datos MySQL y JavaScript con Fetch API para la comunicación asincrónica (AJAX). El sistema permite realizar operaciones completas de gestión de productos como crear, listar, actualizar, eliminar, paginar y buscar en tiempo real sin recargar la página.

---

## Tecnologías utilizadas

- PHP (PDO)
- MySQL
- JavaScript (Fetch API)
- HTML5
- SweetAlert2 (para alertas)
- Bootstrap (opcional para estilos)

---

## Funcionalidades

El sistema permite:

- Registrar productos con código, nombre, descripción, precio y cantidad
- Listar productos desde la base de datos
- Editar productos existentes
- Eliminar productos con confirmación
- Paginar los resultados (5 registros por página)
- Buscar productos en tiempo real por código o nombre

---

## Estructura del proyecto

El Laboratorio está organizado de la siguiente manera:

- Modelo/
  - DB.php (conexión a la base de datos usando PDO)
  - Productos.php (lógica del CRUD)
- registrar.php (controlador principal que maneja todas las acciones)
- index.html (interfaz del usuario)
- script.js (lógica del frontend con Fetch API)

---

## Flujo del sistema

El funcionamiento del sistema es el siguiente:

1. El usuario interactúa con el formulario o buscador en el frontend
2. JavaScript envía la información mediante Fetch API a registrar.php
3. PHP recibe la petición según la acción enviada (Guardar, Modificar, Eliminar, Listar, Paginar, BuscarTexto)
4. Se ejecuta la lógica correspondiente en el modelo Productos.php
5. PHP devuelve una respuesta en formato JSON
6. JavaScript procesa la respuesta y actualiza la interfaz sin recargar la página

---

## Búsqueda en tiempo real

El sistema implementa una búsqueda dinámica que permite filtrar productos mientras el usuario escribe.

La búsqueda se realiza enviando la acción BuscarTexto junto con el texto ingresado, el cual es procesado en SQL utilizando LIKE para buscar coincidencias en el código o nombre del producto.

---

## Estructura lógica (backend)

El archivo registrar.php funciona como controlador principal y maneja las siguientes acciones:

- Guardar: inserta nuevos productos
- Modificar: actualiza productos existentes
- Buscar: obtiene un producto por ID
- Listar: devuelve todos los productos
- Eliminar: elimina un producto por ID
- Paginar: devuelve productos paginados
- BuscarTexto: realiza búsqueda en tiempo real

---

## Notas importantes

- El Laboratorio utiliza PDO para la conexión a la base de datos
- Todas las respuestas del servidor están en formato JSON
- Se implementa validación básica en el backend
- Se evita recarga de página gracias al uso de Fetch API
- La búsqueda en tiempo real requiere el uso de la acción BuscarTexto

---

# 📜 Referencias

1. Guía del Laboratorio de CRUD con Fetch, Json.pdf
2. https://www.youtube.com/watch?v=AXZGTOd8ASk
3. Recursos suministrador por la ing. Irina Fong

---

# ⏱️ Fecha de Ejecución del Laboratorio

Martes 16 de junio de 2026

---

<p align="center">
Este laboratorio ha sido desarrollado por el estudiante de la Universidad 
Tecnológica de Panamá: <br>
Nombre: Orlando Antonio Valdés Bernal<br>
Correo: orlando.valdes2@utp.ac.pa<br>
Curso: Desarrollo de Software 7 - 1GS131<br>
Instructor del Laboratorio: Irina Fong.
</p>