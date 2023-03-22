<?php require_once(__DIR__ . "/../lib/user/user_functions.php") ?>
<div class="container-fluid">
  <div class="collection-sidebar card">
    <h2>Added Items:</h2>
    <div class="added-items"></div>
    <form method="POST" id="confirm-collection-form">
      <input type="hidden" name="type" value="add_collect">
      <input type="hidden" name="user_id" value="<?php echo (int) htmlspecialchars(get_user_id()) ?>" />
      <input type="submit" value="Confirm Collection" class="btn btn-success" />
    </form>
  </div>
</div>
<script>
  function handle_collect(response) {
    var text = JSON.parse(response);
    if (text.success) {
      window.location.href = 'profile.php';
    } else {
      alert(text.message);
    }
  }

  function send_collect_request() {
    var request = new XMLHttpRequest();
    request.open("POST", "add_collect.php", true);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.onreadystatechange = function() {
      if ((this.readyState == 4) && (this.status == 200)) {
        handle_collect(this.responseText);
      }
    }
    var form = document.getElementById("confirm-collection-form");
    var data = new FormData(form);
    var encoded = new FormData(form);
    request.send(encoded);
  }

  $(document).ready(function() {
    $('#confirm-collection-form').submit(function(e) {
      e.preventDefault();
      send_collect_request();
    });
  });
</script>
<!--
/*$(document).ready(function() {
    $('#confirm-collection-form').submit(function(e) {
      e.preventDefault();
      $.ajax({
        url: '',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
          // Check if the response indicates success
          if (response.success) {
            // Redirect user to confirmation page
            window.location.href = 'confirmation.php';
          } else {
            // Display an error message
            alert('Error processing collection: ' + response.error);
          }
        },
        error: function(xhr, status, error) {
          console.error(error);
          alert('Error processing collection. Please try again later.');
        }
      });
    });
  });*/ -->