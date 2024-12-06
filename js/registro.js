// Campos de formulario para Empresa
const empresaFields = `
    <div class="inline-label-field">
        <label for="razon_social">Razón Social:</label>
        <input type="text" id="razon_social" name="razon_social" required>
    </div>

    <div class="inline-label-field">
        <label for="ruc">RUC:</label>
        <input type="number" id="ruc" name="ruc" maxlength="11" required>
    </div>

    <div class="inline-label-field">
        <label for="celular">Celular:</label>
        <input type="number" id="celular" name="celular" maxlength="9" required>
    </div>

    <div class="inline-label-field">
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required>
    </div>

    <!-- Subtítulo para los campos del representante -->
    <div class="form-section-title"><center><strong>REPRESENTANTE</strong></center></div>
    <br>

    <div class="inline-label-field">
        <label for="representante_nombres">Nombres:</label>
        <input type="text" id="representante_nombres" name="representante_nombres" required>
    </div>

    <div class="inline-label-field">
        <label for="representante_apellido_paterno">Apellido Paterno:</label>
        <input type="text" id="representante_apellido_paterno" name="representante_apellido_paterno" required>
    </div>

    <div class="inline-label-field">
        <label for="representante_apellido_materno">Apellido Materno:</label>
        <input type="text" id="representante_apellido_materno" name="representante_apellido_materno" required>
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
        <input type="number" id="dni" name="dni" maxlength="8" required>
    </div>

    <div class="inline-label-field">
        <label for="cip">CIP:</label>
        <input type="number" id="cip" name="cip" maxlength="8" required>
    </div>

    <div class="inline-label-field">
        <label for="celular">Celular:</label>
        <input type="number" id="celular" name="celular" maxlength="9" required>
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
        addFieldValidations();
    });

    // Agregar validaciones a los campos
    function addFieldValidations() {
        const fieldsToValidate = ['ruc', 'dni', 'cip', 'celular']; //Campos a validar

        fieldsToValidate.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    const maxLength = parseInt(field.getAttribute('maxlength'));
                    if (field.value.length > maxLength) {
                        field.value = field.value.slice(0, maxLength); // Limitar a longitud máxima
                    }
                    if (!/^\d+$/.test(field.value)) { //Verificar si es numerico
                        showErrorAlert(`El campo ${fieldId} debe contener solo números.`);
                        field.value = "";  //Limpiar el campo si no es numerico.
                    }
                });
            }
        });

        //Validación adicional para celular (9 digitos)
        const celularField = document.getElementById('celular');
        if (celularField) {
            celularField.addEventListener('blur', () => { //'blur' cuando se quita el foco
                if (celularField.value.length !== 9) {
                    showErrorAlert("El campo celular debe tener 9 dígitos.");
                    celularField.value = ''; // Limpia el campo si no tiene 9 digitos.
                }
            })
        }
    }
});