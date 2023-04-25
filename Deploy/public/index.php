<?php
require(__DIR__ . "/../partials/nav.php");

if (isset($_POST['submit'])) {
  $selectedCluster = $_POST['cluster'];
  $message = "The selected cluster is: " . $selectedCluster;
  echo "<script>alert('" . addslashes($message) . "');</script>";
}
?>
<!doctype html>
<html lang="en">

<head>
  <title>AudioNook Deployment</title>
</head>

<body>

  <div class="container-fluid">
    <div class="row justify-content-center">
      <main class="col-md-9">

        <!-- Dashboard Header -->

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

        <!-- Cluster Selection -->

        <div class="card bg-light">
          <div class="card-body">
            <form id="deploySelection" method="POST" action="">
              <div class="row">
                <div class="col-md-auto"><i class="bi bi-arrow-down-up" style="font-size: 2rem;"></i></div>
                <div class="col-sm-2">
                  <input disabled readonly type="text" class="form-control" value="Select Cluster" id="disabledInput" aria-label="cluster">
                </div>
                <div class="col-md-auto">
                  <i class="bi bi-arrow-left" style="font-size: 2rem;"></i>
                </div>
                <div class="col-sm-2">
                  <select class="form-select form-select-sm" name="cluster" id="clusterSelect" aria-label="Default select example">
                    <option selected>Select Cluster</option>
                    <option value="dev">Dev</option>
                    <option value="qa">QA</option>
                  </select>
                </div>
                <div class="col-md-auto">Select a cluster to deploy from</div>
              </div>
            </form>
          </div>
        </div>

        <br>

        <!-- Deployment Information -->

        <div class="alert alert-primary" role="alert">
          <div class="card-body d-flex justify-content-between">
            <div class="text-end">
              This deployment system <strong>does not use git</strong>, but you can check <a href="https://github.com/AudioNook/IT490-002">AudioNook's Github Repo</a> for the latest code.
            </div>
            <div class="text-start">
              <button type="submit" name="submit" form="deploySelection" class="btn btn-primary">Deploy Cluster</button>
            </div>
          </div>
        </div>

        <!-- Deployment Packages -->
        <div class="container-fluid">

          <div class="row justify-content-center">

            <div class="col-md-10">

              <!-- Deployment Package Header -->
              <div class="container-fluid text-center">
                <div class="card">
                  <h2>Deployment Packages</h2>
                </div>
              </div>
              <br>
              <!-- package information -->
              <div class="container-fluid">
                <div class="row">

                  <div class="col-3">
                    <div class="container">
                      <p class="h2">April 24, 2023 </p> 
                      <p><i class="bi bi-person"></i>Carlos</p>
                      <p>AudioNook-Dev</p>
                      <button class="btn btn-primary">That Was Easy</button>
                    </div>
                  </div>

                  <div class="col-9">
                    <div class="card">
                      <div class="card-header bg-white">
                        <h3 class="card-title">2023.04.24</h3>
                        <br>
                        <button class="btn btn-primary">Roll Back All</button>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                      </div>
                      <div class="card-body">
                        <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePackages" aria-expanded="false" aria-controls="collapseExample">
                          <i class="bi bi-caret-down-fill"></i>Assets
                        </button>
                      </div>

                      <div class="container justify-content-center">
                        <div class="collapse" id="collapsePackages">
                          <div class="card">
                            <table class="table">
                              <thead>
                                <tr>
                                  <th scope="col">#</th>
                                  <th scope="col">Zip</th>
                                  <th scope="col">Environment</th>
                                  <th scope="col">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <th scope="row">1</th>
                                  <td>Mark</td>
                                  <td>Otto</td>
                                  <td><button class="btn btn-primary">Roll Back</button></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>

                      <div class="card-body">
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>

      </main>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const disabledInput = document.getElementById("disabledInput");
      const clusterSelect = document.getElementById("clusterSelect");

      clusterSelect.addEventListener("change", function() {
        if (clusterSelect.value === "dev") {
          disabledInput.value = "QA";
        } else if (clusterSelect.value === "qa") {
          disabledInput.value = "Prod";
        } else {
          disabledInput.value = "Select Cluster";
        }
      });
    });
  </script>
</body>

</html>