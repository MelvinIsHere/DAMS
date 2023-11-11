<?php include_once "header.php"; ?>
<body>
  <div class="wrapper">
    <section class="form signup">
      <header>Techno-Art</header>
      <form action="#" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="error-text"></div>
       
        <div class="field input">
          <label>Email Address</label>
          <input type="text" name="email" placeholder="Enter your email" required>
        </div>
        <div class="field input">
          <label>Password</label>
          <input type="password" name="password" placeholder="Enter new password" required>
          <i class="fas fa-eye"></i>
        </div>
        <div class="input">
          <label for="type">Type of account:</label>

              <select name="type" id="type" class="field input">
                <option value="Dean">Dean</option>
                <option value="Heads">Head</option>
                <option value="Staff">Staffs</option>

                
                
              </select>
        </div>
         <div class="field input">
          <label>Department Name</label>
          <input type="text" name="department" placeholder="Enter Department" required>
        </div>
         <div class="field input">
          <label>Department abbrevation</label>
          <input type="text" name="abbv" placeholder="Enter abbrevation" required>
        </div>
        <div class="field image">
          <label>Select Image</label>
          <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Signup">
        </div>
      </form>
      
    </section>
  </div>

  <script src="javascript/pass-show-hide.js"></script>
  <script src="javascript/signup.js"></script>

</body>
</html>
