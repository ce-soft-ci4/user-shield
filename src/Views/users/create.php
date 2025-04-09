<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('User.create') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

    <div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-12 shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-5"><?= lang('User.create') ?></h5>

        <?php if (session('error') !== null) : ?>
            <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
        <?php elseif (session('errors') !== null) : ?>
            <div class="alert alert-danger" role="alert">
                <?php if (is_array(session('errors'))) : ?>
                    <?php foreach (session('errors') as $error) : ?>
                        <?= $error ?>
                        <br>
                    <?php endforeach ?>
                <?php else : ?>
                    <?= session('errors') ?>
                <?php endif ?>
            </div>
        <?php endif ?>

        <?php if (session('message') !== null) : ?>
            <div class="alert alert-success" role="alert"><?= session('message') ?></div>
        <?php endif ?>

    <form action="<?= site_url('users/create') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="email" class="form-label"><?= lang('User.label_email') ?></label>
            <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" >
        </div>

        <div class="mb-3">
            <label for="username" class="form-label"><?= lang('User.label_name') ?></label>
            <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" >
        </div>

        <div class="mb-3">
            <label for="password" class="form-label"><?= lang('User.label_password') ?></label>
            <input type="password" class="form-control" id="password" name="password" >
        </div>

        <div class="mb-3">
            <label for="password_confirm" class="form-label"><?= lang('User.label_password_confirm') ?></label>
            <input type="password" class="form-control" id="password_confirm" name="password_confirm" >
        </div>

        <div class="mb-3">
            <label class="form-label"><?= lang('User.label_groups') ?></label>
            <?php foreach ($groups as $group=>$detail) : ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="groups[]" value="<?= $group ?>" id="group_<?= $group ?>" <?= old('groups') && in_array($group, old('groups')) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="group_<?= $group ?>">
                        <?= ucfirst($detail['title']) ?>
                    </label>
                </div>
            <?php endforeach ?>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary"><?= lang('User.btn_create') ?></button>
            <a href="<?= site_url('users') ?>" class="btn btn-secondary"><?= lang('User.btn_cancel') ?></a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>