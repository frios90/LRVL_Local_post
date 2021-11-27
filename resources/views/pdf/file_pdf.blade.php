

<!DOCTYPE html>
@php
 $total =152222;
 
 function a () {
     return 'dsadas';
 }
 function CLEAR_WORD ($word) {
    $word = $word;
    $word = str_replace('{"', '', $word);
    $word = str_replace('":""}', '', $word);
    $word = str_replace('\u00ed', 'í', $word);
    $word = str_replace('\u00f3', 'ó', $word);

    $word = str_replace('\/', ' ', $word);

    return $word;
 }

 function CLEAR_ACUTE ($word) {
    $word = $word;   
    $word = str_replace('\u00ed', 'í', $word);
    $word = str_replace('\u00f3', 'ó', $word);
    return $word;
 }
@endphp
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta charset="ISO-8859-1"> 
        <title>Documento</title>
        <style>
            html {
                font-family: sans-serif;
                line-height: 1.15;
                -webkit-text-size-adjust: 100%;
                -webkit-tap-highlight-color: transparent;
            }
            body{
                padding-top: 25px!important;
            }
            .titulo-pdf{
                text-align: center!important;
                font-size: 18px!important;
                color: #c4b01f !important;
                margin-bottom: 50px!important;
            }
            .titulo-seccion{            
                background-color: #189f26!important; 
                padding: 3px 10px 3px 15px!important;
                border-bottom: solid 2px #ff5733!important;
            }
            .titulo-seccion span{ 
                font-size: 12px!important;
                color: #fff!important;
                font-weight: 200!important;
                letter-spacing: 0.1em!important;
            }
            .seccion-contenido {
                padding: 25px 50px 25px!important;
                margin-bottom: 25px!important;
                background-color: #fdf8fa!important;
            }
            .row-question {
                /* height:20px!important; */
            }
            .qa-question {
                width: 35%!important;
                padding: 3px 10px!important;
                font-size: 8px!important;
                font-weight: 200!important;
                letter-spacing: 0.1em!important;
                background-color: #c4b01f!important; 
                color: #555!important;
            }
            .qa-answer {
                width: 55%!important;
                padding: 3px 10px!important;
                font-size: 8px!important;
                color: #666!important;
                font-weight: 200!important;
                border: 0.5px solid #f4e04d!important; 
            }
            .qa-only-answer {
                width: 100%!important;
                padding: 3px 10px!important;
                font-size: 8px!important;
                color: #666!important;
                font-weight: 200!important;
                border: 0.5px solid #f4e04d!important; 
            }
            .inlineblock {
                display: inline-block!important;
            }
            .qamas-question {
                width: 100%!important;
                font-size: 8px!important;
                color: #999!important;
                font-weight: 200!important;
                letter-spacing: 0.1em!important;
            }
            .qamastabla {
                margin-top:25px!important;
                width: 100%!important;
            }
            .qamastabla thead tr th {
                background-color: #c4b01f!important; 
                color: #fff!important;
                font-size: 8px!important;
                padding: 3px 10px!important;
            }
            .qamastabla tbody tr td {
                background-color: #fff!important; 
                color: #555!important;
                font-size: 8px!important;
                text-align: center!important;
                padding: 3px 10px!important;
            }
            .datos-personales-row {
            }
            .datos-personales-row div{
                width: 50%!important;
                font-size: 11px!important;
            }
            .div-radio {
                padding: 5px 15px!important;
            }
            .img-iris-in-the-table {
                height: 150px!important;
                width: 150px!important;
            }
            .folio-date {
                margin-bottom: 50px;
            }
        </style>
    </head>
    <body>
        <div class="folio-date">
            Folio: {{$person_questionnaire->folio}} <br>
            Fecha: {{$person_questionnaire->created_at}}
        </div>      
        <div class="titulo-pdf">
            Anamnesis Holística
        </div>
        <div class="seccion">
            <div class="titulo-seccion">
                <span class="">Datos Personales</span>
            </div>
            <div class="seccion-contenido">
               
                <div class="datos-personales-row">
                    <div class="inlineblock">
                         Nombre: {{$person->name}}
                    </div>
                    <div class="inlineblock">
                         Rut: {{$person->rut}}
                    </div>
                </div>
                <div class="datos-personales-row">
                    <div class="inlineblock">
                        Edad: pendiente por function
                    </div>
                    <div class="inlineblock">
                        Genero: pendiente por function
                    </div>
                </div>
                <div class="datos-personales-row">
                    <div class="inlineblock">
                        Fecha de Nacimiento: {{$person->birthdate}}
                    </div>
                    <div class="inlineblock">
                        Teléfono: {{$person->phone}} 
                    </div>
                </div>
                <div class="datos-personales-row">
                    <div class="inlineblock">
                        Correo: {{$person->email}}
                    </div>
                  
                </div>
                
            </div>   
        </div>

        <div>

        @foreach ($questionnaire->sections as $key_section => $section)
            <div class="seccion">
                <div class="titulo-seccion">
                    <span class=""> {{$section->description}}</span>
                </div>
                
                <div class="seccion-contenido">
                    @foreach ($section->questions as $key_question => $question)
                        @if ($question->code->code == 'QA')
                            @if (count($section->questions) > 1)
                                <div class="row-question">
                                    <div class="qa-question inlineblock">
                                        {{$question->question}} 
                                    </div>
                                    
                                    <div class="qa-answer inlineblock">
                                        {{$question->for_input}}
                                    </div> 
                                </div>
                            @else
                                <div>                                   
                                    <div class="qa-only-answer inlineblock">
                                        {{$question->for_input}}
                                    </div> 
                                </div>
                            @endif
                        @endif
                        @if ($question->code->code == 'QA+')
                            @if (count($section->questions) > 1)
                                <div class="qamas-question">
                                    {{$question->question}} 
                                </div> 
                            @endif                    
                            <div >
                                <table class="qamastabla">
                                    <thead>                            
                                        <tr>
                                        @foreach (json_decode($question->detail) as $key_detail => $detail)
                                            <th >
                                                {{CLEAR_ACUTE(CLEAR_WORD(json_encode($detail)))}}
                                            </th>
                                        @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (json_decode($question->for_input) as $key_for_input => $for_input)
                                            <tr>
                                                @foreach ($for_input as $key_element => $element)
                                                    <td>
                                                        {{$element}}
                                                    </td> 
                                                @endforeach                                                                                                         
                                            </tr>                                        
                                        @endforeach   
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        @if ($question->code->code == 'RADIOS')
                            <div class="row-question">
                                <div class="qa-question inlineblock">
                                    {{$question->question}} 
                                </div>                                
                                <div class="qa-answer inlineblock">
                                    {{$question->for_input}}
                                </div> 
                            </div>
                        @endif
                        @if ($question->code->code == 'COMBOBOX')
                            <label >{{ $question->question }}</label> 
                            @foreach (json_decode($section->for_input) as $key_selected_check => $selected_check)
                                <div>
                                    <label>{{ $selected_check }}</label>
                                </div>    
                            @endforeach
                        @endif
                        @if ($question->code->code == 'SELECTED')
                            <label >{{ $question->question }}</label> {{ $question->for_input }}                            
                        @endif

                        @if ($question->code->code == 'IRIS')
                            @if (count($section->questions) > 1)
                                <div class="qamas-question">
                                    {{$question->question}} 
                                </div> 
                            @endif                    
                            <div >
                                <table class="qamastabla">
                                    <thead> 
                                        <tr>
                                            <th>Ojo Derecho</th>      
                                            <th>Ojo Izquierdo</th>  
                                        </tr>  
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <img class="img-iris-in-the-table" src="{{ public_path('/img/fotos/ojod.jpg')}}">
                                            </td>
                                            <td>
                                                <img class="img-iris-in-the-table"  src="{{ public_path('/img/fotos/ojoi.jpg')}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            @foreach (json_decode($images) as $image)
                                                @if ($image->name == 'derecho.jpg')
                                                    <td>
                                                        <img class="img-iris-in-the-table" src="{{ public_path($image->path) }}">
                                                    </td>
                                                
                                                @endif
                                            @endforeach
                                            @foreach (json_decode($images) as $image)
                                                @if ($image->name == 'izquierdo.jpg')
                                                    <td>
                                                        <img class="img-iris-in-the-table" src="{{ public_path($image->path) }}">
                                                    </td>                                                    
                                                @endif
                                            @endforeach
                                           
                                        </tr>
                                    </tbody>
                                    <thead> 
                                                                 
                                        <tr>
                                        @foreach (json_decode($question->detail) as $key_detail => $detail)
                                            <th >
                                                {{CLEAR_ACUTE(CLEAR_WORD(json_encode($detail)))}}
                                            </th>
                                        @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (json_decode($question->for_input) as $key_for_input => $for_input)
                                            <tr>
                                                @foreach ($for_input as $key_element => $element)
                                                    <td>
                                                        {{$element}}
                                                    </td> 
                                                @endforeach                                                                                                         
                                            </tr>                                        
                                        @endforeach   
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
            
        </div>
    </body>
</html>

