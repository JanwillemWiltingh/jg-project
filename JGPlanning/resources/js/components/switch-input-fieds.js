$(document).ready(function () {
    const a = $('input[name="date-format"]:checked').val();
    console.log(a);

    if(a === 'month') {
        $('#month-group').show();
        $('#week-group').hide();
        $('#day-group').hide();
    } else if(a === 'weeks') {
        $('#month-group').hide();
        $('#week-group').show();
        $('#day-group').hide();
    } else {
        $('#month-group').hide();
        $('#week-group').hide();
        $('#day-group').show();
    }


    $('input[name="date-format"]').change(function () {
        const a = $('input[name="date-format"]:checked').val();
        console.log(a);

        if(a === 'month') {
            $('#month-group').show();
            $('#week-group').hide();
            $('#day-group').hide();
        } else if(a === 'weeks') {
            $('#month-group').hide();
            $('#week-group').show();
            $('#day-group').hide();
        } else {
            $('#month-group').hide();
            $('#week-group').hide();
            $('#day-group').show();
        }
    })
});
