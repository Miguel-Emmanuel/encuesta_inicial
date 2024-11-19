const xlsx = require('xlsx');
const mysql = require('mysql');

// Configurar la conexión a la base de datos
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
 database: 'encuesta_01'
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

(async () => {
  try {
    // Iterar sobre las filas del Excel, comenzando desde la columna G (índice 6)
    for (let i = 6; i < data[0].length; i++) { // Comenzar desde índice 6 (Columna G)
      let pregunta = data[0][i]; // Fila 1: Pregunta
      let tipo = data[3][i] ? data[3][i] : 'texto'; // Fila 4: Tipo de dato, asignar 'texto' si es nulo o vacío
      let seccion_id = data[2][i]; // Fila 3: Sección ID
      let activo = 1; // Todos activos
      let created_at = new Date();
      let updated_at = new Date();

      // Preparar la consulta SQL
      const query = `INSERT INTO preguntas (pregunta, depende_p, tipo, seccion_id, activo, ayuda, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)`;
      
      // Ejecutar la consulta con los valores de cada fila
      await ejecutarQuery(query, [pregunta, '', tipo, seccion_id, activo, '', created_at, updated_at]);
    }
    
    console.log('Datos insertados correctamente');
  } catch (err) {
    console.error('Error al insertar los datos:', err);
  } finally {
    connection.end(); // Cerrar la conexión a la base de datos
  }
})();
