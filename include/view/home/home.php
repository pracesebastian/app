<section id="home">
	<div class="container">
		<div class="row">
			<?php $home = new Home(); ?>
			<div class="err text-center"><?php $home->show_error(); ?></div>
			<form action="home/convert" method="POST" enctype="multipart/form-data">
				<input class="input-style col-xs-12" name="file" type="file">
				<input class="input-style col-xs-12" name="outout_name" type="text" placeholder="Nowa nazwa pliku">
				<select class="input-style col-xs-12" name="output_extension">
					<option value="choose">Wybierz rozszerzenie do jakiego pliku chcesz zapisać</option>
					<option value="csv">CSV</option>
					<option value="sql">SQL</option>
					<option value="xml">XML</option>
				</select>
				<button type="submit">Konwertuj</button>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
	
</section>