// Links to the backend API endpoints
const API = "http://localhost/taskmanager/api/";

// Async function to handle user registration
async function register() {
  const username = document.getElementById("registerUsername").value.trim();
  const password = document.getElementById("registerPassword").value;

  // Basic validation
  if (!username || !password) {
    alert("Please fill in all fields.");
    return;
  }

  // Call the registration API endpoint
  const response = await fetch(API + "register.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ username, password }),
  });

  // Handle the response from the server
  const result = await response.json();
  alert(result.message);

  // Clear the registration form if successful
  if (result.success) {
    document.getElementById("registerUsername").value = "";
    document.getElementById("registerPassword").value = "";
  }
}

// Async function to handle user login
async function login() {
  const username = document.getElementById("loginUsername").value.trim();
  const password = document.getElementById("loginPassword").value;

  // Basic validation
  if (!username || !password) {
    alert("Please fill in all fields.");
    return;
  }
  // Call the login API endpoint
  const response = await fetch(API + "login.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ username, password }),
  });

  // Handle the response from the server
  const result = await response.json();
  alert(result.message);

  // If login is successful, store the user ID and show the tasks UI
  if (result.success) {
    localStorage.setItem("user_id", result.user_id);
    document.getElementById("loginUsername").value = "";
    document.getElementById("loginPassword").value = "";

    document.getElementById("loginCard").style.display = "none";
    document.getElementById("registerCard").style.display = "none";
    document.getElementById("tasksCard").style.display = "block";

    loadTasks();
  }
}

// Async function to load tasks for the logged-in user
async function loadTasks() {
  const user_id = localStorage.getItem("user_id");
  const response = await fetch(API + "getTasks.php?user_id=" + user_id);
  const tasks = await response.json();
  const list = document.getElementById("tasks");
  list.innerHTML = "";

  // Check if tasks is an array before iterating
  if (!Array.isArray(tasks)) {
    console.error("Failed to load tasks", tasks);
    return;
  }

  // Create list items for each task and add buttons for toggling completion and deletion
  tasks.forEach((task) => {
    const li = document.createElement("li");
    li.style.display = "flex";
    li.style.alignItems = "center";
    li.style.gap = "8px";

    // Create a span to display the task title with appropriate styling based on completion status
    const span = document.createElement("span");
    span.textContent = task.title;
    span.style.flex = "1";
    span.style.textDecoration = task.completed == 1 ? "line-through" : "none";

    // Create a button to toggle task completion status
    const doneBtn = document.createElement("button");
    doneBtn.textContent = task.completed == 1 ? "Undo" : "Done";
    doneBtn.style.cssText =
      "width:auto;padding:4px 10px;font-size:13px;margin:0";
    doneBtn.onclick = () => toggleTask(task.id, task.completed == 1 ? 0 : 1);

    // Create a button to delete the task
    const delBtn = document.createElement("button");
    delBtn.textContent = "Delete";
    delBtn.style.cssText =
      "width:auto;padding:4px 10px;font-size:13px;margin:0;background:linear-gradient(90deg,#e74c3c,#c0392b)";
    delBtn.onclick = () => deleteTask(task.id);

    // Append the task title and buttons to the list item, then add it to the task list
    li.appendChild(span);
    li.appendChild(doneBtn);
    li.appendChild(delBtn);
    list.appendChild(li);
  });
}

// Async function to add a new task for the logged-in user
async function addTask() {
  const title = document.getElementById("taskInput").value.trim();
  const user_id = parseInt(localStorage.getItem("user_id"));
  if (!title) {
    alert("Task cannot be empty.");
    return;
  }

  // Call the API endpoint to add a new task
  const response = await fetch(API + "addTask.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ title, user_id }),
  });

  // Handle the response from the server and reload tasks if successful
  const result = await response.json();
  if (result.success) {
    document.getElementById("taskInput").value = "";
    loadTasks();
  }
}

// Async function to toggle task completion status
async function toggleTask(id, completed) {
  const user_id = parseInt(localStorage.getItem("user_id"));
  await fetch(API + "updateTask.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, completed, user_id }),
  });
  loadTasks();
}

// Async function to delete a task
async function deleteTask(id) {
  const user_id = parseInt(localStorage.getItem("user_id"));
  await fetch(API + "deleteTask.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, user_id }),
  });
  loadTasks();
}

// Async function to handle user logout
async function logout() {
  await fetch(API + "logout.php", { method: "POST" });
  localStorage.removeItem("user_id");
  location.reload();
}

// Check for existing user session on page load and display appropriate UI
window.addEventListener("DOMContentLoaded", () => {
  if (localStorage.getItem("user_id")) {
    document.getElementById("loginCard").style.display = "none";
    document.getElementById("registerCard").style.display = "none";
    document.getElementById("tasksCard").style.display = "block";
    loadTasks();
  }
});
