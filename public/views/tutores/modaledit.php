<!-- Modal -->
<div class="modal fade" id="editarmodal" tabindex="-1" aria-labelledby="editarmodalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editarmodalLabel">Editar Tutores</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" action="../../../app/Tutores/actualiza.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="id" name="id">
                <div class="col-md-4">
                        <label for="validationDefault01" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="col-md-4">
                        <label for="validationDefault02" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_p" name="apellido_p" required>
                    </div>
                    <div class="col-md-4">
                        <label for="validationDefault02" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_m" name="apellido_m" required>
                    </div>
                    <div class="mb-6">
                        <label for="exampleInputPassword1" class="form-label">Clave_SP</label>
                        <input type="text" class="form-control" id="clave_sp" name="clave_sp">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input type="email" class="form-control" id="correo" name="correo" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-6">
                        <label for="exampleInputEmail1" class="form-label">Telefono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" aria-describedby="emailHelp">
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