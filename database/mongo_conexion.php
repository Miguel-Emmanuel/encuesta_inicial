    <?php
    // ESTE DOCUMENTO YA NO ES DE MONGODB, ES DE MYSQL, PERO ME DIO FLOJERA CAMBIAR LOS NOMBRES EN TODOS LOS ARCHIVOS
    /* $autoloadPath = __DIR__ . '../../vendor/autoload.php';

    require $autoloadPath; // Cargar las dependencias de Composer

    use MongoDB\Client;

    // Crear conexión con MongoDB
    $mongoClient = new Client("mongodb://localhost:27017"); // Ajusta la conexión si es necesario
    $mongoDB = $mongoClient->encuesta; // Base de datos
    $collection = $mongoDB->respaldos; // Colección */

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "respaldo";

    $conexion_respaldo = null;

    try {
        $conexion_respaldo = mysqli_connect($host, $user, $password, $database);
    } catch (mysqli_sql_exception $e) {
        // No hagas un die o exit aquí, simplemente deja $conexion como null
    }
    ?>


