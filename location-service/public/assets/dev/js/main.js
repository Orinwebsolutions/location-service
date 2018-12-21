(function($) {

    var ajaxNonce = locationservice.ajaxNonce;
    var ajaxUrl = locationservice.ajaxUrl;
    var imgPath = locationservice.imagePath;
    var defaultRadius = locationservice.defaultRadius;
    var API_KEY = locationservice.googleMapsAPI;
    var mainSearchCat = locationservice.mainsearchcategory;
    var userLat = userLng = dataSource = "";
    var lat = '-33.8473567';
    var lng = '150.6517957';
    var z = '8';

    var gravitySubmitBtn = locationservice.gravitySubmitBtn;

    var LocationCenterInit = {
        
        successMessage: function() {
            //check if the query sring has success message
            if (document.location.search.length) {
                var url = window.location.search;
            }
        },
        loadCentres: function() {
            $.ajax({
                type: "POST",
                url: ajaxUrl,
                data: {
                    action: "load_location_data",
                    nonce: ajaxNonce
                },
                success: function(response) {
                    dataSource = JSON.parse(response);
                    LocationCenterFind.setupMaps(lat, lng, z);
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });
        },
        handleEnquiryType: function() {
            var rowFormSelection = ".row-form-selection button";
            var rowCentreSearch = ".row-centre-search";
            var gravityFieldEnquiryType = $(".field-enquiry-type").find("select");
            var gravityConfirmation = ".gform_confirmation_message";

            $(rowFormSelection).on("click", function(event) {
                event.preventDefault();
                $(rowCentreSearch).removeClass("hidden");

                //toggle active class
                $(rowFormSelection).removeClass("button-selected").parent("div").removeClass("button-selected");
                $(this).toggleClass("button-selected").parent("div").toggleClass("button-selected");

                //preselect enquiry type in gravity form based on enquiry type selected
                var selectedOption = $(this).attr("title");
                gravityFieldEnquiryType.val(selectedOption);

                //make the message box mandatory if enquire is selected
                locationCareGravityForm.toggleEnquiryMessage(selectedOption);

            });


            $(gravityFieldEnquiryType).on("change", function(event) {

                locationCareGravityForm.toggleEnquiryMessage($(this).val());

            });
        },
    };

    var LocationCenterFind = {

        finderForm: "#location_finder",
        queryCountry: "Australia",
        postcode: "",
        service: "",
        rowSearchResults: $(".row-centre-search-results"),

        nearestCenter: function() {

            var self = this;

            $(this.finderForm).on("submit", function(event) {

                event.preventDefault();
                locationCareGravityForm.resetCentres();

                $(".submit-success-message").addClass("hidden");

                self.postcode = $("input[name='postcode']").val();

                // get users lat and lng based on postcode provided and filter results within radius
                if (self.postcode != "") {

                    var queryCountry = self.queryCountry;
                    var queryPostcode = self.postcode;

                    $.ajax({
                        type: "GET",
                        url: "https://maps.googleapis.com/maps/api/geocode/json?address=" + queryCountry + "|postal_code:" + queryPostcode + "&sensor=false&key=" + API_KEY,
                        dataType: "json"

                    }).success(function(data) {

                        userLat = data.results[0].geometry.location.lat;
                        userLng = data.results[0].geometry.location.lng;
                        
                        self.filterResults();

                    });
                }

            });

        },
        filterResults: function() {

            var self = this;
            var LocationsResults = [];
            var LocationsResultsExceptShelters = [];
            var LocationsNoResult = [];
            
            var filteredData = {};
            filteredData.LocationsResults = LocationsResults;
            filteredData.LocationsResultsExceptShelters = LocationsResultsExceptShelters;
            filteredData.LocationsNoResult = LocationsNoResult;

            $.each(dataSource, function(key, item) {
                var calculatedCentre = self.isWithInRadius(item.location_latitude, item.location_longitude);
                if (calculatedCentre.isWithInRadius && item.location_category == mainSearchCat) {
                    item.distance = calculatedCentre.distance;
                    LocationsResults.push(item);
                }
                if (calculatedCentre.isWithInRadius && item.location_category != mainSearchCat  && LocationsResultsExceptShelters.length < 9) {
                    LocationsResultsExceptShelters.push(item);
                }
                if (item.location_category != mainSearchCat && LocationsNoResult.length < 5) {
                    LocationsNoResult.push(item);
                }
            });

            LocationsResults.sort(function(a, b) {
                return parseFloat(a.distance) - parseFloat(b.distance);
            });

            for (var i = 0; i < LocationsResults.length; i++) {
                filteredData[LocationsResults[i][0]] = LocationsResults[i][1];
            }
            for (var i = 0; i < LocationsResultsExceptShelters.length; i++) {
                filteredData[LocationsResultsExceptShelters[i][0]] = LocationsResultsExceptShelters[i][1];
            }
            for (var i = 0; i < LocationsNoResult.length; i++) {//Adding first 5 branches for no result display
                filteredData[LocationsNoResult[i][0]] = LocationsNoResult[i][1];
            }

            sherlterResultCount = LocationsResults.length;
            exceptsherlterResultCount = LocationsResultsExceptShelters.length;
            self.renderResult(filteredData, sherlterResultCount, exceptsherlterResultCount);

        },

        renderResult: function(filteredData, sherlterResultCount, exceptsherlterResultCount) {
            //process mustache template rendering
            var template = $('#template').html();
            var target = $("#target");
            
            var rendered = Mustache.render(template, filteredData);
            $(target).html(rendered);
            $('#distance').html(defaultRadius);

            // scroll and show search results
            var rowSearchResults = "#row_centre_search_results";
            var resultsCount = ".results-count";

            $(rowSearchResults).show("fast", function() {
                childCareHelper.scrollTo(rowSearchResults);
                setTimeout(function() {
                    equalHeight('#target .js-box');
                }, 500);
            });
            $('#contact-us').find('.contact_us_form').removeClass('displayForm');
            if (sherlterResultCount <= 0 && exceptsherlterResultCount <= 0) {
                $('.results-container').removeClass('hidden');
                $(".main_results").addClass('hidden');
                $(".secondary_results").addClass('hidden');
                /*$(".no_results").removeClass('hidden');*/ /*Hidding no result section for now*/
                $(".no_results").addClass('hidden'); /*Hidding no result section for now*/
                $(".error-message-container").removeClass("hidden");
            }else if (sherlterResultCount >= 0  && exceptsherlterResultCount <= 0) {
                $('.results-container').removeClass('hidden');
                $(".main_results").removeClass('hidden');
                $(".secondary_results").addClass('hidden');
                /*$(".no_results").removeClass('hidden');*/ /*Hidding no result section for now*/
                $(".no_results").addClass('hidden'); /*Hidding no result section for now*/
                $(".error-message-container").removeClass("hidden");
            }else if( sherlterResultCount <= 0  && exceptsherlterResultCount >= 0 ){
                $('.results-container').removeClass('hidden');
                $(".main_results").addClass('hidden');
                $(".secondary_results").removeClass('hidden');
                $(".no_results").addClass('hidden');
                $(".error-message-container").removeClass("hidden");
            }else {
                $('.results-container').removeClass('hidden');
                $(".main_results").removeClass('hidden');
                $(".secondary_results").removeClass('hidden');
                $(".no_results").addClass('hidden');
                $(".error-message-container").addClass("hidden");
            }

            /*load map with search zip code*/
            var z = '11';
            var initializeMap = this.setupMaps(userLat, userLng, z);

            /*Display hidden contact form*/
             $('#anchor_contact_form').on("click", function(ev) {
                $('#contact-us').find('.contact_us_form').addClass('displayForm');
            });

        },

        //Initialize map object
        setupMaps: function(lat, lng, z) {
            var map;
            map = new GMaps({
                el: '#map-locations',
                lat: lat,
                lng: lng,
                zoom: parseInt(z),
                scrollwheel: false
            });
            map.setOptions({minZoom: 4, maxZoom: 20});
            var bounds = new google.maps.LatLngBounds();
            for (l in dataSource) {
                var currentLocation = dataSource[l];
                var posttitle = currentLocation.post_title;
                if(currentLocation.location_service != null){
                    var service = "<p><strong>Service</strong> : " + currentLocation.location_service + "</p>";
                }else{
                    var service = ''; 
                }
                if(currentLocation.location_address != null){
                    var address = "<p>" + currentLocation.location_address + "</p>";
                }else{
                    var address = ''; 
                }
                if(currentLocation.location_phone != null){
                    var phone = "<p><strong>Phone </strong>: <a href='tel:"+ currentLocation.location_phone +"'>"+ currentLocation.location_phone +"</a></p>";
                }else{
                    var phone = '';
                }
                if(currentLocation.location_hours != null){
                    var hours1 = currentLocation.location_hours;
                }else{
                    var hours1 = '';                    
                }
                if(currentLocation.location_hours_02 != null){
                    var hours2 = currentLocation.location_hours_02;
                }else{
                    var hours2 = '';
                }
                
                var marker = map.addMarker({
                    lat: currentLocation.location_latitude,
                    lng: currentLocation.location_longitude,
                    title: currentLocation.post_title,
                    infoWindow: {
                        content: "<h4 class='infor_title'>" + posttitle + "</h4>"+ service + phone + address  +"<p styles='width: 235px !important;margin: 0 auto;'><strong>Hours</strong> :<br/> "+ hours1 +"<br/><br/>"+ hours2 +"</p>",
                        maxWidth: 300,
                    }
                });
                bounds.extend(marker.position);
            }
        },

        
        isWithInRadius: function(locationserviceLat, locationserviceLng) {

            var calculatedCentre = new Object();
            var dLat = this.toRadians(userLat - locationserviceLat);
            var dLon = this.toRadians(userLng - locationserviceLng);

            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(this.toRadians(locationserviceLat)) * Math.cos(this.toRadians(userLat)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);

            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            var calcDistance = 6378.16 * c;

            if (calcDistance <= defaultRadius) {
                calculatedCentre['isWithInRadius'] = true;
            } else {
                calculatedCentre['isWithInRadius'] = false;
            }
            calculatedCentre['distance'] = parseInt(calcDistance);
            return calculatedCentre;
        },

        toRadians: function(value) {
            //converts degrees to radians
            return value * Math.PI / 180;
        },

    };

    function equalHeight(group) {
        tallest = 0;
        $(group).each(function() {
            $(this).height('auto');
            thisHeight = $(this).height();
            if (thisHeight > tallest) {
                tallest = thisHeight;
            }
        });
        $(group).height(tallest);
    }

    $(window).on('resize', function() {
        equalHeight('#target .js-box');
    });

    var locationCareGravityForm = {

        checkbox: ".select-locationservice-centre",
        rowContactForm: ".row-contact-form",
        gravityFieldMap: "",
        selectedCentre: ".selected-centre",
        selectedCentresDisplay: "",


        toggleEnquiryMessage: function(selectedOption) {
            var gravityFieldMessage = ".field-enquiry-message";
            var enquireMessage = $(gravityFieldMessage).find("textarea");
            if (selectedOption == "Enquire") {
                $(gravityFieldMessage).addClass("required");
                locationCareGravityForm.appendRequireSpan();
            } else {
                $(gravityFieldMessage).removeClass("required");
                enquireMessage.next("span").remove();
            }
        },
        prepareGravityForm: function(gravityFieldMap) {
            locationCareGravityForm.appendRequireSpan();
            // create a place to collect and display selected centres
            this.gravityFieldMap = gravityFieldMap;
            var html = $("<div/>");
            html.addClass("selected-centres");

            $(this.gravityFieldMap).append(html);
            this.selectedCentresDisplay = $(".selected-centres");

            var htmlTitle = $("<div/>");
            htmlTitle.addClass("title");
            htmlTitle.text("Selected Centres");
            html.append(htmlTitle);
        },
        appendRequireSpan: function() {
            // inject * for all the required fields
            $(".required").find("input, select, textarea").each(function() {
                var span = $("<span/>");
                span.text("*");
                if (!$(this).next().is("span")) {
                    $(this).after(span);
                }
            });
        },
        sendToGravityForm: function() {
            var self = this;
            $(document).on("change", this.checkbox, function(event) {
                self.toggleGravityForm();
                self.moveSelectedCentres($(this));
                childCareHelper.scrollTo("#row-contact-form");
            });
        },
        toggleGravityForm: function() {
            var countChecked = $(this.checkbox + ":checked").length;
            if (countChecked > 0) {
                $(this.rowContactForm).removeClass("hidden");
            } else {
                $(this.rowContactForm).addClass("hidden");
            }
        },
        moveSelectedCentres: function(currentSelected) {
            var self = this;
            var currentCentreTitle = $(currentSelected).attr("data-title");
            var currentCentreEmail = $(currentSelected).attr("data-email");
            var currentCentreId = $(currentSelected).attr("data-checkbox");
            if ($(currentSelected).is(':checked')) {
                $(this.gravityFieldMap).find("select").append($("<option>", {
                    value: currentCentreTitle + ": " + currentCentreEmail,
                    text: currentCentreTitle,
                    selected: true,
                    "data-selected": currentCentreId
                }));
                var img = $('<img/>');
                img.addClass('close');
                img.attr('data-selected', currentCentreId);
                img.attr('src', imgPath + "close-icon.png");
                var html = $("<div/>");
                html.addClass("selected-centre");
                html.attr("data-selected", currentCentreId);
                html.text(currentCentreTitle);
                this.selectedCentresDisplay.append(html);
                html.append(img);
                $(document).on("click", ".close", function(e) {
                    e.preventDefault();
                    var currentCentreId = $(this).attr("data-selected");
                    $('[data-selected = "' + currentCentreId + '"]').remove();
                    $('[data-checkbox = "' + currentCentreId + '"]').prop('checked', false);
                    self.toggleGravityForm();
                });
            } else {
                $('[data-selected="' + currentCentreId + '"]').remove();
            }
        },
        resetCentres: function() {
            $(this.selectedCentre).remove();
            $(this.gravityFieldMap).find("select").empty();
            $(this.rowContactForm).addClass("hidden");
        },
        handleSubmit: function() {
            $(gravitySubmitBtn).on("click", function(e) {
                e.preventDefault();
                childCareHelper.formValidate();
            });
        },
    };

    var childCareHelper = {
        scrollTo: function(anchorSelector, offset) {
            if (!offset) {
                var offSet = 200;
            }
            $("html,body").animate({
                'scrollTop': $(anchorSelector).offset().top - offSet
            }, "slow");
        },
        isEmail: function(emailAddress) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(emailAddress);
        },
        formValidate: function() {
            var validation = true;
            $(".required").find("input, select, textarea").each(function() {
                if ($(this).val() == "") {
                    $(this).next("span").addClass("error");
                    validation = false;
                } else {
                    $(this).next("span").removeClass("error");
                }
            });
            $(".email").find("input").each(function() {
                if (!childCareHelper.isEmail($(this).val())) {
                    $(this).next("span").addClass("error");
                    validation = false;
                } else {
                    $(this).next("span").removeClass("error");
                }
            });
            if (validation) {
                console.log($(gravitySubmitBtn).parents('form'));
                $(gravitySubmitBtn).parents('form').trigger("submit", [true]);
                //jQuery("#gform_1").trigger("submit",[true]);
            } else {
                return false;
            }
        },
        sortAssocObject: function(list) {
            var sortable = [];
            for (var key in list) {
                sortable.push([key, list[key]]);
            }
            sortable.sort(function(a, b) {
                return (a[1] < b[1] ? -1 : (a[1] > b[1] ? 1 : 0));
            });
            var orderedList = {};
            for (var i = 0; i < sortable.length; i++) {
                orderedList[sortable[i][0]] = sortable[i][1];
            }
            return orderedList;
        },
    };

    $(document).ready(function() {
        LocationCenterInit.successMessage();
        LocationCenterInit.loadCentres();
        LocationCenterInit.handleEnquiryType();

        LocationCenterFind.nearestCenter();

        locationCareGravityForm.prepareGravityForm(locationservice.gravityFieldMap);
        locationCareGravityForm.sendToGravityForm();
        locationCareGravityForm.handleSubmit();
    });
})(jQuery);
