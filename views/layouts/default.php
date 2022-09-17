<?php
/**
 * @var $this \App\Core\View
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="<?= $this->request->getBaseUrl('/favicon.ico') ?>">
    <title><?= $this->title ?? 'Todo List' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid justify-content-between">
        <a class="navbar-brand" href="<?= $this->router->getUrl('home') ?>">Todo List</a>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="<?= $this->router->getUrl('home') ?>">Main
                        page</a>
                </li>
                <?php if($this->user): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $this->router->getUrl('logout') ?>">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $this->router->getUrl('login') ?>">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

@content

</body>
</html>