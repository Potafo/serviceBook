
{{-- <script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script> --}}
{{-- <link rel="stylesheet" href="{{ asset('black') }}/css/switchery.min.css"> --}}
<script src="{{ asset('black') }}/js/plugins/switchery.min.js"></script>
<link rel="stylesheet" href="{{ asset('black') }}/css/toastr.min.css">
<script src="{{ asset('black') }}/js/plugins/toastr.min.js"></script>

@foreach($configurations as $key=>$value)
<div class="form-row">
    <div class="form-group  col-md-6">
        <h4>{{ ucwords($value->config_name) }}</h4>
            @if($value->input_type == "checkbox")
                <?php $fieldname = strtolower(str_replace(" ", "_", $value->config_name)); ?>
                <input type="checkbox" data-id="{{ $value->id }}" data-field="{{ $fieldname }}" name="status" class="form-control js-switch" {{ $data[$fieldname] == 'Y' ? 'checked' : '' }}>

            @elseif($value->input_type == "textbox")

            @endif
    </div>
</div><br/>
@endforeach


<style>

.switchery{background-color:#fff;border:1px solid #dfdfdf;border-radius:20px;cursor:pointer;display:inline-block;height:30px;position:relative;vertical-align:middle;width:50px;-moz-user-select:none;-khtml-user-select:none;-webkit-user-select:none;-ms-user-select:none;user-select:none;box-sizing:content-box;background-clip:content-box}
.switchery>small{background:#fff;border-radius:100%;box-shadow:0 1px 3px rgba(0,0,0,0.4);height:30px;position:absolute;top:0;width:30px}
.switchery-small{border-radius:20px;height:20px;width:33px}
.switchery-small>small{height:20px;width:20px}
.switchery-large{border-radius:40px;height:40px;width:66px}
.switchery-large>small{height:40px;width:40px}

        </style>
<script>

    $(document).ready(function(){

       let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
       elems.forEach(function(html) {
           let switchery = new Switchery(html,  { size: 'small' });
       });

      //$.noConflict();



           $('.js-switch').change(function () {
               //e.preventDefault();
               //alert("sdsf");
               let status = $(this).prop('checked') === true ? 'Y' : 'N';
               let field = $(this).data('field');
               let vendor = $('#vendor_name_config').val();
               $.ajax({
                   type: "GET",
                   dataType: "json",
                   url: '{{ route('configuration.config_update') }}',
                   data: {'status': status, 'field': field,'vendor':vendor},
                   success: function (data) {
                       $('#alertmessage').html(data.message);
                       $('.alert-success').css('display','block');
                       $(".alert-success").fadeOut(3000);
                       //showNotification('top','right');
                       //toastr.options.closeButton = true;
                       //toastr.options.closeMethod = 'fadeOut';
                      // toastr.options.closeDuration = 100;
                       //toastr.success(data.message);
                   }
               });
           });



       });


   </script>
