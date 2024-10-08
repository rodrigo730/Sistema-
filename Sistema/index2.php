<?php
use phpseclib3\Net\SSH2;
use phpseclib3\Net\SCP;

require 'vendor/autoload.php';

// Configurações de conexão SSH
$servidor = 'seuservidor.com';
$usuario = 'seu_usuario';
$senha = 'sua_senha';
$pastaLocal = '/caminho/para/pasta_local';
$pastaRemota = '/caminho/para/pasta_remota';

// Conectar via SSH
$ssh = new SSH2($servidor);
if (!$ssh->login($usuario, $senha)) {
    die('Falha ao fazer login no servidor SSH.');
}

// Criar uma instância do SCP
$scp = new SCP($ssh);

// Função para enviar arquivos recursivamente
function enviarPastaViaSCP($scp, $pastaLocal, $pastaRemota) {
    foreach (scandir($pastaLocal) as $file) {
        if ($file != "." && $file != "..") {
            $caminhoLocal = $pastaLocal . '/' . $file;
            $caminhoRemoto = $pastaRemota . '/' . $file;

            if (is_dir($caminhoLocal)) {
                // Cria a pasta no servidor e envia recursivamente
                $scp->exec("mkdir -p " . escapeshellarg($caminhoRemoto));
                enviarPastaViaSCP($scp, $caminhoLocal, $caminhoRemoto);
            } else {
                // Envia o arquivo
                $scp->put($caminhoRemoto, $caminhoLocal, SCP::SOURCE_LOCAL_FILE);
                echo "Arquivo enviado: $file\n";
            }
        }
    }
}

// Enviar arquivos via SCP
enviarPastaViaSCP($scp, $pastaLocal, $pastaRemota);
?>
