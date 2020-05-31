<!DOCTYPE html>
<html lang="en">
<body>
	<form action="inbound.php" method="POST" enctype="multipart/form-data">
		Select image to upload: <input type="file" name="image"/><br/><br/>
		Location: <input type="text" name="location"/><br/><br/>
		Date Taken: <input type="date" name="date_taken"/><br/><br/>
		<input type="submit" name="submit" value="UPLOAD"/>
	</form>
</body>
</html>
