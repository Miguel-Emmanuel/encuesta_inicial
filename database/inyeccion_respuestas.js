const xlsx = require('xlsx');
const mysql = require('mysql');

// Configurar la conexión a la base de datos
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'inyeccion'
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
const workbook = xlsx.readFile("C:/Users/al222/OneDrive/Documentos/UTVT/CUATRIMESTRE_10/dual/encuesta inicial/db/inyeccion de datos reales/entrevista.xlsx");
const sheet = workbook.Sheets[workbook.SheetNames[0]];
const data = xlsx.utils.sheet_to_json(sheet, { header: 1 }); // Leer todos los datos como un array

// Obtener ID de usuario basado en el email
async function obtenerUsuarioId(email) {
  const query = 'SELECT id FROM usuarios WHERE email = ?';
  const resultados = await ejecutarQuery(query, [email]);
  return resultados.length > 0 ? resultados[0].id : null;
}

// Obtener ID de pregunta basado en el nombre de la pregunta
async function obtenerPreguntaId(nombrePregunta) {
  const query = 'SELECT id FROM preguntas WHERE pregunta = ?';
  const resultados = await ejecutarQuery(query, [nombrePregunta]);
  return resultados.length > 0 ? resultados[0].id : null;
}

// Insertar respuestas en la tabla `respuestas`
async function insertarRespuestas(email, respuestas) {
  const usuarioId = await obtenerUsuarioId(email);
  if (!usuarioId) {
    console.error(`No se encontró el usuario con email: ${email}`);
    return;
  }

  for (const [pregunta, respuesta] of Object.entries(respuestas)) {
    const preguntaId = await obtenerPreguntaId(pregunta);
    if (!preguntaId) {
      console.error(`No se encontró la pregunta con nombre: ${pregunta}`);
      continue;
    }

    const query = `INSERT INTO respuestas (pregunta_id, usuario_id, respuesta, created_at) VALUES (?, ?, ?, ?)`;
    const valores = [preguntaId, usuarioId, respuesta, new Date()];

    try {
      await ejecutarQuery(query, valores);
      console.log(`Respuesta insertada para usuario ${email} en pregunta ${pregunta}`);
    } catch (err) {
      console.error('Error al insertar respuesta:', err);
    }
  }
}

// Función principal para procesar las respuestas
async function procesarRespuestas() {
    try {
      for (let fila = 5; fila < data.length; fila++) { // Ajusta el índice de fila según tu Excel
        const row = data[fila];
        const email = row[1]; // Columna B (índice 1) para el email del usuario
        const respuestas = {};
  
        // Ahora las preguntas se buscan en la fila 1 (índice 0)
        for (let col = 7; col < row.length; col++) {
          const preguntaNombre = data[0][col]; // Fila 1 con nombres de preguntas
          const respuesta = row[col];
          if (preguntaNombre && respuesta) {
            respuestas[preguntaNombre] = respuesta;
          }
        }
  
        // Insertar respuestas para este usuario
        await insertarRespuestas(email, respuestas);
      }
  
      console.log('Todas las respuestas han sido insertadas correctamente');
    } catch (error) {
      console.error('Error al procesar respuestas:', error);
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
  

// Ejecutar la función principal
procesarRespuestas();
