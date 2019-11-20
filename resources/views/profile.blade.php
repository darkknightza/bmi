@extends('layout')
@section('style')

@endsection
@section('body')
    <div class="row justify-content-center" v-if="show_detail_profile">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 offset-3">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">โปรไฟล์</h1>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-4">
                                            <label>ชื่อ:</label>
                                        </div>
                                        <div class="col-8">
                                            <label>@{{ profile.name }} @{{ profile.lastname }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label>เลขบัตร:</label>
                                        </div>
                                        <div class="col-8">
                                            <label>@{{ profile.card_id }}</label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label>เพศ:</label>
                                        </div>
                                        <div class="col-8">
                                            <label>@{{disPlayGender(profile.gender) }}</label>
                                        </div>
                                    </div>
                                    <div class="row" v-if="user_type_id === 2">
                                        <div class="col-4">
                                            <label>รหัสนักศึกษา:</label>
                                        </div>
                                        <div class="col-8">
                                            <label>@{{ profile.student_id }}</label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-12" style="text-align: center">
                                            <button class="btn btn-warning" @click="toggle_profile">แก้ไขโปรไฟล์
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center" v-if="show_edit_profile">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 offset-3">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">แก้ไขโปรไฟล์</h1>
                                </div>
                                <form>
                                    @csrf
                                    <div class="form-group">
                                        <div class="row" style="padding-bottom: 10px">
                                            <div class="col-4">
                                                <label>ชื่อ:</label>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" class="form-control"
                                                       placeholder="ชื่อ" v-model="profile.name">
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 10px">
                                            <div class="col-4">
                                                <label>นามสกุล:</label>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" class="form-control"
                                                       placeholder="นามสกุล" v-model="profile.lastname">
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 10px">
                                            <div class="col-4">
                                                <label>เลขบัตร:</label>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" class="form-control"
                                                       placeholder="เลขบัตรประชาชน" v-model="profile.card_id">
                                            </div>
                                        </div>
                                        <div class="row" style="padding-bottom: 10px">
                                            <div class="col-4">
                                                <label>เพศ:</label>
                                            </div>
                                            <div class="col-8">
                                                <select class="form-control" v-model="profile.gender">
                                                    <option value="M">ชาย</option>
                                                    <option value="F">หญิง</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" v-if="user_type_id === 2" style="padding-bottom: 10px">
                                            <div class="col-4">
                                                <label>รหัสนักศึกษา:</label>
                                            </div>
                                            <div class="col-8">
                                                <input type="text" class="form-control"
                                                       placeholder="รหัสนักศึกษา" v-model="profile.student_id">
                                            </div>
                                        </div>
                                        <div align="center">
                                            <button type="button" class="btn btn-danger btn-user"
                                                    @click="toggle_profile">
                                                ยกเลิก
                                            </button>
                                            <button type="button" class="btn btn-success btn-user"
                                                    @click="EditProfile">
                                                บันทึก
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
                profile: [],
                show_edit_profile: false,
                show_detail_profile: true
            },
            created: function () {
                this.getProfile();
            },
            methods: {
                getProfile() {
                    let that = this;
                    axios.get('{{url('/get_profile')}}')
                        .then(function (response) {
                            if (response.data.success) {
                                that.profile = response.data.profile;
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                disPlayGender(value) {
                    if (value === 'M') {
                        return 'เพศชาย'
                    } else {
                        return 'เพศหญิง'
                    }
                },
                EditProfile() {
                    let that = this;
                    axios.post('{{url('/save_profile')}}', this.profile)
                        .then(function (response) {
                            if (response.data.success) {
                                alert('ทำรายการสำเร็จ')
                                that.toggle_profile();
                            }
                        })
                        .catch(function (error) {
                            console.log(error)
                        });
                },
                toggle_profile() {
                    this.show_edit_profile = !this.show_edit_profile
                    this.show_detail_profile = !this.show_detail_profile
                }
            },
        });
    </script>
@endsection
