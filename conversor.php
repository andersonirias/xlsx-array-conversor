<?php

$zip = new ZipArchive;
$res = $zip->open('teste-tabela-exel.xlsx');

if ($res === true) {
	
  $zip->extractTo('xlsx/');
  $zip->close();

  $sheet1 = simplexml_load_file('xlsx/xl/worksheets/sheet1.xml');

  if ($sheet1 == false)
    exit('Falha ao abrir o arquivo sheet1.xml');

  $tabela = [];

  foreach ($sheet1 as $item['row']) {

    foreach ($item['row'] as $item2['row']) {

      if (count($item2['row']) > 0) {

        foreach ($item2['row']->c as $row) {

          $atr = json_decode(json_encode($row), 1);

	  if ($atr == false)
            exit('Falha ao converter dados');

          if (isset($atr['@attributes']['t']))
            $tabela[$atr['@attributes']['r']] = true;
          else
            $tabela[$atr['@attributes']['r']] = 'vazio';

        }

      }

    }

  }

  if (count($tabela) > 0) {

    $sharedStrings = simplexml_load_file('xlsx/xl/sharedStrings.xml');

    if ($sharedStrings == false)
      exit('Falha ao abrir o arquivo sharedStrings.xml');

    $i = 0;
    $dados = json_decode(json_encode($sharedStrings), 1);

    if ($dados == false)
      exit('Falha ao converter dados do arquivo sharedStrings.xml');

    foreach ($tabela as $key => $item) {

      if (trim($item) != 'vazio') {

        $tabela[$key] = $dados['si'][$i]['t'];
        $i++;

      }

    }

  }

} else {
	
  exit('Falha ao abrir arquivo .xlsx');

}

print_r($tabela);

?>
