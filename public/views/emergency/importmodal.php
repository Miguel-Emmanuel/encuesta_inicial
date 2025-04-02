<?php
// Conexión a MySQL de respaldo
include("../../../database/mongo_conexion.php"); 

// Obtener los respaldos desde MySQL
$query = "SELECT id, nombre, ruta, fecha_creacion FROM respaldos ORDER BY fecha_creacion DESC";
$result = mysqli_query($conexion_respaldo, $query);
?>

<!-- Modal -->
<?php while ($backup = mysqli_fetch_assoc($result)): ?>
  <div class="modal fade" id="importBackdrop_<?php echo $backup['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Importar Respaldo del Dump a la Base de Datos</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Advertencia antes de importar -->
          <p class="text-danger">
            <strong>¡Advertencia!</strong> ¿Es este el respaldo que desea cargar?
            Asegúrese de que la fecha de creación y el nombre sean correctos.
          </p>
          
          <table style="width: 100%;">
            <tr style="text-align:center;">
              <td>
                <form action="../../../database/exportar/importar.php" method="post" id="importForm_<?php echo $backup['id']; ?>" onsubmit="return confirmImport('<?php echo $backup['id']; ?>')">
                  <input type="hidden" value="<?php echo $backup['id']; ?>" name="id">
                  <input type="hidden" value="data" name="accion">
                  <button type="submit" class="btn btn-success w-75">Importar <?php echo $backup['nombre']; ?></button>
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

<script>
  // Función para mostrar la confirmación
  function confirmImport(backupId) {
    return confirm("¿Está seguro de que desea importar este respaldo? Esta acción es irreversible.");
  }
</script>
