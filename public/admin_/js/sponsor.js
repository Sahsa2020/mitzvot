var g_countries = null;
var g_added_countries = null;
var g_states = null;
var g_sponsors = null;


$(document).ready(function() {
    var added_country_url = BASE_URL + 'sponsor/country/added';
    var country_url = BASE_URL + 'sponsor/country/';
    var is_checked = false;
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
    // state
    $('#country_selector').change(function() {
        // console.log($(this).val());
        getStatesOn($(this).val());
        appendStatesDiv();
    });
    $('.sponsor-country-name').click(function(e){
        $('.sponsor-country-list').toggleClass('hidden');
        e.preventDefault();
        e.stopPropagation();
        return false;
    });
    // $('.sponsor-country-list').click(function(e){
    //     e.preventDefault();
    //     e.stopPropagation();
    //     return false;
    // });
    $('body').on('click.sponsor-list', function(){
        if(!$('.sponsor-country-list').hasClass('hidden')){
            $('.sponsor-country-list').addClass('hidden');
        }
    });
    $('.add-country').click(function(){
        $('#addCountryModal').modal('show');
    });
    $('.submit-add-country-js').click(function(){
        $('#addCountryModal form').submit();
    });
    $('.submit-add-state-js').click(function(){
        $('#addStateModal form').submit();
    });
    $('.submit-add-place-js').click(function(){
        $('#addPlaceModal form').submit();
    });
    $('.sponsors-place-input-value').blur(function(){
        let place_id = $(this).data('place-id');
        let field = $(this).data('field');
        let value = $(this).val();
        $.ajax({
            type: 'put',
            url: '/api/v1/places/' + place_id,
            data: {value: value, field: field},
            success: function(){
                console.log("success");
            }
        });
    });
});
