<?php

class Config {
  const DATE_FORMAT = "Y-m-d H:i:s";

  //DATABASE CONFIG
  public static function DB_HOST(){
    return Config::get_env("DB_HOST","localhost");
  }
  public static function DB_USERNAME(){
    return Config::get_env("DB_USERNAME","developer");
  }
  public static function DB_PASSWORD(){
    return Config::get_env("DB_PASSWORD","Teachme2021");
  }
  public static function DB_SCHEME(){
    return Config::get_env("DB_SCHEME","teachme");
  }
  public static function DB_PORT(){
    return Config::get_env("DB_PORT","3306");
  }
  public static function BASE_URL(){
    return Config::get_env("BASE_URL","cen-308-online-learning-system");
  }

  //SMTP CONFIG
  public static function SMTP_HOST(){
    return Config::get_env("SMTP_HOST","smtp.gmail.com");
  }
  public static function SMTP_PORT(){
    return Config::get_env("SMTP_PORT", 587);
  }
  public static function SMTP_USERNAME(){
    return Config::get_env("SMTP_USERNAME","teachm769@gmail.com");
  }
  public static function SMTP_PASSWORD(){
    return Config::get_env("SMTP_PASSWORD","developer007");
  }

  //JWT
  const JWT_SECRET = "JmKLzuHZG63h3jnzJDz";
  const JWT_TOKEN_TIME = 604800;

  public static function get_env($name, $default){
    return isset($_ENV[$name]) && trim($_ENV[$name]) != '' ? $_ENV[$name] : $default;
  }
}

?>

