	const navItems = document.querySelectorAll(".navbar .nav-links:nth-child(2) li a");
	navItems.forEach(navitem => {
		navitem.classList.remove("active");
	});
	if (document.querySelector(".titleOfArticle")) {
		document.title = `Reading // ${document.querySelector(".titleOfArticle").textContent}`;
	}

	const commentView = document.querySelector(".comment-view .comments"),
		commentS = document.querySelectorAll(".comment-view .comment-item"),
		reactIcon = document.querySelectorAll(".reaction .reactIcon span"),
		aboutAuthor = document.querySelector(".author-details .about-author");

	if (reactIcon && commentS && commentView) {
		reactIcon.forEach(Ricon => {
			Ricon.addEventListener("click", (e) => {
				if (Ricon.classList.contains("fa-comments")) {
					document.querySelector(".comment-view").classList.add("active");
				}
				if (Ricon.classList.contains("fa-user-tag")) {
					aboutAuthor.classList.add("active");
				}
				if (Ricon.classList.contains("fa-heart")) {
					Ricon.className = "fas fa-heart";
				}
			})
		})
		document.addEventListener("click", (event) => {
			if (!reactIcon[1].contains(event.target) && !commentView.contains(event.target)) {
				document.querySelector(".comment-view").classList.remove("active");
				document.querySelectorAll(".reaction .reactIcon span .badge")[1].textContent = commentS.length;
			}
			if (!reactIcon[2].contains(event.target) && !aboutAuthor.contains(event.target)) {
				aboutAuthor.classList.remove("active");
			}
		});

		if (document.querySelectorAll(".reaction .reactIcon span .badge")[1]) {
			document.querySelectorAll(".reaction .reactIcon span .badge")[1].textContent = commentS.length;
		}
		// likes system for preventing morethan one click
		let likes = document.querySelector('.reactIcon .fa-heart');
		if (likes) {
			let loveReact = document.querySelector('main article'),
				ReactLove = document.querySelector('article .react-love');

			function increment() {
				let like = parseInt(likes.querySelector('.badge').textContent);
				document.querySelector('.reactIcon .badge').textContent = like + 1;
			}

			loveReact.ondblclick = (e) => {
				if (!likes.contains(e.target) && !ReactLove.classList.contains('love')) {
					ReactLove.classList.add('love');
					setTimeout(() => {
						ReactLove.classList.remove('love');
					}, 2000);
					increment();
					likes.click();
				}
			}
			likes.onclick = () => {
				increment();
				likes.onclick = null;
			}
		}
		document.addEventListener("keydown", (e)=> {
			if ((e.ctrlKey && e.key === 's') ||
			    (e.ctrlKey && e.key === 'p')) {
				e.preventDefault();
				printDiv();
            }
			 if(document.querySelectorAll('.otherSources .dataReleted a .card').length > 0){
            if (e.keyCode === 39) {
            document.querySelectorAll('.otherSources .dataReleted a')[0].click();
            }
        }
		});
		function printDiv() {
			var mainArticle = document.getElementById("mainArticle");
			var printableContent = mainArticle.innerHTML;
			var images = mainArticle.getElementsByTagName("img");
			for (var i = 0; i < images.length; i++) {
			  var img = images[i];
			  var canvas = document.createElement("canvas");
			  var context = canvas.getContext("2d");
			  canvas.width = img.width;
			  canvas.height = img.height;
			  context.drawImage(img, 0, 0, img.width, img.height);
			  var dataURL = canvas.toDataURL("image/png");
			  printableContent = printableContent.replace(img.src, dataURL);
			}
		  
			var printWindow = window.open('', '_blank');
			printWindow.document.open();
			printWindow.document.write('<html><head><title>'+document.title+'</title></head><body>' + printableContent + '</body></html>');
			printWindow.document.close();
			printWindow.print();
			printWindow.close();
		  }
	}