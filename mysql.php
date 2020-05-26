<?php
 $op = $_GET['OP'];
//  print_r($_GET);
 try {
    $conexao = new PDO("mysql:host=localhost; dbname=librarian", "root", "");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");

    switch ($op) {
        case '1':
            $consulta = $conexao->query("SELECT * FROM tobjeto");
        
            while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
                // echo "Nome: {$linha['nome']} - Usuário: {$linha['usuario']}<br />";
                print_r( $linha['CodigoObjeto'].' : '. $linha['NomeObjeto'].'('.$linha['obsObjeto'].')');
                echo '<br>';
            }            
            break;
        case '2':
            $consulta = $conexao->query("SELECT MAX(tobjeto.CodigoObjeto) as CODIGO FROM tobjeto WHERE tobjeto.FKidClasse = ".$_GET['CLASSE']." AND tobjeto.FKidSubClasse = ".$_GET['SUBCLASSE']);
        
            while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
                // echo "Nome: {$linha['nome']} - Usuário: {$linha['usuario']}<br />";
                if(isset($linha['CODIGO'])){
                    echo(explode(".",$linha['CODIGO'])[2]+1);
                }else{
                    echo '1';
                }
                
            }            
            break;
        case '3':
            try {
                $classe =$_POST["classe"];
                $subclasse =$_POST["subclasse"];
                $codigo =$_POST["codigo"];
                $objeto =$_POST["objeto"];
                $observacao =$_POST["observacao"];


                $stmt = $conexao->prepare("INSERT INTO `tobjeto` (`FKidClasse`, `FKidSubClasse`, `CodigoObjeto`, `NomeObjeto`, `obsObjeto`) VALUES (:classe, :subclasse, :codigo, :objeto, :observacao)");
                $stmt->execute(array(
                    ":classe" => $classe,
                    ":subclasse" => $subclasse,
                    ":codigo" => $codigo,
                    ":objeto" => $objeto,
                    ":observacao" => $observacao

               ));
                
                echo '{"status": "sucesso", "msg": "Registro adicionado com sucesso"}';
                // $stmt->rowCount(); 
            } catch(PDOException $e) {
                $erro = $e->getMessage();
                echo '{"status": "erro", "msg": "Houve um erro"}';                
                // echo 'Error: ' . $e->getMessage();
            }            
            break;                
        default:
            # code...
            break;
    }

} catch (PDOException $erro) {
    echo "Erro na conexão:" . $erro->getMessage();
}