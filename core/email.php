<?
// Cleans up and verifies an e-mail (with MX pointer)
// returns '' if it fails
// otherwise a valid e-mail address
function sanitize_email($email)
{
  $s_email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if(!filter_var($s_email, FILTER_VALIDATE_EMAIL)) {
    return '';
  }
  $domain = explode('@',$s_email);
  $domain = $domain[1];
  if (getmxrr($domain, $mxhosts) === true)
  return $s_email;
  return '';
}
