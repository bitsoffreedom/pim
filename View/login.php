<div id="content">
<?php
if ($wrongdata)
	echo "<p>De username/password combinatie is niet geldig</p>";
?>
	<p>Voer uw login gegevens in</p>
	<form action="login" method="post">
	Username <input type="text" name="username" value="<?=$username?>"/><br />
	Password <input type="password" name="password" /><br />
	<input  type="submit" value="login" />
	</form>
</div>
