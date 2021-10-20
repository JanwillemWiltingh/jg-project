$(document).ready(function () {
    $("body").on("click", "#delete_day", function () {
        if ($('#delete_day_div').css('display') === 'none') {
            $('#arrow').addClass('fa-caret-up');

        } else {
            $('#arrow').removeClass('fa-caret-up');
        }
    });

    $('#admin_availability').dataTable();

    $('#admin-availability-dropdown').change(function () {
        window.location = this.value;
    });

    $('#disableDays1, #disableDays2, #disableDays3, #disableDays4, #disableDays5, #disableDays6, #disableDays7').on('change', function () {
        let id = $('#userIdDisableDays').val();
        let ArrayAvailableDays = [];
        for (let i = 1; i < 7; i++)
        {
            if ($('#disableDays' + i + ":checked").val())
            {
                ArrayAvailableDays.push(
                    $('#disableDays' + i).val()
                );
            }
            else
            {
                ArrayAvailableDays.push(
                    ""
                );
            }
        }

        console.log(ArrayAvailableDays);

        $.ajax({
            type: "POST",
            url : id + "/available_days",
            data: {data: ArrayAvailableDays},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        })

        $('#loader').removeClass('d-none');
        setTimeout(function () {
            location.reload();
        }, 100);
    });
});
