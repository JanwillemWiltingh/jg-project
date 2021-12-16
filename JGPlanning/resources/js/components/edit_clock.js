$(document).ready(function () {
    //  Check if
    if($('#total_hours').val()) {
        calculate();

        //  Variable for the old end and start time
        let old_start_time = '';
        let old_end_time = '';

        //  Actions for start_time input
        $('#start_time').on('focusin', function (){
            //  When selecting the input field save the old value
            old_start_time = $(this).val();
        }).on('change', function () {
            change('start_time', 'end_time', old_start_time);
        });

        //  Actions for end_time input
        $('#end_time').on('focusin', function () {
            //  When selecting the input field save the old value
            old_end_time = $(this).val();
        }).on('change', function (){
            change('end_time', 'start_time', old_end_time);
        });
    }

});

function calculate() {
    let start_time = $("#start_time").val();
    let end_time = $("#end_time").val();

    let hours = (parseInt(end_time.split(':')[0]) - parseInt(start_time.split(':')[0])) * 60;
    hours = (hours + (parseInt(end_time.split(':')[1]) - parseInt(start_time.split(':')[1]))) / 60;

    $('#total_hours').val(hours.toFixed(1))
}

function change(main_field, second_field, old_value) {
    let start_time = parseInt($('#start_time').val().split(':')[0])
    let end_time = parseInt($('#end_time').val().split(':')[0]);

    if(start_time >= end_time) {
        $('#' + main_field).val(old_value);
    } else {
        calculate();

        if(second_field === 'start_time') {
            $('#' + second_field).attr({
                'max' : $('#' + main_field).val()

            });
        } else {
            $('#' + second_field).attr({
                'min' : $('#' + main_field).val()

            });
        }
    }
}
