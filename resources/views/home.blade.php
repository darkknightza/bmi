@extends('layout')
@section('style')

@endsection
@section('body')
  @if(Auth::User()->user_type_id != 1)
    <example-component></example-component>
  @else
    <div class="container" style="padding: 10px">
      <label>เกณฑ์ของนักศึกษา</label>
      <table class="table table-striped table-bordered dataTable no-footer">
        <thead>
          <tr>
            <th>น้ำหนักต่ำกว่าเกณฑ์</th>
            <th>สมส่วน</th>
            <th>น้ำหนักเกิน</th>
            <th>โรคอ้วน</th>
            <th>โรคอ้วนอันตราย</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>@{{ disPlay(report_1.bmi_1,report_1.count) }}</th>
            <th>@{{ disPlay(report_1.bmi_2,report_1.count) }}</th>
            <th>@{{ disPlay(report_1.bmi_3,report_1.count) }}</th>
            <th>@{{ disPlay(report_1.bmi_4,report_1.count) }}</th>
            <th>@{{ disPlay(report_1.bmi_5,report_1.count) }}</th>
          </tr>
        </tbody>
      </table>
      <br>
      <label>เกณฑ์ของบุคลากร</label>
      <table class="table table-striped table-bordered dataTable no-footer">
        <thead>
          <tr>
            <th>น้ำหนักต่ำกว่าเกณฑ์</th>
            <th>สมส่วน</th>
            <th>น้ำหนักเกิน</th>
            <th>โรคอ้วน</th>
            <th>โรคอ้วนอันตราย</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>@{{ disPlay(report_2.bmi_1,report_2.count) }}</th>
            <th>@{{ disPlay(report_2.bmi_2,report_2.count) }}</th>
            <th>@{{ disPlay(report_2.bmi_3,report_2.count) }}</th>
            <th>@{{ disPlay(report_2.bmi_4,report_2.count) }}</th>
            <th>@{{ disPlay(report_2.bmi_5,report_2.count) }}</th>
          </tr>
        </tbody>
      </table>
      <br>
      <label>จำนวนเครื่องที่ใช้สูงสุด 5 เครื่อง</label>
      <table class="table table-striped table-bordered dataTable no-footer">
        <thead>
          <tr>
            <th v-for="data in report_3">@{{ data.hw_name }}</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th v-for="data in report_3">@{{disPlay( data.count,report_3_count) }}</th>
          </tr>
        </tbody>
      </table>
      <br>
      <label>จำนวนระหว่างนักศึกษากับบุคลากร</label>
      <table class="table table-striped table-bordered dataTable no-footer">
        <thead>
          <tr>
            <th>จำนวนนักศึกษา</th>
            <th>จำนวนบุคลากร</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>@{{disPlay( report_4.student,report_4.count) }}</th>
            <th>@{{disPlay( report_4.personnel,report_4.count) }}</th>
          </tr>
        </tbody>
      </table>
      <br>
      <label>จำนวนผู้ใช้ระหว่างช่วงเช้ากับช่วงบ่าย</label>
      <table class="table table-striped table-bordered dataTable no-footer">
        <thead>
          <tr>
            <th>ตั้งแต่เวลา 00:01 - 12:00</th>
            <th>ตั้งแต่เวลา 12:01 - 24:00</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>@{{report_5.time_00}}</th>
            <th>@{{report_5.time_12}}</th>
          </tr>
        </tbody>
      </table>
    </div>
  @endif
@endsection
@section('script')
  <script>
    let app = new Vue({
      el: '#app',
      data: {
        report_1: [],
        report_2: [],
        report_3: [],
        report_4: [],
        report_5: [],
        report_3_count: 0,
      },
      created: function () {
        this.getLocation()
      },
      methods: {
        getLocation () {
          let that = this
          axios.get('{{url('/get_report')}}')
            .then(function (response) {
              if (response.data.success) {
                that.report_1 = response.data.student
                that.report_2 = response.data.personnel
                that.report_3 = response.data.report_3
                that.report_4 = response.data.report_4
                that.report_5 = response.data.report_5
                that.report_3_count = response.data.report_3_count
              }
            })
            .catch(function (error) {
              console.log(error)
            })
        },
        disPlay (value, count) {
          console.log(count)
          if (count === 0) {
            return 0
          } else {
            return Math.round((value / count) * 100) + '%'
          }
        },
      },
    })
  </script>
@endsection
