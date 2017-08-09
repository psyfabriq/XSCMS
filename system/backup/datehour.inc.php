<?php
class date_hour
{
	/*************/
	/* Variables */
	/*************/
     var $date;					// Date
	var $hour;					    // Hour
	var $desfase = 2;		        // Time lag

	/********************/
	/* Setter functions */
	/********************/

	// Sets date
	function set_date ($valor)
  {
    $this->date = $valor;
  }
	// Sets hour
  function set_hour ($valor)
  {
    $this->hour = $valor;
  }
	// Sets time lag
  function set_desfase ($valor)
  {
    $this->desfase = $valor;
  }

	/*************************************************/
	/* Functions get_dia (), get_mes (), get_anyo () */
	/* --------------------------------------------- */
	/* Returns date in parts.                        */
	/*************************************************/
  function get_dia ($ceros = true)
  {
    $res = substr ($this->date, 6, 2);
    if ($ceros == true) $res = $this->ceros ($res, 2, 0);
    return $res;
  }
  function get_mes ($ceros = true)
  {
    $res = substr ($this->date, 4, 2);
    if ($ceros == true) $res = $this->ceros ($res, 2, 0);
    return $res;
  }
  function get_anyo ($ceros = true)
  {
    $res = substr ($this->date, 0, 4);
    if ($ceros == true) $res = $this->ceros ($res, 4, 0);
    return $res;
  }

	/****************************************/
	/* Functions get_hour (), get_minuto () */
	/* ------------------------------------ */
	/* Returns hour in parts.               */
	/****************************************/
  function get_hour ($ceros = true)
  {
    $res = substr ($this->hour, 0, 2);
    if ($ceros == true) $res = $this->ceros ($res, 2, 0);
    return $res;
  }
  function get_minuto ($ceros = true)
  {
    $res = substr ($this->hour, 2, 2);
    if ($ceros == true) $res = $this->ceros ($res, 2, 0);
    return $res;
  }

	/*************************/
	/* Function date ()     */
	/* --------------------- */
	/* Returns current date  */
	/* in dd/mm/yyyy format. */
	/*************************/
  function date ()
  {
  	$dia = $this->ceros (trim (gmdate ("j", time() + (3600 * $this->desfase))), 2, 0);
  	$mes = $this->ceros (trim (gmdate ("n", time() + (3600 * $this->desfase))), 2, 0);
  	$anyo = trim (gmdate("Y", time () + (3600 * $this->desfase)));
  	$date = $dia.'/'.$mes. '/'. $anyo;
  	return $date;
  }

	/************************/
	/* Function hour ()     */
	/* -------------------- */
	/* Returns current hour */
	/* in hh:mm format.     */
	/************************/
  function hour()
  {
    return gmdate ("H:i", time() + (3600 * $this->desfase));
  }

	/*********************************************/
	/* Function ceros ()                         */
	/* ----------------------------------------- */
	/* Adds $num zeros in the side of $cad       */
	/* specified by $izq_dr (0: left, 1: right). */
	/*********************************************/
  function ceros ($cad, $num, $izq_dr)
  {
  	if ($izq_dr == 0)
      while (strlen ($cad) < $num) $cad = "0".$cad;
    else
      while (strlen ($cad) < $num) $cad .= "0";
  	return $cad;
  }

	/******************************************/
	/* Function date_bd ()                   */
	/* -------------------------------------- */
	/* Returns a date in yyyymmdd format.     */
	/* If you don't specify the $f parameter, */
	/* it takes $this->date instead.         */
	/******************************************/
  function date_bd ($f = "")
  {
    if ($f == "") $f = $this->date ();
    $p1 = strpos ($f, "/");
    $p2 = strpos ($f, "/", $p1 + 1);
    $d = substr ($f, 0, $p1);                 // Día
    $m = substr ($f, $p1 + 1, $p2 - $p1 - 1); // Mes
    $a = substr ($f, $p2 + 1);                // Año
    return $a.$m.$d;
  }

	/*********************************************/
	/* Function hour_bd ()                       */
	/* ----------------------------------------- */
	/* Returns an hour in hhmm format.           */
	/* If you don't specify the $hour parameter, */
	/* it takes $this->hour instead.             */
	/*********************************************/
  function hour_bd ($hour = "")
  {
    if ($hour == "") $hour = $this->hour ();
    $p = strpos ($hour, ":");
    $h = substr ($hour, 0, $p);  // hour
    $m = substr ($hour, $p + 1); // Minuto
    return $h.$m;
  }

	/*****************************************/
	/* Function date_ed ()                  */
	/* ------------------------------------- */
	/* Returns $f date in dd/mm/yyyy format. */
	/* $f must have aaaammdd format          */
	/*****************************************/
  function date_ed ($f)
  {
    $d = substr ($f, 6);    // Día
    $m = substr ($f, 4, 2); // Mes
    $a = substr ($f, 0, 4); // Año
    $m = intval ($m);
    $m = $this->texto_mes ($m);
    return $d."/".$m."/".$a;
  }

	/***************************************/
	/* Function hour_ed ()                 */
	/* ----------------------------------- */
	/* Returns $hour hour in hh:mm format. */
	/* $hour must have hhmm format.        */
	/***************************************/
  function hour_ed ($hour)
  {
    $hour = $this->ceros ($hour, 4, 0);
    $h = substr ($hour, 0, 2); // hour
    $m = substr ($hour, 2);    // Minuto
    if ($h == 24) $h = "0";
    return $h.":".$m;
  }

	/***********************************/
	/* Function texto_mes ()           */
	/* ------------------------------- */
	/* Returns the first three letters */
	/* of the month specified by $mes. */
	/***********************************/
  function texto_mes ($mes)
  {
		$arr_meses = split (",", "JAN,FEB,MAR,APR,MAY,JUN,JUL,AUG,SEP,OCT,NOV,DEC");
  	return $arr_meses[$mes-1];
  }
}
?>