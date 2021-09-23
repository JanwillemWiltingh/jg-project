$(document).ready(function (){
    $("body").on("click", "#dropdown_button", function() {
        if($('#dropdown_id').css('display') === 'none')
        {
            document.getElementById('dropdown_id').style.display = "block";
            $('#arrow').addClass('fa-caret-up');

        }
        else
        {
            document.getElementById('dropdown_id').style.display = "none";
            $('#arrow').removeClass('fa-caret-up');

        }
    });

});
