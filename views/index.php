<?php
/**
 * @var \App\Core\View $this
 */

?>

<div class="container mt-5">
    <form action="<?= $this->router->getUrl('task-store') ?>" method="post" novalidate class="row">
        <?php if($this->session->getFlash('success')): ?>
            <div class="col-lg-12">
                <div class="alert alert-success">Task is added</div>
            </div>
        <?php endif; ?>
        <div class="mb-3 col-lg-6">
            <label for="form-username" class="form-label">Your name</label>
            <input type="text" class="form-control <?= $this->error('username', 'is-invalid') ?>" name="username" id="form-username" value="<?= $this->old('username') ?>">
            <?php if($this->error('username')): ?>
                <span class="invalid-feedback"><?= $this->error('username')?></span>
            <?php endif; ?>
        </div>
        <div class="mb-3 col-lg-6">
            <label for="form-email" class="form-label">E-Mail</label>
            <input type="email" class="form-control <?= $this->error('email', 'is-invalid') ?>" name="email" id="form-email" value="<?= $this->old('email') ?>">
            <?php if($this->error('email')): ?>
                <span class="invalid-feedback"><?= $this->error('email')?></span>
            <?php endif; ?>
        </div>
        <div class="mb-3 col-lg-12">
            <label for="form-text" class="form-label">Message</label>
            <textarea class="form-control <?= $this->error('text', 'is-invalid') ?>" name="text" id="form-text"><?= $this->old('text') ?></textarea>
            <?php if($this->error('text')): ?>
                <span class="invalid-feedback"><?= $this->error('text')?></span>
            <?php endif; ?>
        </div>
        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary">Create a task</button>
        </div>
    </form>

    <form action="<?= $this->router->getUrl('home') ?>" class="row mt-5">
        <div class="mb-3 col-lg-3">
            <label for="form-text" class="form-label">Column</label>
            <select class="form-select" name="order_column" id="form-text">
                <option value="">Not sort</option>
                <option value="username" <?= $orderColumn === 'username' ? 'selected' : '' ?>>Name</option>
                <option value="email" <?= $orderColumn === 'email' ? 'selected' : '' ?>>E-Mail</option>
                <option value="is_completed" <?= $orderColumn === 'is_completed' ? 'selected' : '' ?>>Status</option>
            </select>
        </div>
        <div class="mb-3 col-lg-3">
            <label for="form-text" class="form-label">Sort</label>
            <select class="form-select" name="order_sort" id="form-text">
                <option value="">Not sort</option>
                <option value="asc" <?= $orderSort === 'asc' ? 'selected' : '' ?>>ASC</option>
                <option value="desc" <?= $orderSort === 'desc' ? 'selected' : '' ?>>DESC</option>
            </select>
        </div>
        <div class="mb-3 col-lg-3 d-flex align-items-end">
            <button type="submit" class="btn btn-info">Accept filter</button>
        </div>
    </form>

    <div class="row mt-5">
        <?php if($totalTasks === 0): ?>
            <div class="col-lg-12">
                <div class="alert alert-info">Tasks not found</div>
            </div>
        <?php endif; ?>
        <?php foreach ($tasks as $task): ?>
            <div class="col-lg-4">
                <div class="card mb-2">
                    <div class="card-body">
                        <h5 class="card-title"><?= $this->esc($task->username) ?></h5>
                        <div class="fw-bold mb-2"><?= $this->esc($task->email) ?></div>
                        <div>
                            <?php if(!$this->user): ?>
                                <p class="card-text"><?= $this->esc($task->text) ?></p>
                            <?php else: ?>
                                <form action="<?= $this->router->getUrl('task-update') ?>?task_id=<?= $task->id ?>" method="post">
                                    <textarea name="edited_text"
                                              <?= $this->error('edited_text', 'is-invalid') ?>
                                              class="form-control mb-2"
                                    ><?= $this->esc($task->text) ?></textarea>
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </form>
                                <?php if(!$task->is_completed): ?>
                                    <a href="<?= $this->router->getUrl('task-completed') ?>?task_id=<?= $task->id ?>" class="btn btn-success mt-1">Set completed</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if($task->is_completed): ?>
                                <div class="alert alert-success mt-2">Task is completed</div>
                            <?php else: ?>
                                <div class="alert alert-primary">Task is not completed</div>
                            <?php endif; ?>
                            <?php if($task->is_edited): ?>
                                <div class="alert alert-info mt-2">Admin edited</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>"><a class="page-link" href="<?= $this->router->getUrl('home') ?>?page=<?= $i ?>&order_column=<?= $this->esc($orderColumn) ?>&order_sort=<?= $this->esc($orderSort) ?>"><?= $i ?></a></li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>