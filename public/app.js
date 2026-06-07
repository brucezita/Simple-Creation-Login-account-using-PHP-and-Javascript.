// =============================
// JavaScript for Signup/Login
// =============================
//
// This script:
// 1) Listens to signup/login form submits
// 2) Sends JSON requests using fetch()
// 3) Displays server responses

// Grab the message container (where we show success/error text).
const messageEl = document.getElementById('message');

// Helper function to display a message.
function showMessage(text, isSuccess) {
  // Put the text into the element.
  messageEl.textContent = text;

  // Toggle CSS class for coloring.
  messageEl.className = 'message ' + (isSuccess ? 'success' : 'error');
}

// Helper function to convert a form into a JS object.
function formToObject(form) {
  // Create an empty object to store form values.
  const data = {};

  // Use FormData to easily read input values.
  const formData = new FormData(form);

  // Loop through each key/value in the FormData.
  for (const [key, value] of formData.entries()) {
    data[key] = value;
  }

  // Return the collected data.
  return data;
}

// Signup form submit handler.
const signupForm = document.getElementById('signupForm');
if (signupForm) {
  signupForm.addEventListener('submit', async (event) => {
    // Stop browser from reloading the page.
    event.preventDefault();

    // Convert form fields into an object.
    const payload = formToObject(signupForm);

    try {
      // Send POST request to signup API.
      const res = await fetch('/PHP/api/signup.php', {
        method: 'POST',

        // Tell server we are sending JSON.
        headers: {
          'Content-Type': 'application/json',
        },

        // Convert JS object to JSON string.
        body: JSON.stringify(payload),
      });

      // Parse JSON response.
      const data = await res.json();

      // If request succeeded.
      if (data.ok) {
        showMessage(data.message || 'Signup successful!', true);

        // Optional: clear the signup form.
        signupForm.reset();
      } else {
        // If server returned ok=false.
        showMessage(data.error || 'Signup failed.', false);
      }
    } catch (err) {
      // Network/server error that prevented getting a response.
      showMessage('Server error. Check your PHP/DB setup.', false);
      console.error(err);
    }
  });
}

// Login form submit handler.
const loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', async (event) => {
    // Stop browser from reloading the page.
    event.preventDefault();

    // Convert login form fields into object.
    const payload = formToObject(loginForm);

    try {
      // Send POST request to login API.
      const res = await fetch('/PHP/api/login.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      // Parse JSON response.
      const data = await res.json();

      if (data.ok) {
        // Show success message.
        showMessage(data.message || 'Login successful!', true);

        // Refresh page so PHP can read the session and show logged-in state.
        setTimeout(() => {
          window.location.reload();
        }, 500);
      } else {
        showMessage(data.error || 'Login failed.', false);
      }
    } catch (err) {
      showMessage('Server error. Check your PHP/DB setup.', false);
      console.error(err);
    }
  });
}

