<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>
<?= lang('User.profile') ?>
<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-header">
            <h5><?= lang('User.label_title_infos') ?></h5>
        </div>
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3"><?= lang('User.label_name') ?></dt>
                <dd class="col-sm-9"><?= esc($user->username) ?></dd>

                <dt class="col-sm-3"><?= lang('User.label_email') ?></dt>
                <dd class="col-sm-9"><?= esc($user->email) ?></dd>

                <dt class="col-sm-3"><?= lang('User.label_groups') ?></dt>
                <dd class="col-sm-9"><?php
                    foreach ($groups as $group=>$detail) {
                        if ($user->inGroup($group)) {
                            echo '<span class="badge bg-primary">' . esc($detail['title']) . '</span> ';
                        }
                    }
                    ?></dd>

                <dt class="col-sm-3"><?= lang('User.label_created_at') ?></dt>
                <dd class="col-sm-9"><?= $user->created_at ?></dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5><?= lang('User.label_title_update_profile') ?></h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('profile/update') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="email" class="form-label"><?= lang('User.label_email') ?></label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email', $user->email) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label"><?= lang('User.label_name') ?></label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= old('username', $user->username) ?>" required>
                </div>

                <hr>
                <h5><?= lang('User.label_title_update_pwd') ?></h5>

                <div class="mb-3">
                    <label for="current_password" class="form-label"><?= lang('User.label_password_current') ?></label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label"><?= lang('User.label_password_new') ?></label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <div class="mb-3">
                    <label for="password_confirm" class="form-label"><?= lang('User.label_password_new_confirm') ?></label>
                    <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary"><?= lang('User.btn_update_profile') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
