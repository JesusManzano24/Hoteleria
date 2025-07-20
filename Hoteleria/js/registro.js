// Variables
let clickCount = 0; // Contador de clics
const logo = document.getElementById('logo');
const registroFormContainer = document.getElementById('registroFormContainer');
const adminLoginFormContainer = document.getElementById('adminLoginFormContainer');
const adminLoginForm = document.getElementById('adminLoginForm');

// Evento de clic en el logo
logo.addEventListener('click', () => {
  clickCount++;
  if (clickCount === 3) {
    registroFormContainer.style.display = 'none';
    adminLoginFormContainer.style.display = 'block';
  }
});

// Envío del formulario de registro
document.getElementById('registroForm').addEventListener('submit', function(event) {
  event.preventDefault();

  // Establecer la fecha de registro
  document.getElementById('fecha_registro').value =
    new Date().toISOString().split('T')[0];

  const formData = new FormData(this);

  fetch('../php/registrar_usuario.php', {
    method: 'POST',
    body: formData
  })
  .then(response => {
    // Captura errores HTTP
    if (!response.ok) {
      return response.text().then(txt => {
        throw new Error(`HTTP ${response.status} ${response.statusText}\n${txt}`);
      });
    }
    // Leer como texto para depurar cualquier HTML extra o warnings
    return response.text();
  })
  .then(txt => {
    // Mostrar la respuesta cruda del servidor
    console.log('>>> RESPUESTA RAW:', txt);

    // Intentar parsear a JSON
    let data;
    try {
      data = JSON.parse(txt);
    } catch (e) {
      throw new Error('No es JSON válido: ' + e.message);
    }

    // Procesar objeto JSON
    if (data.success) {
      alert('Registro exitoso!');
      window.location.href = 'login.html';
    } else {
      alert('Error en el registro: ' + data.error);
    }
  })
  .catch(error => {
    console.error('Error al enviar los datos:', error);
    alert('Hubo un error al registrar. Revisa la consola para más detalles.');
  });
});

// Evento de inicio de sesión del administrador
adminLoginForm.addEventListener('submit', function(event) {
  event.preventDefault();

  const correo = document.getElementById('adminCorreo').value;
  const password = document.getElementById('adminPassword').value;

  fetch('../php/login_admin.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ correo, password })
  })
  .then(response => {
    if (!response.ok) {
      return response.text().then(txt => {
        throw new Error(`HTTP ${response.status} ${response.statusText}\n${txt}`);
      });
    }
    return response.json();
  })
  .then(data => {
    if (data.success) {
      window.location.href = 'admin.html';
    } else {
      alert('Credenciales incorrectas');
    }
  })
  .catch(error => {
    console.error('Error al verificar las credenciales:', error);
    alert('Hubo un error al iniciar sesión.');
  });
});
