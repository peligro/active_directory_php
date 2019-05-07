<?php
class Active Directory
{
	/**
    **@autor César Cancino Zapata
    **@soporte yo@cesarcancino.com www.cesarcancino.com
    **@name conexión active directory
    **@param user : cadena de texto con el usuario de red
    **@param password: contraseña de la red del usuario
    **@return arreglo en donde los dos primeros índices indican el estado de la conexión.
    **/
    public static function active_directory($user, $password)
    {
      $server = 'la IP del server';
      $domain = 'dominio de la red';
      $port = 389;
      $dn = "dc=cencosud, dc=corp";
      $filter = "sAMAccountName=" . $user . "*";
      $attr = array("displayname", "mail", "givenname", "sn", "useraccountcontrol", "cn", "department", "samaccountname", "telephonenumber", "memberof");
      $connection = @ldap_connect($server, $port);
      ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
      $bind = @ldap_bind($connection, $user.$domain, $password);
      if($bind==false)
      {
        switch(ldap_error($connection))
        {
          case 'Invalid credentials':
            $mensaje="Los datos ingresados no son correctos";
          break;
          default:
            $mensaje="Indefinido";
          break;
        }
        return array
        (
          'estado'=>'0',
          'mensaje'=>$mensaje
        );
      }else
      {
        $info = ldap_get_entries($connection, ldap_search($connection, $dn, $filter, $attr));
        return array
        (
          'estado'=>'1',
          'mensaje'=>'success',
          'cn'=>$info[0]["cn"][0],
          'mail'=>$info[0]["mail"][0],
          'department'=>$info[0]["department"][0],
          'samaccountname'=>$info[0]["samaccountname"][0],
          'telephonenumber'=>$info[0]["telephonenumber"][0],
          'memberof'=>$info[0]["memberof"][0],
          'password'=>$password
        );
      }
      ldap_close($connection);
    }
}