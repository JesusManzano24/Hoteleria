// Variables
let clickCount = 0; // Contador de clics
const logo = document.getElementById('logo');
const registroFormContainer = document.getElementById('registroFormContainer');
const adminLoginFormContainer = document.getElementById('adminLoginFormContainer');
const adminLoginForm = document.getElementById('adminLoginForm');

// Evento de clic en el logo
logo.addEventListener('click', function() {
    clickCount++;

    if (clickCount === 3) {
        // Al tercer clic, mostrar el formulario de login de admin
        registroFormContainer.style.display = 'none';
        adminLoginFormContainer.style.display = 'block';
    }
});

// Establecer la fecha actual en el campo de fecha_registro antes de enviar el formulario
document.getElementById('registroForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío por defecto del formulario

    // Establecer la fecha de registro antes de enviar
    const fechaRegistro = document.getElementById('fecha_registro');
    const fechaActual = new Date().toISOString().split('T')[0]; // Formato YYYY-MM-DD
    fechaRegistro.value = fechaActual;

    const formData = new FormData(this); // Obtiene todos los datos del formulario

    // Hacer la solicitud al archivo PHP
    fetch('../php/registrar_usuario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())  // Aseguramos que la respuesta sea JSON
    .then(data => {
        console.log(data); // Para ver qué se recibe desde el servidor
        if (data.success) {
            alert("Registro exitoso!");
            window.location.href = 'login.html'; // Redirigir al login después del registro
        } else {
            alert("Error en el registro: " + data.error);
        }
    })
    .catch(error => {
        console.error('Error al enviar los datos:', error);
        alert('Hubo un error al registrar. Intenta de nuevo.');
    });
});

// Evento de inicio de sesión del administrador
adminLoginForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar el envío normal del formulario

    // Obtener las credenciales del formulario de login
    const correo = document.getElementById('adminCorreo').value;
    const password = document.getElementById('adminPassword').value;

    // Hacer la solicitud al archivo PHP para validar las credenciales del administrador
    fetch('../php/login_admin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ correo: correo, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Si el login es exitoso, redirigir al admin.html
            window.location.href = 'admin.html';
        } else {
            alert("Credenciales incorrectas");
        }
    })
    .catch(error => {
        console.error('Error al verificar las credenciales:', error);
        alert('Hubo un error al iniciar sesión.');
    });
});
