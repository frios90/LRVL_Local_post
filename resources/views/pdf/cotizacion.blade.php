

<!DOCTYPE html>
@php
 function CAL_ITEM_PRICE ($data, $item) {
    $uf         = $data->uf;
    $percentage = $data->percentage_uf;
    $price      = round($item->qty * (($uf/100)* $percentage));
    return MONEDA($price);
 }

 function MONEDA($numero){
    $numero = (string)$numero;
    $puntos = floor((strlen($numero)-1)/3);
    $tmp = "";
    $pos = 1;
    for($i=strlen($numero)-1; $i>=0; $i--){
        $tmp = $tmp.substr($numero, $i, 1);
        if($pos%3==0 && $pos!=strlen($numero))
        $tmp = $tmp.".";
        $pos = $pos + 1;
    }
    $formateado = "$ ".strrev($tmp);
    return $formateado;
}
@endphp
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Cotización</title>
        <style class="">        
        
        </style>
    </head>
    <body>
       <div style="margin: 15px 50px;">

            <div class="background-title-company" style="margin-left: -50px; color: #fff; border-radius: 0px 200px 200px 0px; padding: 10px 150px 25px 25px;background-color: rgba(29, 51, 68, 1);  float: left">
                <span style="font-size: 18px; font-weight: 600;">Chinook</span><br>
                <span style="font-size: 10px; font-weight: 200;">Importadora de repuestos automotrices</span><br>
            </div> 
        
            <table style="border-radius: 10px 10px 10px 10px; padding: 5px 5px 25px 5px;border: solid 2px rgba(29, 51, 68, 1); width: 35%; float: right">
                <tbody>
                    <tr>
                        <th colspan="2">
                            <h2 style="text-align:center;  color:rgba(29, 51, 68, 1); font-size:12px;padding: 5px 15px;">
                                Cotización
                            </h2>
                        </th>
                    </tr>
                    <tr style="">
                        <th style=" font-size: 11px!important; text-align:center; width: 20%">N° Cotización</th>
                        <td style="font-size: 11px!important; text-align:center; width: 20%">{{ $data->number }}</td>
                    </tr>
                    <tr>
                        <th style="font-size: 11px!important; text-align:center; width: 20%">Emitida</th>
                        <td style="font-size: 11px!important; text-align:center; width: 20%">{{ $data->created_at }}</td>
                    </tr>  
                          
                </tbody>
            </table>     
            
            <table style="width: 75%; margin-top: 150px; margin-bottom: 25margin-left: auto!importantpx; margin-left: auto!important; margin-right: auto!important"> 
                <tr>
                    <th colspan="2" >
                        <h2 style="margin-left:-100px; text-align:left; border-bottom: solid 2px rgba(29, 51, 68, 1); color:rgba(29, 51, 68, 1); font-size:12px;padding: 5px 15px;">
                            Datos de la Empresa
                        </h2>
                    </th>
                </tr>               
                <tr>
                    <th style="border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 100px!important">Dirección</th>
                    <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 350px!important">10 de Julio #483, Stgo., R. Metropolitana</td>
                </tr>
                <tr>
                    <th style="border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important;text-align:left;width: 100px!important">Teléfonos</th>
                    <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 350px!important">{{ $data->user->phone }}</td>
                </tr>
             
                <tr>
                    <th style="border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important;text-align:left;width: 100px!important">Resp. Cotización</th>
                    <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 350px!important">{{ $data->user->name }}</td>
                </tr>
                <tr>
                    <th style="border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important;text-align:left;width: 100px!important">Correo</th>
                    <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 350px!important">{{ $data->user->email }}</td>
                </tr>
            </table>
            
            <table style="width: 75%; margin: 0px auto!important">
                <tr>
                    <th colspan="2">
                        <h2 style="margin-left:-100px;text-align:left; border-bottom: solid 2px rgba(29, 51, 68, 1); color:rgba(29, 51, 68, 1); font-size:12px;padding: 5px 15px;">
                            Datos del Cliente
                        </h2>
                    </th>
                </tr>
                <tr>
                    <th style="border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 100px!important">Cliente</th>
                    <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 350px">{{$data->customer->name}}</td>
                </tr>

                <tr>
                    <th style="border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important;text-align:left;width: 100px!important">Folio</th>
                    <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 350px">{{$data->number}}</td>
                </tr>
                <tr>
                    <th style="border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important;text-align:left;width: 100px!important">Teléfonos</th>
                    <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 350px">{{$data->customer->phone}}</td>
                </tr>
                <tr>
                    <th style="border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important;text-align:left;width: 100px!important">Correos</th>
                    <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);font-size: 10px!important; text-align:left; width: 350px">{{$data->customer->email}}</td>
                </tr>               
               
            </table>

            <table style="width: 100%; margin: 50px 0px">
                <thead style="text-align:left; background-color:rgba(29, 51, 68, 1); color:#fff; font-size:12px;padding: 5px 15px;">
                    <tr>
                        <th style="padding: 3px 10px;font-size: 11px!important;text-align:center;width: 5%">Item</th>
                        <th style="padding: 3px 10px;font-size: 11px!important;text-align:left;width: 55%">Descripción del item</th>
                        <th style="padding: 3px 10px;font-size: 11px!important;text-align:right;width: 20%">Cantidad</th>
                        <th style="padding: 3px 10px;font-size: 11px!important;text-align:right;width: 20%">Precio total item</th>    
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->detail as $key_item => $item) 
                    <tr>
                        <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);padding: 3px 10px;font-size: 12px!important;text-align:center;width: 5%">{{ $key_item + 1 }}</td>
                        <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);padding: 3px 30px;font-size: 12px!important;text-align:left;width: 55%">{{ $item->name }}</td>
                        <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);padding: 3px 10px;font-size: 12px!important;text-align:right;width: 20%">{{ $item->qty }}</td>
                        <td style="border-left:solid 1px rgba(29, 51, 68, 1);border-bottom:solid 1px rgba(29, 51, 68, 1);padding: 3px 10px;font-size: 12px!important;text-align:right;width: 20%">{{ MONEDA($item->total) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td  style="background-color:rgba(29, 51, 68, 1); color: #fff; padding: 3px 10px;font-size: 11px!important;text-align:right;width: 5%">Neto</td>
                        <td style="text-align: right;">{{MONEDA($data->base)}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="background-color:rgba(29, 51, 68, 1); color: #fff;padding: 3px 10px;font-size: 11px!important;text-align:right;width: 5%"> Iva</td>
                        <td style="text-align: right;">{{MONEDA($data->iva)}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td style="background-color:rgba(29, 51, 68, 1); color: #fff;padding: 3px 10px;font-size: 11px!important;text-align:right;width: 5%">Total</td>
                        <td style="text-align: right;">{{MONEDA($data->total)}}</td>
                    </tr>
                </tbody>
            </table>

            <div style="text-align: center; font-size: 12px; color: rgba(29, 51, 68, 1)">
                Esperando que el presente contenido sea de su utilidad, quedamos atentos a cualquier duda.
            </div>
            <div style="text-align: center; font-size: 14px; color: rgba(29, 51, 68, 1)">
                Cotización valida por 30 días a partir de la fecha de emisión.
            </div>             
            
       </div>
    </body>
</html>
