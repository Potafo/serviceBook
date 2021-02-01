
<html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=320, initial-scale=1" />
      <title>Order Confirm</title>
      <style type="text/css">

        /* ----- Client Fixes ----- */

        /* Force Outlook to provide a "view in browser" message */
        #outlook a {
          padding: 0;
        }

        /* Force Hotmail to display emails at full width */
        .ReadMsgBody {
          width: 100%;
        }

        .ExternalClass {
          width: 100%;
        }

        /* Force Hotmail to display normal line spacing */
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%;
        }


         /* Prevent WebKit and Windows mobile changing default text sizes */
        body, table, td, p, a, li, blockquote {
          -webkit-text-size-adjust: 100%;
          -ms-text-size-adjust: 100%;
        }

        /* Remove spacing between tables in Outlook 2007 and up */
        table, td {
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
        }

        /* Allow smoother rendering of resized image in Internet Explorer */
        img {
          -ms-interpolation-mode: bicubic;
        }

         /* ----- Reset ----- */

        html,
        body,
        .body-wrap,
        .body-wrap-cell {
          margin: 0;
          padding: 0;
          background: #ffffff;
          font-family: Arial, Helvetica, sans-serif;
          font-size: 14px;
          color: #464646;
          text-align: left;
        }

        img {
          border: 0;
          line-height: 100%;
          outline: none;
          text-decoration: none;
        }

        table {
          border-collapse: collapse !important;
        }

        td, th {
          text-align: left;
          font-family: Arial, Helvetica, sans-serif;
          font-size: 14px;
          color: #464646;
          line-height:1.5em;
        }

        b a,
        .footer a {
          text-decoration: none;
          color: #464646;
        }

        a.blue-link {
          color: blue;
          text-decoration: underline;
        }

        /* ----- General ----- */

        td.center {
          text-align: center;
        }

        .left {
          text-align: left;
        }

        .body-padding {
          padding: 24px 40px 40px;
        }

        .border-bottom {
          border-bottom: 1px solid #D8D8D8;
        }

        table.full-width-gmail-android {
          width: 100% !important;
        }


        /* ----- Header ----- */
        .header {
          font-weight: bold;
          font-size: 16px;
          line-height: 16px;
          height: 16px;
          padding-top: 19px;
          padding-bottom: 7px;
        }

        .header a {
          color: #464646;
          text-decoration: none;
        }

        /* ----- Footer ----- */

        .footer a {
          font-size: 12px;
        }
      </style>

      <style type="text/css" media="only screen and (max-width: 650px)">
        @media only screen and (max-width: 650px) {
          * {
            font-size: 16px !important;
          }

          table[class*="w320"] {
            width: 320px !important;
          }

          td[class="mobile-center"],
          div[class="mobile-center"] {
            text-align: center !important;
          }

          td[class*="body-padding"] {
            padding: 20px !important;
          }

          td[class="mobile"] {
            text-align: right;
            vertical-align: top;
          }
        }
      </style>

    </head>
    <body style="padding:0; margin:0; display:block; background:#ffffff; -webkit-text-size-adjust:none">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
     <td valign="top" align="left" width="100%" style="background: #f9f8f8;">
     <center>

       <table class="w320 full-width-gmail-android">
          <tr>
            <td width="100%" height="48" valign="top">

                  <table class="full-width-gmail-android" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td class="header center" width="100%">
                        <a href="#">
                          Service Book
                        </a>
                      </td>
                    </tr>
                  </table>

            </td>
          </tr>
        </table>

        <table cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
          <tr>
            <td align="center">
              <center>
                <table class="w320" cellspacing="0" cellpadding="0" width="100%">
                  <tr>
                    <td class="body-padding mobile-padding">

                    <table cellpadding="0" cellspacing="0" width="100%">
                      <tr>
                        <td style="text-align:center; font-size:30px;  " bgcolor="#dcdf1d">
                            Your status --> {{ $status }}.
                        </td>
                      </tr>
                      <tr>
                        <td style="padding-bottom:20px;">
                          Dear Customer, <br>
                          <br>
                          Thank u for Choosing {{ $vendor_details[0]->name }}.<br>
                          <br>
                          <br>
                         Here are the details.<br>
                          <br>
                         <b>Order Number:</b> {{ $jobcard }}<br>
                         Date: {{ date('d-m-Y') }}<br>
                        </td>
                      </tr>
                    </table>


                    <table cellspacing="0" cellpadding="0" width="100%">
                      <tr>
                        <td>
                          <b>Customer Details</b>
                        </td>

                      </tr>
                      <tr>
                        <td class="border-bottom" height="5"></td>
                        <td class="border-bottom" height="5"></td>
                      </tr>
                      <tr>
                        <td style="padding-top:5px; vertical-align:top;">
                            {{ $cust_name }},<br>
                           {{ $cust_mobile }},<br>
                           {{ $cust_email }}<br>
                       </td>

                     </tr>
                    </table>
                    <table cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                          <td>
                            <b>Product Details</b>
                          </td>

                        </tr>
                        <tr>
                          <td class="border-bottom" height="5"></td>
                          <td class="border-bottom" height="5"></td>
                        </tr>
                        <tr>
                          <td style="padding-top:5px; vertical-align:top;">
                            Product name : {{ $product_det[0]->pdtname }},<br>
                            Remarks : {{ $product_det[0]->remarks }}<br>

                         </td>

                       </tr>
                      </table><br>
                      <table cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                          <td>
                            <b>Service Details . <a href='{{ url('view_jobcard_details_fromemail/'.$jobcard.'/'.$vendor_details[0]->id.'/'.$taxenabled.'')  }}'>click here</a> </b>
                          </td>

                        </tr>

                      </table>
                    <table cellspacing="0" cellpadding="0" width="100%">
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
                                @if(Session::get('tax_enabled')=='Y' )
                                <th>
                                  Tax %
                                </th>
                                @endif

                                <th >
                                  Total
                                </th>
                            </tr>
                          </thead>
                          @if(count($servicelist)>0)
                          <?php
                          $i=1;
                          $final = 0;
                          $tax_total=0;
                          ?>
                          @foreach($servicelist as $key=>$value)
                            <?php
                            $price_each = $value->price;
                            $taxval = 0;
                            $total = 0;
                            if (Session::get('tax_enabled') == 'Y') {
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
                                          @if (Session::get('tax_enabled') == 'Y')
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
                                          @if (Session::get('tax_enabled') == 'Y')
                                            <tr>
                                        <td></td><td> </td><td> </td>
                                                <td>Total Tax =</td><td>{{ $tax_total }}</td><td> </td></tr>
                                        @endif
                                        <tr>
                                        <td> </td><td> </td><td> </td>
                                            <td>Total =</td><td>{{ $final  }} </td><td> </td></tr>
                    </table>
{{-- {{ $servicelist[0]->id }} --}}

                    <table cellspacing="0" cellpadding="0" width="100%">
                      <tr>
                        <td class="left" style="text-align:left;">
                          Thank You,
                        </td>
                      </tr>
                      <tr>

                      </tr>
                    </table>

                    </td>
                  </tr>
                </table>
              </center>
            </td>
          </tr>
        </table>
        @if($endstatus=='Y')
        <table>
        <tr>
            <td>
                <a href="{{ url('customer_rating_email/'.$jobcard.'')  }}"> Please Click here </a>
            </td>
        </tr>
        </table>
        @endif
        <table class="w320" bgcolor="#E5E5E5" cellpadding="0" cellspacing="0" border="0" width="100%">
          <tr>
            <td style="border-top:1px solid #B3B3B3;" align="center">
              <center>
                    Contact Us<br>
                    {{ $vendor_details[0]->name }},
                    {{ $vendor_details[0]->contact_number }},<br>
                    {{ $vendor_details[0]->address }},<br>
                    {{ $vendor_details[0]->mail_id }},<br>
                    {{ $vendor_details[0]->website }}
              </center>
            </td>
          </tr>

        </table>

      </center>
      </td>
    </tr>
    </table>
    </body>
    </html>
