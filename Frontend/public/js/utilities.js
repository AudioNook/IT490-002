function validate_jwt() {
  console.log("Validating JWT...");
  const token = document.cookie.split(';').find(cookie => cookie.trim().startsWith('jwt=')).split('=')[1];

  if (!token) {
    // redirect to login page
    window.location.href = '/src/public/login.php';
    console.log("Bad little cookie");
    return;
  }

  // Decode the token and get the expiration time
  const decoded = JSON.parse(atob(token.split('.')[1]));
  const exp = decoded.exp * 1000; // convert seconds to milliseconds

  // Check if the token is expired
  if (Date.now() >= exp) {
    // redirect to login page
    window.location.href = '/login.php';
    return;
  }

  // Token is valid, do nothing
  console.log("Token is all good");
}

function isValidUsername(username) {
  const reUser = new RegExp('^[a-z0-9_-]{3,30}$');
  return reUser.test(username);
}

function isValidPassword(password) {
  const rePass = new RegExp('^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}$');
  return rePass.test(password);
}

function isValidEmail(email) {
  const reEmail = new RegExp('^.+@.+\..+$');
  return reEmail.test(email);
}


function validate_register(form) {
  let isValid = true;
  let username = form.username.value;
  let password = form.password.value;
  let confirm = form.confirm.value;
  let email = form.email.value;

  if (!email || !username || !password || !confirm) {
    display_msg('Please fill in all required fields.', 'warning');
    return false;
  }

  if (!isValidUsername(username)) {
    isValid = false;
    display_msg("Invalid Username: \n Minimum four characters, at least one letter and one number.", "warning");
  }

  if (confirm != password) {
    isValid = false;
    display_msg("Passwords do not match");
  } else {
    if (!isValidPassword(password)) {
      isValid = false;
      display_msg("Invalid Password: \n Minimum eight characters, at least one letter, one number and one special character", "warning");
    }
  }

  if (!isValidEmail(email)) {
    isValid = false;
    display_msg("Invalid Email", "warning");
  }
  return isValid;
}
function validate_login(form) {
  let isValid = true;
  let username = form.username.value;
  let password = form.password.value;

  if (!username || !password) {
    display_msg('Please fill in all required fields.', 'warning');
  }
  return isValid;
}

const classes = {
  'success': 'alert-success',
  'info': 'alert-info',
  'warning': 'alert-warning',
  'danger': 'alert-danger'
};

function display_msg(message, type = 'info') {
  const alert_msg = document.getElementById('alert_msg');
  const innerDiv = document.createElement('div');

  innerDiv.className = `alert ${classes[type]}`;
  innerDiv.innerText = message;

  alert_msg.appendChild(innerDiv);
  clear_msg();
}

let msg_timeout = null;

function clear_msgs() {
  const alert_msg = document.getElementById('alert_msg');
  if (!msg_timeout && alert_msg) {
    msg_timeout = setTimeout(() => {
      console.log('Removing message');
      if (alert_msg.children.length > 0) {
        alert_msg.children[0].remove();
      }
      msg_timeout = null;
      if (alert_msg.children.length > 0) {
        clear_msgs();
      }
    }, 3000);
  }
}

window.addEventListener('load', () => setTimeout(clear_msgs, 100));

let collected_items = [];

function add_items(form, event) {
  event.preventDefault();

  const release_id = form.release_id.value;
  const title = form.title.value;
  const cover_image = form.cover_image.value;
  const format = form.format.value;
  const genres = form.genres.value;

  const item = {
    release_id: release_id,
    title: title,
    cover_image: cover_image,
    format: format,
    genre: genres,
  };
  collected_items.push(item);

  // Add hidden input fields to the form that submits to collect.php
  const collectionForm = document.getElementById("confirm-collection-form");
  for (const [key, value] of Object.entries(item)) {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = `items[${collected_items.length - 1}][${key}]`;
    input.value = value;
    collectionForm.appendChild(input);
  }

  const newItem = `
    <p>
      <img src="${cover_image}" alt="Album cover" width="50" height="50" />
      ${title}
    </p>
  `;

  const addedItemsDiv = document.querySelector(".added-items");
  addedItemsDiv.insertAdjacentHTML("beforeend", newItem);
}
