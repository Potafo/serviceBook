<table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
    <tr>
      <td align="center">
        <center>
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr>
                <td style="text-align:center; font-size:30px; padding-bottom:40px; ">
                    <b>Service Details</b>
                </td>
                </tr>
            </table>
        </center>
      </td>
    </tr>
    <tr>
        <td align="center">
          <center>
              <?php //dd(print_r($data_email['product_det'][0]['pdtname'])); ?>
              <table cellspacing="0" cellpadding="0" width="100%">
                  <tr>
                  <td style="text-align:center;">
                      <b>Customer Details</b>
                  </td>
                  <td style="text-align:center;">
                    <b>Product Details</b>
                </td>
                  </tr>
                  <tr>
                    <td style="padding-top:5px; vertical-align:top;text-align:center;">
                       Customer Name :{{ $data_email['cust_name'] }},<br>
                       Mobile :{{ $data_email['cust_mobile'] }},<br>
                       Email :{{ $data_email['cust_email'] }}<br>
                   </td>
                   <td style="padding-top:5px; vertical-align:top;text-align:center;">
                    Product name : {{ $data_email['product_det'][0]['pdtname'] }},<br>
                    Remarks : {{ $data_email['product_det'][0]['remarks'] }}<br>

                 </td>
                  </tr>
              </table>
              <table cellspacing="0" cellpadding="0" width="80%">
                <tr>
                  <td>
                    <b>Service Details</b>
                  </td>

                </tr>

              </table>
            <table cellspacing="0" cellpadding="0" width="80%" border='1'>
                <thead class=" text-primary">
                    <tr>
                      <th>
                        Slno
                      </th>
                        <th>
                          Service
                        </th>
                        <th>
                          Service Remarks
                        </th>
                      <th >
                          Price
                        </th>
                        @if($data_email['taxenabled']=='Y' )
                        <th>
                          Tax %
                        </th>
                        @endif

                        <th >
                          Total
                        </th>
                    </tr>
                  </thead>
                  @if(count($data_email['servicelist'])>0)
                  <?php
                  $i=1;
                  $final = 0;
                  $tax_total=0;
                  ?>
                  @foreach($data_email['servicelist'] as $key=>$value)
                    <?php
                    $price_each = $value->price;
                    $taxval = 0;
                    $total = 0;
                    if ($data_email['taxenabled'] == 'Y') {
                        $taxval = $value->tax_amount;
                        $tax_total=intval($tax_total) + intval($taxval);
                    }

                    $total = intval($taxval)  + intval($price_each);
                    $final = intval($final) + intval($total);
                    ?>
                              <tr>
                                  <td>
                                    {{ $i++ }}
                                  </td>
                                  <td>
                                      {{ $value->service_name }}
                                  </td>
                                  <td>
                                       {{ $value->service_remarks }}
                                  </td>
                                  <td>
                                     {{ $price_each }}
                                  </td>
                                  @if ($data_email['taxenabled'] == 'Y')
                                    <td>
                                        {{ $taxval }}
                                     </td>

                                    @endif
                                    <td>
                                        {{ $total }}
                                          </td>

                                  </tr>
                                  @endforeach
                                  @endif
                                  @if ($data_email['taxenabled'] == 'Y')
                                    <tr>
                                <td></td><td> </td><td> </td>
                                        <td>Total Tax =</td><td>{{ $tax_total }}</td><td> </td></tr>
                                @endif
                                <tr>
                                <td> </td><td> </td><td> </td>
                                    <td>Total =</td><td>{{ $final  }} </td><td> </td></tr>
            </table>
          </center>
        </td>
      </tr>
</table>




