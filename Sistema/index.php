<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
</head>
<body>
    <?php
    
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $names = $file['name'];
        $tmp_name = $file['tmp_name'];
        $errors = $file['error'];
    
        foreach ($names as $index => $name) {
            if ($errors[$index] !== UPLOAD_ERR_OK) {
                echo "Erro ao carregar o arquivo $name. Código de erro: " . $errors[$index] . "<br>";
                continue;
            }
    
            
            $targetFile = 'upload/' . basename($name);
    
            
            $fileCounter = 1;
            while (file_exists($targetFile)) {
                $fileInfo = pathinfo($name);
                $targetFile = 'upload/' . $fileInfo['filename'] . '_' . $fileCounter . '.' . $fileInfo['extension'];
                $fileCounter++;
            }
    
            
            if (move_uploaded_file($tmp_name[$index], $targetFile)) {
                echo "Arquivo $name carregado com sucesso!<br>";
            } else {
                echo "Falha ao mover o arquivo $name para a pasta de destino.<br>";
            }
        }
    } else {
        echo "Nenhum arquivo enviado.<br>";
    }
    
    
    
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="file[]" multiple>
        <button>Upload</button>
        
    </form>
</body>
</html>