function validateEduMail(text)
{
  var patt = /^[a-z0-9\._]+@([a-z0-9]+\.)+edu$/i;
  if (! patt.test(text))
      return false;
  return true;

}


function validateAltEMail(text)
{
  var patt = /^[a-z0-9\._]+@([a-z0-9]+\.)+[a-z0-9]+$/i;

  if (! patt.test(text))
      return false;
  return true;

}
