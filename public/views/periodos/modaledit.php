<!-- Modal -->
<div class="modal fade" id="editarmodal" tabindex="-1" aria-labelledby="editarmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarmodalLabel">Editar periodo escolar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../../../app/Controllers/Periodos/actualiza.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <div class="col-md-4">
                        <label for="validationDefault01" class="form-label">Alias:</label>
                        <input type="text" class="form-control" id="alias" name="alias" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Inicio del cuatrimestre:</label>
                        <input class="form-control" type="date" name="inicio" id="inicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Fin del cuatrimestre:</label>
                        <input class="form-control" type="date" name="fin" id="fin" required>
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