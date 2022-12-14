<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $usuario = Usuario::instanciarUsuario($params['username'], $params['password'], $params['isAdmin'], $params['user_type'],
        $params['fecha_inicio'], $params['fecha_fin']);

        $usuario->CrearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por id
        $params = $request->getParsedBody();

        $usuario = Usuario::ObtenerUsuario($params['id']);
        if($usuario != false){
          $payload = json_encode($usuario);
        }else{
          $payload = json_encode(array("Error" => "No existe usuario con ese id"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::ObtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $fueModificado = Usuario::ModificarUsuario($params['username'], $params['password'], $params['id']);
        if($fueModificado){
          $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        }else{
          $payload = json_encode(array("error" => "No se pudo modificar el usuario o no hubo ningun tipo de cambio"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    //Comente lo anterior porque no da de baja sino que hace baja logica, agrega una fecha de baja
    public function BorrarUno($request, $response, $args)
    {
      $params = $request->getParsedBody();

      $fueBorrado = Usuario::BorrarUsuario($params['id']);
      if($fueBorrado){
        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
      }else{
        $payload = json_encode(array("error" => "No se pudo borrar el usuario"));
      }

      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }
}
