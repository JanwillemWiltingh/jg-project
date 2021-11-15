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

    $('#week').on('change', function () {
        window.location.href = this.value.substring(6);
    });

    $('#manageDropdown').on('change', function () {
        if (this.value == "Uitgezette dagen")
        {
            $('#DaysDiv').hide();
            $('#disabledDaysDiv').show();
        }
        else
        {
            $('#DaysDiv').show();
            $('#disabledDaysDiv').hide();
        }
    });

    for (let i = 1; i <= 7; i++)
    {
        for (let a = 1; a <= $('#count_disable'+ i).val(); a++)
        {
            $('#remove_disable_days'+ a + i).on('click', function (){
                let id = $('#id_disable' + a + i).val();
                $.ajax({
                    type: "POST",
                    data: {id: id},
                    url : "/admin/rooster/manage_disable",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                })
            });
        }
    }

    for (let i = 1; i <= 7; i++)
    {
        for (let a = 1; a <= $('#count_disable'+ i).val(); a++)
        {
            $('#remove_days'+ a + i).on('click', function (){
                let id = $('#id' + a + i).val();

                console.log(id);

                $.ajax({
                    type: "POST",
                    data: {id:id},
                    url : "/admin/rooster/manage_day_disable",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                })
            });
        }
    }
    // function switch_time() {
    //     const checkbox_state = $('#time-switch').is(':checked');
    //
    //     if(checkbox_state) {
    //         $('#switch-label').text('Precies');
    //
    //         $('.precies').show();
    //         $('.uren').hide();
    //     } else {
    //         $('#switch-label').text('Uren');
    //
    //         $('.precies').hide();
    //         $('.uren').show();
    //     }
    // }

//    Compare table switch button UwU
    const checkbox_state = $('#time-switch').is(':checked');

    if(checkbox_state) {
        $('#switch-label').text('Precies');

        $('.precies').show();
        $('.uren').hide();
    } else {
        $('#switch-label').text('Uren');

        $('.precies').hide();
        $('.uren').show();
    }

    $('#time-switch').on('change', function (){
        const checkbox_state = $('#time-switch').is(':checked');

        if(checkbox_state) {
            $('#switch-label').text('Precies');

            $('.precies').show();
            $('.uren').hide();
        } else {
            $('#switch-label').text('Uren');

            $('.precies').hide();
            $('.uren').show();
        }
    });

    console.log(checkbox_state);
});
