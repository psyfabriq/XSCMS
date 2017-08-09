<?php
        
class ACL
  {
    var $perms = array();   //Array : Stores the permissions for the user
    var $userID = 0;      //Integer : Stores the ID of the current user
    var $userRoles = array(); //Array : Stores the roles of the current user
    protected static $db=null;
    function __constructor($userID = '')
    {
     
      self::$db = Registry::get('DBOBJ');
      if ($userID != '')
      {
        $this->userID = floatval($userID);
      } else {
        if( Session::get('UID')!=''){
             $this->userID = floatval(Session::get('UID'));
             $this->userRoles = $this->getUserRoles('ids');
             Session::set('USER',$this->getUsername($this->userID));
          
        }else{
          //var_dump("expression");
          Session::set('UID',1);
          Session::set('USER','Guest');
     
          $this->userRoles[]=1;
        }
      }
      $this->buildACL();
    }
    function ACL($userID = '')
    {
      $this->__constructor($userID);
    }
    protected function buildACL()
    {
      //first, get the rules for the user's role
      if (count($this->userRoles) > 0)
      {
        $this->perms = array_merge($this->perms,$this->getRolePerms($this->userRoles));

      }
      //then, get the individual user permissions
      //$this->perms = array_merge($this->perms,$this->getUserPerms($this->userID));
    }
    function getPermKeyFromID($permID)
    {
      $query = "SELECT perm_key FROM auth_permissions WHERE auth_permissions.perm_id = ? LIMIT 1";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->bind_param("s",floatval($permID));
          $stmt->execute();
          $stmt->bind_result($perm_desc);
          $stmt->fetch();
          $stmt->close();
      }
           return $perm_desc;
    }
    function getPermNameFromID($permID)
    {
      $query = "SELECT perm_desc FROM auth_permissions WHERE auth_permissions.perm_id = ? LIMIT 1";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->bind_param("s",floatval($permID));
          $stmt->execute();
          $stmt->bind_result($perm_desc);
          $stmt->fetch();
          $stmt->close();
      }
           return $perm_desc;
    }
    function getRoleNameFromID($roleID)
    {
      $query = "SELECT role_name FROM auth_roles WHERE auth_roles.role_id = ?  LIMIT 1";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->bind_param("s",floatval($roleID));
          $stmt->execute();
          $stmt->bind_result($role_name);
          $stmt->fetch();
          $stmt->close();
      }
       return $role_name;
    }
    function getUserRoles()
    {
      $resp = array();
      $query = "SELECT  role_id FROM auth_user_role WHERE auth_user_role.user_id = ?";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->bind_param("s",$this->userID);
          $stmt->execute();
          $stmt->bind_result($role_id);
          while ($stmt->fetch()) {
              $resp[]=$role_id;
          }
          $stmt->close();
      }
      return $resp;
    }

    function getUserID(){
      return $this->userID;
    }
    
    function getAllRoles($format='ids')
    {
      $resp = array();
      $format = strtolower($format);
      $query = "SELECT role_name, role_id FROM auth_roles ORDER BY auth_roles.role_name";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->execute();
          $stmt->bind_result($role_name,$role_id);
          while ($stmt->fetch()) {
              if ($format == 'full')
              {
                $resp[] = array("ID" => $role_id,"Name" => $role_name);
              } else {
                $resp[] = $role_id;
              }
          }
          $stmt->close();
      }
      return $resp;
    }
    function getAllPerms($format='ids')
    {
      $resp = array();
      $format = strtolower($format);
      $query = "SELECT perm_desc, perm_id, perm_key , perm_parent FROM auth_permissions ORDER BY auth_permissions.perm_desc  ASC";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->execute();
          $stmt->bind_result($perm_name,$perm_id,$perm_key, $perm_parent);
          while ($stmt->fetch()) {
              if ($format == 'full')
              {
                 $resp[$perm_key] = array('ID' => $perm_id, 'Name' => $perm_name, 'Key' => $perm_key, 'Parent' => $perm_parent);
              } else {
                $resp[] = $perm_id;
              }
          }
          $stmt->close();
      }
      return $resp;
    }
    function getRolePerms($role)
    {
      $perms = array();
      $query='';
      if (is_array($role))
      {

        $query = "SELECT
                          auth_role_perm.perm_id AS ID,
                          auth_role_perm.value AS hP,
                          auth_permissions.perm_key AS pK,
                          auth_permissions.perm_desc AS Name,
                          auth_permissions.perm_parent AS Parent,
                          auth_permissions.perm_hash AS dhash
                        FROM auth_role_perm
                          RIGHT OUTER JOIN auth_permissions
                            ON auth_role_perm.perm_id = auth_permissions.perm_id
                        WHERE auth_role_perm.role_id IN (".implode(",",$role).")
                        ORDER BY auth_role_perm.id";
      } else {
         $query = "SELECT
                          auth_role_perm.perm_id AS ID,
                          auth_role_perm.value AS hP,
                          auth_permissions.perm_key AS pK,
                          auth_permissions.perm_desc AS Name,
                          auth_permissions.perm_parent AS Parent,
                          auth_permissions.perm_hash AS dhash
                        FROM auth_role_perm
                          LEFT OUTER JOIN auth_permissions
                            ON auth_role_perm.perm_id = auth_permissions.perm_id
                        WHERE auth_role_perm.role_id = ".floatval($role)."
                        ORDER BY auth_role_perm.id";
      }
      if ($stmt = self::$db->prepare($query)) {
        //   $stmt->bind_param("s",$bind);
          $stmt->execute();
          $stmt->bind_result($ID,$hP,$pK,$Name,$Parent,$hdata);
          while ($stmt->fetch()) {
                $pK = strtolower($pK);
                if ($pK == '') { continue; }
                 if ($hP === 1) {
                  $hP = true;
                } else {
                  $hP = false;
                }

                $perms[$hdata] = array('perm' => $pK,'inheritted' => true,'value' => $hP,'Name' => $Name,'ID' => $ID,'Parent' => $Parent);
          }
          $stmt->close();
      }

      return $perms;
    }
    function userHasRole($roleID)
    {
      foreach($this->userRoles as $k => $v)
      {
        if (floatval($v) === floatval($roleID))
        {
          return true;
        }
      }
      return false;
    }
    function hasPermission($permKey,$parentKey)
    {
      $permKey    = strtolower($permKey);
      $parentKey = strtolower($parentKey);
      $key=$permKey.'_'.$parentKey;
      $hdata = hash('md4',$key, false);
      if (array_key_exists($hdata,$this->perms))
      {
        if ($this->perms[$hdata]['value'] === '1' || $this->perms[$hdata]['value'] === true)
        {
          return true;
        } else {
          return false;
        }
      } else {

        $this->checkPermission($permKey,$parentKey, $hdata);
        return false;
      }
    }
    function hasPermissionAll($hashid)
    {
      if (array_key_exists($hashid,$this->perms))
      {
          return true;
      } else {
        return false;
      }
    }
    function getUsername($userID)
    {
      $query = "SELECT `real_name` FROM `auth_users` WHERE auth_users.user_id = ? LIMIT 1";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->bind_param("s",$userID);
          $stmt->execute();
          $stmt->bind_result($real_name);
          $stmt->fetch();
          $stmt->close();
      }
       return $real_name;
    }



 private function checkPermission($permKey,$parentKey, $hdata){
    $insertv=false;
   $permKey=trim(mysql_escape_string(strtolower($permKey)));
   $parentKey=trim(mysql_escape_string(strtolower($parentKey)));

    $query = "SELECT   perm_id FROM auth_permissions WHERE auth_permissions.perm_hash ='$hdata' ";

    if ($stmt =self::$db->prepare($query)) {
       // $stmt->bind_param("ss",trim(mysql_escape_string(strtolower($permKey))),trim(mysql_escape_string(strtolower($parentKey))));
        $stmt->execute();
        $stmt->bind_result($perm_id);
        $stmt->fetch();
        if($stmt->num_rows ===0){$insert=true;}
        $stmt->close();
    }
     if($insert===true){
      $all_query_ok=true;
      $groupIDS=$this->getSystemGroups();
      $permissionID=0;
      $permname=trim(mysql_escape_string(str_replace("_", " ", ucfirst($permKey))));
      $permkey=trim(mysql_escape_string($permKey));
      $q  ="INSERT INTO auth_permissions (`perm_desc`, `perm_key`, `perm_parent`, `perm_hash` ) VALUES ('$permname','$permkey','$parentKey','$hdata')";
       try {
         self::$db->autocommit(FALSE);
         self::$db->query($q) ? null : $all_query_ok=false;

         if($all_query_ok<>false){
         $permissionID=self::$db->insert_id;
         foreach ($groupIDS as  $groupID) {
           $mysqltime = date('Y-m-d H:i:s');
           $q2="INSERT INTO auth_role_perm (`role_id`, `perm_id`, `value`, `add_date`) VALUES ('$groupID', '$permissionID', '1', '$mysqltime')";
           self::$db->query($q2) ? null : $all_query_ok=false;
         }
       }
        $all_query_ok ? self::$db->commit() : self::$db->rollback();
        self::$db->close();
       }catch (Exception $e){}

        $this->perms = array_merge($this->perms,$this->getRolePerms($this->userRoles));
    }
 }
  protected function getSystemGroups(){
    $groupIDS=array();
    $query = "SELECT   role_id FROM auth_roles WHERE auth_roles.role_system = 1";
      if ($stmt = self::$db->prepare($query)) {
          $stmt->execute();
          $stmt->bind_result($role_id);
          while ($stmt->fetch()) {
              array_push($groupIDS, $role_id);
          }
          $stmt->close();
      }
      return $groupIDS;
  }

  }
    /*
    function getUserPerms($userID)
    {
      $strSQL = "SELECT * FROM `user_perms` WHERE `userID` = " . floatval($userID) . " ORDER BY `addDate` ASC";
      $data = mysql_query($strSQL);
      $perms = array();
      while($row = mysql_fetch_assoc($data))
      {
        $pK = strtolower($this->getPermKeyFromID($row['permID']));
        if ($pK == '') { continue; }
        if ($row['value'] == '1') {
          $hP = true;
        } else {
          $hP = false;
        }
        $perms[$pK] = array('perm' => $pK,'inheritted' => false,'value' => $hP,'Name' => $this->getPermNameFromID($row['permID']),'ID' => $row['permID']);
      }
      return $perms;
    }
    */

?>