const xlsx = require("xlsx");
const mysql = require("mysql");

// Configurar la conexión a la base de datos
const connection = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "encuesta_inyeccion",
});

// Conectar la base de datos
connection.connect((err) => {
  if (err) {
    console.error("Error al conectar a la base de datos:", err);
    return;
  }
  console.log("✅ Conectado a MySQL");
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
const data = xlsx.utils.sheet_to_json(sheet, { header: 1 }); // Leer todos los datos como un array

// Obtener un mapeo de preguntas para evitar consultas repetidas
async function obtenerMapaPreguntas() {
  const query = "SELECT id, pregunta FROM preguntas";
  const resultados = await ejecutarQuery(query);
  
  const mapa = {};
  resultados.forEach((row) => {
    mapa[row.pregunta.trim().toLowerCase()] = row.id;
  });

  return mapa;
}

// Obtener el ID de estudiante basado en el email
async function obtenerEstudianteId(email) {
  const query = `
    SELECT e.id 
    FROM estudiantes e
    JOIN usuarios u ON e.usuario_id = u.id
    WHERE u.email = ?
  `;
  const resultados = await ejecutarQuery(query, [email.trim()]);
  return resultados.length > 0 ? resultados[0].id : null;
}

// Insertar respuestas en la tabla `respuestas`
async function insertarRespuestas(estudianteId, respuestas, mapaPreguntas) {
  const query = `INSERT INTO respuestas (pregunta_id, estudiante_id, respuesta, created_at) VALUES ?`;
  const valores = [];

  for (const [pregunta, respuesta] of Object.entries(respuestas)) {
    const preguntaId = mapaPreguntas[pregunta.trim().toLowerCase()];
    if (preguntaId) {
      valores.push([preguntaId, estudianteId, respuesta, new Date()]);
    } else {
      console.warn(`⚠️ Advertencia: No se encontró la pregunta "${pregunta}" en la base de datos.`);
    }
  }

  if (valores.length > 0) {
    await ejecutarQuery(query, [valores]);
    console.log(`✅ ${valores.length} respuestas insertadas para el estudiante ${estudianteId}`);
  }
}

// Función principal para procesar las respuestas
async function procesarRespuestas() {
  try {
    const mapaPreguntas = await obtenerMapaPreguntas(); // Obtener preguntas una vez y usarlas después

    for (let fila = 5; fila < data.length; fila++) { // Ajusta el índice de fila según tu Excel
      const row = data[fila];
      const email = row[1]?.trim(); // Columna B (índice 1) para el email del usuario

      if (!email) {
        console.warn(`⚠️ Advertencia: Se encontró una fila sin email en la fila ${fila + 1}. Se omite.`);
        continue;
      }

      const estudianteId = await obtenerEstudianteId(email);
      if (!estudianteId) {
        console.warn(`⚠️ No se encontró estudiante para el email: ${email}. Se omite.`);
        continue;
      }

      const respuestas = {};
      for (let col = 6; col < row.length; col++) {
        const preguntaNombre = data[0][col]?.trim();
        const respuesta = row[col];

        if (preguntaNombre && respuesta) {
          respuestas[preguntaNombre] = respuesta;
        }
      }

      // Insertar respuestas
      await insertarRespuestas(estudianteId, respuestas, mapaPreguntas);
    }

    console.log("🎉 Todas las respuestas han sido insertadas correctamente");
  } catch (error) {
    console.error("❌ Error al procesar respuestas:", error);
  } finally {
    connection.end((err) => {
      if (err) {
        console.error("❌ Error al cerrar la conexión:", err);
      } else {
        console.log("🔌 Conexión cerrada correctamente.");
      }
    });
  }
}

// Ejecutar la función principal
procesarRespuestas();
