<?php
require(__DIR__ . "/../partials/nav.php");
$button = '<button type="button" class="btn btn-primary">Deploy Cluster</button>';
$dButton = '<button disabled type="button" class="btn btn-danger">Deploy Cluster</button>';
$deploy_msg = 'Select a cluster to deploy to.';
?>
<!doctype html>
<html lang="en">

<head>
  <title>AudioNook Deployment</title>
  <!-- Custom styles for this template -->
</head>

<body>

  <div class="container-fluid">
    <main class="col-md-9 col-lg-10 px-md-4 mx-auto text-center">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
          </div>
          <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            This week
          </button>
        </div>
      </div>
      <div class="card bg-light">
        <div class="card-body">
          <div class="row">
            <div class="col-md-auto"><i class="bi bi-arrow-down-up" style="font-size: 2rem;"></i></div>
            <div class="col-sm-4">
              <select class="form-select form-select-sm" aria-label="Default select example">
                <option selected>Select Cluster</option>
                <option value="1">Dev</option>
                <option value="2">QA</option>
                <option value="3">Prod</option>
              </select>
            </div>
            <div class="col-md-auto">
              <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
            </div>
            <div class="col-sm-4">
              <select class="form-select form-select-sm" aria-label="Default select example">
                <option selected>Select Cluster</option>
                <option value="1">Dev</option>
                <option value="2">QA</option>
                <option value="3">Prod</option>
              </select>
            </div>
            <div class="col-md-auto"><?php echo $deploy_msg; ?></div>
          </div>
        </div>
      </div>
      <br>
      <div class="alert alert-primary" role="alert">
        <div class="card-body d-flex justify-content-between">
          <div class="text-end">
            This deployment system <strong>does not use git</strong>, but you can check <a href="https://github.com/AudioNook/IT490-002">AudioNook's Github Repo<a> for the latest code.
          </div>
          <div class="text-start">
            <?php echo $button ?>
          </div>
        </div>
      </div>
      <br>
      <div class="card">
        <h2>Deployment Packages</h2>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Environment</th>
              <th scope="col">Version</th>
              <th scope="col">Name</th>
              <th scope="col">Time</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1,001</td>
              <td>random</td>
              <td>data</td>
              <td>placeholder</td>
              <td>text</td>
            </tr>
            <tr>
              <td>1,015</td>
              <td>random</td>
              <td>tabular</td>
              <td>information</td>
              <td>text</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
  </div>
</body>

</html>