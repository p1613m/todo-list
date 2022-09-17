<?php
/**
 * @var \App\Core\View $this
 */
$this->title = 'Login';
?>

<div class="container mt-5">
    <form action="<?= $this->router->getUrl('login-send') ?>" method="post" novalidate class="row">
        <div class="mb-3 col-lg-12">
            <label for="from-login" class="form-label">Login</label>
            <input type="text" class="form-control <?= $this->error('login', 'is-invalid') ?>" name="login" id="form-login" value="<?= $this->old('login') ?>">
            <?php if($this->error('login')): ?>
                <span class="invalid-feedback"><?= $this->error('login')?></span>
            <?php endif; ?>
        </div>
        <div class="mb-3 col-lg-12">
            <label for="form-email" class="form-label">Password</label>
            <input type="password" class="form-control <?= $this->error('password', 'is-invalid') ?>" name="password" id="form-login">
            <?php if($this->error('password')): ?>
                <span class="invalid-feedback"><?= $this->error('password')?></span>
            <?php endif; ?>
        </div>
        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
</div>