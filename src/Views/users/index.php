<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('User.list') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="container d-flex justify-content-center p-5">
    <div class="card col-12 col-md-12 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-5"><?= lang('User.list') ?></h5>

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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="<?= site_url('users/new') ?>" class="btn btn-primary"><?= lang('User.create') ?></a>
            </div>

            <div class="container mt-4">
                <div class="table-responsive">
    <table class="table table-striped ">
        <thead>
        <tr>
            <th><?= lang('User.label_id') ?></th>
            <th><?= lang('User.label_name') ?></th>
            <th><?= lang('User.label_email') ?></th>
            <th><?= lang('User.label_groups') ?></th>
            <th><?= lang('User.label_status') ?></th>
            <th><?= lang('User.label_actions') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= esc($user->username) ?></td>
                <td><?= esc($user->email) ?></td>
                <td>
                    <?php
                    foreach ($groups as $group=>$detail) {
                        if ($user->inGroup($group)) {
                            echo '<span class="badge bg-primary">' . esc($detail['title']) . '</span> ';
                        }
                    }
                    ?>
                </td>
                <td>
                    <?php if ($user->active) : ?>
                        <span class="badge bg-success"><?= lang('User.label_active') ?></span>
                    <?php else : ?>
                        <span class="badge bg-danger"><?= lang('User.label_inactive') ?></span>
                    <?php endif ?>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="<?= site_url('users/edit/' . $user->id) ?>" class="btn btn-sm btn-primary"><?= lang('User.btn_edit') ?></a>

                        <?php if ($user->id != auth()->id()) : ?>
                            <?php if ($user->active) : ?>
                                <a href="<?= site_url('users/disable/' . $user->id); ?>" class="btn btn-sm btn-warning" onclick="return confirm('<?= lang('User.confirm_disable'); ?>')"><?= lang('User.btn_inactive'); ?></a>
                            <?php else : ?>
                                <a href="<?= site_url('users/enable/' . $user->id); ?>" class="btn btn-sm btn-success"><?= lang('User.btn_active'); ?></a>
                            <?php endif ?>

                            <a href="<?= site_url('users/delete/' . $user->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?= lang('User.confirm_delete'); ?>')"><?= lang('User.btn_delete'); ?></a>
                        <?php endif ?>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
                </div>
</div>
<?= $this->endSection() ?>
