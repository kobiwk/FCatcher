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
