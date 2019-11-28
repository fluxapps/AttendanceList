srAttendanceList = {
	initUserDetails: function (ajax_links) {
		$('tr.xali_user_details_row').each(function (row_index, row) {
			$(row).find('input[type=radio] ~ a.xali_link_save').on('click', function () {
				$(this).hide();
				$(row).find('input[type=radio] ~ span.xali_saving').show();
				status = 0;
				$(row).find('input[type=radio]').each(function (j, radio) {
					if ($(radio).is(':checked')) {
						status = radio.value;
					}
				});

				$.ajax({
					url: ajax_links[row_index]["save_status"],
					type: "GET",
					data: {
						"status": status
					}
				}).done(function () {
					$(row).find('input[type=radio] ~ font.xali_warning').hide();
					$(row).find('input[type=radio] ~ span.xali_saving').hide();
					if ($('a.xali_absence_reason ~ select').length > 0) {
						if (status != 1) {
							$(row).find('a.xali_absence_reason ~ select, a.xali_absence_reason ~ a.xali_link_save').hide();
						} else {
							$(row).find('a.xali_absence_reason ~ select').show();
						}
					} else {
						if (status != 1) {
							$(row).find('a.xali_absence_reason').hide();
						} else {
							$(row).find('a.xali_absence_reason').show();
						}
					}
				});
			});

			$(row).find('input[type=radio]').each(function (k, radio) {
				$(radio).on('click', function () {
					$(row).find('input[type=radio] ~ a.xali_link_save').show();
					// $(row).find('input[type=radio] ~ font.xali_warning').show();
				});

			});

			$(row).find('a.xali_absence_reason ~ select').each(function (k, radio) {
				$(radio).on('change', function () {
					$(row).find('a.xali_absence_reason ~ a.xali_link_save').show();
					// $(row).find('a.xali_absence_reason ~ font.xali_warning').show();
				});
			});

			$(row).find('a.xali_absence_reason ~ a.xali_link_save').on('click', function () {
				$(this).hide();
				$(row).find('a.xali_absence_reason ~ span.xali_saving').show();
				absence_reason = $(row).find('a.xali_absence_reason ~ select').val();
				$.ajax({
					url: ajax_links[row_index]["save_absence_reason"],
					type: "GET",
					data: {
						"absence_reason": absence_reason
					}
				}).done(function () {
					$(row).find('a.xali_absence_reason ~ font.xali_warning').hide();
					$(row).find('a.xali_absence_reason ~ span.xali_saving').hide();
				});
			});
		});
	}
}

