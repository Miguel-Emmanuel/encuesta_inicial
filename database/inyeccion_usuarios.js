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
const workbook = xlsx.readFile(
  "C:/Users/al222/OneDrive/Documentos/UTVT/CUATRIMESTRE_10/dual/encuesta inicial/db/inyeccion de datos reales/entrevista1.xlsx"
);
const sheet = workbook.Sheets[workbook.SheetNames[0]];

// Obtener los datos de las columnas deseadas
function obtenerDatos() {
  const datos = [];
  let fila = 6; // Los datos empiezan desde la fila 6

  // Leer mientras existan datos en al menos una de las columnas relevantes
  while (
    sheet[`B${fila}`] || // email
    sheet[`D${fila}`] || // nombre
    sheet[`E${fila}`] || // apellido paterno
    sheet[`F${fila}`] || // apellido materno
    sheet[`I${fila}`] || // género
    sheet[`T${fila}`]    // teléfono
  ) {
    const email = sheet[`B${fila}`] ? sheet[`B${fila}`].v : null;
    const nombre = sheet[`D${fila}`] ? sheet[`D${fila}`].v : null;
    const apellidoPaterno = sheet[`E${fila}`] ? sheet[`E${fila}`].v : null;
    const apellidoMaterno = sheet[`F${fila}`] ? sheet[`F${fila}`].v : null;

    // Leer género (columna I)
    let generoTexto = sheet[`I${fila}`] ? sheet[`I${fila}`].v : null;
    // Convertir a minúsculas para comparar fácilmente
    generoTexto = generoTexto ? generoTexto.toString().toLowerCase() : '';
    let genero = 1; // Por defecto "Masculino" => 1
    if (generoTexto === 'femenino') {
      genero = 2;
    } 
    // (Si no es "femenino", se queda en 1 = "masculino")

    // Leer teléfono (columna T) y limpiar
    let telefonoRaw = sheet[`T${fila}`] ? sheet[`T${fila}`].v : '';
    // Convertir a string y eliminar todo lo que no sean dígitos
    let telefono = telefonoRaw.toString().replace(/\D/g, '');
    // Si quedó vacío, poner "0000000"
    if (!telefono) {
      telefono = '0000000';
    }

    // Guardar objeto de datos si el email no está vacío
    if (email && email.trim() !== '') {
      datos.push({
        email,
        nombre,
        apellido_paterno: apellidoPaterno,
        apellido_materno: apellidoMaterno,
        genero,
        telefono
      });
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
  // Contraseña encriptada
  const pass = '$2y$10$aER9aGyQDx3kDNS8I8tUseDYXSRTMB6eiGZ6XwjJH768ur7Uczj2C';
  const rolId = await obtenerRolId(nombreRol);

  for (let index = 0; index < datos.length; index++) {
    const {
      email,
      nombre,
      apellido_paterno,
      apellido_materno,
      genero,
      telefono
    } = datos[index];

    console.log(`Fila ${index + 1}: Email extraído: "${email}"`);

    if (!email || email.trim() === '') {
      console.log(
        `El campo email está vacío en la fila ${index + 1}. Se omite la inserción.`
      );
      continue;
    }

    const queryUsuario = `INSERT INTO usuarios (email, nombre, apellido_paterno, apellido_materno, pass, rol_id) 
                          VALUES (?, ?, ?, ?, ?, ?)`;

    try {
      // Insertar en 'usuarios'
      const resultadoUsuario = await ejecutarQuery(queryUsuario, [
        email,
        nombre,
        apellido_paterno,
        apellido_materno,
        pass,
        rolId
      ]);
      const usuarioId = resultadoUsuario.insertId;

      console.log(
        `Usuario insertado: ${email}, Nombre: ${nombre}, Apellido Paterno: ${apellido_paterno}, ID: ${usuarioId}`
      );

      // Extraer números del email para la matrícula
      const match = email.match(/\d+/);
      const matricula = match ? match[0] : null;

      // Insertar en la tabla 'estudiantes'
      const queryEstudiante = `INSERT INTO estudiantes (usuario_id, matricula, telefono, genero) VALUES (?, ?, ?, ?)`;
      await ejecutarQuery(queryEstudiante, [
        usuarioId,
        matricula,
        telefono,
        genero
      ]);

      console.log(
        `Estudiante insertado con usuario_id: ${usuarioId}, Matricula: ${matricula}, Teléfono: ${telefono}, Género: ${genero}`
      );
    } catch (err) {
      if (err.code === 'ER_DUP_ENTRY') {
        let nuevoEmail = `${
          email.split('@')[0]
        }-duplicado-${index}@${email.split('@')[1]}`;
        console.log(
          `Correo duplicado encontrado. Insertando como: ${nuevoEmail}`
        );

        try {
          // Insertar usuario con nuevo correo
          const resultadoUsuario = await ejecutarQuery(queryUsuario, [
            nuevoEmail,
            nombre,
            apellido_paterno,
            apellido_materno,
            pass,
            rolId
          ]);
          const usuarioId = resultadoUsuario.insertId;

          // Insertar estudiante con el nuevo usuario
          const queryEstudiante = `INSERT INTO estudiantes (usuario_id, matricula, telefono, genero) VALUES (?, ?, ?, ?)`;
          await ejecutarQuery(queryEstudiante, [usuarioId, null, telefono, genero]);
          console.log(
            `Estudiante insertado con usuario_id: ${usuarioId}, Teléfono: ${telefono}, Género: ${genero}`
          );
        } catch (err2) {
          console.error(
            `Error al reinsertar el correo duplicado: ${nuevoEmail}`,
            err2
          );
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
