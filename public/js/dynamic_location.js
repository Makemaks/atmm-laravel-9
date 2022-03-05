dynamic_location_geocoder = new google.maps.Geocoder();

(function( $ ) {

	$.fn.dynamic_location = function( custom_options,region_data ) {
		if(!region_data) {
			return;
		}

		var options = $.extend({
			country_selector: "#country",
			default_country: "us",
			state_selector: "#state",
			state_selector2: "#state2",
			default_state: "ak",
			zip_selector: "#postalCode",
			default_zip: "",
			country_filter: []
		}, custom_options );

		function country_field_change(country) {
			var zip_field = $(options.zip_selector);
			var state_field = $(options.state_selector);
			var state_field2 = $(options.state_selector2);

			state_field.empty();
			state_field2.empty();

			state_field2.append("<option value='' disabled selected>*State/Region<\/option>");
			state_field.append("<option value='' disabled selected>*State/Region<\/option>");
			$.each(region_data[country].states,function(index,val) {
				//state_field.append("<option value='" + val.code + "'>" + val.name + "<\/option>");
				state_field.append("<option value='" + val.name + "'>" + val.name + "<\/option>");
				state_field2.append("<option value='" + val.name + "'>" + val.name + "<\/option>");
			});
		}

		function getState(zipcode) {
			dynamic_location_geocoder.geocode( { 'address': zipcode}, function (result, status) {
				if(!result || !result[0]) {
					$(options.state_selector).val('');
					$(options.state_selector2).val('');
					return;
				}

				var state = "N/A";
				for (var component in result[0]['address_components']) {
					for (var i in result[0]['address_components'][component]['types']) {
						if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1") {
							state = result[0]['address_components'][component]['short_name'];
							$(options.state_selector).val(state);
							$(options.state_selector2).val(state);
							return;
						}
					}
				}
			});
		}

		var country_field = $(options.country_selector);

		if($(country_field).length) {
			country_field.change(function() {
				country_field_change($(this).val());
			});

			country_field.empty();

			//only list countries that are not filtered
			$.each(region_data,function(index,val) {
				if($.isArray(options.country_filter)) {
					if($.inArray(val.code,options.country_filter) === -1) {
						return true;
					}
				}

				var opt = $("<option></option>");

				opt.attr('value',val.code);
				opt.text(val.name);

				country_field.append(opt);
			});

			country_field.val(options.default_country);
			country_field.change();
		}
		else {
			country_field_change(options.default_country);
		}

		$(options.zip_selector).keyup(function() {
			var theval = $(this).val();

			if(theval.length >= 5) {
				getState($(this).val());
			}
		});

		$(options.zip_selector).val(options.default_zip);
		$(options.zip_selector).keyup();
	};

}( jQuery ));