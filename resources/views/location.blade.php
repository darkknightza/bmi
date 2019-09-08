@extends('layout')
@section('style')

@endsection
@section('body')
    <div class="card" v-if="table_location">
        <div style="padding: 20px" >
            <table id="myTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>ที่ตั้ง</th>
                    <th>ตึก</th>
                    <th>อัพเดทครั้งล่าสุด</th>
                    @if(Auth::User()->user_type_id==1)
                    <th>เครื่องมือ</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr v-for="(list,key) in locations">
                    <td>@{{ key+1 }}</td>
                    <td>@{{ list.board_name }}</td>
                    <td>@{{ list.site_name }}</td>
                    <td>@{{ list.time_update }}</td>
                    @if(Auth::User()->user_type_id==1)
                    <td>
                        <button class="btn btn-warning" @click="editLocation(list.board_id)">แก้ไข</button>
                    </td>
                    @endif
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-center" v-show="show_edit_location">

        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 offset-3">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">แก้ไขสถานที่</h1>
                                </div>
                                <form>
                                    @csrf
                                    <div class="form-group">
                                        <label>ชื่อสถานที่</label>
                                        <input type="text" class="form-control" placeholder="ชื่อสถานที่" v-model="board_name">
                                    </div>
                                    <div class="form-group">
                                        <label>ตึก</label>
                                        <select class="form-control" v-model="site_id">
                                            <option v-for="data in site" :value="data.site_id">@{{ data.site_name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>สถานะ</label>
                                        <select class="form-control" v-model="status_id">
                                            <option v-for="data in status_list" :value="data.status_id">@{{ data.status_name }}</option>
                                        </select>
                                    </div>
                                    <div align="center">
                                        <button type="button" class="btn btn-danger btn-user" @click="toggle_location">
                                            ยกเลิก
                                        </button>
                                        <button type="button" class="btn btn-success btn-user">
                                            บันทึก
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                site_id: '',
                status_id: '',
                board_name: '',
                board_id: ''
            },
            created: function () {
                this.getLocation();
                this.all_location_status();
            },
            methods: {
                getLocation() {
                    let that = this;
                    axios.get('{{url('/get_location')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.locations =response.data.data;
                                setTimeout(function () {
                                    $('#myTable').DataTable();
                                }, 100);
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                editLocation(id) {
                    let that = this;
                    this.show_edit_location = true;
                    this.table_location = false;
                    let data = null;
                    axios.get('{{url('/get_location')}}/'+id)
                        .then(function (response) {
                            if (response.data.success) {
                                data = response.data.data;
                                that.board_name = data.board_name;
                                that.site_id = data.site_id;
                                that.status_id = data.board_status;
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                SaveLocation() {
                    let that = this;
                    this.show_edit_location = true;
                    this.table_location = false;
                    let data = null;
                    axios.get('{{url('/save_board')}}?board_id='+id)
                        .then(function (response) {
                            if (response.data.success) {

                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                all_location_status() {
                    let that = this;
                    axios.get('{{url('/get_location_list')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.site =response.data.site;
                                that.status_list =response.data.status;
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                toggle_location(){
                    this.getLocation();
                    this.show_edit_location = false;
                    this.table_location = true;
                }
            },
        });
    </script>
@endsection
