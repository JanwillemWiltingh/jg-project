$(document).ready(function () {
    // Menu Button
    $('.toggle-btn').on('click', 	function(){
        $(this).toggleClass('onclick');
        $('.nav-bar-open').toggleClass('visible');
        $('.toggle-btn').toggleClass('visible');
        // $('.nav-container').css('background': 'red');
    });

    $("body").on("click", "#delete_day", function () {
        if ($('#delete_day_div').css('display') === 'none') {
            $('#arrow').addClass('fa-caret-up');

        } else {
            $('#arrow').removeClass('fa-caret-up');
        }
    });

    $('#admin_availability').dataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.18/i18n/Dutch.json"
        },
    });

    $('#admin-availability-dropdown').change(function () {
        window.location = "/admin/rooster/" + this.value+ "/" + $('#request_week').val() + "/" + $('#request_year').val();
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
        setTimeout(function () {
            location.reload();
        }, 100);
    });

    $('#week').on('change', function () {
        window.location.href = "/rooster/" + this.value.substring(6) + '/' + this.value.slice(0, -4);
    });

    $('#manageDropdown').on('change', function () {
        if (this.value === "Uitgezette weken" || this.value === "Uitgezette dagen")
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

    $('#weekDropdown').on('change', function () {
        if (this.value === "Uitzetten")
        {
            $('#addWeeks').hide();
            $('#addDisable').show();
        }
        else
        {
            $('#addWeeks').show();
            $('#addDisable').hide();
        }
    });

    for (let i = 1; i <= 7; i++)
    {
        for (let a = 1; a <= $('#count_disable'+ i).val(); a++)
        {
            $('#remove_disable_days'+ a + i).on('click', function (){
                let id = $('#id_disable' + a + i).val();

                if ($('#role' + a + i).val() === "User")
                {
                    $.ajax({
                        type: "POST",
                        data: {id: id},
                        url: "/rooster/manage_disable",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            $('#submit_refresh').removeClass('d-none');
                        }
                    })
                }
                else
                {
                    $.ajax({
                        type: "POST",
                        data: {id: id},
                        url: "/admin/rooster/manage_disable",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            $('#submit_refresh').removeClass('d-none');
                        }
                    })
                }
            });
        }
    }

    for (let i = 1; i <= 7; i++)
    {
        for (let a = 1; a <= $('#count_disable'+ i).val(); a++)
        {
            $('#remove_days'+ a + i).on('click', function (){
                let id = $('#id' + a + i).val();

                if ($('#role' + a + i).val() === "User")
                {
                    $.ajax({
                        type: "POST",
                        data: {id:id},
                        url : "/rooster/manage_day_disable",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            $('#submit_refresh').removeClass('d-none');
                        }
                    })
                }
                else
                {
                    $.ajax({
                        type: "POST",
                        data: {id:id},
                        url : "/admin/rooster/manage_day_disable",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function () {
                            $('#submit_refresh').removeClass('d-none');
                        }
                    })
                }
            });
        }
    }
    // $('#test').on('click', function () {
    //     $('#submit_refresh').removeClass('d-none');
    // });

//    Disable Clock in
    const button = $('#clock_button');
    const enable_at = button.data('enable_at');

    if(jQuery.type(button.val()) !== 'undefined') {
        if(jQuery.type(enable_at) !== 'null') {
            console.log('Je kan niet inklokken tot: ' + enable_at);

            //  Get the current date time and make only time of it (H:i)
            var dt = new Date();
            var time = dt.getHours().toString().padStart(2, '0') + ':' + dt.getMinutes().toString().padStart(2, '0');

            console.log('Huidige tijd: ' + time);

            //  Keep checking if the enable time has passed
            var intervalId = setInterval(function() {
                if(time < enable_at) {
                    //  if not passed disable the button
                    clearInterval(intervalId);
                    button.prop('disabled', true);
                }
            }, 100);
        }
    }




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

    $('#changeFont').on('click', function (){
        console.log('o');
        $('body').css("font-family", 'Wingdings');
    })

    $('#search').keyup(function () {
        var search = $(this).val();

        // Hide all table tbody rows
        $('table tbody tr').hide();

        // Case-insensitive searching (Note - remove the below script for Case sensitive search )
        $.expr[":"].contains = $.expr.createPseudo(function(arg) {
            return function( elem ) {
                return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
            };
        });

        // Count total search result
        var len = $('table tbody tr:not(.notfound) td:nth-child(2):contains("'+search+'")').length;

        if(len > 0){
            // Searching text in columns and show match row
            $('table tbody tr:not(.notfound) td:contains("'+search+'")').each(function(){
                $(this).closest('tr').show();
            });
        }else{
            $('.notfound').show();
        }

        console.log(search);
    });

    //  Easter Egg UwU
    var i = 0, timeOut = 0;
    $('#easter_egg_title').on('mousedown touchstart', function(e) {
        $(document).prop('title', 'OwO, What\'s this?');

        // $(this).addClass('active');
        timeOut = setInterval(function(){
            // console.log(i++);
        }, 100);
    }).bind('mouseup mouseleave touchend', function() {
        $(document).prop('title', 'JG Planning');

        // $(this).removeClass('active');
        i = 0;
        clearInterval(timeOut);
    });

    for (let i = 0; i < 7; i++)
    {
        $('#edit_rooster_modal' + i).on('click', function () {
            console.log($('#start_rooster' + i).val());
            $('[name="start_time"]').val($('#start_time_user_rooster' + i).val());
            $('#time_picker_av_end').val($('#end_time_user_rooster' + i).val());
            $('#start_date').val($('#start_rooster' + i).val());
            $('#end_date').val($('#end_rooster' + i).val());
            $('#comment_edit').val($('#comment' + i).val());
        });
    }

    for (let i = 0; i < 7; i++)
    {
        $('#disabled_modal_edit' + i).on('click', function () {
            $('#start_week_disable_edit').val($('#start_date_disable' + i).val());
            $('#end_week_disable_edit').val($('#end_date_disable' + i).val());
        });
    }

    $('#submit_refresh').on('click', function () {
        $('#submit_refresh').css({'width': '33px', 'height' : '34px', 'transition' : 'all 0.5s'});
        $('#submit_refresh_text').css({'opacity': '0%', 'transition' : 'all 0.5s'});
        $('#loader').css({'opacity': '100%', 'transition' : 'all 0.5s'});
        setTimeout(function () {
            location.reload();
        }, 100);
    });
});
