<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MatrixController extends Controller
{

	const MAXTEST = 50;
	const MAXSIZE = 100;
	const MAXOPERATION = 1000;
	
	private $matriz = [];
	private $testcases = null;
	private $n = null;
	private $m = null;
	private $operation =[];
	private $resultados =[];
	
	public function show(Request $request)
	{
		$this->resultados = null;
		if($request->isMethod('post')){
			$this->resultados = [];
			if($request->hasFile('testfile')){
				$archivo = $request->file('testfile');
				$_fp = fopen($archivo, "r");

				$testcases = trim(str_ireplace("\n","",fgets($_fp))); // # test case
				if ($this->validateVar($testcases,self::MAXTEST)) { 
					 // Itera el numero de ciclos de prueba
					 for ($i=1;$i<=$testcases;$i++) {
						  $aux = explode(" ", str_ireplace("\n","",fgets($_fp)) );
						  $n = $aux[0]; // Size of matrix
						  $m = $aux[1]; // # of operations

						  // validate variable
						  $runtest = 1;
						  if (!$this->validateVar($n,self::MAXSIZE)) { $runtest = 0; } //  "Size of matrix param is ivalid"
						  if (!$this->validateVar($m,self::MAXOPERATION)) { $runtest = 0; } //  "Number of operation param is ivalid"
						  if ($runtest==1) {
								$this->initMatrix($n);
								for ($ite=1;$ite<=$m;$ite++) {
									 if ($this->validOperation( trim(str_ireplace("\n","",fgets($_fp)) ))) {
										  $this->executeOperation();
									 }
								}
						  }
					 }
				}
				
				fclose($_fp);
			}
		}
		return view('matrix.show', ['result' => $this->resultados]);
	} // end show
	
	
	/**
    funcion que valida si la variables es valida
    parametro:  $var = valor de la variable
                $value = máximo valor que puede tomar
    Resultado:  TRUE = variable valida
                FALSE = variable invalida
*/
private function validateVar($var, $value) {
    $result = true;

    $eval = is_nan(trim($var));
    if ($eval) { $result = false; } else 
        if ($var<1 || $var>$value) { $result = false;  }
    return $result;
}

/**
    funcion que inicializa la matriz 
    parametro: n = dimension de matriz
*/
private function initMatrix($n) {
    global $matriz;
  //  unset($matriz);
    $matriz = [];
    for ($x=1;$x<=$n;$x++) {
        for ($y=1;$y<=$n;$y++) {
            for ($z=1;$z<=$n;$z++) {
                $matriz[$x][$y][$z] = 0;
            }
        }
    }
}

/**
    funcion que valida si la operacion es valida
    parametro:  $op = string de la operacion
    Resultado:  TRUE = operacion valida
                FALSE = operacion invalida
*/
private function validOperation($op) {
    global $operation;
    $result = true;
    
    $operation = explode(" ", $op);
    switch ($operation[0]) {
        case "UPDATE" :
            if (count($operation)!=5) { $result = false; } 
            else {
                if (!$this->validateVar($operation[1],self::MAXSIZE)) { $result = false; }
                if (!$this->validateVar($operation[2],self::MAXSIZE)) { $result = false; }
                if (!$this->validateVar($operation[3],self::MAXSIZE)) { $result = false; }
                if (is_nan($operation[4])) { $restult = false; } else 
                    if ($operation[4]<pow(-10,9) || $operation[4]>pow(10,9)) { $restult = false;  }
            }
            break;
        case "QUERY":
            if (count($operation)!=7) { $result = false; } 
            else {
                if (!$this->validateVar($operation[1],self::MAXSIZE)) { $result = false; }
                if (!$this->validateVar($operation[2],self::MAXSIZE)) { $result = false; }
                if (!$this->validateVar($operation[3],self::MAXSIZE)) { $result = false; }
                if (!$this->validateVar($operation[4],self::MAXSIZE)) { $result = false; }
                if (!$this->validateVar($operation[5],self::MAXSIZE)) { $result = false; }
                if (!$this->validateVar($operation[6],self::MAXSIZE)) { $result = false; }
                if ($operation[1]>$operation[4]) { $restult = false;  }
                if ($operation[2]>$operation[5]) { $restult = false;  }
                if ($operation[3]>$operation[6]) { $restult = false;  }
            }
            break;
        default: $result = false;
    }
    return $result;
}

/**
    funcion que ejecuta la operacion sobre la matriz 
*/
private function executeOperation(){
    global $matriz;
    global $operation;

    switch ($operation[0]) {
        case "UPDATE" :
            $matriz[$operation[1]][$operation[2]][$operation[3]] = $operation[4];
            break;
        case "QUERY":
            $sum = 0;
            for ($x=$operation[1];$x<=$operation[4];$x++) {
                for ($y=$operation[2];$y<=$operation[5];$y++) {
                    for ($z=$operation[3];$z<=$operation[6];$z++) {
                        $sum = $sum + $matriz[$x][$y][$z];
                    }
                }
            }
            $this->resultados[] = $sum;
            break;
    }
}
	
} // end class