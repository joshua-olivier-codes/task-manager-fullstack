// api.js - Centralized API calls and user session management
const API_BASE_URL = "http://localhost/TASKMANAGER/api/";

// Generic function to call API endpoints
async function apiCall(endpoint, data = {}) {
  const response = await fetch(API_BASE_URL + endpoint, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  });

  return await response.json();
}

// User session management
function getUserId() {
  return localStorage.getItem("user_id");
}

// Set user ID in localStorage after successful login
function setUserId(id) {
  localStorage.setItem("user_id", id);
}

// Clear user session on logout
function logoutUser() {
  localStorage.removeItem("user_id");
  location.reload();
}