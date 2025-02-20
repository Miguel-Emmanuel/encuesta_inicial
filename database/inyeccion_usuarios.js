const xlsx = require('xlsx');
const mysql = require('mysql');

// Configurar la conexión a la base de datos
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'encuesta_02'
});

// Convertir consultas en promesas
function ejecutarQuery(query, valores) {
  return new Promise((resolve, reject) => {
    connection.query(query, valores, (err, results) => {
      if (err) {
        return reject(err);
      }
      resolve(results);
    });
  });
}

// Cargar archivo Excel
const workbook = xlsx.readFile("C:/Users/al222/OneDrive/Documentos/UTVT/CUATRIMESTRE_10/dual/encuesta inicial/db/inyeccion de datos reales/entrevista1.xlsx");
const sheet = workbook.Sheets[workbook.SheetNames[0]];

// Obtener los datos de las columnas deseadas
function obtenerDatos() {
  const datos = [];
  let fila = 6; // Los datos empiezan desde la fila 6

  while (sheet[`B${fila}`] || sheet[`D${fila}`] || sheet[`E${fila}`] || sheet[`F${fila}`]) {
    const email = sheet[`B${fila}`] ? sheet[`B${fila}`].v : null;
    const nombre = sheet[`D${fila}`] ? sheet[`D${fila}`].v : null;
    const apellidoPaterno = sheet[`E${fila}`] ? sheet[`E${fila}`].v : null;
    const apellidoMaterno = sheet[`F${fila}`] ? sheet[`F${fila}`].v : null;

    if (email) {
      datos.push({ email, nombre, apellido_paterno: apellidoPaterno, apellido_materno: apellidoMaterno });
    }

    fila++;
  }

  return datos;
}

// Obtener el rol_id a partir del nombre del rol
async function obtenerRolId(nombreRol) {
  const query = 'SELECT id FROM roles WHERE nombre = ?';
  const resultados = await ejecutarQuery(query, [nombreRol]);

  if (resultados.length === 0) {
    throw new Error(`No se encontró el rol con nombre: ${nombreRol}`);
  }

  return resultados[0].id;
}

// Insertar en usuarios y luego en estudiantes
async function insertarDatos(datos, nombreRol) {
  const pass = '123456';
  const rolId = await obtenerRolId(nombreRol);

  for (let index = 0; index < datos.length; index++) {
    const { email, nombre, apellido_paterno, apellido_materno } = datos[index];

    console.log(`Fila ${index + 1}: Email extraído: "${email}"`);

    if (!email || email.trim() === '') {
      console.log(`El campo email está vacío en la fila ${index + 1}. Se omite la inserción.`);
      continue;
    }

    const queryUsuario = `INSERT INTO usuarios (email, nombre, apellido_paterno, apellido_materno, pass, rol_id) VALUES (?, ?, ?, ?, ?, ?)`;

    try {const resultadoUsuario = await ejecutarQuery(queryUsuario, [email, nombre, apellido_paterno, apellido_materno, pass, rolId]);
      const usuarioId = resultadoUsuario.insertId; // Obtener el ID del usuario recién insertado
      
      console.log(`Usuario insertado: ${email}, Nombre: ${nombre}, Apellido Paterno: ${apellido_paterno}, ID: ${usuarioId}`);
      
      // Extraer números del email (formato "al"+números+"@gmail.com")
      const match = email.match(/\d+/); // Busca los números en el email
      const matricula = match ? match[0] : null; // Si encuentra números, los usa; si no, deja null
      
      // Insertar en la tabla `estudiantes`
      const queryEstudiante = `INSERT INTO estudiantes (usuario_id, matricula, telefono, genero) VALUES (?, ?, ?, ?)`;
      await ejecutarQuery(queryEstudiante, [usuarioId, matricula, null, null]); // Se usa `matricula` extraída
      
      console.log(`Estudiante insertado con usuario_id: ${usuarioId}, Matricula: ${matricula}`);
      
    } catch (err) {
      if (err.code === 'ER_DUP_ENTRY') {
        let nuevoEmail = `${email.split('@')[0]}-duplicado-${index}@${email.split('@')[1]}`;
        console.log(`Correo duplicado encontrado. Insertando como: ${nuevoEmail}`);

        try {
          const resultadoUsuario = await ejecutarQuery(queryUsuario, [nuevoEmail, nombre, apellido_paterno, apellido_materno, pass, rolId]);
          const usuarioId = resultadoUsuario.insertId;

          await ejecutarQuery(queryEstudiante, [usuarioId, null, null, null]);
          console.log(`Estudiante insertado con usuario_id: ${usuarioId}`);

        } catch (err2) {
          console.error(`Error al reinsertar el correo duplicado: ${nuevoEmail}`, err2);
        }
      } else {
        console.error('Error al insertar datos:', err);
      }
    }
  }
}

// Función principal
async function procesarDatos() {
  try {
    const datos = obtenerDatos();

    if (datos.length === 0) {
      console.log('No se encontraron datos para insertar.');
      return;
    }

    await insertarDatos(datos, 'Estudiante');

  } catch (error) {
    console.error('Error al procesar datos:', error);
  } finally {
    connection.end(err => {
      if (err) {
        console.error('Error al cerrar la conexión:', err);
      } else {
        console.log('Conexión cerrada correctamente.');
      }
    });
  }
}

// Ejecutar la función
procesarDatos();
