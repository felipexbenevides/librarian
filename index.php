<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <title>Librarian!</title>
    <style>
    </style>
  </head>
  <body>
  <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Librarian</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div> -->
        </nav>
<script>
    librarian = {
        "db" : {
            "classes" : [
                {"nome":"Acadêmico", "codigo": "ACA"},
                {"nome":"Eletrônica", "codigo": "ELE"},
                {"nome":"Hipster", "codigo": "HIP"},
                {"nome":"Fotografia", "codigo": "FOT"},
                {"nome":"Musica", "codigo": "MUS"},
                {"nome":"Pessoal", "codigo": "PES"}
            ],
            "subclasses" : [
                {"nome":"Geral", "codigo": "01"}
            ]
        },
        "codigo": ['-','-','-'],
        "corrente": '-'
    };
    $(document).ready(function(){
        // console.log(librarian);
        init();
        $("select").change(function(){
            updateCodigo();
        });
        $("#cadastrar").click(function(){
            // alert("teste");
            if($("#Objeto").val()){
                $.post( "mysql.php?OP=3", { classe : $("#Classe").val(), subclasse : $("#SubClasse").val(), codigo : $("#Codigo").attr("placeholder"), objeto : $("#Objeto").val(), observacao : $("#Observacao").val()})
                .done(function( data ) {
                    data = JSON.parse(data);
                    console.log(data)
                    if(data.status == 'erro') show(data.msg);
                    updateCodigo();
                    limpar();
                });                
            }
        });
        $("#limpar").click(function(){
            limpar();
        });        
    });
    function limpar() {
        $("#Objeto").val("") ;
            $("#Observacao").val("");
    }
    function updateCodigo() {
        $.get( "mysql.php", { "OP": 2, "CLASSE": ($("#Classe").val()), "SUBCLASSE": ($("#SubClasse").val()) } ).done(function( proximoCodigo ) {
            // alert( "proximoCodigo: " + proximoCodigo );
            librarian.corrente = proximoCodigo.padStart(4,'0');
            librarian.codigo[0] =  librarian.db.classes[($("#Classe").val()-1)].codigo;
            librarian.codigo[1] =  librarian.db.subclasses[($("#SubClasse").val()-1)].codigo;
            librarian.codigo[2] = librarian.corrente;
            $("#Codigo").attr('placeholder', librarian.codigo[0]+'.'+librarian.codigo[1]+'.'+librarian.codigo[2]);
        });




    }
    function init() {
        librarian.db.classes.forEach((element,index) => {
            // console.log(element.nome)
            $("#Classe").append('<option value="'+(index+1)+'">'+element.nome+'</option>');
        });
        librarian.db.subclasses.forEach((element,index) => {
            // console.log(element.nome)
            $("#SubClasse").append('<option value="'+(index+1)+'">'+element.nome+'</option>');
        });  
        updateCodigo();
    }
    function show(msg){
        $("#spanalert").html('<div class="alert alert-danger" role="alert">'+msg+'<button type="button" class="close" data-dismiss="alert" aria-label="Close">    <span aria-hidden="true">&times;</span>  </button></div>');
        $('#spanalert').fadeIn('slow');
        console.log(msg);
        // setTimeout(() => {
        //   $("#spanalert").html('');
        // }, 6000);
    }    
</script>        
        <!-- ALERT -->
        <span id="spanalert">
        </span>
        <form>
            <div class="form-group">
                <label for="Classe">Classe</label>
                <select class="form-control" id="Classe">
                <!-- <option value="1">Acadêmico</option>
                <option value="2">Eletrônica</option>
                <option value="3">Hipster</option>
                <option value="4">Fotografia</option>
                <option value="5">Musica</option>
                <option value="6">Pessoal</option> -->
                </select>
            </div>
            <div class="form-group">
                <label for="SubClasse">SubClasse</label>
                <select class="form-control" id="SubClasse">
                <!-- <option value="1">Geral</option> -->
                </select>
            </div>
            <div class="form-group">
                <label for="Codigo">Codigo</label>
                <input class="form-control" type="text" id="Codigo" placeholder="" readonly>
            </div>            
            <div class="form-group">
                <label for="Objeto">Objeto</label>
                <input class="form-control" type="text" id="Objeto">
            </div>
            <div class="form-group">
                <label for="Observacao">Observação</label>
                <textarea class="form-control" id="Observacao" rows="3"></textarea>
            </div>       
        </form>   
        <button type="button" class="btn btn-outline-primary" id="cadastrar">CADASTRAR</button>
        <button type="button" class="btn btn-outline-secondary" id="limpar">LIMPAR</button>            
  </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>