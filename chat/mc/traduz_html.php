<html>

<head>
<script>
	function converte(){
		document.getElementById("retorno").innerHTML=document.getElementById("texto").value;
		return false;
	}
</script>
</head>

<body>
	<div style="width:45%;float:left;border:1px solid black;height:100%">
	<form method="post">
		<textarea id="texto" name="texto" rows=20 style="width:95%;height:80%;"></textarea>
		<button id="botao" onclick="converte()" type="button">Converter</button>
	</form>
	</div>
	
	<div id="retorno" style="width:45%;float:left;border:1px solid black;height:100%">
	</div>
</body>

</html>