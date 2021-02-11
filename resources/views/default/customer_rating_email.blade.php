{{-- <link rel="stylesheet" type="text/css" href="{{ asset('black') }}/css/style.css"> --}}
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
{{-- <link rel='stylesheet' href='https://raw.githubusercontent.com/kartik-v/bootstrap-star-rating/master/css/star-rating.min.css'> --}}
<style>
.openstar{
    content: url("{{ asset('black') }}/img/star-open.png");
}
.filledstar{
    content: url("{{ asset('black') }}/img/star-filled.png");
}
.button_submit{
    background: #e14eca;
    /* font-size: 0.875rem; */
    border-radius: 0.2857rem;
    padding: 5px 15px;
    cursor: pointer;
    color: #ffffff;
    line-height: 1.35;
    display: inline-block;
    font-weight: 800;
    text-align: center;
    width: 36%;
    height: 6%;
    font-size: large;

}
</style>

<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
    <tr>
      <td align="center">
        <center>
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr>
                <td style="text-align:center; font-size:30px; padding-bottom:40px; padding-top:10px">
                    <b><u>Reviews & Rating</u></b>
                </td>
                </tr>
            </table>
        </center>
      </td>
    </tr>
</table>
<br>
<div class="row container">
    <form action="{{ route('submit_rating') }}" method="post">
        @csrf
        <div class="col-md-4 " style="margin-left: 35%;">
            <h3><b>Reviews</b></h3>
            <div class="row">
                <textarea class="form-control" rows="10" cols="50" placeholder="Write your review here..." name="remark" id="remark" required></textarea><br>
            </div>
            <h3><b>Rating</b></h3>

            <div id="rating_div" class="w3-container">
                <div class="star-rating">
                    <span class="fa divya fa-star-o " data-rating="1" style="font-size:20px;">
                        <img class="openstar">
                    </span>
                    <span class="fa fa-star-o" data-rating="2" style="font-size:20px;">
                        <img class="openstar">
                    </span>
                    <span class="fa fa-star-o" data-rating="3" style="font-size:20px;">
                        <img class="openstar">
                    </span>
                    <span class="fa fa-star-o" data-rating="4" style="font-size:20px;">
                        <img class="openstar">
                    </span>
                    <span class="fa fa-star-o" data-rating="5" style="font-size:20px;">
                        <img class="openstar">
                    </span>
                    <input type="hidden" name="starrating_input" class="rating-value" value="1">
                    <input type="hidden" name="jobcard"  value="{{ $jobcard }}">

                </div>
            </div>
        </div>
        <div class="col-md-4 " style="margin-left: 36%;"><br>
        <p><button type="submit" class="button_submit" id="srr_rating">Submit</button></p>
        </div>
    </form>
</div>
<script>
    var $star_rating = $('.star-rating .fa');
    var SetRatingStar = function() {
      return $star_rating.each(function() {
        if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
          return $(this).find('img').removeClass('openstar').addClass('filledstar');
        } else {
          return $(this).find('img').removeClass('filledstar').addClass('openstar');
        }
      });
    };

    $star_rating.on('click', function() {
      $star_rating.siblings('input.rating-value').val($(this).data('rating'));
      return SetRatingStar();
    });

    SetRatingStar();
    $(document).ready(function() {
    });



    // $("#srr_rating").click(function() {
    //     var $star_rating = $('.star-rating .fa');
    //     var rating = parseInt($star_rating.siblings('input.rating-value').val());
    //     var remk= $('#remark').val();
    //     var email= $('#email').val();
    //     var demo_id= $('#demo_id').val();
    //     if(rating>0 && email!=""){
    //             $.ajax({
    //             url: "save_rating.php",
    //             type: "GET",
    //             data: {
    //                 rate: rating,
    //                 remark:remk,
    //                 email: email,
    //                 demo_id:demo_id

    //             },
    //             success : function(data){
    //                 alert(data);
    //                 location.reload();
    //             }
    //             });
    //     }
    //     else{
    //         alert('Add your email address !');
    //     }

    // });
    // $(".selected").click(function() {
    //         var selected = $(this).hasClass("highlight");
    //         $(".selected").removeClass("highlight");
    //         if(!selected){
    //            $(this).addClass("highlight");
    //         }

    // });

    </script>
