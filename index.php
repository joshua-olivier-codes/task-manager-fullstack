<!doctype html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Task Manager</title>
  <link rel="stylesheet" href="style.css?v=2">
</head>

<!-- Main HTML structure for the Task Manager application -->

<body>
  <div class="container">
    <h1>Task Manager</h1>
    <!-- Login -->
    <div class="card" id="loginCard">
      <h2>Login</h2>
      <input id="loginUsername" placeholder="Username" />
      <input id="loginPassword" type="password" placeholder="Password" />
      <button onclick="login()">Login</button>
    </div>

    <!-- Registration -->
    <div class="card" id="registerCard">
      <h2>Create Account</h2>
      <input id="registerUsername" placeholder="Username" />
      <input id="registerPassword" type="password" placeholder="Password" />
      <button onclick="register()">Register</button>
    </div>

    <!-- Tasks -->
    <div class="card" id="tasksCard" style="display: none">
      <h2>Your Tasks</h2>
      <input id="taskInput" placeholder="New task" />
      <button onclick="addTask()">Add Task</button>
      <ul id="tasks"></ul>
      <!-- Logout button -->
      <button onclick="logout()" style="
            margin-top: 20px;
            background: linear-gradient(90deg, #555, #333);
          ">
        Logout
      </button>
    </div>
  </div>

  <script src="script.js"></script>
</body>

</html>