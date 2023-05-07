<?php require_once(__DIR__ . "/../lib/functions.php") ?>
</style>
<div class="container-fluid">
  <div class="collection-sidebar card">
    <h2>Added Items:</h2>
    <div class="added-items"></div>
    <form method="POST" id="confirm-collection-form">
      <input type="hidden" name="type" value="add_collect">
      <input type="hidden" name="user_id" value="<?php echo (int) htmlspecialchars(get_user_id()) ?>" />
      <button type="submit" value="Confirm Collection" class="btn btn-outline-dark">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-heart" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5v-.5Zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0ZM14 14V5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1ZM8 7.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z" />
        </svg>
        Add To Collection
      </button>
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