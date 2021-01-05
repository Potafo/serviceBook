
      <table class="table tablesorter " id="">
        <thead class=" text-primary" >
          <tr>
            <th>
              Slno
            </th>
            <th>
                Date
              </th>
            <th>
              JobCard Number
            </th>
              <th>
                Customer
              </th>
              <th>
               Sub Total
              </th>
              <th>
                Tax Amount
              </th>
              <th>
                Discount
              </th>
              <th>
                Amount Received
              </th>
          </tr>
        </thead>
        <tbody>
            <?php $billamount=0;
                  $taxamount=0;
                  $discount=0;
                  $amountrecieved=0;
                  $i=1; ?>
            @if(count($jobcard)>0)
                @foreach($jobcard as $key=>$value)
                <?php

                    if (strlen($value->pdtname) > 20){
                        $pdt = substr($value->pdtname, 0, 12) . '...';
                    }
                    else {
                       $pdt=$value->pdtname;
                    }
                    $billamount= intval($billamount)  + intval($value->bill_amount);
                  $taxamount=intval($taxamount)  + intval($value->tax_amount);
                  $discount=intval($discount)  + intval($value->discount_amount);
                  $amountrecieved=intval($amountrecieved)  + intval($value->received_amount);


                ?>
                    <tr  data-id='{{ $value->id }}' style="cursor: pointer">
                        {{-- class="viewjobcards" --}}
                        <td>
                          {{ $i++ }}
                           {{-- {{ $jobcard->firstItem() + $key }} --}}
                        </td>
                        <td>
                            {{ $value->jobcard_date }}
                        </td>
                        <td>
                            {{ $value->jobcard_number }}
                        </td>

                        <td>
                            {{ $value->custname }} - {{ $value->custmobile }}
                        </td>

                        <td>
                            {{ $value->bill_amount }}
                        </td>
                        <td>
                            {{ $value->tax_amount }}
                        </td>
                        <td>
                            {{ $value->discount_amount }}
                        </td>
                        <td>
                            {{ $value->received_amount }}
                        </td>



                    </tr>
            @endforeach

         @endif
         <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>


            <td>{{ $billamount }}</td>
            <td>{{ $taxamount }}</td>
            <td>{{ $discount }}</td>
            <td>{{ $amountrecieved }}</td>
         </tr>
        </tbody>
      </table>


