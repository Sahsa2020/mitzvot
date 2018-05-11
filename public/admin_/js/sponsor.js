var g_countries = null;
var g_added_countries = null;
var g_states = null;
var g_sponsors = null;


$(document).ready(function() {
    var added_country_url = BASE_URL + 'sponsor/country/added';
    $.ajax({
        type: 'get',
        url: added_country_url,
        success: function(data) {
            if (!data.success) {
                g_added_countries = null;
                // data.error && alert(data.error);
                return;
            }
            g_added_countries = null;
            g_added_countries = data.data;
            // console.log(g_added_countries);
            onAddedCountries();
        },
    });

    var country_url = BASE_URL + 'sponsor/country/';
    $.ajax({
        type: 'get',
        url: country_url,
        success: function(data) {
            if (!data.success) {
                g_countries = null;
                // data.error && alert(data.error);
                return;
            }
            g_countries = data.data;
            console.log(g_countries);
            onCountries();
        },
    });

    // function () {
    //     onAddedCountries();
    //     onCountries();
    // }

    function onAddedCountries() {
        $.each(g_added_countries, function(i, g_added_country) {
            $('#country_selector').append(
                $('<option>', { value: g_added_country.id, text: g_added_country.name})
            );
        });
    }

    function onCountries() {
        $.each(g_countries, function(i, g_country) {
            $("#total_countries").append('<li>' +
            '<div class="checkbox">' +
                '<label>' +
                '<input type="checkbox">' +
                '<span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span>' +
                g_country.name +
                '</label>' +
            '</div>' +
            '</li>'
            );
        });
    }

    var is_checked = false;
    $('#all-check').change(
        function () {
            is_checked = !is_checked;
            if (is_checked) {
                $(this).prop('checked', true);
                onCheckAll(true);
            } else {
                $(this).prop('checked', false);
                onCheckAll(false);
            }
        }        
    );

    function onCheckAll(is_checked) {
        if (is_checked) {
            $('#total_countries li').each(function(index) {
                // console.log('debug');
                $('#total_countries .checkbox input').attr('checked', 'checked');
            });            
        } else {
            $('#total_countries li').each(function(index) {
                // console.log('debug');
                $('#total_countries .checkbox input').removeAttr('checked', 'checked');
            });  
        }
    }
    
    // add a country
    $('.option-footer .add-country').click(function() {
        $('#total_countries li .checkbox input').each(function(index) {
            if ($(this).prop('checked')) {
                console.log('add a caountry', index);
                addCountry(g_countries[index]);
            }
        });
    });

    $('.delete.country a').click(function() {
        console.log('delete a caountry');
        $('#total_countries li .checkbox input').each(function(index) {
            if ($(this).prop('checked')) {
                console.log('add a caountry', index);
                deleteCountry(g_countries[index]);
            }
        });
    });

    function addCountry(country_id) {
        var url = BASE_URL + 'sponsor/country/add';
        $.ajax({
            type: 'post',
            url: url,
            data: country_id,
            success: function(data) {
                if (!data.success) {
                    // data.error && alert(data.error);
                    return;
                }
                // g_added_countries = data.added;
                // g_countries = data.total;
               console.log('');
            },
        });
    }

    function deleteCountry(country_id) {
        var url = BASE_URL + 'sponsor/country/delete';
        $.ajax({
            type: 'post',
            url: url,
            data: country_id,
            success: function(data) {
                if (!data.success) {
                    // data.error && alert(data.error);
                    return;
                }
                // g_added_countries = data.added;
                // g_countries = data.total;
               console.log('');
            },
        });
    }

    // state
    $('#country_selector').change(function() {
        // console.log($(this).val());
        getStatesOn($(this).val());
        appendStatesDiv();
    });

    function getStatesOn(country_id) {

        var url = BASE_URL + 'sponsor/state/?country=' + country_id;
        $.ajax({
            type: 'get',
            url: url,
            success: function(data) {
                if (!data.success) {
                    // data.error && alert(data.error);
                    return;
                }
                g_states = null;
                g_states = data.data;
                console.log(g_states);
                $('#state-body').show();
            },
        });
    }

    function appendStatesDiv() {
        //init
        // $.each(g_states, function(i, g_state) {
            $('#state-div').empty(
                // '<div class="col-sm-2">' +
                // '<a href="#" class="close">X</a>' +
                // '<div class="state">' +
                // g_state.name + 
                // '</div>' +
                // '</div>'
            );
        // });

        $.each(g_states, function(i, g_state) {
            $('#state-div').append(
                '<div class="col-sm-2"' + 'state_id="' + g_state.id + '"' + '>' +
                '<a href="#" class="close">X</a>' +
                '<div class="state">' +
                g_state.name + 
                '</div>' +
                '</div>'
            );
        });
    }

    //
    $('#state-div').click(function() {
        // console.log();
        getSponsors($(this).children().attr('state_id'));
        $('.table-box.box-display').css('visibility', 'visible');
    });

    function getSponsors(state_id) {
        var url = BASE_URL + 'sponsor?search_id=' + state_id + '&search_type=' + 4;
        $.ajax({
            type: 'get',
            url: url,
            // data: country_id,
            success: function(data) {
                if (!data.success) {
                    // data.error && alert(data.error);
                    return;
                }
                // g_added_countries = data.added;
                // g_countries = data.total;
                g_sponsors = data.data;
               console.log(g_sponsors);
               appendSponsors();
            },
        });
    }

    function appendSponsors() {
        $('#sponsors-body').empty(
            // '<div class="col-sm-2">' +
            // '<a href="#" class="close">X</a>' +
            // '<div class="state">' +
            // g_state.name + 
            // '</div>' +
            // '</div>'
        );
    // });

        $.each(g_sponsors, function(i, g_sponsor) {
            $('#sponsors-body').append(

                '<tr>' +
                '<td>' +
                    '<div class="checkbox">' +
                        '<label>' +
                        '<input type="checkbox" checked="checked">' +
                        '<span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span>' +
                        '</label>' +
                    '</div>' +
                '</td>' +
                '<td>' + i + '</td>' +
                '<td><input type="text" value="' + g_sponsor.city_name + '"' + 'class="form-control"></td>' +
                '<td><input type="text" value="' + g_sponsor.district_name + '"'+ 'class="form-control"></td>' +
                '<td class="text-center"><input type="text" value="' + g_sponsor.population + '"' + 'class="form-control"></td>' +
                '<td class="text-center"><input type="text" value="' + g_sponsor.unit + '"' +  'class="form-control"></td>' +
                '<td class="text-center">' + g_sponsor.cost_assumption + '</td>' +
                '<td class="text-center">' + g_sponsor.profit_assumption + '</td>' +
                '<td class="text-center">' + g_sponsor.name + '</td>' +
            '</tr>'

            );
        });
    }

    

});
