<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Municipalidad de Cortés">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" href="/img/Logos-Puerto.png" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .bg-azul{
                background:#325032;
            }
    </style>
    <title>Municipalidad de Puerto Cortés</title>
</head>
<body>
    <div class="jumbotron m-0">
        <div class="row m-0">
            <div class="col-md-8 col-12  pb-2">
                <img class="w-100 rounded mx-auto d-block" src="/img/logo-municipalidad-cortes.jpg" alt="">
            </div>
            <div class="col-md-4 col-12">
                <div class="card">
                    <div class="card-body">
                        <form class="p-md-5 pt-3">
                            <div class="form-group pb-3">
                                <label for="exampleInputEmail1">Correo electronico</label>
                                <input type="text" class="form-control" id="email" aria-describedby="emailHelp">
                                <small id="emailHelp" class="form-text text-muted">Nunca compartiremos su correo electrónico con nadie más.</small>
                            </div>
                            <div class="form-group pb-3">
                                <label for="exampleInputPassword1">Contraseña</label>
                                <input type="password" class="form-control" id="password">
                            </div>
                            <button type="button" class="btn btn-primary" onClick="send()">Ingresar</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="jumbotron bg-azul m-0 text-white p-3">
        <div class="row">
            <div class="col-12 col-md-4 text-center">
                <p> ODS-9 metas 9.4, 9.5, 9.6, 9.7 y 9.8 - CD Resultados 1 y 2 - ISO 9001:2015 7.1.3, 8.5.3
                </p>
            </div>
            <div class="col-12 col-md-4 text-center">
                <p class="mb-md-5"> Municipalidad de Puerto Cortés © 2020
                </p>
            </div>
            <div class="col-12 col-md-4 text-center">
                <p class="mb-md-5"> Desarrollo e Innovación Tecnológica
                </p>
            </div>
        </div>
    </div>

</body>
</html>



 <!--bootstrap-->
 <!--bootstrap-->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
 <script>
    async function send (){
        //Se obtienen todos los datos del formulario de Contactos
        axios.defaults.headers = {
        'Content-Type': 'application/json',
    }
        var password = document.getElementById('password').value;
        var email = document.getElementById('email').value;

        axios.post('/verificLogin', {// Se extablece el metodo y la ruta definida el archivo web.php
            password: password,
            usuario: email,
        }, )// Se crea la data que se enviara a traves de la ruta y se le asignan los datos que se obtuvieron del formulario
        .then(function (response) {
            var id_rol = response.data.id_rol;
            localStorage.setItem('id_rol', JSON.stringify(id_rol));
            if(response.data.success == true){
                swal("Verificado","Sus credenciales son correctas","success");
                location.href = "/menu";
            }else{
                swal("Invalido", "Credenciales Incorrectas", "error");
            }
        })
        .catch(function (error) {

        });

    }
</script>
