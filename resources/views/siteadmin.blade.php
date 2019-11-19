@extends('layout')
@section('style')

@endsection
@section('body')
    <div class="card" v-if="table_location">
        @if(Auth::User()->user_type_id==1)
            <div align="right" style="padding-right: 30px">
                <button class="btn btn-primary" @click="AddSite">เพิ่มตึก</button>
            </div>
        @endif
        <div style="padding: 20px">
            <table id="myTable" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>ตึก</th>
                    <th>วิทยาเขต</th>
                    <th>อัพเดทครั้งล่าสุด</th>
                    @if(Auth::User()->user_type_id==1)
                        <th>เครื่องมือ</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr v-for="(list,key) in sites">
                    <td>@{{ key+1 }}</td>
                    <td>@{{ list.site_name }}</td>
                    <td>@{{ list.location_name }}</td>
                    <td>@{{ list.time_update }}</td>
                    @if(Auth::User()->user_type_id==1)
                        <td>
                            <button class="btn btn-warning" @click="editSite(list.site_id)">แก้ไข</button>
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
                                    <h1 v-if="action === 'ADD'" class="h4 text-gray-900 mb-4">เพิ่มตึก</h1>
                                    <h1 v-if="action === 'UPDATE'" class="h4 text-gray-900 mb-4">แก้ไขตึก</h1>
                                </div>
                                <form>
                                    @csrf
                                    <div class="form-group">
                                        <label>ชื่อตึก </label>
                                        <input type="text" class="form-control" placeholder="ชื่อตึก"
                                               v-model="site.site_name">
                                    </div>
                                    <div class="form-group">
                                        <label>วิทยาเขต</label>
                                        <select class="form-control" v-model="site.location_id">
                                            <option value="" disabled>เลือกวิทยาเขต</option>
                                            <option v-for="data in locations" :value="data.location_id">@{{ data.location_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div align="center">
                                        <button type="button" class="btn btn-danger btn-user" @click="toggle_site">
                                            ยกเลิก
                                        </button>
                                        <button type="button" class="btn btn-success btn-user" @click="SaveSite">
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
                sites: [],
                table_location: true,
                location_data: true,
                show_edit_location: false,
                site: [],
                locations: [],
                status_list: [],
                site_id: '',
                status_id: '',
                board_name: '',
                board_id: '',
                list_key_status: [],
                action: null
            },
            created: function () {
                this.getSite();
                this.all_location_status();
            },
            methods: {
                getSite() {
                    let that = this;
                    axios.get('{{url('/get_site_admin')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.sites = response.data.data;
                                setTimeout(function () {
                                    $('#myTable').DataTable();
                                }, 500);
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                editSite(id) {
                    let that = this;
                    this.show_edit_location = true;
                    this.table_location = false;
                    this.site = []
                    let data = null;
                    axios.get('{{url('/get_site')}}/' + id)
                        .then(function (response) {
                            if (response.data.success) {
                                data = response.data.data;
                                that.site = data;
                                that.action = 'UPDATE'
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                AddSite() {
                    this.show_edit_location = true;
                    this.table_location = false;
                    this.site = {
                        site_name: '',
                        site_id: '',
                        location_id: ''
                    }
                    this.action = 'ADD'
                },
                SaveSite() {
                    let that = this;
                    axios.post('{{url('/save_site')}}', this.site)
                        .then(function (response) {
                            if (response.data.success) {
                                alert('ทำรายการสำเร็จ')
                                that.toggle_site();
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
                                that.locations = response.data.locations;
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                toggle_site() {
                    this.getSite();
                    this.show_edit_location = false;
                    this.table_location = true;
                }
            },
        });
    </script>
@endsection
