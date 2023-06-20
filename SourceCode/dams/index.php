
<?php include_once "header.php"; ?>
<style type="text/css">
  .form form .error-text{
  color: green;
  padding: 8px 10px;
  text-align: center;
  border-radius: 5px;
  background: #f8d7da;
  border: 1px solid #f5c6cb;
  margin-bottom: 10px;
  display: none;
}
</style>
<body>
  <div class="wrapper">
    <section class="form login">
      <header>Techno-Art</header>
      <form action="php/login.php" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
        <div class="field input">
          <label>Email Address</label>
          <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter your password" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Login">
        </div>
      </form>
      <div class="link">Not yet signed up? <a href="signup-rev.php">Signup now</a></div>
    </section>
  </div>
  
  <!-- <script src="javascript/pass-show-hide.js"></script> -->
  <!-- <script src="javascript/login-2.js"></script> -->

</body>
</html>
