
$(document).ready(function (){
    $("body").on("click", "#delete_day", function() {
        if($('#delete_day_div').css('display') === 'none')
        {
            document.getElementById('delete_day_div').style.display = "block";
            // $('#arrow').addClass('fa-caret-up');

        }
        else
        {
            document.getElementById('delete_day_div').style.display = "none";
            // $('#arrow').removeClass('fa-caret-up');
        }
    });

    $('#admin_availability').dataTable();

    $('#admin-availability-dropdown').change( function () {
        let user = this.value;

        window.location = user;
    });
});
