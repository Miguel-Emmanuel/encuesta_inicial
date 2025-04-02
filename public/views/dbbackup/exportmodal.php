<?php
// Conexión a MySQL
include("../../../database/mongo_conexion.php"); // Ahora es MySQL

// Obtener los respaldos desde MySQL
$query = "SELECT id, nombre, ruta, fecha_creacion FROM respaldos ORDER BY fecha_creacion DESC";
$result = mysqli_query($conexion_respaldo, $query);
?>

<!-- Modal -->
<?php while ($backup = mysqli_fetch_assoc($result)): ?>
  <div class="modal fade" id="staticBackdrop_<?php echo $backup['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Descargar Datos del Dump: <br> -<?php echo $backup['nombre'] ?></h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table style="width: 100%;">
            <tr style="text-align:center;">
              <td>
                <form action="../../../database/exportar/exportardb.php" method="post">
                  <input type="hidden" value="<?php echo $backup['id'] ?>" name="id">
                  <input type="hidden" value="data" name="accion">
                  <button type="submit" class="btn btn-success w-75">Exportar Data</button>
                </form>
              </td>
            </tr>
            <tr style="text-align:center;">
              <td>
                <form action="../../../database/exportar/exportardb.php" method="post">
                  <input type="hidden" value="<?php echo $backup['id'] ?>" name="id">
                  <input type="hidden" value="structure" name="accion">
                  <button type="submit" class="btn btn-success w-75">Exportar Structure</button>
                </form>
              </td>
            </tr>
            <tr style="text-align:center;">
              <td>
                <form action="../../../database/exportar/exportardb.php" method="post">
                  <input type="hidden" value="<?php echo $backup['id'] ?>" name="id">
                  <input type="hidden" value="all" name="accion">
                  <button type="submit" class="btn w-75" style="background-color: #1976D2; color:white;">Exportar Dump Completo</button>
                </form>
              </td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
<?php endwhile; ?>
