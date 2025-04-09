<?php
include("emergency_conexion.php");
include("../mongo_conexion.php"); // Incluimos el archivo que maneja la conexión a MySQL (mongo_conexion.php)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verifica si la conexión a MySQL fue exitosa
if (!$conexion_exitosa) {
    echo "<script>
        alert('❌ La conexión con MySQL no se ha podido establecer, por favor contacte a soporte.');
        window.history.back();
    </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        $backupId = $_POST['id'];

        // Obtenemos la ruta del respaldo desde la base de datos "respaldo"
        $query = "SELECT ruta FROM respaldos WHERE id = '$backupId'";
        $result = mysqli_query($conexion_respaldo, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // Obtener la ruta del archivo de respaldo
            $backup = mysqli_fetch_assoc($result);
            $filePath = $backup['ruta']; // Ruta completa del archivo de respaldo

            // Depuración: Verificar la ruta
            echo "<pre>DEBUG: filePath = " . htmlspecialchars($filePath) . "</pre>";

            // Verifica si la ruta es válida
            if (!file_exists($filePath)) {
                echo "<script>
                    alert('❌ El archivo no existe en la ruta proporcionada.');
                    window.history.back();
                </script>";
                exit;
            }

            // Define la ruta del ejecutable mysql (para importar) 
            // Nota: Asegúrate de que la ruta sea la correcta en tu servidor.
            $mysqlPath = "/bin/mysql"; // Por ejemplo, /bin/mysql o /usr/bin/mysql, según tu configuración

            // Verifica si mysql existe
            if (!file_exists($mysqlPath)) {
                echo "<script>
                    alert('❌ No se encuentra mysql.exe en la ruta especificada.');
                    window.history.back();
                </script>";
                exit;
            }

            // Conexión a MySQL sin especificar la base de datos (solo servidor)
            $connection = new mysqli($host, $user, $pass);

            // Verifica si hay un error de conexión
            if ($connection->connect_error) {
                echo "<script>
                    alert('❌ Error de conexión: " . $connection->connect_error . "');
                    window.history.back();
                </script>";
                exit;
            }

            // Depuración: Verificar el valor y tipo de $db
            echo "<pre>DEBUG: \$db = " . var_export($db, true) . "</pre>";

            // Verificar si la base de datos existe
            $checkDbQuery = "SHOW DATABASES LIKE '$db'";
            $dbResult = $connection->query($checkDbQuery);

            // Si la base de datos no existe, se crea usando la API de cPanel
            if ($dbResult->num_rows == 0) {

                // Datos de cPanel para la API (ajusta estos valores)
                $cpanelUser  = "desarrollosutvt";       // Usuario de cPanel (sin prefijo para API)
                $cpanelToken = "ARCIYAL0WFSI1APDTB91YPHAFLOTYBJZ"; // API Token generado en cPanel
                $cpanelHost  = "entrevista.desarrollosutvt.com";    // Dominio o IP para acceder a cPanel

                // Nombre de la base de datos a crear (se espera que $db ya incluya el prefijo correcto)
                $dbNameForCpanel = $db;

                // Depuración: Verificar el nombre de la base de datos que se enviará a la API
                echo "<pre>DEBUG: dbNameForCpanel = " . htmlspecialchars($dbNameForCpanel) . "</pre>";

                // Armar la URL para la API de cPanel usando UAPI (el módulo Mysql)
                $url = "https://$cpanelHost:2083/execute/Mysql/create_database?name=" . urlencode($dbNameForCpanel);
                // Depuración: Imprimir la URL de la API
                echo "<pre>DEBUG: URL de API = " . htmlspecialchars($url) . "</pre>";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // Autenticación: se utiliza el API Token
                $headers = [
                    "Authorization: cpanel $cpanelUser:$cpanelToken"
                ];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $apiResult = curl_exec($ch);
                if ($apiResult === false) {
                    echo "cURL Error: " . curl_error($ch);
                    exit;
                }
                curl_close($ch);

                $apiData = json_decode($apiResult, true);

                // Depuración: Imprimir la respuesta completa de la API
                echo "<pre>DEBUG: apiData = " . var_export($apiData, true) . "</pre>";

                if (isset($apiData['status']) && $apiData['status'] == 1) {
                    echo "<script>alert('✅ Base de datos creada correctamente vía API de cPanel.');</script>";

                  // Asigna todos los privilegios al usuario, ajustando el host a 162.240.99.108



                } else {
                    $errorMsg = isset($apiData['errors'][0]) ? $apiData['errors'][0] : 'Error desconocido';
                    echo "<script>alert('❌ Error al crear la base de datos vía API: $errorMsg');</script>";
                    exit;
                }
            } else {
                echo "<script>alert('✅ La base de datos ya existe.');</script>";
            }

            // Cierra la conexión a la base de datos
            $connection->close();

            // Ahora importa el respaldo de la base de datos
            // Depuración: Mostrar comando que se ejecutará
            $command = "$mysqlPath --host=$host --user=$user --password=$pass $db < \"$filePath\"";
            echo "<pre>DEBUG: Comando a ejecutar: " . htmlspecialchars($command) . "</pre>";

            // Usamos exec() para obtener el código de salida en $exitCode
            exec($command, $output, $exitCode);

            if ($exitCode === 0) {
                echo "<script>alert('✅ El archivo se importó correctamente a la base de datos.');</script>";
                include("../../app/Controllers/sessiondestroy_controller.php");
            } else {
                echo "<script>
                    alert('❌ No se pudieron asignar permisos porque el hosting no lo permite.\\nPor favor, comunícate con Sistemas para asignar los privilegios.');
                    window.history.back();
                </script>";
                echo "<pre>Salida del comando: " . print_r($output, true) . "</pre>";
                echo "<pre>Código de resultado: " . $exitCode . "</pre>";
                exit;
            }

        } else {
            echo "<script>
                alert('❌ No se encontró el respaldo en la base de datos.');
                window.history.back();
            </script>";
        }
    }
}
?>
