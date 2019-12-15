@extends('layout')
@section('style')

@endsection
@section('body')
    <div class="card" v-if="table_location">
        @if(Auth::User()->user_type_id==1)
            <div align="right" style="padding-right: 30px">
                <button class="btn btn-primary" @click="AddLocation">เพิ่มที่ตั้ง</button>
            </div>
        @endif
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
                    @if(Auth::User()->user_type_id==1)
                        <td>
                            <button class="btn btn-warning" @click="editLocation(list.hw_id)">แก้ไข</button>
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
                                    <h1 class="h4 text-gray-900 mb-4">แก้ไขที่ตั้งเครื่อง</h1>
                                </div>
                                <form>
                                    @csrf
                                    <div class="form-group">
                                        <label>ชื่อที่ตั้ง </label>
                                        <input ref="hw_name" type="text" class="form-control" placeholder="เลือกชั้น"
                                               v-model="location.hw_name">
                                    </div>
                                    <div class="form-group">
                                        <label>วิทยาเขต</label>
                                        <select class="form-control" v-model="location_id" @change="onChangLocation('location')">
                                            <option :value="null" disabled>-เลือกวิทยาเขต-</option>
                                            <option v-for="data in locations_list" :value="data.location_id">@{{ data.location_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>ตึก</label>
                                        <select class="form-control" v-model="site_id" @change="onChangLocation('site')">
                                            <option :value="null" disabled>-เลือกตึก-</option>
                                            <option v-if="data.location_id === location_id" v-for="data in sites" :value="data.site_id">@{{ data.site_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>ชั้น</label>
                                        <select ref="board_id" class="form-control" v-model="location.board_id">
                                            <option :value="null" disabled>-เลือกชั้น-</option>
                                            <option v-if="data.site_id === site_id" v-for="data in boards" :value="data.board_id">@{{ data.board_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>สถานะ</label>
                                        <select ref="status_id" class="form-control" v-model="location.status_id">
                                            <option :value="null" disabled>-เลือกสถานะ-</option>
                                            <option v-for="data in status_list" :value="data.status_id">@{{
                                                data.status_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div align="center">
                                        <button type="button" class="btn btn-danger btn-user" @click="toggle_location">
                                            ยกเลิก
                                        </button>
                                        <button type="button" class="btn btn-success btn-user" @click="SaveHw">
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
                status_list: [],
                location: [],
                site_id: null,
                board_id: null,
                location_id: null,
                list_key_status: [],
                boards: [],
                locations_list: [],
                sites: [],
            },
            created: function () {
                this.getLocationLists();
                this.all_status();
                this.getBoard();
                this.getSite();
                this.getLocation();
            },
            methods: {
                getLocationLists() {
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
                editLocation(id) {
                    let that = this;
                    this.show_edit_location = true;
                    this.table_location = false;
                    let data = null;
                    axios.get('{{url('/get_hw')}}/' + id)
                        .then(function (response) {
                            if (response.data.success) {
                                data = response.data.data;
                                that.location = data;
                                that.site_id = data.site_id;
                                that.board_id = data.board_id;
                                that.location_id = data.location_id;

                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                AddLocation() {
                    this.show_edit_location = true;
                    this.table_location = false;
                    this.location = {
                        hw_name: null,
                        board_id: null,
                        hw_id: null,
                        status_id: null
                    }
                },
                SaveHw() {
                    if(!this.location.hw_name){
                        this.$refs.hw_name.focus()
                        return
                    }else if(!this.location.board_id){
                        this.$refs.board_id.focus()
                        return
                    }else if(!this.location.status_id){
                        this.$refs.status_id.focus()
                        return
                    }

                    let that = this;
                    axios.post('{{url('/save_hw')}}', this.location)
                        .then(function (response) {
                            if (response.data.success) {
                                alert('ทำรายการสำเร็จ')
                                that.toggle_location();
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                all_status() {
                    let that = this;
                    axios.get('{{url('/get_status')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.status_list = response.data.data;
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                getBoard() {
                    let that = this;
                    axios.get('{{url('/get_board_admin')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.boards = response.data.data;
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                getSite() {
                    let that = this;
                    axios.get('{{url('/get_site_list')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.sites = response.data.site;
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                getLocation() {
                    let that = this;
                    axios.get('{{url('/get_location_list')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.locations_list = response.data.locations;
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
                },
                onChangLocation(txt){
                    if (txt === 'location') {
                        this.site_id = null
                        this.location.board_id = null
                    }else if(txt === 'site'){
                        this.location.board_id = null
                    }
                }
            },
        });
    </script>
@endsection
