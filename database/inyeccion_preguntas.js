const xlsx = require("xlsx");
const mysql = require("mysql");

// Configurar la conexión a la base de datos
const connection = mysql.createConnection({
  host: "localhost",
  user: "root",
  password: "",
  database: "encuesta_02",
});

// Conectar la base de datos
connection.connect((err) => {
  if (err) {
    console.error("Error al conectar a la base de datos:", err);
    return;
  }
  console.log("Conectado a la base de datos MySQL");
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

(async () => {
  try {
    // Iterar sobre las columnas desde la columna G (índice 6)
    for (let i = 6; i < data[0].length; i++) {
      let pregunta = data[0][i]; // Fila 1: Pregunta
      let tipo = data[3][i] ? data[3][i] : "texto"; // Fila 4: Tipo de dato, asignar "texto" si está vacío
      let seccion_id = data[2][i] ? data[2][i] : null; // Fila 3: Sección ID, validar si es null
      let activo = 1; // Todos activos
      let created_at = new Date().toISOString().slice(0, 19).replace("T", " ");
      let updated_at = created_at;

      // Validar si la pregunta está vacía
      if (!pregunta) {
        console.warn(`⚠️ Advertencia: La pregunta en la columna ${i} está vacía. Se omitirá.`);
        continue;
      }

      // Preparar la consulta SQL
      const query = `INSERT INTO preguntas (pregunta, depende_p, tipo, seccion_id, activo, ayuda, created_at, updated_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)`;

      try {
        // Ejecutar la consulta con los valores de cada fila
        await ejecutarQuery(query, [pregunta, "", tipo, seccion_id, activo, "", created_at, updated_at]);
        console.log(`✅ Pregunta insertada correctamente: ${pregunta}`);
      } catch (err) {
        console.error(`❌ Error al insertar la pregunta "${pregunta}":`, err.sqlMessage);
      }
    }

    console.log("🎉 Datos insertados correctamente");
  } catch (err) {
    console.error("❌ Error general al insertar los datos:", err);
  } finally {
    connection.end(); // Cerrar la conexión a la base de datos
  }
})();
