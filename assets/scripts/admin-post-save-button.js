jQuery(document).ready(function ($) {
	waitForElm(".editor-post-publish-button").then(() => {
		let saveButton = $('.editor-post-publish-button');
		let customButton = document.createElement('button');
		customButton.textContent = 'Send mail';
		customButton.classList.add('components-button', 'editor-post-publish-button', 'editor-post-publish-button__button', 'is-primary');
		customButton.addEventListener('click', function (e) {
			saveButton.trigger('click');
			setTimeout(function(){
				let timer = setInterval(function(){
					if(saveButton.text() === "Gem"){
						console.log('Opdater');
						e.preventDefault();
						let data = {
							action: 'admin_send_booking_mail',
							postID: $("#post_ID").val(),
						};
						$.post(ajaxurl, data, function(response) {
							console.log('Response: ' + response);
							//location.reload();
						});
						clearInterval(timer);
					}
				}, 200);
			}, 300);
		});
		saveButton.after(customButton);
	});
	/*PDF link*/
	let invoice = $("#invoice");
	let pdfLink = invoice.val();
	let anchor = document.createElement("a");
	anchor.href = pdfLink;
	anchor.textContent = "Åbn faktura";
	anchor.target = "_blank";
	document.getElementById("invoice").after(anchor);
	invoice.hide();

	/*Kunde link*/
	let kundeAnchor = document.createElement("a");
	kundeAnchor.href = "https://elmirador.dk/wp-admin/user-edit.php?user_id="+$("#bruger").val()+"&wp_http_referer=%2Fwp-admin%2Fusers.php";
	kundeAnchor.textContent = $("#fornavn").val() + " " + $("#efternavn").val();
	kundeAnchor.target = "_blank";
	document.getElementById("kunde").after(kundeAnchor);
	$("#kunde").hide();
	
	/*Id til locked fields*/
	let idsForLock = ["hus-id", "ankomst-udtjekning", "pris-i-alt", "nights", "pris-for-leje", "pris-extra-services", "subtotal"];
	idsForLock.forEach((id) => {
		let element = $("#"+id);
		let val = element.val();
		if(val == null || val === ""){
			val = 0;
		}
		let p = document.createElement("p");
		p.textContent = val;
		document.getElementById(id).after(p);
		element.hide();
	});
});


function waitForElm(selector) {
	return new Promise(resolve => {
		if (document.querySelector(selector)) {
			return resolve(document.querySelector(selector));
		}

		const observer = new MutationObserver(() => {
			if (document.querySelector(selector)) {
				resolve(document.querySelector(selector));
				observer.disconnect();
			}
		});

		observer.observe(document.body, {
			childList: true,
			subtree: true
		});
	});
}