<?php

use Hashids\Hashids;

/**
 * Get secret code
 *
 * @param $id
 * @return string
 */
function getSecretCode($id)
{
    $keys = [3, 6, 2, 5];
    $str = '';

    for ($i = 0; $i < 4; $i++) {
        $x = 0 + $id + $keys[$i];
        $x = $x * $x;
        $x = $x % 26;
        $str .= chr(65 + $x);
    }

    return $str;
}

/**
 * Get prettified date format
 *
 * @param DateTime $date
 * @return string
 */
function prettifyDate(\DateTime $date) {
    return date('D M d Y H:i:s', strtotime($date)) . " GMT" .
        date('O', strtotime($date)) . " (" .date('T', strtotime($date)) .")";
}

/**
 * Get encoded HashID
 *
 * @param $key
 * @return string
 */
function hashEncode($key) {
    /** @noinspection SpellCheckingInspection */
    // $hashId = new Hashids(env('APP_KEY', "millionMitzVot"), 8,
    //     "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");

    return $key;
}

/**
 * Get decoded HashID
 *
 * @param $hash
 * @return array
 */
function hashDecode($hash) {
    /** @noinspection SpellCheckingInspection */
    // $hashId = new Hashids(env('APP_KEY', "millionMitzVot"), 8,
    //     "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890");

    // $decodedIds = $hashId->decode($hash);

    // return array_key_exists(0, $decodedIds) ? $decodedIds[0] : $decodedIds;
    return $hash;
}
/**
 * Get xor text
 *
 * @param $arr
 * @return array
*/
function hash_xor($arr, $uid){
    $hashes = [3, 116, 104, 221, 16, 56, 245, 140, 89, 78, 42, 97, 161, 95, 98, 152, 194, 201, 31, 216, 179, 184, 88, 7, 110, 69, 47, 79, 234, 93, 62, 123, 113, 149, 24, 74, 121, 255, 80, 211, 208, 99, 220, 250, 136, 81, 60, 198, 186, 63, 5, 84, 238, 181, 138, 183, 125, 46, 133, 106, 94, 141, 27, 137, 85, 29, 51, 240, 103, 226, 213, 129, 171, 14, 206, 247, 73, 172, 248, 207, 76, 20, 199, 246, 112, 176, 0, 4, 15, 57, 52, 182, 32, 228, 64, 11, 130, 1, 193, 233, 114, 43, 191, 12, 252, 67, 195, 8, 165, 202, 200, 58, 124, 158, 174, 203, 204, 72, 177, 157, 86, 142, 25, 148, 44, 111, 253, 40, 215, 225, 219, 61, 48, 187, 128, 71, 189, 134, 102, 227, 10, 22, 150, 185, 217, 243, 90, 230, 236, 23, 28, 41, 153, 35, 173, 229, 38, 143, 82, 169, 224, 101, 117, 59, 13, 239, 108, 49, 55, 168, 237, 30, 33, 54, 160, 209, 210, 66, 45, 164, 155, 147, 244, 139, 146, 37, 87, 145, 18, 127, 109, 91, 167, 212, 96, 53, 115, 17, 249, 188, 50, 235, 9, 144, 205, 175, 120, 135, 178, 159, 180, 222, 197, 19, 214, 170, 241, 77, 196, 107, 39, 65, 192, 132, 166, 21, 218, 118, 156, 126, 254, 163, 122, 6, 131, 190, 119, 100, 92, 2, 242, 36, 105, 231, 26, 34, 68, 162, 83, 151, 223, 251, 232, 70, 75, 154];
    $return = [];
    $_uid = $uid % 256;
    foreach($arr as $index => $val){
        $return[] = $val ^ $hashes[($index - 1) % 256] ^ $_uid;
    }
    return $return;
}
/**
 * Get encrypted text
 *
 * @param $arr
 * @return array
*/
function hash_encrypt($arr) {
    $hashes = [121, 238, 76, 58, 71, 237, 169, 177, 30, 165, 182, 197, 115, 43, 244, 94, 251, 98, 9, 25, 250, 135, 109, 26, 254, 228, 102, 113, 183, 17, 234, 166, 93, 36, 75, 206, 158, 105, 84, 46, 230, 128, 34, 69, 123, 132, 78, 148, 127, 213, 2, 153, 211, 32, 125, 57, 145, 52, 74, 218, 168, 160, 199, 243, 110, 101, 92, 225, 186, 138, 200, 172, 13, 181, 49, 91, 73, 176, 4, 107, 198, 62, 252, 136, 120, 48, 24, 156, 35, 134, 103, 188, 47, 246, 60, 171, 231, 152, 151, 201, 64, 224, 205, 11, 117, 196, 56, 18, 232, 190, 20, 37, 208, 85, 39, 14, 41, 242, 133, 192, 81, 203, 249, 95, 65, 83, 12, 15, 55, 137, 253, 27, 68, 67, 247, 202, 194, 195, 220, 70, 45, 82, 53, 112, 108, 170, 21, 215, 207, 89, 141, 144, 209, 63, 140, 178, 185, 245, 99, 96, 204, 163, 248, 86, 150, 28, 33, 143, 184, 223, 54, 179, 154, 191, 50, 8, 173, 59, 1, 51, 23, 87, 111, 233, 104, 155, 119, 5, 131, 139, 162, 219, 229, 42, 61, 118, 79, 146, 116, 106, 221, 10, 44, 80, 72, 97, 149, 216, 226, 100, 7, 164, 217, 40, 0, 174, 193, 88, 90, 31, 239, 222, 147, 210, 6, 114, 167, 241, 157, 66, 19, 16, 161, 255, 240, 129, 159, 212, 22, 130, 126, 175, 122, 214, 189, 142, 180, 187, 3, 227, 235, 124, 236, 29, 38, 77];
    $return = [];
    foreach($arr as $index => $val){
        $return[] = $hashes[$val];
    }
    return $return;
}

/**
 * Get AES encrypted text
 *
 * @param $plaintext, $password
 * @return string
*/
function aes_encrypt($plaintext, $password) {
    $method = "AES-256-CBC";
    $key = $password;
    $iv = openssl_random_pseudo_bytes(16);
    $ciphertext = openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv);
    $hash = hash_hmac('sha256', $ciphertext, $key, true);
    return $iv . $hash . $ciphertext;
}

/**
 * Get decrypted text
 *
 * @param $plaintext, $password
 * @return string
*/
function aes_decrypt($ivHashCiphertext, $password) {
    $method = "AES-256-CBC";
    $iv = substr($ivHashCiphertext, 0, 16);
    $hash = substr($ivHashCiphertext, 16, 32);
    $ciphertext = substr($ivHashCiphertext, 48);
    $key = $password;
    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}