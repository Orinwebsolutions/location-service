<div class="find-location-results-container">
	<!-- step 3 -->
	<div id="row_centre_search_results" class="row-content row-centre-search-results text-center">
		<div class="wrap">
			<div class="row">
				<div id="map-locations"></div>
			</div>
            <div class="row">
    			<div class="results-container hidden vc_col-xs-12" id="target"></div>
            </div>
			<script id="template" type="x-tmpl-mustache">
				<div class='main_results vc_col-sm-10 vc_col-sm-offset-1'>
					<h2 class="sect_title text-center">Nearest RSPCA Shelters</h2>
					<div class="vc_col-sm-8 vc_col-sm-offset-2">
					<p class="sect_description text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
					</div>
					{{#LocationsResults}}
					<div class="vc_col-xs-12 vc_col-sm-4 left js-box result">
						{{#location_url}}<h4 class="title"><a href="{{ location_url }}" target="_blank">{{ post_title }}</a></h4>{{/location_url}}{{^location_url}}<h4 class="title">{{ post_title }}</h4>{{/location_url}}
						<p class="service"><b>Services available: {{ location_service }}</b></p>
						<p class="address">{{{ location_address }}}</p>
						{{#location_phone}}<p class="phone">Phone: <a href="tel:{{ location_phone }}">{{ location_phone }}</a></p>{{/location_phone}}
						{{#location_email}}<p class="email">Email: <a href="mailto:{{ location_email }}">{{ location_email }}</a></p>{{/location_email}}
					</div>
					{{/LocationsResults}}
				</div>

				<div class="row error-message-container error-message hidden">
					<h2 class="sect_title text-center">Nearest RSPCA Shelters</h2>
		    		<div class="vc_col-sm-8 vc_col-sm-offset-2 text-center">There are no RSPCA locations within the <span id="distance"></span>km radius. Please enter a new postcode or <a id="anchor_contact_form" class="defaultLink" href="#contact-us">contact us</a> directly. Thanks!</div>
		    	</div>
				<br clear="all"/>
				<div class='secondary_results vc_col-sm-10 vc_col-sm-offset-1'>
					<h2 class="sect_title text-center">Other Locations</h2>
					<div class="vc_col-sm-8 vc_col-sm-offset-2">
					<p class="sect_description text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
					</div>
					{{#LocationsResultsExceptShelters}}
					<div class="vc_col-xs-12 vc_col-sm-4 left js-box result">
						{{#location_url}}<h4 class="title"><a href="{{ location_url }}" target="_blank">{{ post_title }}</a></h4>{{/location_url}}{{^location_url}}<h4 class="title">{{ post_title }}</h4>{{/location_url}}
						<p class="service"><b>Services available: {{ location_service }}</b></p>
						<p class="address">{{{ location_address }}}</p>
						{{#location_phone}}<p class="phone">Phone: <a href="tel:{{ location_phone }}">{{ location_phone }}</a></p>{{/location_phone}}
						{{#location_email}}<p class="email">Email: <a href="mailto:{{ location_email }}">{{ location_email }}</a></p>{{/location_email}}
					</div>
					{{/LocationsResultsExceptShelters}}
				</div>
				<div class='no_results vc_col-sm-10 vc_col-sm-offset-1'>
					<h2 class="sect_title text-center">Other Results</h2>
					<div class="vc_col-sm-8 vc_col-sm-offset-2">
					<p class="sect_description text-center">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
					</div>
					{{#LocationsNoResult}}
					<div class="vc_col-xs-12 vc_col-sm-4 left js-box result">
						{{#location_url}}<h4 class="title"><a href="{{ location_url }}" target="_blank">{{ post_title }}</a></h4>{{/location_url}}{{^location_url}}<h4 class="title">{{ post_title }}</h4>{{/location_url}}
						<p class="service"><b>Services available: {{ location_service }}</b></p>
						<p class="address">{{{ location_address }}}</p>
						{{#location_phone}}<p class="phone">Phone: <a href="tel:{{ location_phone }}">{{ location_phone }}</a></p>{{/location_phone}}
						{{#location_email}}<p class="email">Email: <a href="mailto:{{ location_email }}">{{ location_email }}</a></p>{{/location_email}}
					</div>
					{{/LocationsNoResult}}
				</div>
			</script>
		</div>
	</div>
</div>
