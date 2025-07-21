// registro.js

// ——————————————————————————————————————————————
// 1) Variables y selects del DOM
let clickCount = 0;
const logo                   = document.getElementById('logo');
const registroFormContainer  = document.getElementById('registroFormContainer');
const adminLoginFormContainer= document.getElementById('adminLoginFormContainer');
const registroForm           = document.getElementById('registroForm');
const adminLoginForm         = document.getElementById('adminLoginForm');

// ——————————————————————————————————————————————
// 2) Mostrar el formulario de admin al hacer 3 clics en el logo
logo.addEventListener('click', () => {
  if (++clickCount === 3) {
    registroFormContainer.style.display   = 'none';
    adminLoginFormContainer.style.display = 'block';
  }
});

// ——————————————————————————————————————————————
// 3) Envío del formulario de registro (tu código original intacto)
registroForm.addEventListener('submit', function(event) {
  event.preventDefault();

  // Fecha de registro al día
  document.getElementById('fecha_registro').value =
    new Date().toISOString().split('T')[0];

  const formData = new FormData(this);

  fetch('../php/registrar_usuario.php', {
    method: 'POST',
    body: formData
  })
  .then(res => {
    if (!res.ok) {
      // HTTP 500, 404, etc.
      return res.text().then(txt => { throw new Error(`HTTP ${res.status}\n${txt}`); });
    }
    return res.text();
  })
  .then(txt => {
    // Ver qué llega realmente del servidor
    console.log('>>> RESPUESTA RAW registro:', txt);

    // Intentar parsear a JSON
    let data;
    try {
      data = JSON.parse(txt);
    } catch (e) {
      throw new Error('No es JSON válido: ' + e.message);
    }

    // Tu lógica de éxito/error
    if (data.success) {
      alert('Registro exitoso!');
      window.location.href = 'login.html';
    } else {
      alert('Error en el registro: ' + data.error);
    }
  })
  .catch(err => {
    console.error('Error registro:', err);
    alert('Hubo un error al registrar. Revisa la consola.');
  });
});

// ——————————————————————————————————————————————
// 4) Envío del formulario de login de administrador (rol = 1)
adminLoginForm.addEventListener('submit', function(event) {
  event.preventDefault();

  const correo   = document.getElementById('adminCorreo').value.trim();
  const password = document.getElementById('adminPassword').value;

  if (!correo || !password) {
    alert('Debes ingresar correo y contraseña de administrador.');
    return;
  }

  fetch('../php/login_admin.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ correo, password })
  })
  .then(res => {
    if (!res.ok) {
      return res.text().then(txt => { throw new Error(`HTTP ${res.status}\n${txt}`); });
    }
    return res.text();
  })
  .then(txt => {
    console.log('>>> RAW login_admin:', txt);

    let data;
    try {
      data = JSON.parse(txt);
    } catch (e) {
      throw new Error('Respuesta no es JSON válido: ' + e.message);
    }

    if (data.success) {
      window.location.href = 'admin.html';
    } else {
      alert(data.error || 'Credenciales incorrectas');
    }
  })
  .catch(err => {
    console.error('Error login_admin:', err);
    alert('Hubo un error al iniciar sesión. Revisa la consola.');
  });
});
