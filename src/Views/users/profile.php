<?= $this->extend('Views\layout') ?>

<?= $this->section('title') ?>
<?= lang('User.profile') ?>
<?= $this->endSection() ?>

<?= $this->section('h1') ?>
<?= lang('User.profile') ?>
<?= $this->endSection() ?>

<?= $this->section('subtitle') ?>
<div class="">
    <a href="<?= site_url('users') ?>" class="btn btn-primary"><?= lang('User.btn_return_users') ?></a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-header">
            <h5>Informations de base</h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nom d'utilisateur</dt>
                <dd class="col-sm-9"><?= esc($user->username) ?></dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9"><?= esc($user->email) ?></dd>

                <dt class="col-sm-3">Groupes</dt>
                <dd class="col-sm-9"><?php
                    foreach ($groups as $group=>$detail) {
                        if ($user->inGroup($group)) {
                            echo '<span class="badge bg-primary">' . esc($detail['title']) . '</span> ';
                        }
                    }
                    ?></dd>

                <dt class="col-sm-3">Compte créé le</dt>
                <dd class="col-sm-9"><?= $user->created_at ?></dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Mettre à jour mon profil</h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('profile/update') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user->email) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user->username) ?>" required>
                </div>

                <hr>
                <h5>Changer mon mot de passe</h5>

                <div class="mb-3">
                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="password_confirm" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Mettre à jour mon profil</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
