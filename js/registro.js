// Campos de formulario para Empresa
const empresaFields = `
    <div class="inline-label-field">
        <label for="razon_social">Razón Social:</label>
        <input type="text" id="razon_social" name="razon_social" required>
    </div>

    <div class="inline-label-field">
        <label for="ruc">RUC:</label>
        <input type="text" id="ruc" name="ruc" required>
    </div>

    <div class="inline-label-field">
        <label for="celular_empresa">Celular:</label>
        <input type="text" id="celular_empresa" name="celular" required>
    </div>

    <div class="inline-label-field">
        <label for="direccion_empresa">Dirección:</label>
        <input type="text" id="direccion_empresa" name="direccion" required>
    </div>

    <div class="inline-label-field">
        <label for="representante_empresa">Representante:</label>
        <input type="text" id="representante_empresa" name="representante" required>
    </div>
`;

// Campos de formulario para Postulante
const postulanteFields = `
    <div class="inline-label-field">
        <label for="nombre">Nombres:</label>
        <input type="text" id="nombre" name="nombre" required>
    </div>

    <div class="inline-label-field">
        <label for="apellido_paterno">Apellido Paterno:</label>
        <input type="text" id="apellido_paterno" name="apellido_paterno" required>
    </div>

    <div class="inline-label-field">
        <label for="apellido_materno">Apellido Materno:</label>
        <input type="text" id="apellido_materno" name="apellido_materno" required>
    </div>

    <div class="inline-label-field">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required>
    </div>

    <div class="inline-label-field">
        <label for="cip">CIP:</label>
        <input type="text" id="cip" name="cip" required>
    </div>

    <div class="inline-label-field">
        <label for="celular">Celular:</label>
        <input type="text" id="celular" name="celular" required>
    </div>

    <div class="inline-label-field">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>
    </div>

    <div class="inline-label-field">
        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
    </div>

    <div class="sexo-container">
        <div class="sexo-option">
            <input type="radio" id="masculino" name="sexo" value="1" required>
            <label for="masculino">Masculino</label>
        </div>
        <div class="sexo-option">
            <input type="radio" id="femenino" name="sexo" value="2" required>
            <label for="femenino">Femenino</label>
        </div>
    </div>
`;

// Función para manejar cambios en el rol
document.addEventListener('DOMContentLoaded', () => {
    const rolSelect = document.getElementById("rol");
    const dynamicFields = document.getElementById("dynamic-fields");

    // Actualizar campos dinámicos
    rolSelect.addEventListener("change", () => {
        const selectedRol = rolSelect.value;
        dynamicFields.innerHTML = ""; // Limpiar campos anteriores

        if (selectedRol === "2") {
            dynamicFields.innerHTML = empresaFields;
        } else if (selectedRol === "3") {
            dynamicFields.innerHTML = postulanteFields;
        }
    });
});