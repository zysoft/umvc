<p>Hint: the password is 'pw'</p>
<form id="validator_form" action="" method="post">
  <p><strong>email</strong><br /><input type="text" name="email" value="<?=htmlentities($uf_request->parameter('email'))?>" /> <span></span></p>
  <p><strong>password</strong><br /><input type="password" name="password" value="" /> <span></span></p>
  <p><input type="submit" name="submit" value="Login" /></p>
</form>

<p>Dummy form</p>
<form action="" method="post">
  <p><strong>email</strong><br /><input type="text" name="email" value="<?=htmlentities($uf_request->parameter('email'))?>" /> <span></span></p>
  <p><strong>password</strong><br /><input type="password" name="password" value="" /> <span></span></p>
  <p><input type="submit" name="submit" value="Login" /></p>
</form>
