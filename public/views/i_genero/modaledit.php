<!-- Modal -->
<div class="modal fade" id="editarmodal" tabindex="-1" aria-labelledby="editarmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarmodalLabel">Editar Grupos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Controllers/I_genero/actualiza.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id">
                <div class="md-3">
                        <label for="validationDefault01" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombreig" name="nombreig" required>
                    </div>
                    <div class="md-3">
                        <label for="validationDefault02" class="form-label">Descripción:</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required>
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