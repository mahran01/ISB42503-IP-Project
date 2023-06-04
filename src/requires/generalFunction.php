<?php
function postExists ($key)
{
    return array_key_exists($key, $_POST);
}
function getPostIfExist ($key)
{
    if (postExists($key)) {
        return $_POST[$key];
    }
    return null;
}
function sessionExists ($key)
{
    return isset($_SESSION) && array_key_exists($key, $_SESSION);
}
function getSessionIfExist ($key)
{
    if (sessionExists($key)) {
        return $_SESSION[$key];
    }
    return null;
}
?>