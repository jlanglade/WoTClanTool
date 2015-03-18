var onLoad = function() {
	var myURI = URI(window.location.href),
		myURIParams = myURI.search(true);
	$('#btnLogin').on('click', function(evt) {
		evt.preventDefault();
		$.post(gWG_API_URL + 'auth/login/', {
				'application_id': gWG_APP_ID,
				'language': $('html').attr('lang'),
				'redirect_uri': myURI.href(),
				'nofollow': 1,
				// Expires in 2 weeks (maximum session time)
				'expires_at': Math.floor((new Date() / 1000) + 8690400),
				'display': 'page'
			}, function(data) {
				if (data.data.location.indexOf(window.location.href) == -1) {
					window.location.href = data.data.location;
				}
			}, 'json');
	});
};
