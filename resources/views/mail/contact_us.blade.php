@component('mail::message')

<h2 class="text-start">مرحبا لديك رسالة جديدة من {{ $name }}</h2>
    <div class="text-start">
        <h3  class="text-start">تفاصيل المرسل</h3>
        <p  class="text-start">
            الاسم : {{ $name }}<br>
            الهاتف: {{ $phone }}<br>
            العنوان: {{ $address }}<br>
        </p>
    </div>
    <div class="text-start">
        <h3  class="text-start">نص الرسالة : </h3>
        <p  class="text-start"> {{ $messages }}</p>
    </div>
<br>
@endcomponent