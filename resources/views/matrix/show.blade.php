@extends('layouts.master')

@section('title', 'Backend')

@section('content')
	<h1>Bienvenido a la prueba de "Cube Summation"</h1>
	<div align="center" class="col-md-8 col-md-offset-2">
		
	  	@if ($result != null)
		 <h3>Los resultados para el archivo de pruebas ejecutado son:</h3>
			@for ($i=0;$i<count($result);$i++)
														  <?= $result[$i] ?></br>
			@endfor
		@endif
		<h3>Desea ejecutar otra prueba?</h3>
		{{ Form::open(array('url' => action('MatrixController@show'), 'method' => 'POST','files' => true)) }}
        <p>{{ Form::label('test',  'Por favor suba el archivo con el script de la prueba')}}</p>

        <p>{{ Form::file('testfile') }}</p>

        <p>{{Form::submit('Ejecutar Prueba')}}</p>

    {{ Form::close() }}

	</div>
	
@endsection

