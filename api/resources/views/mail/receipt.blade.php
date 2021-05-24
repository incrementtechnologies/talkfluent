@component('mail.header')
@endcomponent
<span class="email-holder">
  <span class="receipt-header">
    TalkFluentSpanish
    <br>
    Recurring Payment Receipt
  </span>
  <span class=content>
    <table>
      <thead>
        <tr>
          <td>Description</td>
          <td>Unit Price</td>
          <td>Qty</td>
          <td>Total Amount</td>
        </tr>
      </thead>
      <tbody>
        <tr style="border-bottom: solid 1px #ddd;">
          <td>{{$details['description']}}</td>
          <td>US ${{$details['unit_price']}}</td>
          <td>{{$details['qty']}}</td>
          <td>US ${{$details['amount']}}</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td style="border-bottom: solid 1px #ddd;">Subtotal</td>
          <td style="border-bottom: solid 1px #ddd;">US ${{$details['subtotal']}}</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td style="border-bottom: solid 1px #ddd;">Tax</td>
          <td style="border-bottom: solid 1px #ddd;">US ${{$details['tax']}}</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td style="border-bottom: solid 1px #ddd;">Discount</td>
          <td style="border-bottom: solid 1px #ddd;">US ${{$details['discount']}}</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td style="border-bottom: solid 1px #ddd;">Total</td>
          <td style="border-bottom: solid 1px #ddd;">US ${{$details['total']}}</td>
        </tr>
      </tbody>
    </table>
  </span>
</span>
@component('mail.footer')
@endcomponent