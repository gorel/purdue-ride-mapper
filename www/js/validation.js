function validateEduMail(text)
{
  var patt = /^[a-z0-9\._]+@([a-z0-9]+\.)+edu$/i;
  if (! patt.test(text))
      return false;
  return true;

}


function validateAltMail(text)
{
  var patt = /^[a-z0-9\._]+@([a-z0-9]+\.)+[a-z0-9]+$/i;

  if (! patt.test(text))
      return false;

  return true;

}

function validatePhone(text)
{
  var patt = /^[0-9]{10}$/g

  if (! patt.test(text))
    return false;

  return true;
}

function validatePass(text)
{
  var patt = /^[a-z0-9]{6,}$/i;

  if (! patt.test(text))
    return false;

  return true;

}
