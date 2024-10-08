<?php
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SCP;

require 'vendor/autoload.php';


$servidor = 'seuservidor.com';
$usuario = 'seu_usuario';
$senha = 'sua_senha';
$pastaLocal = '/caminho/para/pasta_local';
$pastaRemota = '/caminho/para/pasta_remota';


$ssh = new SSH2($servidor);
if (!$ssh->login($usuario, $senha)) {
    die('Falha ao fazer login no servidor SSH.');
}


$scp = new SCP($ssh);


function enviarPastaViaSCP($scp, $pastaLocal, $pastaRemota) {
    foreach (scandir($pastaLocal) as $file) {
        if ($file != "." && $file != "..") {
            $caminhoLocal = $pastaLocal . '/' . $file;
            $caminhoRemoto = $pastaRemota . '/' . $file;

            if (is_dir($caminhoLocal)) {
                
                $scp->exec("mkdir -p " . escapeshellarg($caminhoRemoto));
                enviarPastaViaSCP($scp, $caminhoLocal, $caminhoRemoto);
            } else {
                
                $scp->put($caminhoRemoto, $caminhoLocal, SCP::SOURCE_LOCAL_FILE);
                echo "Arquivo enviado: $file\n";
            }
        }
    }
}


enviarPastaViaSCP($scp, $pastaLocal, $pastaRemota);
?>
