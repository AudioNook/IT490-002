<?php
require __DIR__ . '/../partials/nav.php';

// Check if user is logged in
logged_in(true);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Profile</title>
  </head>
  <body>
    <h1>Welcome to your Profile!</h1>
    <p>Here you can view and edit your account information.</p>

    <div class="container">
  <div class="main-body">
    <div class="row gutters-sm">
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-column align-items-center text-center">
              <img src="https://w7.pngwing.com/pngs/765/504/png-transparent-question-mark-sign-question-mark-white-computer-icons-question-mark-miscellaneous-white-text-thumbnail.png" alt="Admin" class="square" width="150">
              <div class="mt-3">
                <h4>Full Name</h4>
                <p class="text-secondary mb-1">Full Stack Developer</p>
                <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p>
                <button class="btn btn-primary">Follow</button>
                <button class="btn btn-outline-primary">Message</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Full Name</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                John Doe
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Email</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                john@doe.com
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <h6 class="mb-0">Username</h6>
              </div>
              <div class="col-sm-9 text-secondary">
                j-doe
              </div>
            </div>
            <hr>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
