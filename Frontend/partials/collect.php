<?php require_once(__DIR__ . "/../lib/user_functions.php") ?>
</style>
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

    // Remove any previous added items inputs
    $(".added-items-input").remove();

    // Add an input element for each item
    $(".added-items .item").each(function() {
      var itemId = $(this).data("item-id");
      $("<input>").attr({
        type: "hidden",
        name: "items[]",
        value: itemId,
        class: "added-items-input"
      }).appendTo(form);
    });

    var data = new FormData(form);

    // Encode the FormData to x-www-form-urlencoded format
    var encodedData = new URLSearchParams(data).toString();
    request.send(encodedData);
  }


  $(document).ready(function() {
    $('#confirm-collection-form').submit(function(e) {
      e.preventDefault();
      send_collect_request();
    });
  });
</script>