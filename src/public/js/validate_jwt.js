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
  