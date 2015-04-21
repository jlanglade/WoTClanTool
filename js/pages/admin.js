var onLoad = function() {
	checkConnected();
	advanceProgress(i18n.t('loading.claninfos'));
	setNavBrandWithClan();
	$('#btnAddAdmin, #btnAddClan').on('click', function(evt) {
		evt.preventDefault();
	});
	$('#btnClusters [data-cluster]').on('click', function(evt) {
		evt.preventDefault();
		$(this).toggleClass('active');
		if ($(this).parent().find('.active').length == 1) {
			$('.selCluster').hide().find('select').val($(this).data('cluster'));
		} else {
			$('.selCluster').show();
		}
	});
	// Handle search of clan
	$('#txtSearchClan').on('keyup', function(evt) {
		if ($(this).val().length > 2) {
			$('#btnSearchClan').removeAttr('disabled');
		} else {
			$('#btnSearchClan').attr('disabled', 'disabled');
		}
	}).keyup();
	$('#btnSearchClan').on('click', function(evt) {
		evt.preventDefault();
		var lSelectedCluster = $('#dlgSearchClan .selCluster select').val(),
			lAPIKey = gConfig.CLUSTERS[lSelectedCluster].key,
			lAPIUrl = gConfig.CLUSTERS[lSelectedCluster].url;
		$.post(lAPIUrl + 'wgn/clans/list/', {
			'application_id': lAPIKey,
			'language': gConfig.LANG,
			'search': $('#txtSearchClan').val(),
			'order_by': 'tag',
			'limit': 10
		}, function(dataSearchClanResponse) {
			var dataSearchClan = dataSearchClanResponse.data,
				resultHtml = '',
				i = 0,
				myClan = {};
			if (dataSearchClan.length == 0) {
				resultHtml = '';
			} else {
				for (i=0; i<dataSearchClan.length; i++) {
					myClan = dataSearchClan[i];
					resultHtml += '<li data-id=\"' + myClan.clan_id + '\" data-cluster="' + lSelectedCluster + '"><img src=\"' + myClan.emblems.x24.portal + '\" /><span style=\"color:' + myClan.color + '\">[' + myClan.tag + ']</span> <small>' + myClan.name + '</small></li>';
				}
			}
			$('#searchClanResult').html(resultHtml);
		}, 'json');
	});
	$('#searchClanResult').on('click', 'li', function(evt) {
		var myItem = $(this),
			resultHtml = '';
		if ($('#restrictedClans').find('[data-cluster=' + myItem.data('cluster') + '][data-id=' + myItem.data('id') + ']').length == 0) {
			resultHtml += '<div class="alert alert-material-grey alert-dismissible clan cluster' + myItem.data('cluster') + '" role="alert" data-id="' + myItem.data('id') + '" data-cluster="' + myItem.data('cluster') + '">';
			resultHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="' + i18n.t('btn.close') + '"><span aria-hidden="true">&times;</span></button>';
			resultHtml += '<p>' + myItem.html() + '</p>';
			resultHtml += '</div>';
			$('#restrictedClans').append(resultHtml);
		}
	});
	// Handle remove of clan
	$('.clan').on('close.bs.alert', function(evt) {
	});
	// Handle search of player
	$('#txtSearchPlayer').on('keyup', function(evt) {
		if ($(this).val().length > 2) {
			$('#btnSearchPlayer').removeAttr('disabled');
		} else {
			$('#btnSearchPlayer').attr('disabled', 'disabled');
		}
	}).keyup();
	$('#btnSearchPlayer').on('click', function(evt) {
		evt.preventDefault();
		var lSelectedCluster = $('#dlgSearchPlayer .selCluster select').val(),
			lAPIKey = gConfig.CLUSTERS[lSelectedCluster].key,
			lAPIUrl = gConfig.CLUSTERS[lSelectedCluster].url;
		$.post(lAPIUrl + 'wot/account/list/', {
			'application_id': lAPIKey,
			'language': gConfig.LANG,
			'type': 'startswith',
			'search': $('#txtSearchPlayer').val(),
			'limit': 10
		}, function(dataSearchPlayerResponse) {
			var dataSearchPlayer = dataSearchPlayerResponse.data,
				resultHtml = '',
				i = 0,
				myPlayer = {};
			if (dataSearchPlayer.length == 0) {
				resultHtml = '';
			} else {
				for (i=0; i<dataSearchPlayer.length; i++) {
					myPlayer = dataSearchPlayer[i];
					resultHtml += '<li data-id=\"' + myPlayer.account_id + '\" data-cluster="' + lSelectedCluster + '">' + myPlayer.nickname + '</li>';
				}
			}
			$('#searchPlayerResult').html(resultHtml);
		}, 'json');
	});
	$('#searchPlayerResult').on('click', 'li', function(evt) {
		var myItem = $(this),
			resultHtml = '';
		if ($('#listAdmins').find('[data-cluster=' + myItem.data('cluster') + '][data-id=' + myItem.data('id') + ']').length == 0) {
			resultHtml += '<div class="alert alert-material-grey alert-dismissible player cluster' + myItem.data('cluster') + '" role="alert" data-id="' + myItem.data('id') + '" data-cluster="' + myItem.data('cluster') + '">';
			resultHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="' + i18n.t('btn.close') + '"><span aria-hidden="true">&times;</span></button>';
			resultHtml += '<p>' + myItem.html() + '</p>';
			resultHtml += '</div>';
			$('#listAdmins').append(resultHtml);
		}
	});
	// Handle remove of admin
	$('.player').on('close.bs.alert', function(evt) {
	});
	var mySliderInactivityThreshold = $('#sliderInactivityThreshold'),
		myBadgeInactivityThreshold = $('#badgeInactivityThreshold');
	mySliderInactivityThreshold.noUiSlider({
		start: parseInt(gConfig.THRESHOLDS_MAX_BATTLES),
		step: 1,
		range: {
			min: 0,
			max: 60
		}
	});
	// Update inactivity threshold value on slide
	mySliderInactivityThreshold.on({
		'slide': function(evt) {
			myBadgeInactivityThreshold.text(i18n.t('install.inactivitythreshold.value', { count: parseInt(mySliderInactivityThreshold.val()) }));
		},
		'set': function(evt) {
			// Sets the inactivity threshold on server
		}
	});
	afterLoad();
};