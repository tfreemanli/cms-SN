
<html>
<head>
	<title>Combobox</title>
	<meta charset="utf-8">
	<style type="text/css">
		*{
			box-sizing:border-box;
		}
		input,select{
			width: 200px;
		}
		#words{
			display: none;
		}
	</style>
</head>
<body>
	<input id="show" type="text" name=""> <br />
	<select id="words">
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>G</option>
	</select>
 
	<script type="text/javascript">
		let show = document.getElementById('show');
		let words = document.getElementById('words');
		show.onclick = function(e){
			words.style.display = 'block';
		}
		words.size = 3;
		words.onchange = function(e){
			var option = this.options[this.selectedIndex];
			show.value = option.innerHTML;
			words.style.display = 'none';
		}
    </script>
    
	<select id="aaa">
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>7777</option>
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>7777</option>
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>7777</option>
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>7777</option>
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>7777</option>
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>7777</option>
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>7777</option>
		<option>A</option>
		<option>B</option>
		<option>C</option>
		<option>D</option>
		<option>E</option>
		<option>F</option>
		<option>7777</option>
	</select>
</body>
</html>