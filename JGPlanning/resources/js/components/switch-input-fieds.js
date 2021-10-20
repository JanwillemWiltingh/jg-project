$(document).ready(function () {
    const a = $('input[name="date-format"]:checked').val();
    console.log(a);

    if(a === 'month') {
        $('#month-group').show();
        $('#week-group').hide();
    } else {
        $('#month-group').hide();
        $('#week-group').show();
    }


    $('input[name="date-format"]').change(function () {
        const a = $('input[name="date-format"]:checked').val();
        console.log(a);
        
        if(a === 'month') {
            $('#month-group').show();
            $('#week-group').hide();
        } else {
            $('#month-group').hide();
            $('#week-group').show();
        }
    })
});
