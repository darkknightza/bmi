@extends('layout')
@section('style')

@endsection
@section('body')
    <div class="card" v-if="table_location">
        <div style="padding: 20px">
            <table id="myTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>ที่ตั้ง</th>
                    <th>ชั้น</th>
                    <th>ตึก</th>
                    <th>วิทยาเขต</th>
                    <th>สถานะ</th>
                    <th>อัพเดทครั้งล่าสุด</th>
                    @if(Auth::User()->user_type_id==1)
                        <th>เครื่องมือ</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr v-for="(list,key) in locations">
                    <td>@{{ key+1 }}</td>
                    <td>@{{ list.hw_name }}</td>
                    <td>@{{ list.board_name }}</td>
                    <td>@{{ list.site_name }}</td>
                    <td>@{{ list.location_name }}</td>
                    <td>@{{ list.status_name }}</td>
                    <td>@{{ list.time_update }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
      let app = new Vue({
        el: '#app',
        data: {
          locations: [],
          table_location: true,
          location_data: true,
          show_edit_location: false,
          site: [],
          status_list: [],
          location: [],
          site_id: '',
          status_id: '',
          board_name: '',
          board_id: '',
          list_key_status: []
        },
        created: function () {
          this.getLocation();
        },
        methods: {

          getLocation() {
            let that = this;
            axios.get('{{url('/get_location_user')}}')
              .then(function (response) {
                if (response.data.success) {
                  that.locations = response.data.data;
                  setTimeout(function () {
                    $('#myTable').DataTable();
                  }, 500);
                }
              })
              .catch(function (error) {
                console.log(error)
              });
          },
          toggle_location() {
            this.getLocation();
            this.show_edit_location = false;
            this.table_location = true;
          }
        },
      });
    </script>
@endsection
