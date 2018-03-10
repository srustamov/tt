<?php  namespace System\Engine\Http;

/**
 * @author  Samir Rustamov <rustemovv96@gmail.com>
 * @link    https://github.com/srustamov/TT
 */


use System\Engine\Http\Request;



class Middleware
{

    /**
     * @param String $extension
     * @return bool|mixed
     * @throws \Exception
     */
    public static function init( String $extension )
    {

      $request  = new Request();

      $excepts  = [];

      $name     = $extension;

      if (strpos($extension, ':') !== false)
      {
          list($name, $guard) = explode(':', $name, 2);

          if(strpos($guard,'|') !== false)
          {
            list($guard,$excepts) = explode('|',$guard,2);
            $excepts = explode(',',$excepts);
          }

      }
      elseif (strpos($extension,'|') !== false)
      {
          list($name,$excepts) = explode('|',$extension,2);
          $excepts = explode(',',$excepts);
      }

      foreach ($excepts as $action)
      {
        if (strtolower($request->action()) == strtolower($action)) {
          return true;
        }

      }

      $middleware = "\\App\\Middleware\\{$name}";

      if (class_exists($middleware))
      {
          call_user_func_array(
              [ new $middleware() , "handle" ], [ $request  , $guard ?? "user" ]
          );

      }
      else
      {
          throw new \Exception("Middleware {$middleware} class not found");
      }
    }



}
