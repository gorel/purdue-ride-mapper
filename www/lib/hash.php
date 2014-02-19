<?php

/**
 *
 * Hash functions
 *
 * @author	Timothy Thong <tthong@purdue.edu>
 * @version     1.0
 *
 */

/**
 *
 * Hash user passwords and registration tokens
 *
 * @param	string $str string to be hashed
 * @returns	string the hashed string
 *
 */
function saltedHash($str)
{
        $salt   = "mamma mia! gustavo appears again!";

        return md5($salt.$str);            
}

?>

