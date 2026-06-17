// Variable que controla si el formulario está en modo Guardar o Modificar
let accion = "Guardar";

// Variable que guarda la página actual de la paginación
let paginaActual = 1;

// Cuando el DOM esté completamente cargado, ejecuta estas acciones
document.addEventListener("DOMContentLoaded", () => {

    // Carga la primera página de productos al iniciar
    listarProductos(1);

    // Asigna evento al botón de guardar
    document
        .getElementById("btnGuardar")
        .addEventListener("click", procesarFormulario);

});

// Función que decide qué acción ejecutar según el estado actual
function procesarFormulario()
{
    switch(accion)
    {
        case "Guardar":
            guardarProducto();
        break;

        case "Modificar":
            modificarProducto();
        break;
    }
}

// Función para guardar un nuevo producto
function guardarProducto()
{
    // Captura todos los datos del formulario
    let datos = new FormData(
        document.getElementById("frmProducto")
    );

    // Indica al backend la acción a realizar
    datos.append("accion","Guardar");

    // Envío de datos al servidor mediante fetch
    fetch("registrar.php",{
        method:"POST",
        body:datos
    })
    .then(r=>r.json())
    .then(data=>{

        // Si la operación fue exitosa
        if(data.success)
        {
            // Muestra mensaje de éxito
            Swal.fire(
                "Éxito",
                data.message,
                "success"
            );

            // Reinicia el formulario
            document.getElementById("frmProducto").reset();

            // Recarga la lista de productos
            listarProductos();
        }
        else
        {
            // Muestra errores de validación
            Swal.fire(
                "Error",
                data.errors.join("\n"),
                "error"
            );
        }

    });
}

// Función para modificar un producto existente
function modificarProducto()
{
    // Captura datos del formulario
    let datos = new FormData(
        document.getElementById("frmProducto")
    );

    // Indica acción de modificación al backend
    datos.append("accion","Modificar");

    // Envío al servidor
    fetch("registrar.php",{
        method:"POST",
        body:datos
    })
    .then(r=>r.json())
    .then(data=>{

        // Si la actualización fue exitosa
        if(data.success)
        {
            // Mensaje de confirmación
            Swal.fire(
                "Correcto",
                data.message,
                "success"
            );

            // Limpia el formulario
            document.getElementById("frmProducto").reset();

            // Regresa el estado a guardar
            accion = "Guardar";

            // Cambia el texto del botón
            document.getElementById("btnGuardar")
                .textContent = "Registrar";

            // Recarga la lista
            listarProductos();
        }

    });
}

// Función para listar productos con paginación y búsqueda opcional
function listarProductos(pagina = 1, buscar = "") {

    // Se crean los datos a enviar al backend
    let datos = new FormData();

    // Acción por defecto: paginación
    datos.append("accion", "Paginar");

    // Página solicitada
    datos.append("pagina", pagina);

    // Si existe texto de búsqueda, se modifica la acción
    if (buscar.trim() !== "") {
        datos.append("buscar", buscar);
        datos.set("accion", "Buscar");
    }

    // Petición al servidor
    fetch("registrar.php", {
        method: "POST",
        body: datos
    })
    .then(r => r.json())
    .then(data => {

        // Variable donde se construye la tabla HTML
        let tabla = "";

        // Recorre los productos recibidos
        data.data.forEach(p => {
            tabla += `
            <tr>
                <td>${p.id}</td>
                <td>${p.codigo}</td>
                <td>${p.producto}</td>
                <td>${p.descripcion}</td>
                <td>${p.precio}</td>
                <td>${p.cantidad}</td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editar(${p.id})">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="eliminar(${p.id})">Eliminar</button>
                </td>
            </tr>
            `;
        });

        // Inserta la tabla en el HTML
        document.getElementById("tblProductos").innerHTML = tabla;

        // Actualiza la página actual
        paginaActual = pagina;

        // Muestra el número de página
        document.getElementById("numPagina").innerHTML = `Página ${pagina}`;
    });
}

// Función para cargar datos de un producto en el formulario
function editar(id)
{
    let datos = new FormData();

    // Se solicita búsqueda por ID
    datos.append("accion","Buscar");
    datos.append("id",id);

    fetch("registrar.php",{
        method:"POST",
        body:datos
    })
    .then(r=>r.json())
    .then(data=>{

        // Datos del producto
        let p = data.data;

        // Se llenan los campos del formulario
        document.getElementById("id").value = p.id;
        document.getElementById("codigo").value = p.codigo;
        document.getElementById("producto").value = p.producto;
        document.getElementById("descripcion").value = p.descripcion;
        document.getElementById("precio").value = p.precio;
        document.getElementById("cantidad").value = p.cantidad;

        // Cambia a modo modificación
        accion = "Modificar";

        // Cambia el texto del botón
        document.getElementById("btnGuardar")
            .textContent = "Actualizar";
    });
}

// Función para eliminar un producto
function eliminar(id)
{
    // Confirmación con SweetAlert
    Swal.fire({
        title: "¿Eliminar producto?",
        text: "Esta acción no se puede deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar"
    })
    .then((result)=>{

        // Si el usuario confirma
        if(result.isConfirmed)
        {
            let datos = new FormData();

            datos.append("accion","Eliminar");
            datos.append("id",id);

            fetch("registrar.php",{
                method:"POST",
                body:datos
            })
            .then(r=>r.json())
            .then(data=>{

                // Mensaje de confirmación
                Swal.fire(
                    "Correcto",
                    data.message,
                    "success"
                );

                // Recarga la tabla
                listarProductos();

            });
        }
    });
}

// Ir a la siguiente página
function paginaSiguiente()
{
    listarProductos(++paginaActual);
}

// Ir a la página anterior
function paginaAnterior()
{
    if(paginaActual > 1)
    {
        listarProductos(--paginaActual);
    }
}

// Función de búsqueda en tiempo real
function buscarProductos() {

    // Captura texto del input
    let texto = document.getElementById("buscador").value;

    // Prepara datos para envío
    let datos = new FormData();
    datos.append("accion", "BuscarTexto");
    datos.append("buscar", texto);

    // Petición al backend
    fetch("registrar.php", {
        method: "POST",
        body: datos
    })
    .then(async r => {

        // Convierte primero a texto para depuración
        let text = await r.text();

        // Muestra respuesta en consola para detectar errores
        console.log(text);

        // Convierte a JSON
        return JSON.parse(text);
    })
    .then(data => {

        // Si falla la respuesta, no continúa
        if (!data.success) return;

        // Construcción de la tabla
        let tabla = "";

        data.data.forEach(p => {
            tabla += `
            <tr>
                <td>${p.id}</td>
                <td>${p.codigo}</td>
                <td>${p.producto}</td>
                <td>${p.descripcion}</td>
                <td>${p.precio}</td>
                <td>${p.cantidad}</td>
            </tr>
            `;
        });

        // Render en pantalla
        document.getElementById("tblProductos").innerHTML = tabla;
    })
    .catch(err => {
        // Manejo de errores de JSON o red
        console.error("Error JSON:", err);
    });
}