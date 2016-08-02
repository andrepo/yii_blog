<?php
function is_prime($val) {
    for ($i = 2; $i < $val; $i++) {
        if ( $val % $i == 0 ) {
            return false;
        }
    }
    return true;
}

/*

b) Lets there is an array A[], with n items. Lets the equilibrium index is x,
such that the number of odd numbers in A from 0 to x-1 is equal to the number of
even numbers from x – n

e.g:

[1,3,5,4,8,10] here x is 3
[1,2,3,5,2,4,8] here x is 4
[2,3,3,8,1,7,4,2] here x is 4

1
1,3,5
4,8,10

Write a function that takes an array as argument and return x.

*/

function equilibrium($arr) {
    $n = count($arr);
    for ($i = 0; $i <= $n; $i++) {
        $odds = 0;
        $even = 0;
        // Forward
        for ($x = 0; $x <= $i - 1; $x++) {
            if ( $arr[$x] % 2 ) {
                $odds++;
            }
        }
        // Backwards
        for ($z = $n; $z >= $i; $z--) {
            if ( $arr[$z] % 2 == 0 ) {
                $even++;
            }
        }

        if ( $odds == $even ) {
            return $i;
        }
    }
}
