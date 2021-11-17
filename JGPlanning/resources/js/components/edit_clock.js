$(document).ready(function () {
    //  Check if
    if($('#total_hours').val()) {
        calculate();

        $('#start_time').on('change', function () {
            calculate();
            $('#end_time').attr({
                'min' : $('#start_time').val()
            });
        });

        $('#end_time').on('change', function (){
            calculate();
            $('#start_time').attr({
                'max' : $('#end_time').val()
            });
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
