<?php
require(__DIR__ . "/partials/nav.php");
?>

<div class="container-fluid">
    <h1>Register</h1>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" id="username" name="username"/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" type="password" id="password" name="password"/>
            <label class="form-label" for="confirm">Password</label>
            <input class="form-control" type="password" id="confirm" name="confirm"/>

        </div>
        <input type="submit" name="submit" class="mt-3 btn btn-primary" value="register" />
    </form>
</div>