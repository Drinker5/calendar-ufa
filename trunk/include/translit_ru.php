<?php

function translit_ru($st){
     // ������� �������� "��������������" ������.
     $st = strtr($st,"������������������������_",
                      "abvgdeeziyklmnoprstufh'iei");
     $st = strtr($st,"�����Ũ������������������_",
                     "ABVGDEEZIYKLMNOPRSTUFH'IEI");
     // ����� - "���������������".
     $st = strtr($st, array(
                 "�"=>"zh", "�"=>"ts", "�"=>"ch", "�"=>"sh", 
                 "�"=>"shch","�"=>"", "�"=>"yu", "�"=>"ya",
                 "�"=>"ZH", "�"=>"TS", "�"=>"CH", "�"=>"SH", 
                 "�"=>"SHCH","�"=>"", "�"=>"YU", "�"=>"YA",
                 "�"=>"i", "�"=>"Yi", "�"=>"ie", "�"=>"Ye"
                )
     );
     return $st;
}

function translit_lat ($string) 
{
$string = ereg_replace("zh","�",$string);
$string = ereg_replace("Zh","�",$string);
$string = ereg_replace("yo","�",$string);
$string = ereg_replace("Yu","�",$string);
$string = ereg_replace("Ju","�",$string);
$string = ereg_replace("ju","�",$string);
$string = ereg_replace("yu","�",$string);
$string = ereg_replace("sh","�",$string);
$string = ereg_replace("y�","�",$string);
$string = ereg_replace("j�","�",$string);
$string = ereg_replace("y�","�",$string);
$string = ereg_replace("Sh","�",$string);
$string = ereg_replace("Ch","�",$string);
$string = ereg_replace("ch","�",$string);
$string = ereg_replace("Yo","�",$string);
$string = ereg_replace("Ya","�",$string);
$string = ereg_replace("Ja","�",$string);
$string = ereg_replace("Ye","�",$string);
$string = ereg_replace("i","�",$string);
$string = ereg_replace("'","�",$string);
$string = ereg_replace("c","�",$string);
$string = ereg_replace("u","�",$string);
$string = ereg_replace("k","�",$string);
$string = ereg_replace("e","�",$string);
$string = ereg_replace("n","�",$string);
$string = ereg_replace("g","�",$string);
$string = ereg_replace("z","�",$string);
$string = ereg_replace("h","�",$string);
$string = ereg_replace("''","�",$string);
$string = ereg_replace("f","�",$string);
$string = ereg_replace("y","�",$string);
$string = ereg_replace("v","�",$string);
$string = ereg_replace("a","�",$string);
$string = ereg_replace("p","�",$string);
$string = ereg_replace("r","p",$string);
$string = ereg_replace("o","�",$string);
$string = ereg_replace("l","�",$string);
$string = ereg_replace("d","�",$string);
$string = ereg_replace("s","�",$string);
$string = ereg_replace("m","�",$string);
$string = ereg_replace("t","�",$string);
$string = ereg_replace("b","�",$string);
$string = ereg_replace("I","�",$string);
$string = ereg_replace("'","�",$string);
$string = ereg_replace("C","�",$string);
$string = ereg_replace("U","�",$string);
$string = ereg_replace("K","�",$string);
$string = ereg_replace("E","�",$string);
$string = ereg_replace("N","�",$string);
$string = ereg_replace("G","�",$string);
$string = ereg_replace("Z","�",$string);
$string = ereg_replace("H","�",$string);
$string = ereg_replace("''","�",$string);
$string = ereg_replace("F","�",$string);
$string = ereg_replace("Y","�",$string);
$string = ereg_replace("V","�",$string);
$string = ereg_replace("A","�",$string);
$string = ereg_replace("P","�",$string);
$string = ereg_replace("R","�",$string);
$string = ereg_replace("O","�",$string);
$string = ereg_replace("L","�",$string);
$string = ereg_replace("D","�",$string);
$string = ereg_replace("S","�",$string);
$string = ereg_replace("M","�",$string);
$string = ereg_replace("I","�",$string);
$string = ereg_replace("T","�",$string);
$string = ereg_replace("B","�",$string);

return $string;
}
?>