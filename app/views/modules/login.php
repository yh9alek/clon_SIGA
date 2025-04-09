<form action="/login" method="POST">

    <img class="form_img" src="<?= $theme == 0 ? '/assets/src/imgs/TMaz_b.png' : '/assets/src/imgs/TMaz_w.png' ?>">
    <h1 class="from_title">SIGA</h1>
    <p>Sistema de Gestión Aduanera</p>

    <div class="form-group" style="margin-bottom: 20px;">
        <label for="login">Usuario</label>
        <input class="form-control" type="text" name="login" required>
    </div>
    <div class="form-group" style="margin-bottom: 20px;">
        <label for="contrasena">Contraseña</label>
        <input class="form-control" type="password" name="contrasena" required>
    </div>

    <button class="btn form-control" type="submit">Acceder</button>
    <div class="notificaciones">
        <?php foreach($notificaciones as $tipo => $notificacion): ?>
            <p class="alert alert-<?= $tipo ?>"><?= $notificacion ?></p>
        <?php endforeach ?>
    </div>

</form>
