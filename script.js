const btnRegistrar = document.getElementById("Registrar");
const frm = document.getElementById("frmProducto");

btnRegistrar.addEventListener("click", () => {
    const formData = new FormData(frm);

    fetch("registrar.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            let titulo = "";

            switch (response.accion) {
                case "Guardar":
                    titulo = "Producto guardado";
                    break;
                case "Modificar":
                    titulo = "Producto actualizado";
                    break;
                default:
                    titulo = "Acción realizada";
            }

            Swal.fire({
                icon: "success",
                title: titulo,
                showConfirmButton: false,
                timer: 1500
            });

            frm.reset();
            document.getElementById("Accion").value = "Guardar";
            document.getElementById("Registrar").textContent = "Guardar";
            ListarProductos();
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: response.message
            });
            console.warn("Errores:", response.errors);
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: "error",
            title: "Error de red",
            text: "No se pudo contactar al servidor"
        });
    });
});

function ListarProductos() {
    const fd = new FormData();
    fd.append("Accion", "Listar");

    fetch("registrar.php", {
        method: "POST",
        body: fd
    })
    .then(res => res.json())
    .then(response => {
        if (response.success) {
            const tbody = document.querySelector("#tblProductos tbody");
            tbody.innerHTML = "";

            response.data.forEach(prod => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${prod.id}</td>
                    <td>${prod.codigo}</td>
                    <td>${prod.producto}</td>
                    <td>${prod.precio}</td>
                    <td>${prod.cantidad}</td>
                    <td>
                        <button class="btn btn-sm btn-warning btn-editar">Editar</button>
                    </td>
                `;
                tbody.appendChild(tr);

                tr.querySelector(".btn-editar").addEventListener("click", () => {
                    document.getElementById("id").value       = prod.id;
                    document.getElementById("Codigo").value   = prod.codigo;
                    document.getElementById("Producto").value = prod.producto;
                    document.getElementById("Precio").value   = prod.precio;
                    document.getElementById("Cantidad").value = prod.cantidad;

                    document.getElementById("Accion").value   = "Modificar";
                    document.getElementById("Registrar").textContent = "Actualizar";
                });
            });
        }
    });
}

// Cargar productos al entrar
document.addEventListener("DOMContentLoaded", ListarProductos);

