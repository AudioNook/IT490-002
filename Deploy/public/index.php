<?php
require(__DIR__ . "/../partials/nav.php");

if (isset($_POST['submit'])) {
  $selectedCluster = $_POST['cluster'];
  $selectedCluster = strtolower($selectedCluster);
  $deploy = new Deployer();
  $deploy->deploy_from($selectedCluster);
}
if (isset($_POST['rollback_version'])) {
  $version_id = $_POST['rollback_version'];
  $deploy = new Deployer();
  $deploy->rollback_all($version_id);
}

if (isset($_POST['rollback_package'])) {
  $package_id = $_POST['rollback_package'];
  $deploy = new Deployer();
  $deploy->rollback_package($package_id);
}


try {
  $db = get_db();
  $query = 'SELECT v.id AS version_id, v.version_date, p.id AS package_id, p.environment, p.package_type, p.package_name
            FROM Versions v
            INNER JOIN Packages p ON v.id = p.version_id
            ORDER BY v.id, p.id';
  $stmt = $db->prepare($query);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $grouped_versions = [];
  foreach ($results as $version) {
    $grouped_versions[$version['version_id']][] = $version;
  }
  var_dump($grouped_versions);
} catch (PDOException $e) {
  error_log("Database error: " . $e->getMessage());
}

/* remove this when we have a database */
$packages = [1 => "AudioNook", 2 => "AudioNook-DB", 3 => "AudioNook-Web", 4 => "AudioNook-Web-UI", 5 => "something", 6 => "something else", 7 => "something else again"];
//$packages = [];
$index = 0;


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
        <div class="container-fluid min-vh-100">

          <div class="row justify-content-center">

            <div class="col-md-10">

              <!-- Deployment Package Header -->
              <div class="container-fluid text-center">
                <div class="card">
                  <h2>Deployment Packages</h2>
                </div>
              </div>


              <!-- package information -->
              <div class="container-fluid overflow-y-scroll">
                <!-- if there are versions -->
                <?php if (count($grouped_versions) > 0 && !empty($grouped_versions)) : ?>
                  <!-- for each version -->
                  <?php foreach ($grouped_versions as $version_id => $packages) : ?>
                    <?php $index++; ?>
                    <br>
                    <div class="row">

                      <div class="col-3">
                        <div class="container">
                          <p class="h2"><?= date("F j, Y", strtotime($packages[0]['version_date'])) ?></p>
                          <p><i class="bi bi-person"></i>Admin</p>
                          <p>AudioNook-<?= strtoupper($packages[0]['environment']) ?></p>
                          <button class="btn btn-sm btn-primary">That Was Easy</button>
                        </div>
                      </div>

                      <div class="col-9">
                        <div class="card">
                          <div class="card-header bg-white">
                          <h3 class="card-title"><?= htmlspecialchars($packages[0]['version_date']) ?></h3>
                            <br>
                            <form method="post" action="">
                              <input type="hidden" name="rollback_version" value="<?= htmlspecialchars($version_id) ?>">
                              <button type="submit" class="btn btn-sm btn-primary">Roll Back All</button>
                            </form>

                            <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                          </div>
                          <div class="card-body">
                            <button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo "packge-" . $index; ?>" aria-expanded="false" aria-controls="collapseExample">
                              <i class="bi bi-caret-down-fill"></i>Assets
                            </button>
                          </div>

                          <div class="container justify-content-center">
                            <div class="collapse" id="<?php echo "packge-" . $index; ?>">
                              <div class="card">
                                <table class="table">
                                  <thead>
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Zip</th>
                                      <th scope="col">Server</th>
                                      <th scope="col">Action</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <!-- add foreach for each package -->
                                    <?php foreach ($packages as $package) : ?>
                                      <tr>
                                        <th scope="row"><?= htmlspecialchars($package['package_id']) ?></th>
                                        <td><?= htmlspecialchars($package['package_name']) ?></td>
                                        <td><?= htmlspecialchars($package['package_type']) ?></td>
                                        <td>
                                          <form method="post" action="">
                                            <input type="hidden" name="rollback_package" value="<?= htmlspecialchars($package['package_id']) ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">Roll Back</button>
                                          </form>
                                      </td>
                                      </tr>
                                    <?php endforeach; ?>
                                    <!-- end foreach -->
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
                  <?php endforeach; ?>
                <?php else : ?>
                  <div class="justify-content-center">
                    <h2>No Packages Deployed Yet</h2>
                  </div>
                <?php endif; ?>
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
<?php
include(__DIR__ . '/../partials/footer.php');
?>

</html>