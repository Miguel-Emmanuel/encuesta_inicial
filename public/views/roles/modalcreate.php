<!-- Modal -->
<div class="modal fade" id="nuevomodal" tabindex="-1" aria-labelledby="nuevomodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="nuevomodalLabel">Nuevo Rol</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../../../app/Roles/registro_roles.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Nombre del rol:</label>
                        <input class="form-control" name="nombre" id="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Descripción:</label>
                        <input class="form-control" name="descripcion" id="descripcion" required>
                    </div>

                    <div class="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>