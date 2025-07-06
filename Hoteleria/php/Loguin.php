<html>

<head>
    <title>Ecommerce</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
    <link href="../../css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../../css/font-awesome.min.css">


    <style type="text/css">
        body {
            background: url('../../img/fon.jpg');
            overflow-y: scroll;
        }

        #log {

            padding: 60px 40px;
            margin-top: 80px;
            -webkit-box-shadow: -5px 2px 62px 8px rgba(0, 0, 0, 0.75);
            -moz-box-shadow: -5px 2px 62px 8px rgba(0, 0, 0, 0.75);
            box-shadow: -5px 2px 62px 8px rgba(0, 0, 0, 0.75);
        }

        img {
            width: 220px;
            margin: auto;
        }

        h1 {
            color: #FFFF;
            text-align: center;
            font-weight: bolder;
            margin-top: -20px;
        }

        label {
            font-size: 15px;
            color: #FFFF;
        }

        button {

            -webkit-box-shadow: -5px 2px 62px 8px rgba(0, 0, 0, 0.75);
            -moz-box-shadow: -5px 2px 62px 8px rgba(0, 0, 0, 0.75);
            box-shadow: -5px 2px 62px 8px rgba(0, 0, 0, 0.75);
            color: black
        }
    </style>

</head>

<body>
    <div class="container-fluid bg">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-xs-12"></div>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <form id="log" class="" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <h1 style="font-size:2em;">ADMINROKUS</h1>
                    <img class="img img-responsive img-circle" src="../../img/rokulog.jpg">
                    <div class="form-group">
                        <label>Usuario</label>
                        <input type="text" name="nombreusuario" class="form-control" placeholder="user">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Contraseña Usuario">
                    </div>


                    <?php if (!isset($_SESSION['userEcomer'])) {
                        require 'btn.php';
                    } ?>
                    <?php if (!empty($enviar)): ?>
                        <div class="enviar">
                            <?php echo $enviar;  ?>
                        </div>
                        <?php echo $enviado; ?>
                    <?php endif; ?>
                    <br>
                    <?php if (!empty($error)): ?>
                        <br>
                        <div class="error">
                            <?php echo $error ?>
                        </div>
                    <?php endif; ?>

                </form>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-12"></div>
        </div>
    </div>
</body>

<script src="../../js/jquery-3.2.1.min.js"></script>
<script src="../../js/bootstrap.min.js"></script>

</html>