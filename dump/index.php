<!DOCTYPE html>
<html>
<head>
	<title>RSS feed practice</title>
	<script type="text/javascript">
		function showRSS(str) {
			if (str.length == 0) {
				document.getElementById('rssOutput').innerHTML = '';
				return;
			}

			if (window.XMLHttpRequest) {
			xmlhttp = new XMLHttpRequest;
			}

			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById('rssOutput').innerHTML = this.responseText;
				}
			}

			xmlhttp.open('GET', 'getrss.php?q=' + str, true);
			xmlhttp.send();
		}




	</script>
</head>
<body>
	<form>
		<select id="RSSoption" onchange="showRSS(this.value)">
			<option value=''>Select an RSS feed: </option>
			<option value="Google">Google news</option>
			<option value="Pitchfork">Pitchfork reviews</option>
			<option value="JSNews">JS news</option>
			<option value="Idioteq">IdioteQ</option>
		</select>
	</form>
	<br>
	<div id="rssOutput">Rss-feed will be listed here..</div>
</body>
</html>