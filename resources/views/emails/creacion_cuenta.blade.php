


<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Reset Password Email Template</title>
    <meta name="description" content="Reset Password Email Template.">
    <style type="text/css">
        a:hover {text-decoration: underline !important;}
        

        ol {
            counter-reset: item
        }
        li {
            display: block
        }
        li:before {
            content: counters(item, ".") " ";
            counter-increment: item
        }
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
    <!--100% body table-->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
        style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
        <tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="100%" border="0"
                    align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                style="max-width:670px;background:#fff; border-radius:3px;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;text-align: center;">
                                            Creación cuenta informe financiero</h1>
                                        <span
                                            style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                            <br>
                                        <h3 style="color:#1e1e2d; font-weight:500; margin:0;font-size:23px;font-family:'Rubik',sans-serif;text-align: center;">
                                            Respetados Prestadores cordial saludo:
                                        </h3>
                                        <br>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;text-align: justify;">
                                            La Vicepresidencia Administrativa y Financiera Con el ánimo de optimizar el flujo de información financiera entre la NUEVA EPS y las Instituciones Prestadoras de Servicios de Salud y dando cumplimiento a lo dispuesto en la Circular Externa 07-02, la NUEVA EPS adecúa sus canales digitales donde las IPS podrán consultar a través de la herramienta web la publicación del estado de cuenta con la información correspondiente a la  siguiendo los lineamientos impartidos por el agente interventor <b>Julio Alberto Rincón Ramirez</b> en relación con la de los  relación contractual de carácter financiero. 
                                        </p>
                                        <br>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;text-align: justify;">
                                            A partir del 15 de noviembre se dará inicio a la operación de la plataforma digital NUEVAEPS – Gestión Financiera, en la que las IPS  dispondrá de periódicamente de:
                                        </p>
                                        <br><br>
                                          
                                        <ol>
                                            <li>Estado de cuenta</li>
                                            <li>Relación de radicación vs pagos 2024</li>
                                            <ol class="sublista">
                                                <li>2.1 Detalle de radicación</li>
                                                <li>2.2 Detalle de giro</li>
                                            </ol>
                                            <li>Proyección estimada de giro en el mes </li>
                                        </ol>
                                        <br>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
                                            Con la finalidad de garantizar la seguridad de las transacciones, se hace entrega al prestador del usuario y contraseña de acceso único e intransferible al portal virtual. 
                                        </p>
                                        <br>
                                        <p style="color:#455056; font-size:15px;">
                                            Link Acceso: <a href="https://infinanciera.nuevaeps.com.co/" target="_blank">Información Financiera</a>
                                        </p>
                                        <p style="color:#455056; font-size:15px;">
                                            Usuario : {{$data['nit']}}
                                        </p>
                                        <p style="color:#455056; font-size:15px;">
                                            Contraseña : {{$data['password']}}
                                        </p>
                                        <br><br>
                                        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;text-align: justify;">
                                            En ese sentido estamos invitándolos a un YouTube Live que se realizará el lunes 18 de noviembre a las 3pm, donde se brindará toda la información pertinente para el uso de la plataforma. En este mismo correo electrónico recibirá la invitación con el enlace para la conexión. Confiamos en que esta herramienta consolidará una relación mas transparente y efectiva con nuestra red prestadora.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
</body>

</html>