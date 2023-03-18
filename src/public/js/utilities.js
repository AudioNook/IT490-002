function validate_jwt() {
  console.log("Validating JWT...");
  const token = document.cookie.split(';').find(cookie => cookie.trim().startsWith('jwt=')).split('=')[1];
  
  if (!token) {
    // redirect to login page
    window.location.href = '/src/public/login.php';
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

function isValidUsername(username){
  const reUser = new RegExp('^[a-z0-9_-]{3,16}$');
  return reUser.test(username);
}

function isValidPassword(password){
  const rePass = new RegExp('^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$');
  return rePass.test(password);
}

function validate_register(form){
  let isValid = true;
  let username = form.username.value;
  let password = form.password.value;

  if(!isValidUsername(username)){
      isValid = false;
      display_msg("Invalid Username", "warning");
      
  }
  
  if(!isValidPassword(password)){
      isValid = false;
      display_msg("Invalid Password", "warning");
  }
  return isValid;

}

function validate_login(form){
  let isValid = true;
  let username = form.username.value;
  let password = form.password.value;

  if(!isValidUsername(username)){
      isValid = false;
      display_msg("Invalid Username", "warning");
  }

  if(!isValidPassword(password)){
      isValid = false;
      display_msg("Invalid Password", "warning");
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
